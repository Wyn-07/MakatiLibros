import mysql.connector
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel
from sklearn.metrics import mean_absolute_error, mean_squared_error
import numpy as np
import json
import sys

# Establish a connection to the MySQL database
conn = mysql.connector.connect(
    host='localhost',       
    user='root',           
    password='',           
    database='librodb'      
)

cursor = conn.cursor()

# SQL query to fetch all necessary data for content-based filtering
query_books = """
SELECT 
    b.book_id, 
    b.acc_number, 
    b.class_number, 
    b.title, 
    b.copyright, 
    b.image, 
    a.author AS author_name, 
    c.category AS category_name
FROM 
    books b
JOIN 
    author a ON b.author_id = a.author_id
JOIN 
    category c ON b.category_id = c.category_id
LEFT JOIN 
    condemned cd ON b.book_id = cd.book_id       
LEFT JOIN 
    missing ms ON b.book_id = ms.book_id         
WHERE 
    cd.book_id IS NULL AND ms.book_id IS NULL     
"""


# Execute the query
cursor.execute(query_books)

# Fetch all results
results = cursor.fetchall()

# Convert results to a DataFrame for better visualization
column_books = ['Book ID', 'Acc Number', 'Class Number', 'Title', 'Copyright', 'Image', 'Author Name', 'Category Name']
df = pd.DataFrame(results, columns=column_books)

# Display the DataFrame to verify
print(df)


# Function to fetch the latest rated book ID and title for a user
def get_latest_rated_book_info(patrons_id):
    query = """
    SELECT b.book_id, b.title 
    FROM ratings r
    JOIN books b ON r.book_id = b.book_id
    WHERE r.patrons_id = %s 
    ORDER BY r.date DESC 
    LIMIT 1
    """
    cursor.execute(query, (patrons_id,))
    result = cursor.fetchone()
    return result if result else (None, None)  # Return book ID and title or (None, None) if not found

# Fetch the latest rated book ID and title for a specific patron
patrons_id = 1

latest_book_id, latest_book_title = get_latest_rated_book_info(patrons_id)
print(f"Latest rated book ID for patron {patrons_id}: {latest_book_id}, Title: {latest_book_title}")


# Content-Based Filtering
# Prepare the content DataFrame for filtering
book_content_df = df[['Book ID', 'Title', 'Author Name', 'Category Name', 'Copyright']].copy()  

# Create the 'Content' column using .loc to avoid SettingWithCopyWarning
book_content_df.loc[:, 'Content'] = book_content_df.apply(
    lambda row: ' '.join(row[['Title', 'Author Name', 'Category Name', 'Copyright']].dropna().astype(str)), axis=1
)

# Use TF-IDF vectorizer to convert content into a matrix of TF-IDF features
tfidf_vectorizer = TfidfVectorizer(
    stop_words='english',  
    ngram_range=(1, 2),  
    min_df=0.001,             
    max_features=10000,  
)

content_matrix = tfidf_vectorizer.fit_transform(book_content_df['Content'])

# Calculate cosine similarity matrix
content_similarity = linear_kernel(content_matrix, content_matrix)

def get_content_based_recommendations(book_id, top_n):
    # Get the index of the book based on its ID
    index = book_content_df[book_content_df['Book ID'] == book_id].index[0]
    
    # Get similarity scores
    similarity_scores = content_similarity[index]
    
    # Get indices of the most similar books (sorted by similarity score)
    similar_indices = similarity_scores.argsort()[::-1]  # Sort all indices by similarity
    
    # Prepare a list to hold the final recommendations and a set to track unique IDs
    recommendations_list = []
    recommended_ids = set()  
    
    # Loop through the similar indices
    for idx in similar_indices:
        recommended_book_id = book_content_df.loc[idx, 'Book ID']
        
        # Only add unique book IDs to the recommendations list and skip the book itself
        if recommended_book_id != book_id and recommended_book_id not in recommended_ids:
            title = book_content_df.loc[idx, 'Title']
            recommendations_list.append((recommended_book_id, title, similarity_scores[idx]))  # Add similarity score
            recommended_ids.add(recommended_book_id)
        
        # Stop if we have enough recommendations
        if len(recommendations_list) == top_n:
            break
    
    return recommendations_list


# Example call to get content-based recommendations
content_recommended_books = get_content_based_recommendations(latest_book_id, 10)

# Displaying the recommendations in a list format along with cosine similarity
print("Content-Based Recommended Books (ID, Title, and Cosine Similarity):")
for book_id, title, similarity in content_recommended_books:
    print(f"- ID: {book_id}, Title: {title}, Cosine Similarity: {similarity:.4f}")
print("")



if __name__ == "__main__":
    try:
        # Create a response list with only book IDs, converting to standard int
        response = [int(book_id) for book_id, title, score in content_recommended_books]
        
        # Ensure the response is properly formatted as JSON
        json_response = json.dumps(response, ensure_ascii=True)

        # Print the response to be captured by PHP
        print(json_response)

    except Exception as e:
        print(f"Error: {str(e)}")
