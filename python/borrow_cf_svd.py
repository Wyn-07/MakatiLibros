import mysql.connector
import pandas as pd
from sklearn.decomposition import TruncatedSVD
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error, mean_squared_error, root_mean_squared_error
import numpy as np
from scipy.sparse import csr_matrix
from sklearn.preprocessing import normalize
import json
import sys

patrons_id = 1

# Establish a connection to the MySQL database
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='librodb'
)

# Create a cursor object to interact with the database
cursor = conn.cursor()

# SQL query to fetch all necessary data for collaborative filtering based on borrowings
query_borrow = """
SELECT 
    b.borrow_id,
    b.book_id,
    b.patrons_id,
    b.borrow_date,
    bo.title
FROM 
    borrow b
JOIN 
    books bo ON b.book_id = bo.book_id
LEFT JOIN 
    condemned cd ON b.book_id = cd.book_id       
LEFT JOIN 
    missing ms ON b.book_id = ms.book_id 
WHERE 
    b.status = 'Returned' AND cd.book_id IS NULL AND ms.book_id IS NULL  
"""

# Execute the query
cursor.execute(query_borrow)

# Fetch all results
results = cursor.fetchall()

# Convert results to a DataFrame for better visualization
column_borrow = ['Borrow ID', 'Book ID', 'Patrons ID', 'Borrow Date', 'Title']
df = pd.DataFrame(results, columns=column_borrow)


# Calculate Borrow Count
borrow_count = df.groupby(['Patrons ID', 'Book ID']).size().reset_index(name='Borrow Count')

# Merge borrow count back to the original df to include this information
df = pd.merge(df, borrow_count, on=['Patrons ID', 'Book ID'], how='left')


# Close the cursor and connection
cursor.close()
conn.close()

print(df)

# Pivot the DataFrame to create a User-Item matrix (Patrons as rows, Books as columns)
user_item_matrix = df.pivot_table(index='Patrons ID', columns='Book ID', values='Borrow ID', aggfunc='count').fillna(0)
# print(user_item_matrix)

# Split the data into training and test sets
train_data, test_data = train_test_split(df, test_size=0.2, random_state=42)

# Create a training user-item matrix
train_user_item_matrix = train_data.pivot_table(index='Patrons ID', columns='Book ID', values='Borrow ID').fillna(0)

# Convert the training matrix to a sparse matrix format for efficient SVD computation
sparse_matrix = csr_matrix(train_user_item_matrix.values)

# Perform SVD using TruncatedSVD from scikit-learn
n_components = 15          
svd = TruncatedSVD(n_components=n_components, random_state=42)
latent_matrix = svd.fit_transform(sparse_matrix)

# Store the user latent factors and item latent factors separately
user_latent_factors = normalize(latent_matrix, axis=1)
item_latent_factors = normalize(svd.components_.T, axis=0)

def get_collaborative_filtering_recommendations(patrons_id, top_n):
    user_idx = user_item_matrix.index.get_loc(patrons_id)
    user_latent = user_latent_factors[user_idx]
    
    # Compute similarity between user and items
    scores = item_latent_factors.dot(user_latent)

    # Filter already borrowed books
    already_borrowed_books = user_item_matrix.loc[patrons_id][user_item_matrix.loc[patrons_id] > 0].index
    book_scores = [(book_id, score) for book_id, score in zip(user_item_matrix.columns, scores) if book_id not in already_borrowed_books]

    # Sort and select top N recommendations
    ranked_books = sorted(book_scores, key=lambda x: x[1], reverse=True)
    top_recommended_books = ranked_books[:top_n]

    # Get titles for the recommended books
    recommended_books = [book_id for book_id, score in top_recommended_books]
    title_mapping = dict(zip(df['Book ID'], df['Title']))
    
    recommended_titles = [title_mapping.get(book_id, "Unknown Title") for book_id in recommended_books]
    recommended_scores = [score for book_id, score in top_recommended_books]

    return list(zip(recommended_books, recommended_titles, recommended_scores))

def calculate_metrics(test_data):
    total_mae = 0
    total_mse = 0
    num_samples = 0

    test_user_item_matrix = test_data.pivot_table(index='Patrons ID', columns='Book ID', values='Borrow Count').fillna(0)

    for patron_id in test_user_item_matrix.index:
        # Get collaborative filtering recommendations with scores
        recommendations = get_collaborative_filtering_recommendations(patron_id, user_item_matrix.shape[1])
        recommended_books = {book_id: score for book_id, title, score in recommendations}

        for book_id in test_user_item_matrix.columns:
            actual_borrow_count = test_user_item_matrix.at[patron_id, book_id]

            # Get the predicted score for this book (or 0 if the book was not recommended)
            predicted_borrow_count = recommended_books.get(book_id, 0)

            # Only calculate metrics for books that were borrowed or predicted to be borrowed
            if actual_borrow_count > 0 or predicted_borrow_count > 0:
                total_mae += abs(predicted_borrow_count - actual_borrow_count)
                total_mse += (predicted_borrow_count - actual_borrow_count) ** 2
                num_samples += 1

    if num_samples > 0:
        mae = total_mae / num_samples
        mse_value = total_mse / num_samples
        rmse = np.sqrt(mse_value)
    else:
        mae = 0
        mse_value = 0
        rmse = 0

    return mae, rmse, mse_value


# Calculate and display metrics
mae, rmse, mse_value = calculate_metrics(test_data)
print(f"Mean Absolute Error (MAE): {mae:.4f}")
print(f"Root Mean Squared Error (RMSE): {rmse:.4f}")
print(f"Mean Squared Error (MSE): {mse_value:.4f}")

# Example call to get collaborative filtering recommendations
collab_recommended_books = get_collaborative_filtering_recommendations(int(patrons_id), 10)

# Displaying the recommendations
print("Collab Recommended Books (ID, Title, and Score):")
for book_id, title, score in collab_recommended_books:
    print(f"- ID: {book_id}, Title: {title}, Score: {score:.4f}")
print("")

if __name__ == "__main__":
    try:
        # Create a response list with only book IDs
        response = [book_id for book_id, title, score in collab_recommended_books]

        # Ensure the response is properly formatted as JSON
        json_response = json.dumps(response, ensure_ascii=True)

        # Print the response to be captured by PHP
        print(json_response)

    except Exception as e:
        print(f"Error: {str(e)}")
