from utils import *

# Start timing the script
start_time = time.time()

conn = create_connection()
cursor = conn.cursor(prepared=True)

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
LEFT JOIN 
    condemned cd ON b.book_id = cd.book_id       
LEFT JOIN 
    missing ms ON b.book_id = ms.book_id         
WHERE 
    cd.book_id IS NULL AND ms.book_id IS NULL     
"""

cursor.execute(query_books)

results = cursor.fetchall()

column_books = ['Book ID', 'Title', 'Copyright', 'Author Name', 'Category Name']
df = pd.DataFrame(results, columns=column_books)


# Content-Based Filtering
# Prepare the content DataFrame for filtering
book_content_df = df.copy()  # Use the entire DataFrame to create content

# Create the 'Content' column by combining the relevant text fields
book_content_df['Content'] = book_content_df[['Title', 'Author Name', 'Category Name', 'Copyright']].astype(str).fillna('').agg(' '.join, axis=1)

# Optimize TF-IDF vectorization
tfidf_vectorizer = TfidfVectorizer(
    stop_words='english',
    ngram_range=(1, 1),  
    min_df=0.001,         
    max_features=10000,   
)

# Fit and transform the content
content_matrix = tfidf_vectorizer.fit_transform(book_content_df['Content'])

# Use NearestNeighbors for faster recommendations
nn = NearestNeighbors(n_neighbors=10, metric='cosine', n_jobs=-1)  # Use all available cores
nn.fit(content_matrix)

# Function to get content-based recommendations based on book content (not just title)
def get_content_based_recommendations(book_id, n_recommendations=10):
    # Find the index of the input book_id in the DataFrame
    index = book_content_df[book_content_df['Book ID'] == book_id].index[0]

    # Adjust n_neighbors to potentially fetch more than needed in case of duplicates
    distances, indices = nn.kneighbors(content_matrix[index], n_neighbors=n_recommendations + 10)  # Fetch extra neighbors to handle duplicates

    recommendations_list = []
    seen_titles = set()  # Track already recommended book titles

    i = 1  # Start from 1 to skip the input book itself
    while len(recommendations_list) < n_recommendations and i < len(distances[0]):
        recommended_book_id = book_content_df.iloc[indices[0][i]]['Book ID']
        title = book_content_df.iloc[indices[0][i]]['Title']

        # Check if the title has already been recommended or is the input book's title
        if title not in seen_titles and recommended_book_id != book_id:
            similarity = 1 - distances[0][i]  # Convert distance to similarity
            recommendations_list.append((recommended_book_id, title, similarity))
            seen_titles.add(title)  # Add the title to the set to avoid duplicates

        i += 1  # Move to the next neighbor

    return recommendations_list

# Get the book ID passed from PHP as an argument
# book_id = int(sys.argv[1])  
book_id = 2


# SQL query to fetch the title for the specific book ID
query_title = """
SELECT title 
FROM books 
WHERE book_id = %s
"""

# Execute the query, passing the book_id as a parameter
cursor.execute(query_title, (book_id,))

# Fetch the result (title) from the query
result = cursor.fetchone()

# Check if a result was found
if result:
    title = result[0]
    print(f"The title of the book with book_id {book_id} is: {title}")
else:
    print(f"No book found with book_id {book_id}")

# Close the cursor and connection
cursor.close()
conn.close()

    
# Get content-based recommendations for the given book ID
content_recommended_books = get_content_based_recommendations(book_id)

# Displaying the recommendations in a list format along with cosine similarity
print("Content-Based Recommended Books (ID, Title, and Cosine Similarity):")
for book_id, title, similarity in content_recommended_books:
    print(f"- ID: {book_id}, Title: {title}, Cosine Similarity: {similarity:.4f}")
print("")


if __name__ == "__main__":
    try:
        response = [int(book_id) for book_id, title, score in content_recommended_books]
        json_response = json.dumps(response, ensure_ascii=True)
        print(json_response)

    except Exception as e:
        print(f"Error: {str(e)}")


# End timing the script
end_time = time.time()

# Calculate and print the execution time
execution_time = end_time - start_time
print(f"Execution time: {execution_time:.4f} seconds")

