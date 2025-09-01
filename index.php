<?php
require_once 'includes/database.php';

class Project {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Voeg een nieuw project toe
     */
    public function addProject($title, $description, $technologies, $image_url = null, $project_url = null, $github_url = null) {
        try {
            $sql = "INSERT INTO projects (title, description, technologies, image_url, project_url, github_url, created_at) 
                    VALUES (:title, :description, :technologies, :image_url, :project_url, :github_url, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':technologies' => $technologies,
                ':image_url' => $image_url,
                ':project_url' => $project_url,
                ':github_url' => $github_url
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Fout bij toevoegen project: " . $e->getMessage());
        }
    }
    
    /**
     * Haal alle projecten op
     */
    public function getAllProjects() {
        try {
            $sql = "SELECT * FROM projects ORDER BY created_at DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Fout bij ophalen projecten: " . $e->getMessage());
        }
    }
    
    /**
     * Haal een specifiek project op
     */
    public function getProject($id) {
        try {
            $sql = "SELECT * FROM projects WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Fout bij ophalen project: " . $e->getMessage());
        }
    }
    
    /**
     * Update een project
     */
    public function updateProject($id, $title, $description, $technologies, $image_url = null, $project_url = null, $github_url = null) {
        try {
            $sql = "UPDATE projects SET 
                    title = :title, 
                    description = :description, 
                    technologies = :technologies, 
                    image_url = :image_url, 
                    project_url = :project_url, 
                    github_url = :github_url,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':technologies' => $technologies,
                ':image_url' => $image_url,
                ':project_url' => $project_url,
                ':github_url' => $github_url
            ]);
        } catch (PDOException $e) {
            throw new Exception("Fout bij updaten project: " . $e->getMessage());
        }
    }
    
    /**
     * Verwijder een project
     */
    public function deleteProject($id) {
        try {
            $sql = "DELETE FROM projects WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Fout bij verwijderen project: " . $e->getMessage());
        }
    }
    
    /**
     * Zoek projecten op technologie
     */
    public function searchByTechnology($technology) {
        try {
            $sql = "SELECT * FROM projects WHERE technologies LIKE :technology ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':technology' => '%' . $technology . '%']);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Fout bij zoeken projecten: " . $e->getMessage());
        }
    }
}
