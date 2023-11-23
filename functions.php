<?php
require_once 'config.php';
require_once 'vendor/autoload.php'; // Include the Composer autoloader for firebase/php-jwt
 
use Firebase\JWT\JWT;


function connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
function generateToken($userId) {
    // You can use a library like Firebase JWT to generate a secure token
    // Example using Firebase JWT: https://github.com/firebase/php-jwt
  
    $key = "123456789abcd"; // Replace with a secure secret key
    $tokenPayload = [
        "iss" => "gautam", // Issuer
        "aud" => "public", // Audience
        "iat" => time(), // Issued At
        "exp" => time() + (60 * 60 * 24), // Expiration (1 day)
        "sub" => $userId, // Subject (User ID)
    ];

    return JWT::encode($tokenPayload, $key, 'HS256');
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
    $result = $conn->query("SELECT user_id, username, email FROM users WHERE user_id = $id");
    $user = $result->fetch_assoc();
    $conn->close();
    return $user;
}
// updating token
function updateToken($id, $token) {
    $conn = connect();
    $conn->query("UPDATE users SET token = '$token' WHERE user_id = $id");
    $conn->close();
}

// function createUser($data) {
//     $conn = connect();
//     $username = $data['username'];
//     $email = $data['email'];
//     $password = $data['password'];
//     $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
//     $id = $conn->insert_id;
//     $conn->close();
//     return getUser($id);
// }

function registerUser($username, $email, $password) {
    $conn = connect();
    // Perform basic validation
    // Check for unique username
    $result = $conn->query("SELECT user_id FROM users WHERE username = '$username'");
    if ($result->num_rows > 0) {
        $conn->close();
        return ['error' => 'Username already exists'];
    }

    // Check for valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $conn->close();
        return ['error' => 'Invalid email format'];
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')");
    $userId = $conn->insert_id;

     // Generate and store a secure token
     $token = generateToken($userId);
     updateToken($userId, $token);

    $conn->close();

    return ['token' => $token, 'user' => getUser($userId)];

}

function loginUser($username,$password){
    $conn = connect();
    $result = $conn->query("SELECT user_id, username, password FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();
    $conn->close();

    if($user && password_verify($password, $user['password'])){
        // generate and store secure token
        // $token = bin2hex(random_bytes(32));
        $token = generateToken($user['user_id']);
        updateToken($user['user_id'], $token);
        return ['token'=>$token];    
    }
    else{
        return['error'=>'Invalid username and password'];
    }
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


function getUserIdFromToken($token) {
    $conn = connect();
    $result = $conn->query("SELECT user_id FROM users WHERE token = '$token'");
    $id = $result->fetch_assoc()['user_id'];
    $conn->close();
    return $id;
}


?>