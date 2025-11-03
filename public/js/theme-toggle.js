/**
 * Sistema de Toggle de Tema Simplificado
 * Xtreme Performance - 2025
 */

// Función principal para inicializar el tema
function initializeTheme() {
    console.log('🎨 Inicializando sistema de temas...');
    
    // Cargar tema guardado
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    console.log('💾 Tema cargado:', savedTheme);
    
    // Encontrar el toggle
    const toggle = document.getElementById('theme-toggle');
    
    if (toggle) {
        // Configurar estado inicial
        toggle.checked = savedTheme === 'dark';
        console.log('✅ Toggle configurado:', toggle.checked);
        
        // Agregar evento de cambio
        toggle.addEventListener('change', function(event) {
            handleThemeChange(event.target.checked);
        });
        
        // También manejar clicks en el label
        const label = document.querySelector('label[for="theme-toggle"]');
        if (label) {
            label.addEventListener('click', function() {
                // Pequeño delay para que el checkbox se actualice primero
                setTimeout(() => {
                    handleThemeChange(toggle.checked);
                }, 10);
            });
        }
        
        console.log('🎯 Event listeners agregados correctamente');
    } else {
        console.warn('⚠️ No se encontró el toggle de tema');
    }
}

// Función para cambiar el tema
function handleThemeChange(isDark) {
    const theme = isDark ? 'dark' : 'light';
    console.log('🔄 Cambiando tema a:', theme);
    
    // Aplicar tema
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Mostrar notificación
    showThemeNotification(theme);
    
    // Agregar clase de transición temporal
    document.body.style.transition = 'all 0.3s ease';
    setTimeout(() => {
        document.body.style.transition = '';
    }, 300);
}

// Función para mostrar notificación
function showThemeNotification(theme) {
    const message = theme === 'dark' ? '🌙 Modo oscuro activado' : '☀️ Modo claro activado';
    
    if (typeof showToast === 'function') {
        showToast(message, 'info');
    } else if (typeof toastr !== 'undefined') {
        toastr.info(message);
    } else {
        console.log('📢', message);
    }
}

// Función pública para cambiar tema manualmente
function toggleTheme() {
    const toggle = document.getElementById('theme-toggle');
    if (toggle) {
        toggle.checked = !toggle.checked;
        handleThemeChange(toggle.checked);
    } else {
        // Si no hay toggle, cambiar directamente
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        showThemeNotification(newTheme);
    }
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTheme);
} else {
    initializeTheme();
}

// También inicializar con jQuery si está disponible
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        setTimeout(initializeTheme, 100);
    });
}

// Exportar funciones para uso global
window.toggleTheme = toggleTheme;
window.initializeTheme = initializeTheme;