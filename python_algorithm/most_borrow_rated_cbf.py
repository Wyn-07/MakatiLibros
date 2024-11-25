import pandas as pd
import json
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import NearestNeighbors
import sys
from utils import create_connection

# Database connection
conn = create_connection()
cursor = conn.cursor(prepared=True)

# Fetch books data
query_books = """
SELECT 
    b.book_id, 
    b.title, 
    b.copyright, 
    a.author AS author_name, 
    c.category AS category_name
FROM 
    books b
JOIN 
    author a ON b.author_id = a.author_id
JOIN 
    category c ON b.category_id = c.category_id
WHERE 
    b.book_id NOT IN (SELECT book_id FROM condemned)
    AND b.book_id NOT IN (SELECT book_id FROM missing)
"""
cursor.execute(query_books)
results = cursor.fetchall()
column_books = ['Book ID', 'Title', 'Copyright', 'Author Name', 'Category Name']
df = pd.DataFrame(results, columns=column_books)

# Create Content Column
df['Content'] = df[['Title', 'Author Name', 'Category Name', 'Copyright']].astype(str).fillna('').agg(' '.join, axis=1)

# TF-IDF Vectorization
tfidf_vectorizer = TfidfVectorizer(stop_words='english', ngram_range=(1, 1), min_df=0.001, max_features=1000)
content_matrix = tfidf_vectorizer.fit_transform(df['Content'])

# Nearest Neighbors for Recommendations
nn = NearestNeighbors(n_neighbors=10, metric='cosine', n_jobs=-1)
nn.fit(content_matrix)

# Fetch most borrowed book info
def get_most_borrowed_book_info():
    query = """
    SELECT br.book_id, b.title 
    FROM borrow br
    JOIN books b ON br.book_id = b.book_id
    WHERE br.status = 'Returned'
    GROUP BY br.book_id
    ORDER BY COUNT(br.book_id) DESC 
    LIMIT 1
    """
    cursor.execute(query)
    return cursor.fetchone() or (None, None)

# Fetch most rated book info
def get_most_rated_book_info():
    query = """
    SELECT r.book_id, b.title 
    FROM ratings r
    JOIN books b ON r.book_id = b.book_id
    GROUP BY r.book_id
    ORDER BY COUNT(r.book_id) DESC 
    LIMIT 1
    """
    cursor.execute(query)
    return cursor.fetchone() or (None, None)



# Input
patrons_id = int(sys.argv[1])
number = int(sys.argv[2])


def get_patron_interests(patron_id):
    query_interest = """
    SELECT interest 
    FROM patrons 
    WHERE patrons_id = %s
    """
    cursor.execute(query_interest, (patron_id,))
    result = cursor.fetchone()
    return result[0].split(",") if result else []


def get_most_rated_book_for_interest(interest):
    query_most_rated = """
    SELECT r.book_id, b.title 
    FROM ratings r
    JOIN books b ON r.book_id = b.book_id
    JOIN category c ON b.category_id = c.category_id
    WHERE c.category = %s
    GROUP BY r.book_id
    ORDER BY AVG(r.ratings) DESC 
    LIMIT 1
    """
    cursor.execute(query_most_rated, (interest,))
    return cursor.fetchone()  # Returns (book_id, title) or None




# Content-Based Recommendation Function
def get_content_based_recommendations(book_id, n_recommendations):
    try:
        index = df[df['Book ID'] == book_id].index[0]
        distances, indices = nn.kneighbors(content_matrix[index], n_neighbors=n_recommendations + 1)

        recommendations_list = []
        seen_titles = set()
        i = 1

        while len(recommendations_list) < n_recommendations and i < len(distances[0]):
            recommended_book_id = df.iloc[indices[0][i]]['Book ID']
            title = df.iloc[indices[0][i]]['Title']

            if title not in seen_titles and recommended_book_id != book_id:
                similarity = 1 - distances[0][i]
                recommendations_list.append((recommended_book_id, title, similarity))
                seen_titles.add(title)
            i += 1

        return recommendations_list

    except IndexError:
        return []




def get_combined_recommendations_by_cosine(patron_id, number):
    interests = get_patron_interests(patron_id)
    combined_recommendations = []

    for interest in interests:
        most_rated_book = get_most_rated_book_for_interest(interest)
        if most_rated_book:
            book_id, book_title = most_rated_book
            # print(f"Most rated book for {interest}: ID {book_id}, Title {book_title}")
            
            # Get recommendations based on cosine similarity for this book
            recommendations = get_content_based_recommendations(book_id, number)
            
            # Add recommendations to the combined list with their cosine similarity scores
            combined_recommendations.extend(recommendations)

    # Sort the combined recommendations by cosine similarity in descending order
    combined_recommendations.sort(key=lambda x: x[2], reverse=True)

    # Return only the top `number` recommendations
    return combined_recommendations[:number]









# Generate Borrowing Recommendations
most_borrowed_id, most_borrowed_title = get_most_borrowed_book_info()
borrow_recommendations = get_content_based_recommendations(most_borrowed_id, number) if most_borrowed_id else []

# Generate Rating Recommendations
most_rated_id, most_rated_title = get_most_rated_book_info()
rating_recommendations = get_content_based_recommendations(most_rated_id, number) if most_rated_id else []

# Now call the function and generate recommendations
interest_based_recommendations = get_combined_recommendations_by_cosine(patrons_id, number)



# Output Recommendations
if __name__ == "__main__":
    try:
        response = {
            "borrow_cbf": [int(book_id) for book_id, title, score in borrow_recommendations],
            "rating_cbf": [int(book_id) for book_id, title, score in rating_recommendations],
            "rated_interest_cbf": [int(book_id) for book_id, title, score in interest_based_recommendations]
        }

        json_response = json.dumps(response, ensure_ascii=True)
        print(json_response)

    except Exception as e:
        print(f"Error: {str(e)}")
