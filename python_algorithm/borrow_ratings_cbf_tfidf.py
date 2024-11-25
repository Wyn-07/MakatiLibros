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

# Fetch latest borrowed book info
def get_latest_borrowed_book_info(patrons_id):
    query = """
    SELECT b.book_id, b.title 
    FROM borrow br
    JOIN books b ON br.book_id = b.book_id
    WHERE br.patrons_id = %s AND br.status = 'Returned' 
    ORDER BY STR_TO_DATE(CONCAT(br.return_date, ' ', br.return_time), '%m/%d/%Y %H:%i:%s') DESC  
    LIMIT 1
    """
    cursor.execute(query, (patrons_id,))
    return cursor.fetchone() or (None, None)

# Fetch latest rated book info
def get_latest_rated_book_info(patrons_id):
    query = """
    SELECT b.book_id, b.title 
    FROM ratings r
    JOIN books b ON r.book_id = b.book_id
    WHERE r.patrons_id = %s 
    ORDER BY STR_TO_DATE(CONCAT(r.date, ' ', r.time), '%m/%d/%Y %H:%i:%s') DESC 
    LIMIT 1
    """
    cursor.execute(query, (patrons_id,))
    return cursor.fetchone() or (None, None)

# Fetch all borrowed and rated book IDs
def get_user_borrowed_and_rated_ids(patrons_id):
    borrowed_query = "SELECT book_id FROM borrow WHERE patrons_id = %s AND status = 'Returned'"
    rated_query = "SELECT book_id FROM ratings WHERE patrons_id = %s"
    
    cursor.execute(borrowed_query, (patrons_id,))
    borrowed_ids = {row[0] for row in cursor.fetchall()}

    cursor.execute(rated_query, (patrons_id,))
    rated_ids = {row[0] for row in cursor.fetchall()}

    return borrowed_ids, rated_ids

# Content-Based Recommendation Function
def get_content_based_recommendations(book_id, n_recommendations, excluded_ids):
    try:
        index = df[df['Book ID'] == book_id].index[0]
        distances, indices = nn.kneighbors(content_matrix[index], n_neighbors=n_recommendations + 1)

        recommendations_list = []
        seen_titles = set()
        i = 1

        while len(recommendations_list) < n_recommendations and i < len(distances[0]):
            recommended_book_id = df.iloc[indices[0][i]]['Book ID']
            title = df.iloc[indices[0][i]]['Title']

            if title not in seen_titles and recommended_book_id not in excluded_ids and recommended_book_id != book_id:
                similarity = 1 - distances[0][i]
                recommendations_list.append((recommended_book_id, title, similarity))
                seen_titles.add(title)
            i += 1

        return recommendations_list

    except IndexError:
        return []

# Input
patrons_id = int(sys.argv[1])
number = int(sys.argv[2])

# Fetch borrowed and rated IDs
borrowed_ids, rated_ids = get_user_borrowed_and_rated_ids(patrons_id)

# Generate Borrowing Recommendations
latest_borrowed_id, latest_borrowed_title = get_latest_borrowed_book_info(patrons_id)
borrow_recommendations = get_content_based_recommendations(latest_borrowed_id, number, borrowed_ids.union(rated_ids)) if latest_borrowed_id else []

# Generate Rating Recommendations
latest_rated_id, latest_rated_title = get_latest_rated_book_info(patrons_id)
rating_recommendations = get_content_based_recommendations(latest_rated_id, number, borrowed_ids.union(rated_ids)) if latest_rated_id else []

# Output Recommendations
if __name__ == "__main__":
    try:
        response = {
            "borrow_cbf": [int(book_id) for book_id, title, score in borrow_recommendations],
            "rating_cbf": [int(book_id) for book_id, title, score in rating_recommendations]
        }

        json_response = json.dumps(response, ensure_ascii=True)
        print(json_response)

    except Exception as e:
        print(f"Error: {str(e)}")