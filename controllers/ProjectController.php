<?php
require_once 'classes/Project.php';

class ProjectController {
    private $projectModel;
    
    public function __construct() {
        $this->projectModel = new Project();
    }
    
    /**
     * Haal alle projecten op
     */
    public function getAllProjects() {
        try {
            $projects = $this->projectModel->getAllProjects();
            return $this->sendResponse(true, $projects);
        } catch (Exception $e) {
            return $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    /**
     * Haal een specifiek project op
     */
    public function getProject($id) {
        try {
            if (!$id) {
                return $this->sendResponse(false, null, 'Project ID is vereist');
            }
            
            $project = $this->projectModel->getProject($id);
            if ($project) {
                return $this->sendResponse(true, $project);
            } else {
                return $this->sendResponse(false, null, 'Project niet gevonden');
            }
        } catch (Exception $e) {
            return $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    /**
     * Voeg een nieuw project toe
     */
    public function addProject($data) {
        try {
            $title = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            $technologies = $data['technologies'] ?? '';
            $image_url = $data['image_url'] ?? null;
            $project_url = $data['project_url'] ?? null;
            $github_url = $data['github_url'] ?? null;

            if (empty($title) || empty($description) || empty($technologies)) {
                return $this->sendResponse(false, null, 'Titel, beschrijving en technologieën zijn verplicht');
            }

            $projectId = $this->projectModel->addProject(
                $title, 
                $description, 
                $technologies, 
                $image_url, 
                $project_url, 
                $github_url
            );
            
            return $this->sendResponse(true, ['id' => $projectId], 'Project succesvol toegevoegd');
        } catch (Exception $e) {
            return $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    /**
     * Update een bestaand project
     */
    public function updateProject($data) {
        try {
            $id = $data['projectId'] ?? 0;
            $title = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            $technologies = $data['technologies'] ?? '';
            $image_url = $data['image_url'] ?? null;
            $project_url = $data['project_url'] ?? null;
            $github_url = $data['github_url'] ?? null;

            if (!$id || empty($title) || empty($description) || empty($technologies)) {
                return $this->sendResponse(false, null, 'ID, titel, beschrijving en technologieën zijn verplicht');
            }

            $success = $this->projectModel->updateProject(
                $id,
                $title, 
                $description, 
                $technologies, 
                $image_url, 
                $project_url, 
                $github_url
            );
            
            if ($success) {
                return $this->sendResponse(true, null, 'Project succesvol bijgewerkt');
            } else {
                return $this->sendResponse(false, null, 'Fout bij bijwerken van project');
            }
        } catch (Exception $e) {
            return $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    /**
     * Verwijder een project
     */
    public function deleteProject($id) {
        try {
            if (!$id) {
                return $this->sendResponse(false, null, 'Project ID is vereist');
            }

            $success = $this->projectModel->deleteProject($id);
            
            if ($success) {
                return $this->sendResponse(true, null, 'Project succesvol verwijderd');
            } else {
                return $this->sendResponse(false, null, 'Fout bij verwijderen van project');
            }
        } catch (Exception $e) {
            return $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    /**
     * Zoek projecten op technologie
     */
    public function searchByTechnology($technology) {
        try {
            if (empty($technology)) {
                return $this->sendResponse(false, null, 'Zoekterm is vereist');
            }

            $projects = $this->projectModel->searchByTechnology($technology);
            return $this->sendResponse(true, $projects);
        } catch (Exception $e) {
            return $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    /**
     * Verstuur JSON response
     */
    private function sendResponse($success, $data = null, $message = '') {
        return [
            'success' => $success,
            'data' => $data,
            'message' => $message
        ];
    }
}
?>
