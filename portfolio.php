<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <nav class="navbar navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">
                    <i class="fas fa-briefcase"></i> Portfolio Beheer
                </span>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                    <i class="fas fa-plus"></i> Nieuw Project
                </button>
            </div>
        </nav>

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
                        
                        <div class="mb-3">
                            <label for="technologies" class="form-label">Technologieën *</label>
                            <input type="text" class="form-control" id="technologies" name="technologies" 
                                   placeholder="bijv. PHP, MySQL, JavaScript" required>
                            <div class="form-text">Scheid technologieën met komma's</div>
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
