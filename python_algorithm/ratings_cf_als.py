from utils import *

conn = create_connection()


cursor = conn.cursor()

# Fetch ratings and book titles
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
    b.book_id NOT IN (SELECT book_id FROM missing) AND
    b.book_id NOT IN (SELECT book_id FROM condemned)  
"""
cursor.execute(query_ratings)
results = cursor.fetchall()

# Convert the query results into a DataFrame
columns_ratings = ['Rating ID', 'Book ID', 'Patrons ID', 'Date', 'Ratings', 'Title']
df = pd.DataFrame(results, columns=columns_ratings)

# Fetch rated books for the specified user
patrons_id = int(sys.argv[1])
query_user_ratings = """
SELECT book_id 
FROM ratings 
WHERE patrons_id = %s
"""
cursor.execute(query_user_ratings, (patrons_id,))
rated_books = {row[0] for row in cursor.fetchall()}  # Fetch and convert to a set for quick lookup

# Debug print for rated books
# print(f"Rated Books for User {patrons_id}: {rated_books}")

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

# Predict ratings
predicted_ratings = np.dot(user_factors, item_factors.T)



number = int(sys.argv[2])  # Number of recommendations to return

# Function to get top N recommendations for a user
def get_recommendations_for_user(user_id, rated_books_set, num_recommendations=number):
    user_idx = train_user_item_matrix.index.get_loc(user_id)
    user_ratings = predicted_ratings[user_idx, :]

    # Exclude rated books by filtering user ratings
    # Create a mask for unrated books
    unrated_mask = np.array([train_user_item_matrix.columns[idx] not in rated_books_set for idx in range(len(user_ratings))])
    unrated_ratings = user_ratings[unrated_mask]

    # Get top N indices from unrated ratings
    top_indices = np.argsort(unrated_ratings)[-num_recommendations:][::-1]
    recommended_books = [(train_user_item_matrix.columns[idx], unrated_ratings[idx]) for idx in top_indices]

    # Debug print for recommended books after filtering
    # print(f"Recommended Books After Filtering: {recommended_books}")

    return recommended_books

# Get recommendations for the user
recommended_books = get_recommendations_for_user(patrons_id, rated_books)

# Custom JSON encoder to handle numpy types
class NumpyEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, np.integer):
            return int(obj)
        elif isinstance(obj, np.floating):
            return float(obj)
        elif isinstance(obj, np.ndarray):
            return obj.tolist()
        return super(NumpyEncoder, self).default(obj)

if __name__ == "__main__":
    try:
        # Call the function to get the recommended books
        recommendations = get_recommendations_for_user(patrons_id, rated_books)

        # Extract just the book IDs from the recommendations
        response = [book_id for book_id, score in recommendations]
        
        # Convert the list of book IDs to JSON format using the custom encoder
        json_response = json.dumps(response, ensure_ascii=True, cls=NumpyEncoder)

        # Output the JSON response
        print(json_response)

    except Exception as e:
        print(f"Error: {str(e)}")