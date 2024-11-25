from utils import *

conn = create_connection()



# Create a cursor object to interact with the database
cursor = conn.cursor()


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
WHERE 
    b.status = 'Returned' AND b.book_id NOT IN (SELECT book_id FROM condemned)
    AND b.book_id NOT IN (SELECT book_id FROM missing)
"""

# Execute the query and fetch results
cursor.execute(query_borrow)
results = cursor.fetchall()

column_borrow = ['Borrow ID', 'Book ID', 'Patrons ID', 'Borrow Date', 'Title']
df = pd.DataFrame(results, columns=column_borrow)

# User-Item matrix (Patrons as rows, Books as columns), filling NaN with 0
user_item_matrix = df.pivot_table(index='Patrons ID', columns='Book ID', values='Borrow ID', aggfunc='count').fillna(0)

# Split the data into training and test sets
train_data, test_data = train_test_split(df, test_size=0.3, random_state=42)

# Create a training user-item matrix
train_user_item_matrix = train_data.pivot_table(index='Patrons ID', columns='Book ID', values='Borrow ID', aggfunc='count').fillna(0)

# Convert to a sparse matrix for ALS
sparse_train_matrix = csr_matrix(train_user_item_matrix.values)

# ALS Parameters
n_factors = 5  # Number of latent factors
n_iterations = 15  # Number of iterations
lambda_reg = 0.1  # Regularization parameter

# ALS initialization
R = train_user_item_matrix.values
n_users, n_items = R.shape
np.random.seed(42)
user_factors = np.random.rand(n_users, n_factors)
item_factors = np.random.rand(n_items, n_factors)

# ALS update functions
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

# Train ALS model
for iteration in range(n_iterations):
    user_factors = update_user_factors(R, user_factors, item_factors, lambda_reg)
    item_factors = update_item_factors(R, user_factors, item_factors, lambda_reg)

# Generate predicted borrowing scores
predicted_borrowing_scores = np.dot(user_factors, item_factors.T)

# Function to get collaborative filtering recommendations based on borrowing behavior
def get_collaborative_filtering_recommendations(patrons_id, top_n):
    user_idx = train_user_item_matrix.index.get_loc(patrons_id)
    user_borrow_scores = predicted_borrowing_scores[user_idx, :]  # Use predicted scores for borrowing

    # Filter already borrowed books
    already_borrowed_books = user_item_matrix.loc[patrons_id][user_item_matrix.loc[patrons_id] > 0].index
    book_scores = [(train_user_item_matrix.columns[idx], user_borrow_scores[idx]) for idx in range(len(user_borrow_scores)) if train_user_item_matrix.columns[idx] not in already_borrowed_books]

    # Sort and select top N recommendations
    ranked_books = sorted(book_scores, key=lambda x: x[1], reverse=True)
    top_recommended_books = ranked_books[:top_n]

    # Get titles for the recommended books
    recommended_books = [book_id for book_id, score in top_recommended_books]
    title_mapping = dict(zip(df['Book ID'], df['Title']))

    recommended_titles = [title_mapping.get(book_id, "Unknown Title") for book_id in recommended_books]
    recommended_scores = [score for book_id, score in top_recommended_books]

    return list(zip(recommended_books, recommended_titles, recommended_scores))


patrons_id = int(sys.argv[1])  
number = int(sys.argv[2])  

collab_recommended_books = get_collaborative_filtering_recommendations(patrons_id, number)


if __name__ == "__main__":
    try:
        response = [int(book_id) for book_id, title, score in collab_recommended_books]
        print(json.dumps(response, ensure_ascii=True))
    except Exception as e:
        print(f"Error: {str(e)}")


