<?php
require_once 'classes/Portfolio.php';
require_once 'classes/Project.php';

// Eenvoudige demonstratie van de opdracht vereisten
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Opdracht - Compositie & Aggregatie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step-box {
            border-left: 4px solid #007bff;
            padding-left: 20px;
            margin: 20px 0;
        }
        .output-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Portfolio Klasse - Compositie & Aggregatie</h1>
        
        <?php
        echo "<div class='step-box'>";
        echo "<h3>🎯 Doel: Portfolio-klasse met compositie en aggregatie</h3>";
        echo "<p>Een Portfolio-klasse maken die meerdere Project-objecten bevat en beheert.</p>";
        echo "</div>";

        // STAP 1: Portfolio-klasse maken
        echo "<div class='step-box'>";
        echo "<h3>📋 Stap 1: Portfolio-klasse maken</h3>";
        echo "<p>✅ $projects eigenschap toegevoegd (array van Project-objecten)</p>";
        echo "<p>✅ addProject(\$project) methode geïmplementeerd</p>";
        echo "<p>✅ getProjects() methode geïmplementeerd</p>";
        echo "</div>";

        // Maak Portfolio aan
        $mijnPortfolio = new Portfolio("Mijn Projectportfolio", "Student Developer");
        
        echo "<div class='output-box'>";
        echo "<h5>Portfolio aangemaakt:</h5>";
        echo "<p><strong>Naam:</strong> " . $mijnPortfolio->getNaam() . "</p>";
        echo "<p><strong>Eigenaar:</strong> " . $mijnPortfolio->getEigenaar() . "</p>";
        echo "<p><strong>Aantal projecten:</strong> " . $mijnPortfolio->countProjects() . "</p>";
        echo "</div>";

        // STAP 2: Projecten maken en toevoegen
        echo "<div class='step-box'>";
        echo "<h3>📝 Stap 2: Projecten maken en toevoegen</h3>";
        echo "<p>Minimaal twee projecten aanmaken en toevoegen aan de portfolio.</p>";
        echo "</div>";

        // Project 1 maken
        $project1 = new Project(
            "Website Portfolio",
            "Een responsive portfolio website gebouwd met PHP en Bootstrap",
            "Web Development",
            "PHP, HTML, CSS, Bootstrap, MySQL",
            null,
            "https://mijnportfolio.nl",
            "https://github.com/student/portfolio",
            "2024-01-15",
            "2024-02-28"
        );

        // Project 2 maken  
        $project2 = new Project(
            "Contactbeheer App",
            "Een applicatie voor het beheren van contactgegevens met CRUD functionaliteit",
            "Database Development", 
            "PHP, MySQL, JavaScript",
            null,
            null,
            "https://github.com/student/contactapp",
            "2024-03-01",
            null // Nog niet afgerond
        );

        // Projecten toevoegen aan portfolio
        try {
            $mijnPortfolio->addProject($project1);
            $mijnPortfolio->addProject($project2);
            
            echo "<div class='output-box'>";
            echo "<h5>✅ Projecten succesvol toegevoegd!</h5>";
            echo "<p><strong>Aantal projecten in portfolio:</strong> " . $mijnPortfolio->countProjects() . "</p>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Fout: " . $e->getMessage() . "</div>";
        }

        // getInfo() methode aanroepen voor elk project
        echo "<div class='step-box'>";
        echo "<h3>📊 getInfo() methode aanroepen voor elk project</h3>";
        echo "</div>";

        $projecten = $mijnPortfolio->getProjects();
        foreach ($projecten as $index => $project) {
            echo "<div class='output-box'>";
            echo "<h5>Project " . ($index + 1) . ": " . $project->getTitel() . "</h5>";
            echo $project->getInfo(); // Roep getInfo() aan
            echo "</div>";
        }

        // BONUSOPDRACHT: Aantal projecten tellen
        echo "<div class='step-box'>";
        echo "<h3>🏆 Bonusopdracht: Aantal projecten tellen</h3>";
        echo "</div>";

        echo "<div class='output-box'>";
        echo "<h5>📈 Portfolio Statistieken:</h5>";
        echo "<div class='row'>";
        echo "<div class='col-md-3 text-center'>";
        echo "<div class='bg-primary text-white p-3 rounded'>";
        echo "<h2>" . $mijnPortfolio->countProjects() . "</h2>";
        echo "<p>Totaal Projecten</p>";
        echo "</div></div>";

        $actief = count($mijnPortfolio->getActiveProjects());
        echo "<div class='col-md-3 text-center'>";
        echo "<div class='bg-warning text-white p-3 rounded'>";
        echo "<h2>" . $actief . "</h2>";
        echo "<p>Actieve Projecten</p>";
        echo "</div></div>";

        $voltooid = count($mijnPortfolio->getCompletedProjects());
        echo "<div class='col-md-3 text-center'>";
        echo "<div class='bg-success text-white p-3 rounded'>";
        echo "<h2>" . $voltooid . "</h2>";
        echo "<p>Voltooide Projecten</p>";
        echo "</div></div>";

        echo "<div class='col-md-3 text-center'>";
        echo "<div class='bg-info text-white p-3 rounded'>";
        echo "<h2>" . count($mijnPortfolio->getProjectsByCategory("Web Development")) . "</h2>";
        echo "<p>Web Projecten</p>";
        echo "</div></div>";
        echo "</div>";
        echo "</div>";

        // Concepten uitleggen
        echo "<div class='step-box'>";
        echo "<h3>🎓 OOP Concepten Toegepast</h3>";
        echo "<div class='row'>";
        
        echo "<div class='col-md-6'>";
        echo "<div class='output-box'>";
        echo "<h5>🔗 Compositie</h5>";
        echo "<ul>";
        echo "<li>Portfolio <strong>'heeft'</strong> een array van projecten (\$projects)</li>";
        echo "<li>Portfolio beheert de levenscyclus van de projectencollectie</li>";
        echo "<li>Portfolio biedt methoden om projecten toe te voegen, te verwijderen en op te halen</li>";
        echo "</ul>";
        echo "</div></div>";
        
        echo "<div class='col-md-6'>";
        echo "<div class='output-box'>";
        echo "<h5>🔀 Aggregatie</h5>";
        echo "<ul>";
        echo "<li>Project-objecten kunnen <strong>onafhankelijk</strong> van Portfolio bestaan</li>";
        echo "<li>Projecten behouden hun eigen eigenschappen en methoden</li>";
        echo "<li>Projecten kunnen aan meerdere portfolio's worden toegevoegd</li>";
        echo "</ul>";
        echo "</div></div>";
        
        echo "</div>";
        echo "</div>";

        // Samenvatting
        echo "<div class='alert alert-success mt-4'>";
        echo "<h4>✅ Opdracht Voltooid!</h4>";
        echo "<p><strong>Gerealiseerd:</strong></p>";
        echo "<ul>";
        echo "<li>✅ Portfolio-klasse met \$projects eigenschap (array van Project-objecten)</li>";
        echo "<li>✅ addProject(\$project) methode geïmplementeerd</li>";
        echo "<li>✅ getProjects() methode geïmplementeerd</li>";
        echo "<li>✅ Minimaal twee projecten aangemaakt en toegevoegd</li>";
        echo "<li>✅ getInfo() methode aangeroepen voor elk project</li>";
        echo "<li>🏆 <strong>Bonus:</strong> countProjects() methode geïmplementeerd</li>";
        echo "</ul>";
        echo "<p><strong>OOP Concepten:</strong> Compositie (Portfolio heeft projecten) en Aggregatie (Projecten bestaan onafhankelijk)</p>";
        echo "</div>";
        ?>
    </div>
</body>
</html>
