<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'index.php';

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
    $projectManager = new Project();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    switch ($action) {
        case 'getAllProjects':
            $projects = $projectManager->getAllProjects();
            sendResponse(true, $projects);
            break;

        case 'getProject':
            $id = $_GET['id'] ?? 0;
            if (!$id) {
                sendResponse(false, null, 'Project ID is vereist');
            }
            
            $project = $projectManager->getProject($id);
            if ($project) {
                sendResponse(true, $project);
            } else {
                sendResponse(false, null, 'Project niet gevonden');
            }
            break;

        case 'addProject':
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $technologies = $_POST['technologies'] ?? '';
            $image_url = $_POST['image_url'] ?? null;
            $project_url = $_POST['project_url'] ?? null;
            $github_url = $_POST['github_url'] ?? null;

            if (empty($title) || empty($description) || empty($technologies)) {
                sendResponse(false, null, 'Titel, beschrijving en technologieën zijn verplicht');
            }

            $projectId = $projectManager->addProject(
                $title, 
                $description, 
                $technologies, 
                $image_url, 
                $project_url, 
                $github_url
            );
            
            sendResponse(true, ['id' => $projectId], 'Project succesvol toegevoegd');
            break;

        case 'updateProject':
            $id = $_POST['projectId'] ?? 0;
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $technologies = $_POST['technologies'] ?? '';
            $image_url = $_POST['image_url'] ?? null;
            $project_url = $_POST['project_url'] ?? null;
            $github_url = $_POST['github_url'] ?? null;

            if (!$id || empty($title) || empty($description) || empty($technologies)) {
                sendResponse(false, null, 'ID, titel, beschrijving en technologieën zijn verplicht');
            }

            $success = $projectManager->updateProject(
                $id,
                $title, 
                $description, 
                $technologies, 
                $image_url, 
                $project_url, 
                $github_url
            );
            
            if ($success) {
                sendResponse(true, null, 'Project succesvol bijgewerkt');
            } else {
                sendResponse(false, null, 'Fout bij bijwerken van project');
            }
            break;

        case 'deleteProject':
            $id = $_POST['id'] ?? $_GET['id'] ?? 0;
            
            if (!$id) {
                sendResponse(false, null, 'Project ID is vereist');
            }

            $success = $projectManager->deleteProject($id);
            
            if ($success) {
                sendResponse(true, null, 'Project succesvol verwijderd');
            } else {
                sendResponse(false, null, 'Fout bij verwijderen van project');
            }
            break;

        case 'searchByTechnology':
            $technology = $_GET['technology'] ?? '';
            
            if (empty($technology)) {
                sendResponse(false, null, 'Zoekterm is vereist');
            }

            $projects = $projectManager->searchByTechnology($technology);
            sendResponse(true, $projects);
            break;

        default:
            sendResponse(false, null, 'Onbekende actie: ' . $action);
            break;
    }

} catch (Exception $e) {
    sendResponse(false, null, 'Server fout: ' . $e->getMessage());
}
?>
