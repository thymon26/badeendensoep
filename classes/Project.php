<?php
require_once 'includes/database.php';

class Project {
    private $pdo;
    
    // Eigenschappen voor individuele projecten (zoals in de opdracht)
    public $titel;
    private $beschrijving;  // Private eigenschap zoals in opdracht
    public $categorie;
    public $technologies;
    public $image_url;
    public $project_url;
    public $github_url;
    public $startdatum;
    public $einddatum;
    public $id;
    
    /**
     * Constructor - kan gebruikt worden voor database connectie OF voor individueel project
     */
    public function __construct($titel = null, $beschrijving = null, $categorie = null, $technologies = null, $image_url = null, $project_url = null, $github_url = null, $startdatum = null, $einddatum = null) {
        global $pdo;
        $this->pdo = $pdo;
        
        // Als er parameters zijn doorgegeven, maak dan een individueel project object
        if ($titel !== null) {
            $this->titel = $titel;
            $this->beschrijving = $beschrijving;
            $this->categorie = $categorie;
            $this->technologies = $technologies;
            $this->image_url = $image_url;
            $this->project_url = $project_url;
            $this->github_url = $github_url;
            $this->startdatum = $startdatum;
            $this->einddatum = $einddatum;
        }
    }
    
    /**
     * Getter methode voor private eigenschap beschrijving (uit opdracht)
     */
    public function getBeschrijving() {
        return $this->beschrijving;
    }
    
    /**
     * Setter methode voor beschrijving
     */
    public function setBeschrijving($beschrijving) {
        $this->beschrijving = $beschrijving;
    }
    
    /**
     * Methode die projectinformatie retourneert (uit opdracht)
     */
    public function getProjectInfo() {
        $info = "Titel: " . $this->titel . "\n";
        $info .= "Beschrijving: " . $this->getBeschrijving() . "\n";
        $info .= "Categorie: " . $this->categorie . "\n";
        
        if ($this->technologies) {
            $info .= "Technologieën: " . $this->technologies . "\n";
        }
        
        if ($this->startdatum && $this->einddatum) {
            $info .= "Startdatum: " . $this->startdatum . "\n";
            $info .= "Einddatum: " . $this->einddatum . "\n";
            $info .= "Duur: " . $this->getProjectDuration() . "\n";
        }
        
        return $info;
    }
    
    /**
     * Bonusopdracht: Methode die projectduur berekent
     */
    public function getProjectDuration() {
        if (!$this->startdatum || !$this->einddatum) {
            return "Geen datums opgegeven";
        }
        
        try {
            $startDate = new DateTime($this->startdatum);
            $endDate = new DateTime($this->einddatum);
            $interval = $startDate->diff($endDate);
            
            return $interval->format('%y jaar, %m maanden, %d dagen');
        } catch (Exception $e) {
            return "Fout bij berekenen duur: " . $e->getMessage();
        }
    }
    
    /**
     * Controleer of project nog actief is
     */
    public function isActief() {
        if (!$this->einddatum) {
            return true; // Geen einddatum = nog actief
        }
        
        $vandaag = new DateTime();
        $einde = new DateTime($this->einddatum);
        
        return $vandaag <= $einde;
    }
    
    /**
     * Krijg een korte samenvatting
     */
    public function getSamenvatting() {
        $samenvatting = $this->titel;
        
        if ($this->categorie) {
            $samenvatting .= " (" . $this->categorie . ")";
        }
        
        if ($this->startdatum && $this->einddatum) {
            if ($this->isActief()) {
                $samenvatting .= " - ACTIEF";
            } else {
                $samenvatting .= " - VOLTOOID";
            }
        }
        
        return $samenvatting;
    }
    
    /**
     * Voeg een nieuw project toe (uitgebreid met nieuwe velden)
     */
    public function addProject($title, $description, $technologies, $image_url = null, $project_url = null, $github_url = null, $category = 'Algemeen', $start_date = null, $end_date = null) {
        try {
            $sql = "INSERT INTO projects (title, description, category, technologies, image_url, project_url, github_url, start_date, end_date, created_at) 
                    VALUES (:title, :description, :category, :technologies, :image_url, :project_url, :github_url, :start_date, :end_date, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':category' => $category,
                ':technologies' => $technologies,
                ':image_url' => $image_url,
                ':project_url' => $project_url,
                ':github_url' => $github_url,
                ':start_date' => $start_date,
                ':end_date' => $end_date
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
     * Update een project (uitgebreid met nieuwe velden)
     */
    public function updateProject($id, $title, $description, $technologies, $image_url = null, $project_url = null, $github_url = null, $category = 'Algemeen', $start_date = null, $end_date = null) {
        try {
            $sql = "UPDATE projects SET 
                    title = :title, 
                    description = :description,
                    category = :category,
                    technologies = :technologies, 
                    image_url = :image_url, 
                    project_url = :project_url, 
                    github_url = :github_url,
                    start_date = :start_date,
                    end_date = :end_date,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':category' => $category,
                ':technologies' => $technologies,
                ':image_url' => $image_url,
                ':project_url' => $project_url,
                ':github_url' => $github_url,
                ':start_date' => $start_date,
                ':end_date' => $end_date
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
    
    /**
     * Zoek projecten op categorie (nieuwe methode)
     */
    public function searchByCategory($category) {
        try {
            $sql = "SELECT * FROM projects WHERE category LIKE :category ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':category' => '%' . $category . '%']);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Fout bij zoeken projecten op categorie: " . $e->getMessage());
        }
    }
    
    /**
     * Haal actieve projecten op (projecten zonder einddatum of einddatum in de toekomst)
     */
    public function getActiveProjects() {
        try {
            $sql = "SELECT * FROM projects WHERE end_date IS NULL OR end_date >= CURDATE() ORDER BY start_date DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Fout bij ophalen actieve projecten: " . $e->getMessage());
        }
    }
    
    /**
     * Haal voltooide projecten op
     */
    public function getCompletedProjects() {
        try {
            $sql = "SELECT * FROM projects WHERE end_date IS NOT NULL AND end_date < CURDATE() ORDER BY end_date DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Fout bij ophalen voltooide projecten: " . $e->getMessage());
        }
    }
    
    /**
     * Haal alle categorieën op
     */
    public function getAllCategories() {
        try {
            $sql = "SELECT DISTINCT category FROM projects WHERE category IS NOT NULL ORDER BY category";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            throw new Exception("Fout bij ophalen categorieën: " . $e->getMessage());
        }
    }
}
?>
