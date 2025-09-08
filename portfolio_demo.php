<?php
require_once 'classes/Portfolio.php';
require_once 'classes/Project.php';

// Demonstratie van Portfolio-klasse met compositie en aggregatie
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Klasse Demonstratie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">
                    <i class="fas fa-briefcase"></i> 
                    Portfolio Klasse Demonstratie
                </h1>
                <p class="lead text-center mb-5">
                    Demonstratie van compositie en aggregatie concepten met Project en Portfolio klassen
                </p>
            </div>
        </div>

        <?php
        try {
            // Stap 1: Maak een Portfolio-klasse aan
            echo "<div class='alert alert-info'><h4><i class='fas fa-info-circle'></i> Stap 1: Portfolio aanmaken</h4></div>";
            
            $mijnPortfolio = new Portfolio(
                "Mijn Ontwikkelingsportfolio", 
                "John Doe", 
                "Een collectie van mijn beste projecten en ontwikkelingen"
            );
            
            echo "<div class='card mb-4'>";
            echo "<div class='card-body'>";
            echo $mijnPortfolio->getPortfolioInfo();
            echo "</div></div>";
            
            // Stap 2: Maak minstens twee projecten en voeg ze toe
            echo "<div class='alert alert-success'><h4><i class='fas fa-plus-circle'></i> Stap 2: Projecten aanmaken en toevoegen</h4></div>";
            
            // Project 1: Website ontwikkeling (Voltooid)
            $project1 = new Project(
                "E-commerce Website",
                "Een volledig functionele e-commerce website met betalingsintegratie en gebruikersbeheer.",
                "Web Development",
                "PHP, MySQL, Bootstrap, JavaScript, PayPal API",
                "images/ecommerce.jpg",
                "https://example-shop.com",
                "https://github.com/johndoe/ecommerce",
                "2024-01-15",
                "2024-03-20"  // Voltooid project
            );
            
            // Project 2: Mobile App (Actief)
            $project2 = new Project(
                "Fitness Tracker App",
                "Een mobiele applicatie voor het bijhouden van workouts en voortgang.",
                "Mobile Development",
                "React Native, Firebase, Redux",
                "images/fitness-app.jpg",
                null, // Nog niet gelanceerd
                "https://github.com/johndoe/fitness-tracker",
                "2024-06-01",
                null  // Nog actief
            );
            
            // Project 3: Data Analyse Tool (Voltooid)
            $project3 = new Project(
                "Sales Dashboard",
                "Een interactief dashboard voor het analyseren van verkoopgegevens.",
                "Data Science",
                "Python, Pandas, Plotly, Django",
                "images/dashboard.jpg",
                "https://sales-dashboard.example.com",
                "https://github.com/johndoe/sales-dashboard",
                "2023-09-10",
                "2023-12-15"  // Voltooid project
            );
            
            // Voeg projecten toe aan portfolio
            $mijnPortfolio->addProject($project1);
            $mijnPortfolio->addProject($project2);
            $mijnPortfolio->addProject($project3);
            
            echo "<div class='alert alert-success'>✅ " . $mijnPortfolio->countProjects() . " projecten succesvol toegevoegd aan de portfolio!</div>";
            
            // Toon alle projecten met getInfo() methode
            echo "<div class='alert alert-primary'><h4><i class='fas fa-list'></i> Alle Projecten in Portfolio</h4></div>";
            echo "<div class='card mb-4'>";
            echo "<div class='card-body'>";
            echo $mijnPortfolio->displayAllProjects();
            echo "</div></div>";
            
            // Bonusopdracht: Aantal projecten tellen
            echo "<div class='alert alert-warning'><h4><i class='fas fa-trophy'></i> Bonusopdracht: Projecten Tellen</h4></div>";
            echo "<div class='card mb-4'>";
            echo "<div class='card-body'>";
            echo "<h5>Statistieken:</h5>";
            echo "<div class='row'>";
            echo "<div class='col-md-3'><div class='stat-card bg-primary text-white p-3 rounded text-center'>";
            echo "<h3>" . $mijnPortfolio->countProjects() . "</h3>";
            echo "<p>Totaal Projecten</p>";
            echo "</div></div>";
            
            $actieveProjecten = $mijnPortfolio->getActiveProjects();
            echo "<div class='col-md-3'><div class='stat-card bg-warning text-white p-3 rounded text-center'>";
            echo "<h3>" . count($actieveProjecten) . "</h3>";
            echo "<p>Actieve Projecten</p>";
            echo "</div></div>";
            
            $voltooideProjecten = $mijnPortfolio->getCompletedProjects();
            echo "<div class='col-md-3'><div class='stat-card bg-success text-white p-3 rounded text-center'>";
            echo "<h3>" . count($voltooideProjecten) . "</h3>";
            echo "<p>Voltooide Projecten</p>";
            echo "</div></div>";
            
            // Categorieën
            $webProjects = $mijnPortfolio->getProjectsByCategory("Web Development");
            $mobileProjects = $mijnPortfolio->getProjectsByCategory("Mobile Development");
            $dataProjects = $mijnPortfolio->getProjectsByCategory("Data Science");
            
            echo "<div class='col-md-3'><div class='stat-card bg-info text-white p-3 rounded text-center'>";
            echo "<h3>" . (count($webProjects) + count($mobileProjects) + count($dataProjects)) . "</h3>";
            echo "<p>Verschillende Categorieën</p>";
            echo "</div></div>";
            echo "</div>";
            echo "</div></div>";
            
            // Extra demonstraties van Portfolio functionaliteit
            echo "<div class='alert alert-info'><h4><i class='fas fa-cogs'></i> Extra Portfolio Functionaliteiten</h4></div>";
            
            // Filter op actieve projecten
            echo "<div class='card mb-3'>";
            echo "<div class='card-header bg-warning text-white'>";
            echo "<h5><i class='fas fa-play-circle'></i> Actieve Projecten</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            if (!empty($actieveProjecten)) {
                foreach ($actieveProjecten as $project) {
                    echo "<div class='border-start border-warning border-3 ps-3 mb-2'>";
                    echo "<strong>" . $project->getTitel() . "</strong><br>";
                    echo "<small class='text-muted'>Gestart: " . date('d-m-Y', strtotime($project->startdatum)) . "</small>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-muted'>Geen actieve projecten gevonden.</p>";
            }
            echo "</div></div>";
            
            // Filter op voltooide projecten  
            echo "<div class='card mb-3'>";
            echo "<div class='card-header bg-success text-white'>";
            echo "<h5><i class='fas fa-check-circle'></i> Voltooide Projecten</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            if (!empty($voltooideProjecten)) {
                foreach ($voltooideProjecten as $project) {
                    echo "<div class='border-start border-success border-3 ps-3 mb-2'>";
                    echo "<strong>" . $project->getTitel() . "</strong><br>";
                    echo "<small class='text-muted'>Voltooid: " . date('d-m-Y', strtotime($project->einddatum)) . "</small>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-muted'>Geen voltooide projecten gevonden.</p>";
            }
            echo "</div></div>";
            
            // Filter op categorieën
            echo "<div class='card mb-3'>";
            echo "<div class='card-header bg-primary text-white'>";
            echo "<h5><i class='fas fa-tags'></i> Projecten per Categorie</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<div class='row'>";
            
            $categories = ["Web Development", "Mobile Development", "Data Science"];
            foreach ($categories as $category) {
                $categoryProjects = $mijnPortfolio->getProjectsByCategory($category);
                echo "<div class='col-md-4'>";
                echo "<h6 class='text-primary'>" . $category . " (" . count($categoryProjects) . ")</h6>";
                if (!empty($categoryProjects)) {
                    foreach ($categoryProjects as $project) {
                        echo "<div class='badge bg-light text-dark me-1 mb-1'>" . $project->getTitel() . "</div>";
                    }
                } else {
                    echo "<p class='text-muted small'>Geen projecten</p>";
                }
                echo "</div>";
            }
            echo "</div>";
            echo "</div></div>";
            
            // Demonstratie van zoekfunctionaliteit
            echo "<div class='card mb-3'>";
            echo "<div class='card-header bg-secondary text-white'>";
            echo "<h5><i class='fas fa-search'></i> Project Zoeken</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            $gevondenProject = $mijnPortfolio->findProject("E-commerce Website");
            if ($gevondenProject) {
                echo "<div class='alert alert-success'>";
                echo "<strong>Gevonden:</strong> " . $gevondenProject->getTitel() . "<br>";
                echo "<small>" . $gevondenProject->getBeschrijving() . "</small>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning'>Project niet gevonden.</div>";
            }
            echo "</div></div>";
            
            // Portfolio export demonstratie
            echo "<div class='card mb-3'>";
            echo "<div class='card-header bg-dark text-white'>";
            echo "<h5><i class='fas fa-download'></i> Portfolio Export</h5>";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<details>";
            echo "<summary class='btn btn-outline-primary'>Bekijk Portfolio Data Export</summary>";
            echo "<pre class='mt-3 bg-light p-3 rounded'>" . json_encode($mijnPortfolio->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
            echo "</details>";
            echo "</div></div>";
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>";
            echo "<h4><i class='fas fa-exclamation-triangle'></i> Fout opgetreden</h4>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "</div>";
        }
        ?>
        
        <!-- Concepten Uitleg -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4"><i class="fas fa-graduation-cap"></i> OOP Concepten Toegepast</h2>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5><i class="fas fa-puzzle-piece"></i> Compositie</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Gebruikt in:</strong></p>
                        <ul>
                            <li>Portfolio beheert een collectie van Project-objecten</li>
                            <li>Portfolio bevat eigenschappen zoals naam, eigenaar, beschrijving</li>
                            <li>Portfolio heeft methoden voor het beheren van projecten</li>
                        </ul>
                        <p><em>De Portfolio "heeft" projecten - dit is compositie.</em></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-warning text-white">
                        <h5><i class="fas fa-link"></i> Aggregatie</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Gebruikt in:</strong></p>
                        <ul>
                            <li>Project-objecten kunnen bestaan buiten de Portfolio</li>
                            <li>Projecten kunnen aan meerdere portfolio's toegevoegd worden</li>
                            <li>Projecten behouden hun identiteit onafhankelijk van Portfolio</li>
                        </ul>
                        <p><em>Project-objecten kunnen onafhankelijk bestaan - dit is aggregatie.</em></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5><i class="fas fa-check-double"></i> Opdracht Voltooid</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>✅ Stap 1 - Portfolio-klasse:</h6>
                                <ul class="list-unstyled">
                                    <li>✓ $projects eigenschap toegevoegd</li>
                                    <li>✓ addProject() methode geïmplementeerd</li>
                                    <li>✓ getProjects() methode geïmplementeerd</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>✅ Stap 2 - Projecten toevoegen:</h6>
                                <ul class="list-unstyled">
                                    <li>✓ Meerdere projecten aangemaakt</li>
                                    <li>✓ Projecten toegevoegd aan portfolio</li>
                                    <li>✓ getInfo() methode aangeroepen</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>🏆 Bonusopdracht:</h6>
                            <p>✓ countProjects() methode geïmplementeerd en demonstratie van projectentelling</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .stat-card {
            transition: transform 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .project-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        .portfolio-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
        }
        
        .portfolio-info h2 {
            margin-bottom: 15px;
        }
        
        .project-item {
            background: white;
            transition: box-shadow 0.2s ease;
        }
        
        .project-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</body>
</html>
