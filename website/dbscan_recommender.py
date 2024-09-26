import warnings
import sys
import pandas as pd
from sklearn.cluster import KMeans
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.metrics import silhouette_score
import mysql.connector
from scipy.sparse import csr_matrix
import json
from sklearn.decomposition import PCA
from sklearn.metrics import mean_squared_error
import numpy as np


# Suppress warnings
warnings.filterwarnings("ignore", category=UserWarning)
warnings.filterwarnings("ignore", category=DeprecationWarning)

def fetch_data(user_id):
    # Connect to your database
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="librodb"
    )
    
    # Fetch book data
    query_books = "SELECT * FROM books"
    books = pd.read_sql(query_books, conn)
    
    # Fetch borrow data
    query_borrow = f"SELECT * FROM borrow WHERE user_id = {user_id}"
    borrow = pd.read_sql(query_borrow, conn)
    
    # Fetch search data
    query_search = f"SELECT * FROM search WHERE user_id = {user_id}"
    search = pd.read_sql(query_search, conn)
    
    # Fetch favorites data
    query_favorites = f"SELECT * FROM favorites WHERE user_id = {user_id}"
    favorites = pd.read_sql(query_favorites, conn)
    
    conn.close()
    
    return books, borrow, search, favorites

def prepare_data(books, borrow, search, favorites):
    # Create a dictionary to map book IDs to titles and images
    book_id_to_info = pd.Series(books[['title', 'image']].set_index(books['book_id']).to_dict(orient='index')).to_dict()
    
    # Create a content DataFrame with the relevant columns
    content_df = books[['book_id', 'title', 'authors', 'categories', 'published']].copy()
    content_df['Content'] = content_df.apply(lambda row: ' '.join(row.dropna().astype(str)), axis=1)
    
    return content_df, book_id_to_info


from sklearn.metrics import calinski_harabasz_score, davies_bouldin_score

def get_content_based_recommendations(book_ids, content_df, book_id_to_info, top_n):
    all_recommendations = {}
    
    # Apply TF-IDF Vectorization
    tfidf_vectorizer = TfidfVectorizer(stop_words='english', max_df=0.7, min_df=3)
    content_matrix = tfidf_vectorizer.fit_transform(content_df['Content'])
    
    # Optionally apply PCA for dimensionality reduction
    pca = PCA(n_components=1, random_state=42)
    content_matrix_reduced = pca.fit_transform(content_matrix.toarray())
    
    for book_id in book_ids:
        if book_id not in content_df['book_id'].values:
            print(f"Book ID {book_id} not found in the dataset.")
            continue
        
        index = content_df[content_df['book_id'] == book_id].index[0]
        similarity_scores = cosine_similarity(content_matrix[index], content_matrix).flatten()
        similar_indices = similarity_scores.argsort()[::-1][1:top_n + 1]
        recommended_ids = content_df.loc[similar_indices, 'book_id'].values
        recommendations = [book_id_to_info.get(bid, {"title": "Unknown Title", "image": ""}) for bid in recommended_ids]
        all_recommendations[book_id] = recommendations
    
    return all_recommendations


def get_user_books(borrow, search, favorites):
    user_books = pd.concat([borrow['book_id'], search['book_id'], favorites['book_id']]).drop_duplicates().tolist()
    return user_books



def aggregate_recommendations(recommendations_dict, top_n):
    occurrence_count = {}
    
    # Count occurrences of each book and preserve the image URL
    for recommendations in recommendations_dict.values():
        for info in recommendations:
            title = info['title']
            image = info.get('image', '')  # Retrieve the image URL if it exists
            if title in occurrence_count:
                occurrence_count[title]['count'] += 1
            else:
                occurrence_count[title] = {
                    'count': 1,
                    'image': image
                }
    
    # Sort recommendations by occurrence count
    sorted_recommendations = sorted(occurrence_count.items(), key=lambda x: x[1]['count'], reverse=True)
    
    # Prepare the final ranked recommendations, including the image URL
    ranked_recommendations = [{'title': title, 'score': info['count'], 'image': info['image']} 
                              for title, info in sorted_recommendations[:top_n]]
    
    return ranked_recommendations




