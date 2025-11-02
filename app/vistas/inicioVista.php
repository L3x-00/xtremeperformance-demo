<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Xtreme Performance - Mecánica Automotriz</title>
    <meta name="title" content="Xtreme Performance - Mecánica Automotriz" />
    <meta
      name="description"
      content="Taller Automotriz Integral, especializados en reparación, planchado y pintura de vehículos. Contamos con maquina de traccionamiento y laboratorio de matizado.
                Estamos certificados Glasurit, DuPont y Axalta"
    />

        <link rel="shortcut icon" href="./public/img/favicon.png" type="image/svg+xml" />


    <!-- 
    - GOOGLE FONTS LINK
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Mulish&display=swap"
      rel="stylesheet"
    />

    <!-- 
    - FONT ICONS
  -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@40,600,0,0"
    />

    <!-- 
    - custom css link
  -->
    <link rel="stylesheet" href="./public/css/style.css" />

    <!-- 
    - IMAGENES PRE CARGADAS
  -->
    <link rel="preload" as="image" href="./public/img/hero-banner.png" />
    <link rel="preload" as="image" href="./public/img/hero-bg.png" />
  </head>

  <body>
    <!-- 
    - #CABECERA
  -->

    <header class="header">
      <div class="container">
        <div class="header-top">
          <a href="index.html" class="logo" aria-label="Ir al inicio">
            <img
              src="./public/img/LogoWhite.png"
              width="128"
              height="63"
              alt=""
            />
          </a>

          <div class="header-buttons">
            <button
              class="nav-toggle-btn"
              aria-label="toggle menu"
              data-nav-toggler
            >
              <span class="nav-toggle-icon icon-1"></span>
              <span class="nav-toggle-icon icon-2"></span>
              <span class="nav-toggle-icon icon-3"></span>
            </button>
          </div>
        </div>

        <nav class="navbar" data-navbar>
          <ul class="navbar-list">
            <li>
              <a href="index.html" class="navbar-link">Inicio</a>
            </li>

            <li>
              <a href="#nosotros" class="navbar-link">Sobre Nosotros</a>
            </li>

            <li>
              <a href="#servicios" class="navbar-link">Servicios</a>
            </li>

            <li>
              <a href="#proyectos" class="navbar-link">Nuestros Proyectos</a>
            </li>

            <li>
              <a href="#contactanos" class="navbar-link">Contactáctanos</a>
            </li>
          </ul>
        </nav>

        <!-- Botón de login de escritorio, colocado después del menú -->
        <a href="<?php print RUTA; ?>login" class="btn btn-primary login-button">
          <span class="span">INICIAR SESIÓN</span>
        </a>
      </div>
    </header>

    <!-- Botón flotante solo para móvil -->
    <a href="<?php print RUTA; ?>login" class="btn mobile-login-button" aria-label="Iniciar sesión">
      <span class="span">Iniciar sesión</span>
      <span class="material-symbols-rounded" aria-hidden="true">login</span>
    </a>

    <main>
      <article>
        <!-- 
        - #HERO IMAGE
      -->

        <section
          class="hero has-bg-image"
          aria-label="home"
          style="background-image: url('./public/img/hero-bg.png')"
        >
          <div class="container">
            <div class="hero-content">
              <p class="section-subtitle :dark">
                Nosotros tenemos talentosos mecánicos
              </p>

              <h1 class="h1 section-title">MECÁNICA AUTOMOTRIZ</h1>

              <p class="section-text">
                Taller Automotriz Integral, especializados en reparación,
                planchado y pintura de vehículos. Contamos con maquina de
                traccionamiento y laboratorio de matizado
              </p>

              <a href="#servicios" class="btn">
                <span class="span">Nuestros Servicios</span>

                <span class="material-symbols-rounded">arrow_forward</span>
              </a>
            </div>

            <figure class="hero-banner" style="--width: 1228; --height: 789">
              <img
                src="./public/img/hero-banner.png"
                width="1228"
                height="789"
                alt="red motor vehicle"
                class="move-anim"
              />
            </figure>
          </div>
        </section>

        <!-- 
        - #SERVICIOS
      -->

        <section
          class="section service has-bg-image"
          aria-labelledby="service-label"
          style="background-image: url('./public/img/service-bg.jpg')"
          id="servicios"
        >
          <div class="container">
            <p class="section-subtitle :light" id="service-label">
              Nuestros Servicios
            </p>

            <h2 class="h2 section-title">
              Nosotros ofrecemos grandes servicios para tu vehículo
            </h2>

            <ul class="service-list">
              <li>
                <div class="service-card">
                  <figure class="card-icon">
                    <img
                      src="./public/img/services-1.png"
                      width="110"
                      height="110"
                      loading="lazy"
                      alt="Engine Repair"
                    />
                  </figure>

                  <h3 class="h3 card-title">Mantenimiento</h3>

                  <p class="card-text">
                    Revisión periódica del motor, frenos y otros sistemas para
                    prevenir fallas y garantizar el buen rendimiento.
                  </p>

              
                </div>
              </li>

              <li>
                <div class="service-card">
                  <figure class="card-icon">
                    <img
                      src="./public/img/services-2.png"
                      width="110"
                      height="110"
                      loading="lazy"
                      alt="Brake Repair"
                    />
                  </figure>

                  <h3 class="h3 card-title">Planchado</h3>

                  <p class="card-text">
                    Reparación de abolladuras y alisado de superficies dañadas
                    en la carrocería por impactos o accidentes
                  </p>

                </div>
              </li>

              <li>
                <div class="service-card">
                  <figure class="card-icon">
                    <img
                      src="./public/img/services-3.png"
                      width="110"
                      height="110"
                      loading="lazy"
                      alt="Tire Repair"
                    />
                  </figure>

                  <h3 class="h3 card-title">Traccionamiento</h3>

                  <p class="card-text">
                    Sistema que asegura el buen agarre del auto al suelo en
                    distintas condiciones para evitar deslizamientos
                  </p>

                </div>
              </li>

              <li>
                <div class="service-card">
                  <figure class="card-icon">
                    <img
                      src="./public/img/services-4.png"
                      width="110"
                      height="110"
                      loading="lazy"
                      alt="Battery Repair"
                    />
                  </figure>

                  <h3 class="h3 card-title">Reparar bateria</h3>

                  <p class="card-text">
                    Servicio que evalúa, recarga o reemplaza baterías defectuosas para garantizar el arranque y funcionamiento del vehículo
                  </p>

               
                </div>
              </li>

              <li class="service-banner">
                <img
                  src="./public/img/services-5.png"
                  width="646"
                  height="380"
                  loading="lazy"
                  alt="Red Car"
                  class="move-anim"
                />
              </li>

              <li>
                <div class="service-card">
                  <figure class="card-icon">
                    <img
                      src="./public/img/pintura.png"
                      width="110"
                      height="110"
                      loading="lazy"
                      alt="Steering Repair"
                    />
                  </figure>

                  <h3 class="h3 card-title">Pintura</h3>

                  <p class="card-text">
                   Proceso que restaura o renueva la apariencia del vehículo con pintura especializada y acabado profesiona
                  </p>

                  
                </div>
              </li>
            </ul>

           
          </div>
        </section>

        <!-- 
        - #SOBRE NOSOTROS
      -->

        <section class="section about has-before" aria-labelledby="about-label" id="nosotros">
          <div class="container">
            <figure class="about-banner">
              <img
                src="./public/img/about-banner.png"
                width="540"
                height="540"
                loading="lazy"
                alt="vehicle repire equipments"
                class="w-100"
              />
            </figure>

            <div class="about-content">
              <p class="section-subtitle :dark">Sobre Nosotros</p>

                        <h2 class="h2 section-title">
                          Nos comprometemos a cumplir con la calidad
                        </h2>

                        <p class="section-text">
                          En Xtreme Performance ofrecemos servicios automotrices
                          con altos estándares de calidad, precisión y puntualidad.
                          Contamos con personal capacitado y tecnología moderna para
                          brindar soluciones confiables a cada vehículo.
                        </p>

                        <p class="section-text">
                          Nos especializamos en traccionamiento, planchado, mantenimiento
                          y pintura, asegurando resultados duraderos y la satisfacción de
                          nuestros clientes. Tu confianza es nuestra mejor garantía.
                        </p>

              </p>

              <ul class="about-list">
                <li class="about-item">
                  <p>
                    <strong class="display-1 strong">1K+</strong> Clientes Felices
                  </p>
                </li>

                <li class="about-item">
                  <p>
                    <strong class="display-1 strong">10+</strong> Instrumentos
                  </p>
                </li>

                <li class="about-item">
                  <p>
                    <strong class="display-1 strong">10+</strong> Años en el mercado
                  </p>
                </li>

                <li class="about-item">
                  <p>
                    <strong class="display-1 strong">99%</strong> Proyectos Completados
                  </p>
                </li>
              </ul>
            </div>
          </div>
        </section>

        <!-- 
        - #WORK
      -->

        <section class="section work" aria-labelledby="work-label" id="proyectos">
          <div class="container">
            <p class="section-subtitle :light" id="work-label">Nuestros Trabajos</p>

            <h2 class="h2 section-title">Ultimos Proyectos que Realizamos</h2>

            <ul class="has-scrollbar">
              <li class="scrollbar-item">
                <div class="work-card">
                  <figure
                    class="card-banner img-holder"
                    style="--width: 350; --height: 406"
                  >
                    <img
                      src="./public/img/work-1.jpg"
                      width="350"
                      height="406"
                      loading="lazy"
                      alt="Engine Repair"
                      class="img-cover"
                    />
                  </figure>

                  <div class="card-content">
                    <p class="card-subtitle">Reparamiento de autos</p>

                    <h3 class="h3 card-title">Reparación general</h3>

                   
                  </div>
                </div>
              </li>

              <li class="scrollbar-item">
                <div class="work-card">
                  <figure
                    class="card-banner img-holder"
                    style="--width: 350; --height: 406"
                  >
                    <img
                      src="./public/img/work-2.jpg"
                      width="350"
                      height="406"
                      loading="lazy"
                      alt="Car Tyre change"
                      class="img-cover"
                    />
                  </figure>

                  <div class="card-content">
                    <p class="card-subtitle">Reparar autos</p>

                    <h3 class="h3 card-title">Cambio del tirón </h3>

                    
                  </div>
                </div>
              </li>

              <li class="scrollbar-item">
                <div class="work-card">
                  <figure
                    class="card-banner img-holder"
                    style="--width: 350; --height: 406"
                  >
                    <img
                      src="./public/img/work-3.jpg"
                      width="350"
                      height="406"
                      loading="lazy"
                      alt="Battery Adjust"
                      class="img-cover"
                    />
                  </figure>

                  <div class="card-content">
                    <p class="card-subtitle">Reparar autos</p>

                    <h3 class="h3 card-title">Ajustar Baterías</h3>

                    
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </section>
      </article>
    </main>

    <!-- 
    - #FOOTER
  -->

    <footer class="footer" id="contactanos">
      <div class="footer-top section">
        <div class="container">
          <div class="footer-brand">
            <a href="#" class="logo">
              <img
                src="./public/img/logoWhite.png"
                width="128"
                height="63"
                alt="autofix home"
              />
            </a>

            <p class="footer-text">
              Somos un taller automotriz especializado en brindar servicios
  de calidad en traccionamiento, planchado, mantenimiento y
  pintura. Nuestro compromiso es el cuidado y rendimiento
  óptimo de tu vehículo.
            </p>

            <ul class="social-list">
              <li>
                <a href="https://www.facebook.com/p/Xtreme-Performance-Automotive-EIRL-100057547266000/" class="social-link" target="_blank">
                  <img src="./public/img/facebook.svg" alt="facebook" />
                </a>
              </li>

              <li>
                <a href="#" class="social-link">
                  <img src="./public/img/instagram.svg" alt="instagram" />
                </a>
              </li>

              <li>
                <a href="#" class="social-link">
                  <img src="./public/img/twitter.svg" alt="twitter" />
                </a>
              </li>
            </ul>
          </div>

          <ul class="footer-list">
            <li>
              <p class="h3">Horario de Atención</p>
            </li>

            <li>
              <p class="p">Lunes - Viernes</p>

              <span class="span">8:00 a.m - 6:00 p.m</span>
            </li>

            <li>
              <p class="p">Sábado</p>

              <span class="span">8:00 a.m - 2:00 p.m </span>
            </li>

          
          </ul>

          <ul class="footer-list">
            <li>
              <p class="h3">Información de Contacto</p>
            </li>

            <li>
              <a href="tel:+01234567890" class="footer-link">
                <span class="material-symbols-rounded">call</span>

                <span class="span">+51 998 980 547</span>
              </a>
            </li>

            <li>
              <a href="mailto:info@autofix.com" class="footer-link">
                <span class="material-symbols-rounded">mail</span>

                <span class="span">xtremeperformance@gmail.com</span>
              </a>
            </li>

            <li>
              <address class="footer-link address">
                <span class="material-symbols-rounded">location_on</span>

                <span class="span"
                  >Jr Nemesio Raez 2241 El tambo, Huancayo, Peru</span
                >
              </address>
            </li>
          </ul>
        </div>

        <img
          src="./public/img/LogoLow.png"
          width="637"
          height="173"
          loading="lazy"
          alt="Shape"
          class="shape shape-3 move-anim"
        />
      </div>

      <div class="footer-bottom">
        <div class="container">
          <p class="copyright">
            Copyright 2025, Todos los derechos pertenecen a Xtreme Performance.
          </p>

          <img
            src="./public/img/footer-shape-2.png"
            width="778"
            height="335"
            loading="lazy"
            alt="Shape"
            class="shape shape-2"
          />

          <img
            src="./public/img/footer-shape-1.png"
            width="805"
            height="652"
            loading="lazy"
            alt="Red Car"
            class="shape shape-1 move-anim"
          />
        </div>
      </div>
    </footer>

    <!-- 
    - custom js link
  -->
    <script src="./public/js/script.js"></script>
  </body>
</html>
