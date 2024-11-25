from utils import *

from implicit.als import AlternatingLeastSquares

import os
import threadpoolctl

# Set OpenBLAS thread limit to prevent performance issues
os.environ['OPENBLAS_NUM_THREADS'] = '1'
threadpoolctl.threadpool_limits(1, 'blas')

# Start timing the script
start_time = time.time()

# Create a connection to the database
conn = create_connection()
patrons_id = 1

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

# Split the data into training and test sets
train_data, test_data = train_test_split(df, test_size=0.2, random_state=42)

# Check DataFrame structure
print("DataFrame Columns:", df.columns)
print("First few rows of the DataFrame:")
print(df.head())

# Prepare the data for ALS
train_user_item_matrix = train_data.pivot(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)
test_user_item_matrix = test_data.pivot(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)

# Create sparse matrices for training and testing
sparse_train_matrix = csr_matrix(train_user_item_matrix.values)
sparse_test_matrix = csr_matrix(test_user_item_matrix.values)

# Create user-item matrix and sparse matrix (for recommendation function)
user_item_matrix = df.pivot(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)
sparse_matrix = csr_matrix(user_item_matrix.values)

# Initialize the ALS model
model = AlternatingLeastSquares(factors=40, regularization=0.01, iterations=300)

# Train the model using explicit ratings
model.fit(sparse_train_matrix)

# Function to get collaborative filtering recommendations
def get_collaborative_filtering_recommendations(patrons_id, num_recommendations=10):
    # Get the user's row index
    user_idx = user_item_matrix.index.get_loc(patrons_id)

    # Get scores for all items (books)
    book_ids, scores = model.recommend(user_idx, sparse_matrix[user_idx], N=num_recommendations, filter_already_liked_items=True)

    recommended_books = []
    
    # Create a mapping from the book index to the actual book ID
    index_to_book_id = user_item_matrix.columns.tolist()  # This gets the actual book IDs used in the sparse matrix

    # Combine book indices with their actual book IDs
    for idx in range(len(book_ids)):
        book_index = book_ids[idx]  # Get the index returned by the model
        book_id = index_to_book_id[book_index]  # Map index back to actual book ID
        score = scores[idx]            # Get the corresponding score
        title = df.loc[df['Book ID'] == book_id, 'Title'].values[0] if not df.loc[df['Book ID'] == book_id, 'Title'].empty else "Unknown Title"
        recommended_books.append((book_id, title, score))
    
    return recommended_books

# Check what books the user has rated
cursor.execute("SELECT book_id, ratings FROM ratings WHERE patrons_id = %s", (patrons_id,))
user_ratings = cursor.fetchall()
print("User Ratings:", user_ratings)  # This will show all books the user has rated

# Get recommendations for the user
recommended_books = get_collaborative_filtering_recommendations(patrons_id)
print("Recommended Books:", recommended_books)  # Debugging

import numpy as np

# Function to calculate MAE, RMSE, and MSE
def calculate_errors(true_ratings, predicted_ratings):
    # Calculate MAE
    mae = np.mean(np.abs(true_ratings - predicted_ratings))
    
    # Calculate MSE
    mse = np.mean((true_ratings - predicted_ratings) ** 2)
    
    # Calculate RMSE
    rmse = np.sqrt(mse)
    
    return mae, rmse, mse

# Function to get predicted ratings for a user on the test set
def get_predicted_ratings(user_id, model, test_user_item_matrix, sparse_test_matrix):
    user_idx = test_user_item_matrix.index.get_loc(user_id)
    _, predicted_ratings = model.recommend(user_idx, sparse_test_matrix[user_idx], N=sparse_test_matrix.shape[1], filter_already_liked_items=True)
    return predicted_ratings

# Get the true ratings for the user in the test set
true_ratings = test_user_item_matrix.loc[patrons_id][test_user_item_matrix.loc[patrons_id] != 0].values

# Get the predicted ratings for the user in the test set
predicted_ratings = get_predicted_ratings(patrons_id, model, test_user_item_matrix, sparse_test_matrix)

# Get the indices of the books the user has NOT rated in the test set
unrated_book_indices = np.where(test_user_item_matrix.loc[patrons_id] == 0)[0]

# Filter predicted ratings and true ratings to include only unrated books
filtered_predicted_ratings = predicted_ratings[unrated_book_indices]
filtered_true_ratings = test_user_item_matrix.loc[patrons_id].values[unrated_book_indices]

# Calculate MAE, RMSE, and MSE
mae, rmse, mse = calculate_errors(filtered_true_ratings, filtered_predicted_ratings)

# Print the errors
print("Mean Absolute Error (MAE):", mae)
print("Root Mean Square Error (RMSE):", rmse)
print("Mean Square Error (MSE):", mse)

# End timing the script
end_time = time.time()

# Calculate and print the execution time
execution_time = end_time - start_time
print(f"Execution time: {execution_time:.4f} seconds")