def get_collaborative_filtering_recommendations(user_id, top_n, ratings, book_id_to_info):
    # Prepare User-Item matrix for collaborative filtering
    ratings = ratings.groupby(['user_id', 'book_id'], as_index=False).agg({'ratings': 'mean'})
    user_item_matrix = ratings.pivot(index='user_id', columns='book_id', values='ratings').fillna(0)
    user_item_sparse = csr_matrix(user_item_matrix.values)
    user_similarity = cosine_similarity(user_item_sparse)
    user_similarity_df = pd.DataFrame(user_similarity, index=user_item_matrix.index, columns=user_item_matrix.index)
    
    if user_id not in user_item_matrix.index:
        print(f"User ID {user_id} not found in the dataset.")
        return recommend_popular_books(top_n, book_id_to_info)
    
    similar_users = user_similarity_df[user_id].sort_values(ascending=False).index[1:]
    if similar_users.empty:
        print(f"No similar users found for User ID {user_id}.")
        return recommend_popular_books(top_n, book_id_to_info)
    
    similar_users_books = user_item_matrix.loc[similar_users].mean().sort_values(ascending=False)
    user_books = user_item_matrix.loc[user_id]
    unrated_books = similar_users_books[~similar_users_books.index.isin(user_books[user_books > 0].index)]
    
    # Get the top N recommendations
    recommended_books = [(book_id, book_id_to_info.get(book_id, {'title': 'Unknown Title', 'image': ''})['title']) 
                         for book_id in unrated_books.index[:top_n]]

    return [{'title': title, 'score': unrated_books[book_id], 'image': book_id_to_info.get(book_id, {'image': ''})['image']} 
            for book_id, title in recommended_books]


def recommend_popular_books(top_n, book_id_to_info):
    # Dummy function to return popular books
    # You would replace this with a query to get the most popular books in your database
    popular_books = sorted(book_id_to_info.items(), key=lambda x: x[1].get('avg_rating', 0), reverse=True)
    popular_books_list = [book_info for _, book_info in popular_books[:top_n]]
    
    return popular_books_list



def get_hybrid_recommendations(user_id, top_n, books, borrow, search, favorites, ratings):
    # Generate content-based recommendations
    content_df, book_id_to_info = prepare_data(books, borrow, search, favorites)
    user_books = get_user_books(borrow, search, favorites)
    fixed_clusters = 2
    content_based_recommendations = aggregate_recommendations(
        get_content_based_recommendations(user_books, content_df, book_id_to_info, top_n, fixed_clusters), top_n
    )
    
    # Print content-based recommendations for debugging
    print("Content-Based Recommendations:")
    for rec in content_based_recommendations:
        print(rec)
    
    # Generate collaborative filtering recommendations
    collaborative_filtering_recommendations = get_collaborative_filtering_recommendations(user_id, top_n, ratings, book_id_to_info)
    
    # Handle strings returned by the collaborative filtering function
    if isinstance(collaborative_filtering_recommendations, str):
        print("Collaborative Filtering Error:")
        print(collaborative_filtering_recommendations)
        collaborative_filtering_recommendations = []
    else:
        # Print collaborative filtering recommendations for debugging
        print("Collaborative Filtering Recommendations:")
        for rec in collaborative_filtering_recommendations:
            print(rec)
    
    # Combine and deduplicate recommendations
    all_recommendations = {}
    
    for rec in content_based_recommendations:
        title = rec['title']
        all_recommendations[title] = {
            'score': rec['score'],
            'image': rec.get('image', '')  # Ensure image URL is included
        }
    
    for rec in collaborative_filtering_recommendations:
        if isinstance(rec, dict):
            title = rec['title']
            score = rec['score']
            image = rec.get('image', '')  # Ensure image URL is included
            if title in all_recommendations:
                # If the title is already present, combine scores (e.g., by averaging)
                all_recommendations[title]['score'] = max(all_recommendations[title]['score'], score)
            else:
                all_recommendations[title] = {
                    'score': score,
                    'image': image
                }
    
    # Convert to the format expected by PHP
    recommendations_list = [{'title': title, 'image': details['image']} for title, details in sorted(all_recommendations.items(), key=lambda x: x[1]['score'], reverse=True)][:top_n]
 
    return recommendations_list



if __name__ == "__main__":
    user_id = int(sys.argv[1])  # Get user ID from command-line argument
    books, borrow, search, favorites = fetch_data(user_id)
    
    # Fetch ratings for collaborative filtering
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="librodb"
    )
    query_ratings = "SELECT * FROM ratings"
    ratings = pd.read_sql(query_ratings, conn)
    conn.close()
    
    top_n =books['book_id'].nunique()
    hybrid_recommendations = get_hybrid_recommendations(user_id, top_n, books, borrow, search, favorites, ratings)
    
    # Print clean JSON output
    print(json.dumps(hybrid_recommendations, ensure_ascii=True))
