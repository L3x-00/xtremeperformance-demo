/**
 * XTREME PERFORMANCE - Enhanced UI Functions
 * Sistema de mejoras visuales y funcionalidades avanzadas
 */

// Variables globales
let isLoading = false;

// Función para mostrar/ocultar loading
function showLoading(element = null) {
    isLoading = true;
    if (element) {
        const originalText = element.innerHTML;
        element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
        element.disabled = true;
        element.dataset.originalText = originalText;
    } else {
        // Mostrar loading global
        const loader = document.createElement('div');
        loader.id = 'globalLoader';
        loader.className = 'global-loader';
        loader.innerHTML = `
            <div class="loader-content">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Procesando...</p>
            </div>
        `;
        document.body.appendChild(loader);
    }
}

function hideLoading(element = null) {
    isLoading = false;
    if (element && element.dataset.originalText) {
        element.innerHTML = element.dataset.originalText;
        element.disabled = false;
        delete element.dataset.originalText;
    } else {
        const loader = document.getElementById('globalLoader');
        if (loader) {
            loader.remove();
        }
    }
}

// Mejorar formularios con efectos visuales
function enhanceForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    // Agregar efectos a inputs
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        // Efecto focus mejorado
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('input-focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('input-focused');
            if (this.value.trim()) {
                this.parentElement.classList.add('input-has-value');
            } else {
                this.parentElement.classList.remove('input-has-value');
            }
        });

        // Validación en tiempo real
        input.addEventListener('input', function() {
            clearTimeout(this.validationTimeout);
            this.validationTimeout = setTimeout(() => {
                validateField(this);
            }, 500);
        });
    });

    // Mejorar botones submit
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
                showToast('error', 'Por favor corrige los errores en el formulario');
                return false;
            }
            
            showLoading(submitBtn);
            // El loading se quitará cuando la página se recargue o mediante hideLoading()
        });
    }
}

// Validación mejorada de campos
function validateField(field) {
    const fieldType = field.type || field.tagName.toLowerCase();
    const value = field.value.trim();
    let isValid = true;
    let message = '';

    // Remover clases previas
    field.classList.remove('is-valid', 'is-invalid');

    // Validaciones según tipo
    switch (fieldType) {
        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
            message = 'Formato de email inválido';
            break;

        case 'tel':
            const phoneRegex = /^9\d{8}$/;
            isValid = phoneRegex.test(value.replace(/\s/g, ''));
            message = 'Teléfono debe iniciar con 9 y tener 9 dígitos';
            break;

        case 'number':
            isValid = !isNaN(value) && value !== '';
            message = 'Debe ser un número válido';
            break;

        default:
            if (field.hasAttribute('required') && !value) {
                isValid = false;
                message = 'Este campo es requerido';
            } else if (value.length > 0 && value.length < 2) {
                isValid = false;
                message = 'Mínimo 2 caracteres';
            }
    }

    // Aplicar resultado
    if (value === '' && !field.hasAttribute('required')) {
        // Campo vacío opcional - neutral
        return true;
    }

    field.classList.add(isValid ? 'is-valid' : 'is-invalid');

    // Mostrar/ocultar mensaje de error
    let feedback = field.parentElement.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        field.parentElement.appendChild(feedback);
    }
    
    feedback.textContent = isValid ? '' : message;
    return isValid;
}

// Validar formulario completo
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isFormValid = true;

    inputs.forEach(input => {
        if (!validateField(input)) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

// Función para agregar efecto ripple a botones
function addRippleEffect(button) {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');

        this.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
}

// Función para animar contadores
function animateCounter(element, target, duration = 1000, decimals = 0, prefix = '', suffix = '') {
    let start = 0;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function (ease out)
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = start + (target - start) * easeOut;

        element.textContent = prefix + current.toFixed(decimals) + suffix;

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    requestAnimationFrame(updateCounter);
}

// Función para confirmar eliminaciones con estilo
function confirmDelete(message, callback) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar Eliminación
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                        <p class="mb-0">${message}</p>
                        <small class="text-muted">Esta acción no se puede deshacer.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        bsModal.hide();
        callback();
    });

    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });

    bsModal.show();
}

// Función para mostrar detalles en modal mejorado
function showDetailsModal(title, content, size = '') {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ${size}">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--xp-red), #b71c1c); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>
                        ${title}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });

    bsModal.show();
}

// Inicialización cuando carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    // Agregar efectos ripple a todos los botones
    document.querySelectorAll('.btn').forEach(btn => {
        if (!btn.classList.contains('no-ripple')) {
            addRippleEffect(btn);
        }
    });

    // Mejorar todos los formularios en la página
    document.querySelectorAll('form').forEach(form => {
        if (form.id) {
            enhanceForm(form.id);
        }
    });

    // Agregar clase CSS para transiciones suaves
    document.body.classList.add('transitions-enabled');

    // Auto-inicializar tooltips de Bootstrap si existen
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    // Auto-inicializar popovers de Bootstrap si existen
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        new bootstrap.Popover(popover);
    });
});

// Sistema de temas
function initThemeSystem() {
    // Cargar tema guardado
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // Actualizar estado del toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.checked = savedTheme === 'dark';
        
        // Escuchar cambios en el toggle
        themeToggle.addEventListener('change', function() {
            const theme = this.checked ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            
            // Mostrar notificación
            showToast(
                theme === 'dark' ? 'Modo oscuro activado' : 'Modo claro activado',
                'info'
            );
        });
    }
}

// Inicializar sistema de temas cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeSystem);
} else {
    initThemeSystem();
}

// Exportar funciones para uso global
window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.enhanceForm = enhanceForm;
window.validateField = validateField;
window.validateForm = validateForm;
window.animateCounter = animateCounter;
window.confirmDelete = confirmDelete;
window.showDetailsModal = showDetailsModal;
window.initThemeSystem = initThemeSystem;