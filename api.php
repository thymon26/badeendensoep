<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'controllers/ProjectController.php';

// Functie om JSON response te versturen
function sendResponse($success, $data = null, $message = '') {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ]);
    exit;
}

try {
    $controller = new ProjectController();
    
    // Verbeterde actie detectie
    $action = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? '';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Voor POST requests, probeer zowel form data als JSON
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
        } else {
            // Check voor JSON data
            $input = file_get_contents('php://input');
            $json = json_decode($input, true);
            if ($json && isset($json['action'])) {
                $action = $json['action'];
                // Merge JSON data in $_POST voor verdere verwerking
                $_POST = array_merge($_POST, $json);
            }
        }
    }
    
    // Als actie nog steeds leeg is, check voor directe form submit
    if (empty($action) && !empty($_POST)) {
        // Default actie gebaseerd op aanwezige velden
        if (isset($_POST['title']) && !isset($_POST['projectId'])) {
            $action = 'addProject';
        } elseif (isset($_POST['title']) && isset($_POST['projectId'])) {
            $action = 'updateProject';
        }
    }

    switch ($action) {
        case 'getAllProjects':
            $result = $controller->getAllProjects();
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'getProject':
            $id = $_GET['id'] ?? 0;
            $result = $controller->getProject($id);
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'addProject':
            $result = $controller->addProject($_POST);
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'updateProject':
            $result = $controller->updateProject($_POST);
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'deleteProject':
            $id = $_POST['id'] ?? $_GET['id'] ?? 0;
            $result = $controller->deleteProject($id);
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'searchByTechnology':
            $technology = $_GET['technology'] ?? '';
            $result = $controller->searchByTechnology($technology);
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'searchByCategory':
            $category = $_GET['category'] ?? '';
            $result = $controller->searchByCategory($category);
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'getActiveProjects':
            $result = $controller->getActiveProjects();
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'getCompletedProjects':
            $result = $controller->getCompletedProjects();
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        case 'getAllCategories':
            $result = $controller->getAllCategories();
            sendResponse($result['success'], $result['data'], $result['message']);
            break;

        default:
            sendResponse(false, null, 'Onbekende actie: "' . $action . '"');
            break;
    }

} catch (Exception $e) {
    sendResponse(false, null, 'Server fout: ' . $e->getMessage());
}
?>
