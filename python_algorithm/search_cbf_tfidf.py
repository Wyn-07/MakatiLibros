from utils import *

conn = create_connection()

cursor = conn.cursor(prepared=True)

# SQL query to fetch books data
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
WHERE 
    b.book_id NOT IN (SELECT book_id FROM condemned)
    AND b.book_id NOT IN (SELECT book_id FROM missing)    
"""

cursor.execute(query_books)

# Fetch results and convert them to a DataFrame
results = cursor.fetchall()

column_books = ['Book ID', 'Acc Number', 'Class Number', 'Title', 'Copyright', 'Image', 'Author Name', 'Category Name']
df = pd.DataFrame(results, columns=column_books)

# Content-Based Filtering
# Prepare the content DataFrame for filtering
book_content_df = df[['Book ID', 'Title', 'Author Name', 'Category Name', 'Copyright']].copy()

# Create the 'Content' column by combining the relevant text fields
book_content_df['Content'] = book_content_df[['Title', 'Author Name', 'Category Name', 'Copyright']].astype(str).fillna('').agg(' '.join, axis=1)


# Use TF-IDF vectorizer to convert content into a matrix of TF-IDF features
tfidf_vectorizer = TfidfVectorizer(
    stop_words='english',  
    ngram_range=(1, 1),  
    min_df=0.001,              
    max_features=10000,  
)

# Transform content using the TF-IDF vectorizer
content_matrix = tfidf_vectorizer.fit_transform(book_content_df['Content'])


# Use NearestNeighbors (KNN) for recommendations based on cosine similarity
nn = NearestNeighbors(n_neighbors=10, metric='cosine', n_jobs=-1)  # Utilize cosine similarity
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




if __name__ == "__main__":
    try:
        # Get book ID from command line argument
        book_id = int(sys.argv[1])  
        
        # Get the top 10 content-based recommendations
        content_recommended_books = get_content_based_recommendations(book_id, 10)
        
        # Prepare response
        response = [int(book_id) for book_id, title, score in content_recommended_books]

        # Convert to JSON and print
        json_response = json.dumps(response, ensure_ascii=True)
        
        print(json_response)

    except Exception as e:
        
        print(f"Error: {str(e)}")
        

