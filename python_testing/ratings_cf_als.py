import pandas as pd
import numpy as np
from scipy.sparse import csr_matrix
from sklearn.model_selection import train_test_split
import time

# Start timing the script
start_time = time.time()

# Assume you have a function that creates a database connection
from utils import create_connection  
conn = create_connection()

patrons_id = 1  

# Create a cursor object to interact with the database
cursor = conn.cursor()

# SQL query to fetch ratings and book details
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
WHERE 
    b.book_id NOT IN (SELECT book_id FROM condemned)
    AND b.book_id NOT IN (SELECT book_id FROM missing)
"""

# Execute the query
cursor.execute(query_ratings)

# Fetch all results
results = cursor.fetchall()

# Convert the query results into a DataFrame
columns_ratings = ['Rating ID', 'Book ID', 'Patrons ID', 'Date', 'Ratings', 'Title']
df = pd.DataFrame(results, columns=columns_ratings)

# Check the structure of the DataFrame
print("DataFrame structure:")
print(df.head(10))
print()


# Split the data into training and testing sets
train_data, test_data = train_test_split(df, test_size=0.2, random_state=42)

# Create user-item matrices for training and testing
train_user_item_matrix = train_data.pivot(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)
test_user_item_matrix = test_data.pivot(index='Patrons ID', columns='Book ID', values='Ratings').fillna(0)

# Convert to sparse matrices
sparse_train_matrix = csr_matrix(train_user_item_matrix.values)

# ALS Parameters
n_factors = 10  # Number of latent factors
n_iterations = 20  # Number of iterations
lambda_reg = 0.1  # Regularization parameter

# Convert to numpy arrays
R = train_user_item_matrix.values
n_users, n_items = R.shape

# Initialize user and item latent factor matrices randomly
np.random.seed(42)
user_factors = np.random.rand(n_users, n_factors)
item_factors = np.random.rand(n_items, n_factors)

# ALS update function
def update_user_factors(R, user_factors, item_factors, lambda_reg):
    for i in range(n_users):
        A = np.dot(item_factors.T, item_factors) + lambda_reg * np.eye(n_factors)
        b = np.dot(item_factors.T, R[i, :])
        user_factors[i, :] = np.linalg.solve(A, b)
    return user_factors

def update_item_factors(R, user_factors, item_factors, lambda_reg):
    for j in range(n_items):
        A = np.dot(user_factors.T, user_factors) + lambda_reg * np.eye(n_factors)
        b = np.dot(user_factors.T, R[:, j])
        item_factors[j, :] = np.linalg.solve(A, b)
    return item_factors

# Train the ALS model
for iteration in range(n_iterations):
    user_factors = update_user_factors(R, user_factors, item_factors, lambda_reg)
    item_factors = update_item_factors(R, user_factors, item_factors, lambda_reg)
    
    predicted_ratings = np.dot(user_factors, item_factors.T)
    loss = np.sum((R[R > 0] - predicted_ratings[R > 0]) ** 2)

# Predict ratings
predicted_ratings = np.dot(user_factors, item_factors.T)

# Function to get top N recommendations for a user
def get_recommendations_for_user(user_id, num_recommendations=10):
    user_idx = train_user_item_matrix.index.get_loc(user_id)
    user_ratings = predicted_ratings[user_idx, :]
    top_indices = user_ratings.argsort()[-num_recommendations:][::-1]
    
    # Retrieve the recommended books with their titles
    recommended_books = [
        (train_user_item_matrix.columns[idx], user_ratings[idx], df[df['Book ID'] == train_user_item_matrix.columns[idx]]['Title'].values[0]) 
        for idx in top_indices
    ]
    return recommended_books


recommended_books = get_recommendations_for_user(patrons_id)

# Get recommendations for the user
# Create a DataFrame to display the recommendations in a table format
recommendations_df = pd.DataFrame(recommended_books, columns=["Book ID", "Predicted Ratings", "Title"])

# Display the DataFrame as a table
print("Collaborative Recommended Books:")
print(recommendations_df.to_string(index=False))
print()

# Error calculation functions
def calculate_errors(true_ratings, predicted_ratings):
    mae = np.mean(np.abs(true_ratings - predicted_ratings))  # MAE
    mse = np.mean((true_ratings - predicted_ratings) ** 2)  # MSE
    rmse = np.sqrt(mse)  # RMSE
    return mae, rmse, mse

# Get the true ratings for the user in the test set
true_ratings = test_user_item_matrix.loc[patrons_id][test_user_item_matrix.loc[patrons_id] != 0].values

# Get the predicted ratings for the user in the test set
def get_predicted_ratings(user_id, predicted_ratings, test_user_item_matrix):
    user_idx = test_user_item_matrix.index.get_loc(user_id)
    return predicted_ratings[user_idx, :]

predicted_ratings_test = get_predicted_ratings(patrons_id, predicted_ratings, test_user_item_matrix)

# Filter predicted and true ratings
unrated_book_indices = np.where(test_user_item_matrix.loc[patrons_id] == 0)[0]
filtered_predicted_ratings = predicted_ratings_test[unrated_book_indices]
filtered_true_ratings = test_user_item_matrix.loc[patrons_id].values[unrated_book_indices]

# Calculate errors
mae, rmse, mse = calculate_errors(filtered_true_ratings, filtered_predicted_ratings)

# Print error metrics
print("Mean Absolute Error (MAE):", mae)
print("Root Mean Square Error (RMSE):", rmse)
print("Mean Square Error (MSE):", mse)

# End timing the script
end_time = time.time()

# Calculate and print the execution time
execution_time = end_time - start_time
print(f"Execution time: {execution_time:.4f} seconds")
