<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //if error, show exceptions

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// echo '<pre>';
// var_dump($id);
// echo '</pre>';

$statement = $pdo->prepare('DELETE FROM products where id = :id');
$statement->bindValue(':id', $id);
$statement->execute();

header("Location: index.php");