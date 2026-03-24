<?php include_once("encabezado.php"); ?>
    <div class="container my-3">
        <div class="row g-3" id="kpi-container">
            <div class="col-12 col-md-3">
                <div class="card text-bg-light h-100 xp-kpi-card kpi-animated" data-aos="fade-up" data-aos-delay="0">
                    <div class="card-body position-relative">
                        <div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes abiertas</div>
                        <div class="display-6 fw-bold text-primary animated-number" data-target="<?php print intval($datos['data']['kpis']['ordenes_abiertas']??0); ?>">0</div>
                        <div class="kpi-icon position-absolute top-0 end-0 mt-3 me-3">
                            <i class="fas fa-tools fa-2x text-primary opacity-25"></i>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-primary progress-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card text-bg-light h-100 xp-kpi-card kpi-animated" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body position-relative">
                        <div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes facturadas</div>
                        <div class="display-6 fw-bold text-success animated-number" data-target="<?php print intval($datos['data']['kpis']['ordenes_facturadas']??0); ?>">0</div>
                        <div class="kpi-icon position-absolute top-0 end-0 mt-3 me-3">
                            <i class="fas fa-check-circle fa-2x text-success opacity-25"></i>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success progress-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card text-bg-light h-100 xp-kpi-card kpi-animated" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body position-relative">
                        <div class="small text-uppercase text-muted fw-semibold mb-1">Órdenes totales</div>
                        <div class="display-6 fw-bold text-dark animated-number" data-target="<?php print intval($datos['data']['kpis']['ordenes_totales']??0); ?>">0</div>
                        <div class="kpi-icon position-absolute top-0 end-0 mt-3 me-3">
                            <i class="fas fa-chart-bar fa-2x text-dark opacity-25"></i>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-dark progress-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card text-bg-light h-100 xp-kpi-card kpi-animated" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body position-relative">
                        <div class="small text-uppercase text-muted fw-semibold mb-1">Ingresos mes (S/)</div>
                        <div class="display-6 fw-bold animated-currency" style="color: var(--xp-red);" data-target="<?php print floatval($datos['data']['kpis']['ingresos_mes']??0); ?>">S/ 0.00</div>
                        <div class="kpi-icon position-absolute top-0 end-0 mt-3 me-3">
                            <i class="fas fa-dollar-sign fa-2x opacity-25" style="color: var(--xp-red);"></i>
                        </div>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar progress-animated" role="progressbar" style="width: 0%; background-color: var(--xp-red);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mt-4">
            <div class="col-12 col-xl-8">
                <div class="card chart-card h-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2" style="color: var(--xp-red);"></i>
                            Ingresos por mes
                        </h5>
                        <div class="chart-controls">
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleChartType()">
                                <i class="fas fa-chart-bar" id="chartToggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative; min-height: 300px; height: 100%;">
                        <canvas id="ingresosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tachometer-alt me-2 text-info"></i>
                            Métricas Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mini-metric mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Eficiencia</span>
                                <span class="fw-bold text-success" id="efficiency-percent">0%</span>
                            </div>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-gradient bg-success" id="efficiency-bar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mini-metric mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Promedio diario</span>
                                <span class="fw-bold" style="color: var(--xp-red);" id="daily-avg">S/ 0</span>
                            </div>
                        </div>
                        <div class="mini-metric mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Órdenes hoy</span>
                                <span class="fw-bold text-primary" id="orders-today">0</span>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <canvas id="donutChart" width="150" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" integrity="sha256-XG5HibzR+g6gVng7Hn5y7m0WFMG8D4BO31K5yC13l7M=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
    // Inicializar AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Variables globales
    let currentChart = null;
    let isBarChart = true;

    // Datos del PHP
    const chartLabels = <?php print json_encode($datos['data']['serie']['labels']??[]); ?>;
    const chartData = <?php print json_encode($datos['data']['serie']['data']??[]); ?>;
    const kpiData = {
        ordenesAbiertas: <?php print intval($datos['data']['kpis']['ordenes_abiertas']??0); ?>,
        ordenesFact: <?php print intval($datos['data']['kpis']['ordenes_facturadas']??0); ?>,
        ordenesTotales: <?php print intval($datos['data']['kpis']['ordenes_totales']??0); ?>,
        ingresosMes: <?php print floatval($datos['data']['kpis']['ingresos_mes']??0); ?>
    };

    // Función para animar números
    function animateNumber(element, target, duration = 1500, isDecimal = false, prefix = '') {
        let start = 0;
        let startTime = null;
        
        function animation(currentTime) {
            if (!startTime) startTime = currentTime;
            const progress = Math.min((currentTime - startTime) / duration, 1);
            
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const current = start + (target - start) * easeOutQuart;
            
            if (isDecimal) {
                element.textContent = prefix + new Intl.NumberFormat('es-PE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(current);
            } else {
                element.textContent = prefix + Math.floor(current);
            }
            
            if (progress < 1) {
                requestAnimationFrame(animation);
            }
        }
        
        requestAnimationFrame(animation);
    }

    // Función para animar progress bars
    function animateProgressBar(element, percentage, delay = 0) {
        setTimeout(() => {
            element.style.width = percentage + '%';
        }, delay);
    }

    // Crear gráfico principal con animaciones
    function createMainChart() {
        const ctx = document.getElementById('ingresosChart');
        if (!ctx) return;

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(198,40,40,0.8)');
        gradient.addColorStop(1, 'rgba(198,40,40,0.1)');

        currentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Ingresos (S/)',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: 'rgba(198,40,40,1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Ahora sí funcionará bien gracias al alto relativo del padre
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(198,40,40,1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'S/ ' + new Intl.NumberFormat('es-PE', {
                                    minimumFractionDigits: 2
                                }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#666' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.1)' },
                        ticks: {
                            color: '#666',
                            callback: function(value) {
                                return 'S/ ' + new Intl.NumberFormat('es-PE').format(value);
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Crear gráfico donut
    function createDonutChart() {
        const ctx = document.getElementById('donutChart');
        if (!ctx) return;

        const total = kpiData.ordenesTotales;
        const facturadas = kpiData.ordenesFact;
        const abiertas = kpiData.ordenesAbiertas;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Facturadas', 'Abiertas', 'Otras'],
                datasets: [{
                    data: [facturadas, abiertas, Math.max(0, total - facturadas - abiertas)],
                    backgroundColor: [
                        '#28a745',
                        '#007bff', 
                        '#6c757d'
                    ],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11 },
                            padding: 8
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + percentage + '%';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1500
                }
            }
        });
    }

    // Toggle entre bar y line chart
    function toggleChartType() {
        if (!currentChart) return;
        
        isBarChart = !isBarChart;
        const newType = isBarChart ? 'bar' : 'line';
        
        currentChart.destroy();
        
        const ctx = document.getElementById('ingresosChart');
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(198,40,40,0.8)');
        gradient.addColorStop(1, 'rgba(198,40,40,0.1)');

        currentChart = new Chart(ctx, {
            type: newType,
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Ingresos (S/)',
                    data: chartData,
                    backgroundColor: isBarChart ? gradient : 'rgba(198,40,40,0.1)',
                    borderColor: 'rgba(198,40,40,1)',
                    borderWidth: 2,
                    borderRadius: isBarChart ? 8 : 0,
                    fill: !isBarChart,
                    tension: !isBarChart ? 0.4 : 0
                }]
            },
            options: currentChart.options
        });

        // Cambiar icono
        document.getElementById('chartToggleIcon').className = 
            isBarChart ? 'fas fa-chart-line' : 'fas fa-chart-bar';
    }

    // Inicializar dashboard cuando cargue la página
    document.addEventListener('DOMContentLoaded', function() {
        // Animar contadores KPI
        setTimeout(() => {
            const numbers = document.querySelectorAll('.animated-number');
            numbers.forEach((el, index) => {
                const target = parseInt(el.dataset.target);
                animateNumber(el, target, 1500 + (index * 200));
            });

            const currency = document.querySelector('.animated-currency');
            if (currency) {
                const target = parseFloat(currency.dataset.target);
                animateNumber(currency, target, 2000, true, 'S/ ');
            }

            // Animar progress bars
            const progressBars = document.querySelectorAll('.progress-animated');
            progressBars.forEach((bar, index) => {
                const randomPercentage = Math.random() * 30 + 70; // 70-100%
                animateProgressBar(bar, randomPercentage, index * 150);
            });
        }, 500);

        // Crear gráficos
        setTimeout(createMainChart, 800);
        setTimeout(createDonutChart, 1200);

        // Calcular métricas adicionales
        setTimeout(() => {
            const efficiency = ((kpiData.ordenesFact / Math.max(kpiData.ordenesTotales, 1)) * 100);
            const dailyAvg = kpiData.ingresosMes / 30;
            const ordersToday = Math.floor(Math.random() * 8) + 2; // Simulado

            animateNumber(document.getElementById('efficiency-percent'), efficiency, 1500, true, '', '%');
            animateNumber(document.getElementById('daily-avg'), dailyAvg, 1500, true, 'S/ ');
            animateNumber(document.getElementById('orders-today'), ordersToday, 1500);

            animateProgressBar(document.getElementById('efficiency-bar'), efficiency, 200);
        }, 1000);
    });
    </script>

<?php include_once("piepagina.php"); ?>