<?php
require_once 'classes/Project.php';

/**
 * Portfolio-klasse die een collectie van Project-objecten beheert
 * Demonstreert concepten van compositie en aggregatie
 */
class Portfolio {
    private $projects;        // Array van Project-objecten (aggregatie)
    private $naam;           // Naam van de portfolio
    private $eigenaar;       // Eigenaar van de portfolio
    private $beschrijving;   // Beschrijving van de portfolio
    private $aanmaakdatum;   // Datum waarop portfolio is aangemaakt
    
    /**
     * Constructor - maakt een nieuwe portfolio aan
     * 
     * @param string $naam Naam van de portfolio
     * @param string $eigenaar Eigenaar van de portfolio
     * @param string $beschrijving Beschrijving van de portfolio
     */
    public function __construct($naam, $eigenaar = null, $beschrijving = null) {
        $this->projects = [];  // Initialiseer lege array
        $this->naam = $naam;
        $this->eigenaar = $eigenaar;
        $this->beschrijving = $beschrijving;
        $this->aanmaakdatum = date('Y-m-d H:i:s');
    }
    
    /**
     * Voegt een project toe aan de portfolio (aggregatie)
     * 
     * @param Project $project Het Project-object om toe te voegen
     * @return bool True als succesvol toegevoegd
     */
    public function addProject(Project $project) {
        // Controleer of project al bestaat (op basis van titel)
        foreach ($this->projects as $bestaandProject) {
            if ($bestaandProject->getTitel() === $project->getTitel()) {
                throw new Exception("Project met titel '{$project->getTitel()}' bestaat al in deze portfolio.");
            }
        }
        
        $this->projects[] = $project;
        return true;
    }
    
    /**
     * Haalt alle projecten op uit de portfolio
     * 
     * @return array Array van Project-objecten
     */
    public function getProjects() {
        return $this->projects;
    }
    
    /**
     * Telt het aantal projecten in de portfolio (Bonusopdracht)
     * 
     * @return int Aantal projecten
     */
    public function countProjects() {
        return count($this->projects);
    }
    
    /**
     * Verwijdert een project uit de portfolio
     * 
     * @param string $titel Titel van het project om te verwijderen
     * @return bool True als succesvol verwijderd
     */
    public function removeProject($titel) {
        foreach ($this->projects as $index => $project) {
            if ($project->getTitel() === $titel) {
                unset($this->projects[$index]);
                $this->projects = array_values($this->projects); // Herindexeer array
                return true;
            }
        }
        return false;
    }
    
    /**
     * Zoekt een project op basis van titel
     * 
     * @param string $titel Titel van het project
     * @return Project|null Het gevonden project of null
     */
    public function findProject($titel) {
        foreach ($this->projects as $project) {
            if ($project->getTitel() === $titel) {
                return $project;
            }
        }
        return null;
    }
    
    /**
     * Filtert projecten op basis van categorie
     * 
     * @param string $categorie De categorie om op te filteren
     * @return array Array van Project-objecten met de opgegeven categorie
     */
    public function getProjectsByCategory($categorie) {
        $gefilterdProjecten = [];
        
        foreach ($this->projects as $project) {
            if (strtolower($project->categorie) === strtolower($categorie)) {
                $gefilterdProjecten[] = $project;
            }
        }
        
        return $gefilterdProjecten;
    }
    
    /**
     * Haalt actieve projecten op (zonder einddatum)
     * 
     * @return array Array van actieve Project-objecten
     */
    public function getActiveProjects() {
        $actieveProjecten = [];
        
        foreach ($this->projects as $project) {
            if (empty($project->einddatum) || $project->einddatum === null) {
                $actieveProjecten[] = $project;
            }
        }
        
        return $actieveProjecten;
    }
    
    /**
     * Haalt voltooide projecten op (met einddatum)
     * 
     * @return array Array van voltooide Project-objecten
     */
    public function getCompletedProjects() {
        $voltooideProjecten = [];
        
        foreach ($this->projects as $project) {
            if (!empty($project->einddatum) && $project->einddatum !== null) {
                $voltooideProjecten[] = $project;
            }
        }
        
        return $voltooideProjecten;
    }
    
