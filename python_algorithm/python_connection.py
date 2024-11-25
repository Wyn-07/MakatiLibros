import mysql.connector

def create_connection():
    """Create and return a MySQL database connection."""
    try:
        conn = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='librodb'
        )
        return conn
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None