<?php
/**
 * Project klasse voor schoolopdracht
 * Deze klasse demonstreert OOP concepten zoals constructors, visibiliteit en methoden
 */
class ProjectOpdracht {
    // Eigenschappen
    public $titel;
    private $beschrijving;  // Private eigenschap
    public $categorie;
    public $startdatum;     // Voor bonusopdracht
    public $einddatum;      // Voor bonusopdracht
    
    /**
     * Constructor - initialiseert de eigenschappen wanneer een object wordt aangemaakt
     */
    public function __construct($titel, $beschrijving, $categorie, $startdatum = null, $einddatum = null) {
        $this->titel = $titel;
        $this->beschrijving = $beschrijving;
        $this->categorie = $categorie;
        $this->startdatum = $startdatum;
        $this->einddatum = $einddatum;
    }
    
    /**
     * Getter methode voor de private eigenschap beschrijving
     */
    public function getBeschrijving() {
        return $this->beschrijving;
    }
    
    /**
     * Setter methode voor beschrijving (bonus)
     */
    public function setBeschrijving($beschrijving) {
        $this->beschrijving = $beschrijving;
    }
    
    /**
     * Methode die de projectinformatie retourneert
     */
    public function getProjectInfo() {
        $info = "Titel: " . $this->titel . "\n";
        $info .= "Beschrijving: " . $this->getBeschrijving() . "\n";
        $info .= "Categorie: " . $this->categorie . "\n";
        
        // Als er datum informatie is, voeg die ook toe
        if ($this->startdatum && $this->einddatum) {
            $info .= "Startdatum: " . $this->startdatum . "\n";
            $info .= "Einddatum: " . $this->einddatum . "\n";
            $info .= "Duur: " . $this->getProjectDuration() . "\n";
        }
        
        return $info;
    }
    
    /**
     * Bonusopdracht: Methode die de projectduur berekent
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
     * Extra methode: Controleer of project nog actief is
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
     * Extra methode: Krijg een korte samenvatting
     */
    public function getSamenvatting() {
        $samenvatting = $this->titel . " (" . $this->categorie . ")";
        
        if ($this->isActief()) {
            $samenvatting .= " - ACTIEF";
        } else {
            $samenvatting .= " - VOLTOOID";
        }
        
        return $samenvatting;
    }
}

// Voorbeeldgebruik volgens de opdracht
echo "<h1>Project Klasse Opdracht - Thymen</h1>\n";
echo "<hr>\n";

// Stap 4: Maak objecten van de Project-klasse
echo "<h2>Mijn Projecten:</h2>\n";

// Project 1
$project1 = new ProjectOpdracht(
    "Portfolio Website", 
    "Een website om mijn werk te tonen", 
    "Schoolopdracht",
    "2024-09-01",
    "2024-12-15"
);

// Project 2
$project2 = new ProjectOpdracht(
    "Webshop Project", 
    "Een volledig functionele online winkel", 
    "Eindproject",
    "2025-01-10",
    "2025-06-30"
);

// Project 3 - zonder datums
$project3 = new ProjectOpdracht(
    "Game Development", 
    "Een eenvoudige 2D game in JavaScript", 
    "Hobby Project"
);

// Toon projectinformatie
echo "<div style='background: #f8f9fa; padding: 20px; margin: 10px 0; border-radius: 8px;'>";
echo "<h3>Project 1:</h3>";
echo "<pre>" . $project1->getProjectInfo() . "</pre>";
echo "</div>";

echo "<div style='background: #f8f9fa; padding: 20px; margin: 10px 0; border-radius: 8px;'>";
echo "<h3>Project 2:</h3>";
echo "<pre>" . $project2->getProjectInfo() . "</pre>";
echo "</div>";

echo "<div style='background: #f8f9fa; padding: 20px; margin: 10px 0; border-radius: 8px;'>";
echo "<h3>Project 3:</h3>";
echo "<pre>" . $project3->getProjectInfo() . "</pre>";
echo "</div>";

// Demonstratie van getter voor private eigenschap
echo "<h2>Demonstratie van Private Eigenschap:</h2>\n";
echo "<p>Project 1 beschrijving via getter: <strong>" . $project1->getBeschrijving() . "</strong></p>\n";

// Demonstratie van extra methoden
echo "<h2>Extra Functionaliteit:</h2>\n";
echo "<ul>";
echo "<li>Project 1 samenvatting: " . $project1->getSamenvatting() . "</li>";
echo "<li>Project 2 samenvatting: " . $project2->getSamenvatting() . "</li>";
echo "<li>Project 3 samenvatting: " . $project3->getSamenvatting() . "</li>";
echo "</ul>";

echo "<h2>Alle Projecten Overzicht:</h2>\n";
$alleProjecten = [$project1, $project2, $project3];

echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
echo "<tr style='background: #3498db; color: white;'>";
echo "<th style='padding: 10px;'>Titel</th>";
echo "<th style='padding: 10px;'>Categorie</th>";
echo "<th style='padding: 10px;'>Status</th>";
echo "<th style='padding: 10px;'>Duur</th>";
echo "</tr>";

foreach ($alleProjecten as $project) {
    echo "<tr>";
    echo "<td style='padding: 10px;'>" . $project->titel . "</td>";
    echo "<td style='padding: 10px;'>" . $project->categorie . "</td>";
    echo "<td style='padding: 10px;'>" . ($project->isActief() ? "ACTIEF" : "VOLTOOID") . "</td>";
    echo "<td style='padding: 10px;'>" . $project->getProjectDuration() . "</td>";
    echo "</tr>";
}

echo "</table>";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
}

h1 {
    color: #2c3e50;
    text-align: center;
}

h2 {
    color: #3498db;
    border-bottom: 2px solid #3498db;
    padding-bottom: 5px;
}

h3 {
    color: #e74c3c;
}

pre {
    background: white;
    padding: 15px;
    border-radius: 5px;
    border-left: 4px solid #3498db;
}

table {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

hr {
    border: none;
    border-top: 3px solid #3498db;
    margin: 30px 0;
}
</style>