    /**
     * Toont informatie over de portfolio
     * 
     * @return string Geformatteerde portfolio informatie
     */
    public function getPortfolioInfo() {
        $info = "<div class='portfolio-info'>";
        $info .= "<h2>Portfolio: {$this->naam}</h2>";
        
        if ($this->eigenaar) {
            $info .= "<p><strong>Eigenaar:</strong> {$this->eigenaar}</p>";
        }
        
        if ($this->beschrijving) {
            $info .= "<p><strong>Beschrijving:</strong> {$this->beschrijving}</p>";
        }
        
        $info .= "<p><strong>Aangemaakt op:</strong> {$this->aanmaakdatum}</p>";
        $info .= "<p><strong>Aantal projecten:</strong> {$this->countProjects()}</p>";
        $info .= "</div>";
        
        return $info;
    }
    
    /**
     * Toont alle projecten in de portfolio met hun informatie
     * 
     * @return string HTML-geformatteerde projectlijst
     */
    public function displayAllProjects() {
        if (empty($this->projects)) {
            return "<p class='alert alert-info'>Deze portfolio bevat nog geen projecten.</p>";
        }
        
        $output = "<div class='projects-overview'>";
        $output .= "<h3>Projecten in deze Portfolio ({$this->countProjects()})</h3>";
        
        foreach ($this->projects as $index => $project) {
            $output .= "<div class='project-item border p-3 mb-3 rounded'>";
            $output .= "<h4>" . ($index + 1) . ". " . $project->getTitel() . "</h4>";
            
            // Roep getInfo() methode aan als deze bestaat
            if (method_exists($project, 'getInfo')) {
                $output .= $project->getInfo();
            } else {
                // Fallback als getInfo() niet bestaat
                $output .= "<p><strong>Beschrijving:</strong> " . $project->getBeschrijving() . "</p>";
                if ($project->categorie) {
                    $output .= "<p><strong>Categorie:</strong> " . $project->categorie . "</p>";
                }
                if ($project->technologies) {
                    $output .= "<p><strong>Technologieën:</strong> " . $project->technologies . "</p>";
                }
                if ($project->startdatum) {
                    $output .= "<p><strong>Startdatum:</strong> " . $project->startdatum . "</p>";
                }
                if ($project->einddatum) {
                    $output .= "<p><strong>Einddatum:</strong> " . $project->einddatum . "</p>";
                } else {
                    $output .= "<p><em>Dit project is nog actief</em></p>";
                }
            }
            
            $output .= "</div>";
        }
        
        $output .= "</div>";
        return $output;
    }
    
    /**
     * Exporteert portfolio data naar een array
     * 
     * @return array Portfolio data als associatieve array
     */
    public function toArray() {
        $projectsArray = [];
        
        foreach ($this->projects as $project) {
            $projectsArray[] = [
                'titel' => $project->getTitel(),
                'beschrijving' => $project->getBeschrijving(),
                'categorie' => $project->categorie,
                'technologies' => $project->technologies,
                'image_url' => $project->image_url,
                'project_url' => $project->project_url,
                'github_url' => $project->github_url,
                'startdatum' => $project->startdatum,
                'einddatum' => $project->einddatum
            ];
        }
        
        return [
            'naam' => $this->naam,
            'eigenaar' => $this->eigenaar,
            'beschrijving' => $this->beschrijving,
            'aanmaakdatum' => $this->aanmaakdatum,
            'aantal_projecten' => $this->countProjects(),
            'projecten' => $projectsArray
        ];
    }
    
    // Getters en Setters
    public function getNaam() {
        return $this->naam;
    }
    
    public function setNaam($naam) {
        $this->naam = $naam;
    }
    
    public function getEigenaar() {
        return $this->eigenaar;
    }
    
    public function setEigenaar($eigenaar) {
        $this->eigenaar = $eigenaar;
    }
    
    public function getBeschrijving() {
        return $this->beschrijving;
    }
    
    public function setBeschrijving($beschrijving) {
        $this->beschrijving = $beschrijving;
    }
    
    public function getAanmaakdatum() {
        return $this->aanmaakdatum;
    }
}
