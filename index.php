<?php
require_once 'functions.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['user_id'])) {
            echo json_encode(getUser($_GET['user_id']));
        } else {
            echo json_encode(getAllUsers());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode(createUser($data));
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode(updateUser($_GET['user_id'], $data));
        break;
    case 'DELETE':
        deleteUser($_GET['user_id']);
        echo json_encode(['message' => 'User deleted successfully']);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

?>