<?php
require_once 'classes/Project.php';

// Haal projecten op voor de portfolio
$projectModel = new Project();
$projects = $projectModel->getAllProjects();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thymen's Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .portfolio-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .project-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            background: white;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .project-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }

        .project-image-placeholder {
            height: 250px;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .card-body {
            padding: 2rem;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .tech-badge {
            background-color: var(--secondary-color);
            color: white;
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            margin: 0.2rem;
            border-radius: 20px;
            display: inline-block;
        }

        .project-links {
            margin-top: 1.5rem;
            border-top: 1px solid #eee;
            padding-top: 1.5rem;
        }

        .btn-custom {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .admin-link {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50px;
            padding: 15px 20px;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .admin-link:hover {
            background-color: #c0392b;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .empty-portfolio {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-portfolio i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1><i class="fas fa-code"></i> Thymen's Portfolio</h1>
            <p>Welkom bij mijn portfolio! Hier vind je een overzicht van mijn projecten en werkzaamheden.</p>
            <a href="#portfolio" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-down"></i> Bekijk Mijn Werk
            </a>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-briefcase"></i> Mijn Projecten</h2>
                <p class="lead">Een selectie van mijn recentste projecten en werkzaamheden</p>
            </div>

            <div class="row">
                <?php if (empty($projects)): ?>
                    <div class="col-12">
                        <div class="empty-portfolio">
                            <i class="fas fa-folder-open"></i>
                            <h3>Nog geen projecten</h3>
                            <p>Er zijn nog geen projecten toegevoegd aan het portfolio.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($projects as $project): ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card project-card">
                                <?php if ($project['image_url']): ?>
                                    <img src="<?= htmlspecialchars($project['image_url']) ?>" 
                                         class="project-image" alt="<?= htmlspecialchars($project['title']) ?>">
                                <?php else: ?>
                                    <div class="project-image-placeholder">
                                        <i class="fas fa-code"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($project['title']) ?></h5>
                                        <?php if (isset($project['category']) && $project['category']): ?>
                                            <span class="badge bg-primary"><?= htmlspecialchars($project['category']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (isset($project['start_date']) || isset($project['end_date'])): ?>
                                        <div class="project-dates mb-2">
                                            <?php if (isset($project['start_date']) && $project['start_date']): ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-play"></i> Start: <?= date('d-m-Y', strtotime($project['start_date'])) ?>
                                                </small><br>
                                            <?php endif; ?>
                                            
                                            <?php if (isset($project['end_date']) && $project['end_date']): ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-stop"></i> Eind: <?= date('d-m-Y', strtotime($project['end_date'])) ?>
                                                </small>
                                            <?php elseif (isset($project['start_date']) && $project['start_date']): ?>
                                                <small class="text-success">
                                                    <i class="fas fa-clock"></i> Actief
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="card-text"><?= htmlspecialchars($project['description']) ?></p>
                                    
                                    <div class="mb-3">
                                        <?php 
                                        $technologies = explode(',', $project['technologies']);
                                        foreach ($technologies as $tech): 
                                        ?>
                                            <span class="tech-badge"><?= trim(htmlspecialchars($tech)) ?></span>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php if ($project['project_url'] || $project['github_url']): ?>
                                        <div class="project-links">
                                            <?php if ($project['project_url']): ?>
                                                <a href="<?= htmlspecialchars($project['project_url']) ?>" 
                                                   target="_blank" class="btn btn-custom me-2">
                                                    <i class="fas fa-external-link-alt"></i> Live Demo
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($project['github_url']): ?>
                                                <a href="<?= htmlspecialchars($project['github_url']) ?>" 
                                                   target="_blank" class="btn btn-outline-dark">
                                                    <i class="fab fa-github"></i> GitHub
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Admin Link -->
    <a href="portfolio.php" class="admin-link" title="Portfolio Beheer">
        <i class="fas fa-cog"></i> Beheer
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
