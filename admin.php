<?php
// Beveilig deze pagina
require_once 'includes/auth.php';
checkAdminLogin();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Portfolio Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <!-- Header met logout -->
        <nav class="navbar navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">
                    <i class="fas fa-shield-alt"></i> Portfolio Beheer
                </span>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user"></i> Welkom, <?= htmlspecialchars(getAdminUser()) ?>
                    </span>
                    <small class="navbar-text me-3 text-muted">
                        Ingelogd: <?= htmlspecialchars(getLoginTime()) ?>
                    </small>
                    <a href="index.php" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-eye"></i> Bekijk Portfolio
                    </a>
                    <a href="login.php?logout=1" class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Uitloggen
                    </a>
                </div>
            </div>
        </nav>

        <!-- Admin functionaliteit knoppen -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                        <i class="fas fa-plus"></i> Nieuw Project
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary filter-btn" id="showAll">
                            <i class="fas fa-list"></i> Alle Projecten
                        </button>
                        <button type="button" class="btn btn-outline-success filter-btn" id="showActive">
                            <i class="fas fa-play"></i> Actieve Projecten
                        </button>
                        <button type="button" class="btn btn-outline-secondary filter-btn" id="showCompleted">
                            <i class="fas fa-check"></i> Voltooide Projecten
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert voor berichten -->
        <div id="alertContainer"></div>

        <!-- Zoek functie -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Zoek op technologie...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Projecten Grid -->
        <div class="row" id="projectsGrid">
            <!-- Projecten worden hier dynamisch geladen -->
        </div>
    </div>

    <!-- Modal voor project toevoegen/bewerken -->
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nieuw Project Toevoegen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="projectForm">
                        <input type="hidden" id="projectId" name="projectId">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titel *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Beschrijving *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Categorie *</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="Algemeen">Algemeen</option>
                                        <option value="Schoolopdracht">Schoolopdracht</option>
                                        <option value="Eindproject">Eindproject</option>
                                        <option value="Professioneel">Professioneel</option>
                                        <option value="Hobby Project">Hobby Project</option>
                                        <option value="Open Source">Open Source</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="technologies" class="form-label">Technologieën *</label>
                                    <input type="text" class="form-control" id="technologies" name="technologies" 
                                           placeholder="bijv. PHP, MySQL, JavaScript" required>
                                    <div class="form-text">Scheid technologieën met komma's</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Startdatum</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Einddatum</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                    <div class="form-text">Laat leeg als project nog actief is</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image_url" class="form-label">Afbeelding URL</label>
                            <input type="url" class="form-control" id="image_url" name="image_url">
                        </div>
                        
                        <div class="mb-3">
                            <label for="project_url" class="form-label">Project URL</label>
                            <input type="url" class="form-control" id="project_url" name="project_url">
                        </div>
                        
                        <div class="mb-3">
                            <label for="github_url" class="form-label">GitHub URL</label>
                            <input type="url" class="form-control" id="github_url" name="github_url">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="button" class="btn btn-primary" id="saveProjectBtn">Opslaan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal voor project verwijderen -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Verwijderen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Weet je zeker dat je dit project wilt verwijderen?</p>
                    <p class="text-muted" id="deleteProjectTitle"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Verwijderen</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
