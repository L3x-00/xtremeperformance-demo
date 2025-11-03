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
    console.log('🌙 Inicializando sistema de temas...');
    
    // Cargar tema guardado
    const savedTheme = localStorage.getItem('theme') || 'light';
    console.log('🎨 Tema guardado:', savedTheme);
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // Actualizar estado del toggle
    const themeToggle = document.getElementById('theme-toggle');
    console.log('🔘 Toggle encontrado:', !!themeToggle);
    
    if (themeToggle) {
        themeToggle.checked = savedTheme === 'dark';
        console.log('✅ Toggle configurado, modo dark:', savedTheme === 'dark');
        
        // Escuchar cambios en el toggle
        themeToggle.addEventListener('change', function() {
            const theme = this.checked ? 'dark' : 'light';
            console.log('🔄 Cambiando tema a:', theme);
            
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            
            // Mostrar notificación si la función existe
            if (typeof showToast === 'function') {
                showToast(
                    theme === 'dark' ? '🌙 Modo oscuro activado' : '☀️ Modo claro activado',
                    'info'
                );
            } else {
                console.log('📢 Tema cambiado a:', theme);
            }
        });
    } else {
        console.warn('⚠️ No se encontró el toggle de tema (#theme-toggle)');
    }
    
    // Aplicar el tema inmediatamente
    document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
}

// Inicializar sistema de temas cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeSystem);
} else {
    initThemeSystem();
}

// También inicializar cuando jQuery esté listo (por compatibilidad)
$(document).ready(function() {
    setTimeout(initThemeSystem, 100); // Pequeño delay para asegurar que todo esté cargado
    setTimeout(initMicrointeractions, 200); // Inicializar microinteracciones después
});

// ===== MICROINTERACCIONES Y ANIMACIONES =====

// Agregar animaciones de entrada a elementos
function addEntranceAnimations() {
    // Observador para animaciones cuando entran al viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    // Observar elementos que deben animarse
    document.querySelectorAll('.card, .table, .btn-group').forEach(el => {
        observer.observe(el);
    });
}

// Loading skeleton para tablas
function showTableSkeleton(tableSelector, rows = 5) {
    const table = document.querySelector(tableSelector);
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const headers = table.querySelectorAll('thead th').length || 4;
    
    let skeletonRows = '';
    for (let i = 0; i < rows; i++) {
        skeletonRows += '<tr>';
        for (let j = 0; j < headers; j++) {
            skeletonRows += '<td><div class="skeleton skeleton-text"></div></td>';
        }
        skeletonRows += '</tr>';
    }
    
    tbody.innerHTML = skeletonRows;
    table.classList.add('loading-skeleton');
}

// Remover skeleton de tabla
function hideTableSkeleton(tableSelector) {
    const table = document.querySelector(tableSelector);
    if (table) {
        table.classList.remove('loading-skeleton');
    }
}

// Agregar efectos hover dinámicos
function addHoverEffects() {
    // Agregar clase hover-lift a cards
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('hover-lift');
    });

    // Agregar efectos a botones
    document.querySelectorAll('.btn').forEach(btn => {
        btn.classList.add('microinteraction');
        
        // Efecto ripple mejorado
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Efectos en filas de tabla
    document.querySelectorAll('.table-hover tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Animación de contadores mejorada
function animateCounterAdvanced(element, start, end, duration = 2000, prefix = '', suffix = '') {
    const startTimestamp = performance.now();
    
    const step = (timestamp) => {
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const current = Math.floor(easeOutQuart * (end - start) + start);
        
        element.textContent = prefix + current.toLocaleString() + suffix;
        
        if (progress < 1) {
            requestAnimationFrame(step);
        }
    };
    
    requestAnimationFrame(step);
}

// Loading state para botones
function setButtonLoading(buttonSelector, isLoading = true) {
    const buttons = document.querySelectorAll(buttonSelector);
    
    buttons.forEach(btn => {
        if (isLoading) {
            btn.classList.add('btn-loading');
            btn.disabled = true;
            btn.setAttribute('data-original-text', btn.textContent);
        } else {
            btn.classList.remove('btn-loading');
            btn.disabled = false;
            const originalText = btn.getAttribute('data-original-text');
            if (originalText) {
                btn.textContent = originalText;
                btn.removeAttribute('data-original-text');
            }
        }
    });
}

// Progreso animado
function animateProgress(progressSelector, targetPercent, duration = 1000) {
    const progressBars = document.querySelectorAll(progressSelector);
    
    progressBars.forEach(bar => {
        bar.style.width = '0%';
        bar.setAttribute('aria-valuenow', '0');
        
        setTimeout(() => {
            bar.style.transition = `width ${duration}ms ease-out`;
            bar.style.width = targetPercent + '%';
            bar.setAttribute('aria-valuenow', targetPercent);
        }, 100);
    });
}

// Notificación flotante mejorada
function showFloatingNotification(message, type = 'info', duration = 4000) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} floating-notification animate-scale`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, duration);
}

// Inicializar todas las microinteracciones
function initMicrointeractions() {
    console.log('🎨 Inicializando microinteracciones...');
    
    addEntranceAnimations();
    addHoverEffects();
    
    // Agregar clases de animación flotante a iconos especiales
    document.querySelectorAll('.fa-car, .fa-wrench, .fa-cogs').forEach(icon => {
        icon.classList.add('hover-float');
    });
    
    // Mejorar formularios con efectos focus
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    console.log('✨ Microinteracciones inicializadas correctamente');
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
window.showTableSkeleton = showTableSkeleton;
window.hideTableSkeleton = hideTableSkeleton;
window.animateCounterAdvanced = animateCounterAdvanced;
window.setButtonLoading = setButtonLoading;
window.animateProgress = animateProgress;
window.showFloatingNotification = showFloatingNotification;
window.initMicrointeractions = initMicrointeractions;