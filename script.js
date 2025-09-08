// Portfolio Beheer JavaScript
class PortfolioBeheer {
    constructor() {
        this.currentProjectId = null;
        this.init();
    }

    init() {
        this.bindEvents();
        
        // Laad alleen projecten als de projectsGrid bestaat
        const projectsGrid = document.getElementById('projectsGrid');
        if (projectsGrid) {
            this.loadProjects();
            this.setActiveButton('showAll');
        }
    }

    bindEvents() {
        // Filter buttons - controleer eerst of ze bestaan
        const showAllBtn = document.getElementById('showAll');
        if (showAllBtn) {
            showAllBtn.addEventListener('click', () => {
                this.loadProjects();
                this.setActiveButton('showAll');
            });
        }

        const showActiveBtn = document.getElementById('showActive');
        if (showActiveBtn) {
            showActiveBtn.addEventListener('click', () => {
                this.loadActiveProjects();
                this.setActiveButton('showActive');
            });
        }

        const showCompletedBtn = document.getElementById('showCompleted');
        if (showCompletedBtn) {
            showCompletedBtn.addEventListener('click', () => {
                this.loadCompletedProjects();
                this.setActiveButton('showCompleted');
            });
        }

        // Save project button
        const saveProjectBtn = document.getElementById('saveProjectBtn');
        if (saveProjectBtn) {
            saveProjectBtn.addEventListener('click', () => {
                this.saveProject();
            });
        }

        // Search functionality
        const searchBtn = document.getElementById('searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                this.searchProjects();
            });
        }

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.searchProjects();
                }
            });
        }

        const clearSearchBtn = document.getElementById('clearSearchBtn');
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.value = '';
                }
                this.loadProjects();
            });
        }

        // Confirm delete button
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => {
                this.deleteProject();
            });
        }

        // Reset form when modal is hidden
        const addProjectModal = document.getElementById('addProjectModal');
        if (addProjectModal) {
            addProjectModal.addEventListener('hidden.bs.modal', () => {
                this.resetForm();
            });
        }
    }

    async loadProjects() {
        try {
            this.showLoading();
            const response = await fetch('api.php?action=getAllProjects');
            const result = await response.json();

            if (result.success) {
                this.displayProjects(result.data);
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij laden van projecten: ' + error.message);
        }
    }

    async loadActiveProjects() {
        try {
            this.showLoading();
            const response = await fetch('api.php?action=getActiveProjects');
            const result = await response.json();

            if (result.success) {
                this.displayProjects(result.data, 'actief');
                this.showAlert('info', `${result.data.length} actieve projecten gevonden`);
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij laden actieve projecten: ' + error.message);
        }
    }

    async loadCompletedProjects() {
        try {
            this.showLoading();
            const response = await fetch('api.php?action=getCompletedProjects');
            const result = await response.json();

            if (result.success) {
                this.displayProjects(result.data, 'voltooid');
                this.showAlert('info', `${result.data.length} voltooide projecten gevonden`);
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij laden voltooide projecten: ' + error.message);
        }
    }

    async searchProjects() {
        const searchInput = document.getElementById('searchInput');
        
        // Controleer of searchInput bestaat
        if (!searchInput) {
            console.warn('searchInput element niet gevonden');
            return;
        }
        
        const searchTerm = searchInput.value.trim();
        
        if (!searchTerm) {
            this.loadProjects();
            return;
        }

        try {
            this.showLoading();
            const response = await fetch(`api.php?action=searchByTechnology&technology=${encodeURIComponent(searchTerm)}`);
            const result = await response.json();

            if (result.success) {
                this.displayProjects(result.data);
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij zoeken: ' + error.message);
        }
    }

    displayProjects(projects, filterType = 'alle') {
        const grid = document.getElementById('projectsGrid');
        
        // Controleer of grid element bestaat
        if (!grid) {
            console.warn('projectsGrid element niet gevonden');
            return;
        }
        
        if (projects.length === 0) {
            let emptyMessage = '';
            let emptyIcon = '';
            
            switch(filterType) {
                case 'actief':
                    emptyIcon = 'fas fa-play-circle';
                    emptyMessage = {
                        title: 'Geen actieve projecten',
                        subtitle: 'Er zijn momenteel geen projecten in uitvoering.'
                    };
                    break;
                case 'voltooid':
                    emptyIcon = 'fas fa-check-circle';
                    emptyMessage = {
                        title: 'Geen voltooide projecten',
                        subtitle: 'Er zijn nog geen projecten afgerond.'
                    };
                    break;
                default:
                    emptyIcon = 'fas fa-folder-open';
                    emptyMessage = {
                        title: 'Geen projecten gevonden',
                        subtitle: 'Voeg je eerste project toe om te beginnen!'
                    };
            }
            
            grid.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="${emptyIcon}"></i>
                        <h4>${emptyMessage.title}</h4>
                        <p>${emptyMessage.subtitle}</p>
                    </div>
                </div>
            `;
            return;
        }

        grid.innerHTML = projects.map(project => this.createProjectCard(project)).join('');
    }

    createProjectCard(project) {
        const technologies = project.technologies.split(',').map(tech => 
            `<span class="tech-badge">${tech.trim()}</span>`
        ).join('');

        const imageHtml = project.image_url 
            ? `<img src="${project.image_url}" class="project-image" alt="${project.title}">`
            : `<div class="project-image-placeholder"><i class="fas fa-code"></i></div>`;

        const linksHtml = this.createProjectLinks(project);
        
        // Voeg categorie en datum informatie toe
        const categoryHtml = project.category ? `<span class="badge bg-primary mb-2">${project.category}</span>` : '';
        
        let dateInfoHtml = '';
        if (project.start_date || project.end_date) {
            dateInfoHtml = '<div class="project-dates mb-2">';
            if (project.start_date) {
                dateInfoHtml += `<small class="text-muted"><i class="fas fa-play"></i> Start: ${this.formatDate(project.start_date)}</small><br>`;
            }
            if (project.end_date) {
                dateInfoHtml += `<small class="text-muted"><i class="fas fa-stop"></i> Eind: ${this.formatDate(project.end_date)}</small>`;
            } else if (project.start_date) {
                dateInfoHtml += `<small class="text-success"><i class="fas fa-clock"></i> Actief</small>`;
            }
            dateInfoHtml += '</div>';
        }

        return `
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card project-card">
                    ${imageHtml}
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">${project.title}</h5>
                            ${categoryHtml}
                        </div>
                        ${dateInfoHtml}
                        <p class="card-text">${project.description}</p>
                        <div class="mb-2">
                            ${technologies}
                        </div>
                        ${linksHtml}
                        <div class="project-actions">
                            <button class="btn btn-primary btn-sm" onclick="portfolioBeheer.editProject(${project.id})">
                                <i class="fas fa-edit"></i> Bewerken
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="portfolioBeheer.confirmDelete(${project.id}, '${project.title}')">
                                <i class="fas fa-trash"></i> Verwijderen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('nl-NL');
    }

    createProjectLinks(project) {
        const links = [];
        
        if (project.project_url) {
            links.push(`<a href="${project.project_url}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                <i class="fas fa-external-link-alt"></i> Live Demo
            </a>`);
        }
        
        if (project.github_url) {
            links.push(`<a href="${project.github_url}" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="fab fa-github"></i> GitHub
            </a>`);
        }

        return links.length > 0 ? `<div class="project-links">${links.join('')}</div>` : '';
    }

    async saveProject() {
        const form = document.getElementById('projectForm');
        const formData = new FormData(form);
        
        // Validatie
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const projectId = formData.get('projectId');
        const action = projectId ? 'updateProject' : 'addProject';
        
        // Voeg de actie toe aan de FormData
        formData.append('action', action);

        try {
            const response = await fetch('api.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert('success', projectId ? 'Project succesvol bijgewerkt!' : 'Project succesvol toegevoegd!');
                this.closeModal();
                this.loadProjects();
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij opslaan: ' + error.message);
        }
    }

    async editProject(id) {
        try {
            const response = await fetch(`api.php?action=getProject&id=${id}`);
            const result = await response.json();

            if (result.success) {
                const project = result.data;
                
                // Vul formulier
                document.getElementById('projectId').value = project.id;
                document.getElementById('title').value = project.title;
                document.getElementById('description').value = project.description;
                document.getElementById('category').value = project.category || 'Algemeen';
                document.getElementById('technologies').value = project.technologies;
                document.getElementById('image_url').value = project.image_url || '';
                document.getElementById('project_url').value = project.project_url || '';
                document.getElementById('github_url').value = project.github_url || '';
                document.getElementById('start_date').value = project.start_date || '';
                document.getElementById('end_date').value = project.end_date || '';

                // Update modal titel
                document.getElementById('modalTitle').textContent = 'Project Bewerken';

                // Open modal
                const modal = new bootstrap.Modal(document.getElementById('addProjectModal'));
                modal.show();
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij laden project: ' + error.message);
        }
    }

    confirmDelete(id, title) {
        this.currentProjectId = id;
        document.getElementById('deleteProjectTitle').textContent = title;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    async deleteProject() {
        if (!this.currentProjectId) return;

        try {
            const formData = new FormData();
            formData.append('action', 'deleteProject');
            formData.append('id', this.currentProjectId);

            const response = await fetch('api.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showAlert('success', 'Project succesvol verwijderd!');
                this.closeDeleteModal();
                this.loadProjects();
            } else {
                this.showAlert('danger', result.message);
            }
        } catch (error) {
            this.showAlert('danger', 'Fout bij verwijderen: ' + error.message);
        }

        // Reset de current project ID
        this.currentProjectId = null;
    }

    resetForm() {
        document.getElementById('projectForm').reset();
        document.getElementById('projectId').value = '';
        document.getElementById('category').value = 'Algemeen'; // Reset naar default
        document.getElementById('modalTitle').textContent = 'Nieuw Project Toevoegen';
    }

    closeModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('addProjectModal'));
        modal.hide();
    }

    closeDeleteModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();
    }

    showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        
        // Controleer of alertContainer bestaat
        if (!alertContainer) {
            console.warn('alertContainer element niet gevonden');
            return;
        }
        
        const alertId = 'alert-' + Date.now();
        
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        alertContainer.innerHTML = alertHtml;
        
        // Auto remove na 5 seconden
        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    showLoading() {
        const projectsGrid = document.getElementById('projectsGrid');
        if (projectsGrid) {
            projectsGrid.innerHTML = `
                <div class="col-12">
                    <div class="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Laden...</span>
                        </div>
                        <p class="mt-2">Projecten laden...</p>
                    </div>
                </div>
            `;
        }
    }

    setActiveButton(buttonId) {
        // Remove active class from all filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        });

        // Add active class to clicked button
        const activeBtn = document.getElementById(buttonId);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.classList.remove('btn-outline-primary');
            activeBtn.classList.add('btn-primary');
        }
    }
}

// Initialiseer de applicatie alleen als de DOM geladen is
document.addEventListener('DOMContentLoaded', function() {
    // Controleer of we op een pagina zijn die de portfolio beheer nodig heeft
    const hasPortfolioElements = document.getElementById('projectsGrid') || 
                                 document.getElementById('saveProjectBtn') || 
                                 document.getElementById('searchInput');
    
    if (hasPortfolioElements) {
        const portfolioBeheer = new PortfolioBeheer();
    } else {
        console.log('Portfolio beheer niet geïnitialiseerd - elementen niet gevonden');
    }
});
