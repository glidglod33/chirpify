<?php

$username = "root";
$password = '';

try {
    $conn = new PDO("mysql:host=localhost;dbname=chirpify", $username, $password);
}catch (PDOException $e){
    echo $e->getMessage();
}
$stmt = $conn->prepare("INSERT into accounts(username,password) VALUES (:username, :password)");
$stmt->bindParam(':username', $_POST['gebruikersnaam']);
$stmt->bindParam(':password', $_POST['wachtwoord']);
$stmt->execute();