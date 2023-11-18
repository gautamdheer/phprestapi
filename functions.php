<?php
require_once 'config.php';

function connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getAllUsers() {
    $conn = connect();
    $result = $conn->query("SELECT * FROM users");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $users;
}

function getUser($id) {
    $conn = connect();
    $result = $conn->query("SELECT * FROM users WHERE user_id = $id");
    $user = $result->fetch_assoc();
    $conn->close();
    return $user;
}

function createUser($data) {
    $conn = connect();
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
    $id = $conn->insert_id;
    $conn->close();
    return getUser($id);
}

function updateUser($id, $data) {
    $conn = connect();
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $conn->query("UPDATE users SET username='$username', email='$email', password='$password' WHERE user_id = $id");
    $conn->close();
    return getUser($id);
}

function deleteUser($id) {
    $conn = connect();
    $conn->query("DELETE FROM users WHERE user_id = $id");
    $conn->close();
}


?>