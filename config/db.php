<?php

$host = 'localhost';
$username = 'root';
$password = ''; // default in XAMPP
$dbname = 'book_reservation';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully to the database.<br>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>