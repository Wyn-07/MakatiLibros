<?php
// Database connection using PDO
// $dsn = 'mysql:host=librodb.c5immkqoex0w.ap-southeast-1.rds.amazonaws.com;dbname=librodb;charset=utf8';
// $username = 'admin';
// $password = 'librodbaws123';

// try {
//     $pdo = new PDO($dsn, $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }

?>


<?php
// Database connection using PDO
$dsn = 'mysql:host=localhost;dbname=librodb;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>