<?php
// require controller
require_once '../app/controllers/Api/TaskController.php';

$controller = new TaskController();

// Routing
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        header('Content-Type: application/json');
        try {
           echo $controller->index();
        } catch (JsonException $e) {
            // log error
        }
        break;
    case 'POST':
        header('Content-Type: application/json');
        try {
            echo $controller->create();
        } catch (JsonException $e) {
            // log error
        }
        break;
    case 'DELETE':
        header('Content-Type: application/json');
        try {
            echo $controller->delete();
        } catch (JsonException $e) {
            // log error
        }
        break;
    default:
        // Method Not Allowed
        http_response_code(405);
        try {
            echo json_encode(array("message" => "Method not allowed"), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            // log error
        }
}