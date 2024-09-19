<?php
function getAuthorList($pdo) {
    // Prepare the SQL query to select all from the author table in ascending alphabetical order
    $query = "SELECT * FROM author ORDER BY author ASC";
    
    // Prepare and execute the statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all results
    $authorList = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $authorList[] = [
            'author_id' => $row['author_id'],
            'author' => $row['author']
        ];
    }
    return $authorList;
}
?>
