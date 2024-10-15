import mysql.connector
import pandas as pd
from sklearn.decomposition import TruncatedSVD
from sklearn.model_selection import train_test_split
from sklearn.metrics import mean_absolute_error, mean_squared_error
from scipy.sparse import csr_matrix
import numpy as np
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

# SQL query to fetch all necessary data for collaborative filtering
query_ratings = """
SELECT 
    r.rating_id,
    r.book_id,
    r.patrons_id,
    r.date,
    r.ratings,
    b.title
FROM 
    ratings r
JOIN 
    books b ON r.book_id = b.book_id
LEFT JOIN 
    condemned cd ON b.book_id = cd.book_id       
LEFT JOIN 
    missing ms ON b.book_id = ms.book_id         
WHERE 
    cd.book_id IS NULL AND ms.book_id IS NULL    
"""


# Execute the query
cursor.execute(query_ratings)

# Fetch all results
results = cursor.fetchall()

# Convert results to a DataFrame for better visualization
column_ratings = ['Rating ID', 'Book ID', 'Patrons ID', 'Date', 'Ratings', 'Title']
df = pd.DataFrame(results, columns=column_ratings)

# Close the cursor and connection
cursor.close()
conn.close()

# Pivot the DataFrame to create a User-Item matrix (Patrons as rows, Books as columns)
user_item_matrix = df.pivot_table(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)

# Split the data into training and test sets
train_data, test_data = train_test_split(df, test_size=0.2, random_state=42)

# Create a training user-item matrix
train_user_item_matrix = train_data.pivot_table(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)

# Convert the training matrix to a sparse matrix format for efficient SVD computation
sparse_matrix = csr_matrix(train_user_item_matrix.values)

# Perform SVD using TruncatedSVD from scikit-learn
n_components = 5  # You can experiment with this number
svd = TruncatedSVD(n_components=n_components, random_state=42)
latent_matrix = svd.fit_transform(sparse_matrix)

# Store the user latent factors and item latent factors separately
user_latent_factors = latent_matrix  # User latent factors
item_latent_factors = svd.components_.T  # Item latent factors (transpose)

def get_collaborative_filtering_recommendations(patrons_id, top_n):
    # Find the latent representation of the user
    user_idx = user_item_matrix.index.get_loc(patrons_id)
    user_latent = user_latent_factors[user_idx]

    # Compute the similarity between the user's latent vector and all item latent vectors
    scores = item_latent_factors.dot(user_latent)

    # Create a list of tuples (book_id, score)
    book_scores = list(zip(user_item_matrix.columns, scores))

    # Filter out books that the user has already rated
    already_rated_books = user_item_matrix.loc[patrons_id][user_item_matrix.loc[patrons_id] > 0].index
    book_scores = [(book_id, score) for book_id, score in book_scores if book_id not in already_rated_books]

    # Sort the scores in descending order
    ranked_books = sorted(book_scores, key=lambda x: x[1], reverse=True)

    # Get the top N recommendations
    top_recommended_books = ranked_books[:top_n]

    # Get the corresponding titles and scores
    recommended_books = [book_id for book_id, score in top_recommended_books]

    # Ensure titles match the IDs correctly
    recommended_titles = [df.loc[df['Book ID'] == book_id, 'Title'].values[0] for book_id in recommended_books]
    
    recommended_scores = [score for book_id, score in top_recommended_books]

    return list(zip(recommended_books, recommended_titles, recommended_scores))

# Calculate predictions for MAE, RMSE, and MSE
def calculate_ratings_metrics(test_data):
    total_mae = 0
    total_mse = 0
    num_samples = 0

    # Create a test matrix from the ratings data
    test_user_item_matrix = test_data.pivot_table(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)

    for patron_id in test_user_item_matrix.index:
        # Get collaborative filtering recommendations with scores
        recommendations = get_collaborative_filtering_recommendations(patron_id, user_item_matrix.shape[1])
        recommended_books = {book_id: score for book_id, title, score in recommendations}

        for book_id in test_user_item_matrix.columns:
            actual_rating = test_user_item_matrix.at[patron_id, book_id]

            # Get the predicted score for this book (or 0 if the book was not recommended)
            predicted_rating = recommended_books.get(book_id, 0)

            # Only calculate metrics for books that were rated or predicted to be rated
            if actual_rating > 0 or predicted_rating > 0:
                total_mae += abs(predicted_rating - actual_rating)
                total_mse += (predicted_rating - actual_rating) ** 2
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
mae, rmse, mse_value = calculate_ratings_metrics(test_data)
print(f"Mean Absolute Error (MAE): {mae:.4f}")
print(f"Root Mean Squared Error (RMSE): {rmse:.4f}")
print(f"Mean Squared Error (MSE): {mse_value:.4f}")

# Example call to get collaborative filtering recommendations
collab_recommended_books = get_collaborative_filtering_recommendations(patrons_id, 10)

# Displaying the recommendations
print("Collab Recommended Books (ID, Title, and Score):")
for book_id, title, score in collab_recommended_books:
    print(f"- ID: {book_id}, Title: {title}, Score: {score:.4f}")
