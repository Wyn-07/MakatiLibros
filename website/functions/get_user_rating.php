<?php
session_start();
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=librodb', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $book_id = $data['book_id'];
    $patrons_id = $data['patrons_id'];

    $stmt = $pdo->prepare('SELECT ratings FROM ratings WHERE book_id = :book_id AND patrons_id = :patrons_id LIMIT 1');
    $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
    $stmt->bindParam(':patrons_id', $patrons_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['success' => true, 'rating' => $result['ratings']]);
    } else {
        echo json_encode(['success' => true, 'rating' => null]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
