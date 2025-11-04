<!DOCTYPE html><!DOCTYPE html>

<html lang="es"><html lang="es">

  <head>  <head>

    <meta charset="UTF-8" />    <meta charset="UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    <meta name="viewport" content="width=device-width, initial-scale=1.0" />



    <title>Xtreme Performance - Mecánica Automotriz</title>    <title>Xtreme Performance - Mecánica Automotriz</title>

    <meta name="title" content="Xtreme Performance - Mecánica Automotriz" />    <meta name="title" content="Xtreme Performance - Mecánica Automotriz" />

    <meta    <meta

      name="description"      name="description"

      content="Taller Automotriz Integral, especializados en reparación, planchado y pintura de vehículos. Contamos con maquina de traccionamiento y laboratorio de matizado.      content="Taller Automotriz Integral, especializados en reparación, planchado y pintura de vehículos. Contamos con maquina de traccionamiento y laboratorio de matizado.

                Estamos certificados Glasurit, DuPont y Axalta"                Estamos certificados Glasurit, DuPont y Axalta"

    />    />



        <link rel="shortcut icon" href="./public/img/favicon.png" type="image/svg+xml" />        <link rel="shortcut icon" href="./public/img/favicon.png" type="image/svg+xml" />





    <!--     <!-- 

    - GOOGLE FONTS LINK    - GOOGLE FONTS LINK

  -->  -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />    <link rel="preconnect" href="https://fonts.googleapis.com" />

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link    <link

      href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Mulish&display=swap"      href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Mulish&display=swap"

      rel="stylesheet"      rel="stylesheet"

    />    />



    <!--     <!-- 

    - FONT ICONS    - FONT ICONS

  -->  -->

    <link    <link

      rel="stylesheet"      rel="stylesheet"

      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@40,600,0,0"      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@40,600,0,0"

    />    />



    <!--     <!-- 

    - custom css link    - custom css link

  -->  -->

    <link rel="stylesheet" href="./public/css/style.css" />    <link rel="stylesheet" href="./public/css/style.css" />    <!-- 

    - IMAGENES PRE CARGADAS

    <!--   -->

    - IMAGENES PRE CARGADAS    <link rel="preload" as="image" href="./public/img/hero-banner.png" />

  -->    <link rel="preload" as="image" href="./public/img/hero-bg.png" />

    <link rel="preload" as="image" href="./public/img/hero-banner.png" />  </head>

    <link rel="preload" as="image" href="./public/img/hero-bg.png" />

  </head>  <body>

    <!-- 

  <body>    - #CABECERA

    <!--   -->

    - #CABECERA

  -->    <header class="header">

      <div class="container">

    <header class="header">        <div class="header-top">

      <div class="container">          <a href="index.html" class="logo" aria-label="Ir al inicio">

        <a href="index.html" class="logo">            <img

          <img              src="./public/img/LogoWhite.png"

            src="./public/img/LogoWhite.png"              width="128"

            width="128"              height="63"

            height="63"              alt=""

            alt="autofix home"            />

          />          </a>

        </a>

          <div class="header-buttons">

        <nav class="navbar" data-navbar>            <button

          <ul class="navbar-list">              class="nav-toggle-btn"

            <li>              aria-label="toggle menu"

              <a href="index.html" class="navbar-link">Inicio</a>              data-nav-toggler

            </li>            >

              <span class="nav-toggle-icon icon-1"></span>

            <li>              <span class="nav-toggle-icon icon-2"></span>

              <a href="#nosotros" class="navbar-link">Sobre Nosotros</a>              <span class="nav-toggle-icon icon-3"></span>

            </li>            </button>

          </div>

            <li>        </div>

              <a href="#servicios" class="navbar-link">Servicios</a>

            </li>        <nav class="navbar" data-navbar>

          <ul class="navbar-list">

            <li>            <li>

              <a href="#proyectos" class="navbar-link">Nuestros Proyectos</a>              <a href="index.html" class="navbar-link">Inicio</a>

            </li>            </li>



            <li>            <li>

              <a href="#contactanos" class="navbar-link">Contáctanos</a>              <a href="#nosotros" class="navbar-link">Sobre Nosotros</a>

            </li>            </li>

          </ul>

        </nav>            <li>

              <a href="#servicios" class="navbar-link">Servicios</a>

     <a href="<?php print RUTA; ?>login" class="btn btn-primary">            </li>

  <span class="span">INICIAR SESIÓN</span>

  </a>            <li>

        <button              <a href="#proyectos" class="navbar-link">Nuestros Proyectos</a>

          class="nav-toggle-btn"            </li>

          aria-label="toggle menu"

          data-nav-toggler            <li>

        >              <a href="#contactanos" class="navbar-link">Contactáctanos</a>

          <span class="nav-toggle-icon icon-1"></span>            </li>

          <span class="nav-toggle-icon icon-2"></span>          </ul>

          <span class="nav-toggle-icon icon-3"></span>        </nav>

        </button>

      </div>        <?php

    </header>          // Determinar a dónde debe apuntar el botón según sesión

          $loginHref = RUTA.'login';

    <main>          $loginText = 'INICIAR SESIÓN';

      <article>          if (class_exists('Sesion')) {

        <!--             $__s = new Sesion();

        - #HERO IMAGE            if ($__s->getLogin()) {

      -->              $__u = $__s->getUsuario();

              $t = $__u['tipoUsuario'] ?? null;

        <section              if ($t === ADMON) { $loginHref = RUTA.'Tablero'; }

          class="hero has-bg-image"              else if ($t === MECANICO) { $loginHref = RUTA.'TableroMecanico'; }

          aria-label="home"              else if ($t === CLIENTE) { $loginHref = RUTA.'TableroCliente'; }

          style="background-image: url('./public/img/hero-bg.png')"              $loginText = 'IR AL PANEL';

        >            }

          <div class="container">          }

            <div class="hero-content">        ?>

              <p class="section-subtitle :dark">        <!-- Botón de login de escritorio, colocado después del menú -->

                Nosotros tenemos talentosos mecánicos        <a href="<?php print $loginHref; ?>" class="btn btn-primary login-button">

              </p>          <span class="span"><?php print $loginText; ?></span>

        </a>

              <h1 class="h1 section-title">MECÁNICA AUTOMOTRIZ</h1>      </div>

    </header>

              <p class="section-text">

                Taller Automotriz Integral, especializados en reparación,    <!-- Botón flotante solo para móvil -->

                planchado y pintura de vehículos. Contamos con maquina de    <a href="<?php print $loginHref; ?>" class="btn mobile-login-button" aria-label="Iniciar sesión">

                traccionamiento y laboratorio de matizado      <span class="span"><?php print $loginText; ?></span>

              </p>      <span class="material-symbols-rounded" aria-hidden="true">login</span>

    </a>

              <a href="#servicios" class="btn">

                <span class="span">Nuestros Servicios</span>    <main>

      <article>

                <span class="material-symbols-rounded">arrow_forward</span>        <!-- 

              </a>        - #HERO IMAGE

            </div>      -->



            <figure class="hero-banner" style="--width: 1228; --height: 789">        <section

              <img          class="hero has-bg-image"

                src="./public/img/hero-banner.png"          aria-label="home"

                width="1228"          style="background-image: url('./public/img/hero-bg.png')"

                height="789"        >

                alt="red motor vehicle"          <div class="container">

                class="move-anim"            <div class="hero-content">

              />              <p class="section-subtitle :dark">

            </figure>                Nosotros tenemos talentosos mecánicos

          </div>              </p>

        </section>

              <h1 class="h1 section-title">MECÁNICA AUTOMOTRIZ</h1>

        <!-- 

        - #SERVICIOS              <p class="section-text">

      -->                Taller Automotriz Integral, especializados en reparación,

                planchado y pintura de vehículos. Contamos con maquina de

        <section                traccionamiento y laboratorio de matizado

          class="section service has-bg-image"              </p>

          aria-labelledby="service-label"

          style="background-image: url('./public/img/service-bg.jpg')"              <a href="#servicios" class="btn">

          id="servicios"                <span class="span">Nuestros Servicios</span>

        >

          <div class="container">                <span class="material-symbols-rounded">arrow_forward</span>

            <p class="section-subtitle :light" id="service-label">              </a>

              Nuestros Servicios            </div>

            </p>

            <figure class="hero-banner" style="--width: 1228; --height: 789">

            <h2 class="h2 section-title">              <img

              Nosotros ofrecemos grandes servicios para tu vehículo                src="./public/img/hero-banner.png"

            </h2>                width="1228"

                height="789"

            <ul class="service-list">                alt="red motor vehicle"

              <li>                class="move-anim"

                <div class="service-card">              />

                  <figure class="card-icon">            </figure>

                    <img          </div>

                      src="./public/img/services-1.png"        </section>

                      width="110"

                      height="110"        <!-- 

                      loading="lazy"        - #SERVICIOS

                      alt="Engine Repair"      -->

                    />

                  </figure>        <section

          class="section service has-bg-image"

                  <h3 class="h3 card-title">Mantenimiento</h3>          aria-labelledby="service-label"

          style="background-image: url('./public/img/service-bg.jpg')"

                  <p class="card-text">          id="servicios"

                    Revisión periódica del motor, frenos y otros sistemas para        >

                    prevenir fallas y garantizar el buen rendimiento.          <div class="container">

                  </p>            <p class="section-subtitle :light" id="service-label">

              Nuestros Servicios

                          </p>

                </div>

              </li>            <h2 class="h2 section-title">

              Nosotros ofrecemos grandes servicios para tu vehículo

              <li>            </h2>

                <div class="service-card">

                  <figure class="card-icon">            <ul class="service-list">

                    <img              <li>

                      src="./public/img/services-2.png"                <div class="service-card">

                      width="110"                  <figure class="card-icon">

                      height="110"                    <img

                      loading="lazy"                      src="./public/img/services-1.png"

                      alt="Brake Repair"                      width="110"

                    />                      height="110"

                  </figure>                      loading="lazy"

                      alt="Engine Repair"

                  <h3 class="h3 card-title">Planchado</h3>                    />

                  </figure>

                  <p class="card-text">

                    Reparación de abolladuras y alisado de superficies dañadas                  <h3 class="h3 card-title">Mantenimiento</h3>

                    en la carrocería por impactos o accidentes

                  </p>                  <p class="card-text">

                    Revisión periódica del motor, frenos y otros sistemas para

                </div>                    prevenir fallas y garantizar el buen rendimiento.

              </li>                  </p>



              <li>              

                <div class="service-card">                </div>

                  <figure class="card-icon">              </li>

                    <img

                      src="./public/img/services-3.png"              <li>

                      width="110"                <div class="service-card">

                      height="110"                  <figure class="card-icon">

                      loading="lazy"                    <img

                      alt="Tire Repair"                      src="./public/img/services-2.png"

                    />                      width="110"

                  </figure>                      height="110"

                      loading="lazy"

                  <h3 class="h3 card-title">Traccionamiento</h3>                      alt="Brake Repair"

                    />

                  <p class="card-text">                  </figure>

                    Sistema que asegura el buen agarre del auto al suelo en

                    distintas condiciones para evitar deslizamientos                  <h3 class="h3 card-title">Planchado</h3>

                  </p>

                  <p class="card-text">

                </div>                    Reparación de abolladuras y alisado de superficies dañadas

              </li>                    en la carrocería por impactos o accidentes

                  </p>

              <li>

                <div class="service-card">                </div>

                  <figure class="card-icon">              </li>

                    <img

                      src="./public/img/services-4.png"              <li>

                      width="110"                <div class="service-card">

                      height="110"                  <figure class="card-icon">

                      loading="lazy"                    <img

                      alt="Battery Repair"                      src="./public/img/services-3.png"

                    />                      width="110"

                  </figure>                      height="110"

                      loading="lazy"

                  <h3 class="h3 card-title">Reparar bateria</h3>                      alt="Tire Repair"

                    />

                  <p class="card-text">                  </figure>

                    Servicio que evalúa, recarga o reemplaza baterías defectuosas para garantizar el arranque y funcionamiento del vehículo

                  </p>                  <h3 class="h3 card-title">Traccionamiento</h3>



                                 <p class="card-text">

                </div>                    Sistema que asegura el buen agarre del auto al suelo en

              </li>                    distintas condiciones para evitar deslizamientos

                  </p>

              <li class="service-banner">

                <img                </div>

                  src="./public/img/services-5.png"              </li>

                  width="646"

                  height="380"              <li>

                  loading="lazy"                <div class="service-card">

                  alt="Red Car"                  <figure class="card-icon">

                  class="move-anim"                    <img

                />                      src="./public/img/services-4.png"

              </li>                      width="110"

                      height="110"

              <li>                      loading="lazy"

                <div class="service-card">                      alt="Battery Repair"

                  <figure class="card-icon">                    />

                    <img                  </figure>

                      src="./public/img/pintura.png"

                      width="110"                  <h3 class="h3 card-title">Reparar bateria</h3>

                      height="110"

                      loading="lazy"                  <p class="card-text">

                      alt="Steering Repair"                    Servicio que evalúa, recarga o reemplaza baterías defectuosas para garantizar el arranque y funcionamiento del vehículo

                    />                  </p>

                  </figure>

               

                  <h3 class="h3 card-title">Pintura</h3>                </div>

              </li>

                  <p class="card-text">

                   Proceso que restaura o renueva la apariencia del vehículo con pintura especializada y acabado profesiona              <li class="service-banner">

                  </p>                <img

                  src="./public/img/services-5.png"

                                    width="646"

                </div>                  height="380"

              </li>                  loading="lazy"

            </ul>                  alt="Red Car"

                  class="move-anim"

                           />

          </div>              </li>

        </section>

              <li>

        <!--                 <div class="service-card">

        - #SOBRE NOSOTROS                  <figure class="card-icon">

      -->                    <img

                      src="./public/img/pintura.png"

        <section class="section about has-before" aria-labelledby="about-label" id="nosotros">                      width="110"

          <div class="container">                      height="110"

            <figure class="about-banner">                      loading="lazy"

              <img                      alt="Steering Repair"

                src="./public/img/about-banner.png"                    />

                width="540"                  </figure>

                height="540"

                loading="lazy"                  <h3 class="h3 card-title">Pintura</h3>

                alt="vehicle repire equipments"

                class="w-100"                  <p class="card-text">

              />                   Proceso que restaura o renueva la apariencia del vehículo con pintura especializada y acabado profesiona

            </figure>                  </p>



            <div class="about-content">                  

              <p class="section-subtitle :dark">Sobre Nosotros</p>                </div>

              </li>

                        <h2 class="h2 section-title">            </ul>

                          Nos comprometemos a cumplir con la calidad

                        </h2>           

          </div>

                        <p class="section-text">        </section>

                          En Xtreme Performance ofrecemos servicios automotrices

                          con altos estándares de calidad, precisión y puntualidad.        <!-- 

                          Contamos con personal capacitado y tecnología moderna para        - #SOBRE NOSOTROS

                          brindar soluciones confiables a cada vehículo.      -->

                        </p>

        <section class="section about has-before" aria-labelledby="about-label" id="nosotros">

                        <p class="section-text">          <div class="container">

                          Nos especializamos en traccionamiento, planchado, mantenimiento            <figure class="about-banner">

                          y pintura, asegurando resultados duraderos y la satisfacción de              <img

                          nuestros clientes. Tu confianza es nuestra mejor garantía.                src="./public/img/about-banner.png"

                        </p>                width="540"

                height="540"

              </p>                loading="lazy"

                alt="vehicle repire equipments"

              <ul class="about-list">                class="w-100"

                <li class="about-item">              />

                  <p>            </figure>

                    <strong class="display-1 strong">1K+</strong> Clientes Felices

                  </p>            <div class="about-content">

                </li>              <p class="section-subtitle :dark">Sobre Nosotros</p>



                <li class="about-item">                        <h2 class="h2 section-title">

                  <p>                          Nos comprometemos a cumplir con la calidad

                    <strong class="display-1 strong">10+</strong> Instrumentos                        </h2>

                  </p>

                </li>                        <p class="section-text">

                          En Xtreme Performance ofrecemos servicios automotrices

                <li class="about-item">                          con altos estándares de calidad, precisión y puntualidad.

                  <p>                          Contamos con personal capacitado y tecnología moderna para

                    <strong class="display-1 strong">10+</strong> Años en el mercado                          brindar soluciones confiables a cada vehículo.

                  </p>                        </p>

                </li>

                        <p class="section-text">

                <li class="about-item">                          Nos especializamos en traccionamiento, planchado, mantenimiento

                  <p>                          y pintura, asegurando resultados duraderos y la satisfacción de

                    <strong class="display-1 strong">99%</strong> Proyectos Completados                          nuestros clientes. Tu confianza es nuestra mejor garantía.

                  </p>                        </p>

                </li>

              </ul>              </p>

            </div>

          </div>              <ul class="about-list">

        </section>                <li class="about-item">

                  <p>

        <!--                     <strong class="display-1 strong">1K+</strong> Clientes Felices

        - #WORK                  </p>

      -->                </li>



        <section class="section work" aria-labelledby="work-label" id="proyectos">                <li class="about-item">

          <div class="container">                  <p>

            <p class="section-subtitle :light" id="work-label">Nuestros Trabajos</p>                    <strong class="display-1 strong">10+</strong> Instrumentos

                  </p>

            <h2 class="h2 section-title">Ultimos Proyectos que Realizamos</h2>                </li>



            <ul class="has-scrollbar">                <li class="about-item">

              <li class="scrollbar-item">                  <p>

                <div class="work-card">                    <strong class="display-1 strong">10+</strong> Años en el mercado

                  <figure                  </p>

                    class="card-banner img-holder"                </li>

                    style="--width: 350; --height: 406"

                  >                <li class="about-item">

                    <img                  <p>

                      src="./public/img/work-1.jpg"                    <strong class="display-1 strong">99%</strong> Proyectos Completados

                      width="350"                  </p>

                      height="406"                </li>

                      loading="lazy"              </ul>

                      alt="Engine Repair"            </div>

                      class="img-cover"          </div>

                    />        </section>

                  </figure>

        <!-- 

                  <div class="card-content">        - #WORK

                    <p class="card-subtitle">Reparamiento de autos</p>      -->



                    <h3 class="h3 card-title">Reparación general</h3>        <section class="section work" aria-labelledby="work-label" id="proyectos">

          <div class="container">

                               <p class="section-subtitle :light" id="work-label">Nuestros Trabajos</p>

                  </div>

                </div>            <h2 class="h2 section-title">Ultimos Proyectos que Realizamos</h2>

              </li>

            <ul class="has-scrollbar">

              <li class="scrollbar-item">              <li class="scrollbar-item">

                <div class="work-card">                <div class="work-card">

                  <figure                  <figure

                    class="card-banner img-holder"                    class="card-banner img-holder"

                    style="--width: 350; --height: 406"                    style="--width: 350; --height: 406"

                  >                  >

                    <img                    <img

                      src="./public/img/work-2.jpg"                      src="./public/img/work-1.jpg"

                      width="350"                      width="350"

                      height="406"                      height="406"

                      loading="lazy"                      loading="lazy"

                      alt="Car Tyre change"                      alt="Engine Repair"

                      class="img-cover"                      class="img-cover"

                    />                    />

                  </figure>                  </figure>



                  <div class="card-content">                  <div class="card-content">

                    <p class="card-subtitle">Reparar autos</p>                    <p class="card-subtitle">Reparamiento de autos</p>



                    <h3 class="h3 card-title">Cambio del tirón </h3>                    <h3 class="h3 card-title">Reparación general</h3>



                                       

                  </div>                  </div>

                </div>                </div>

              </li>              </li>



              <li class="scrollbar-item">              <li class="scrollbar-item">

                <div class="work-card">                <div class="work-card">

                  <figure                  <figure

                    class="card-banner img-holder"                    class="card-banner img-holder"

                    style="--width: 350; --height: 406"                    style="--width: 350; --height: 406"

                  >                  >

                    <img                    <img

                      src="./public/img/work-3.jpg"                      src="./public/img/work-2.jpg"

                      width="350"                      width="350"

                      height="406"                      height="406"

                      loading="lazy"                      loading="lazy"

                      alt="Battery Adjust"                      alt="Car Tyre change"

                      class="img-cover"                      class="img-cover"

                    />                    />

                  </figure>                  </figure>



                  <div class="card-content">                  <div class="card-content">

                    <p class="card-subtitle">Reparar autos</p>                    <p class="card-subtitle">Reparar autos</p>



                    <h3 class="h3 card-title">Ajustar Baterías</h3>                    <h3 class="h3 card-title">Cambio del tirón </h3>



                                        

                  </div>                  </div>

                </div>                </div>

              </li>              </li>

            </ul>

          </div>              <li class="scrollbar-item">

        </section>                <div class="work-card">

      </article>                  <figure

    </main>                    class="card-banner img-holder"

                    style="--width: 350; --height: 406"

    <!--                   >

    - #FOOTER                    <img

  -->                      src="./public/img/work-3.jpg"

                      width="350"

    <footer class="footer" id="contactanos">                      height="406"

      <div class="footer-top section">                      loading="lazy"

        <div class="container">                      alt="Battery Adjust"

          <div class="footer-brand">                      class="img-cover"

            <a href="#" class="logo">                    />

              <img                  </figure>

                src="./public/img/logoWhite.png"

                width="128"                  <div class="card-content">

                height="63"                    <p class="card-subtitle">Reparar autos</p>

                alt="autofix home"

              />                    <h3 class="h3 card-title">Ajustar Baterías</h3>

            </a>

                    

            <p class="footer-text">                  </div>

              Somos un taller automotriz especializado en brindar servicios                </div>

  de calidad en traccionamiento, planchado, mantenimiento y              </li>

  pintura. Nuestro compromiso es el cuidado y rendimiento            </ul>

  óptimo de tu vehículo.          </div>

            </p>        </section>

      </article>

            <ul class="social-list">    </main>

              <li>

                <a href="https://www.facebook.com/p/Xtreme-Performance-Automotive-EIRL-100057547266000/" class="social-link" target="_blank">    <!-- 

                  <img src="./public/img/facebook.svg" alt="facebook" />    - #FOOTER

                </a>  -->

              </li>

    <footer class="footer" id="contactanos">

              <li>      <div class="footer-top section">

                <a href="#" class="social-link">        <div class="container">

                  <img src="./public/img/instagram.svg" alt="instagram" />          <div class="footer-brand">

                </a>            <a href="#" class="logo" aria-label="Xtreme Performance">

              </li>              <img

                src="./public/img/LogoWhite.png"

              <li>                width="128"

                <a href="#" class="social-link">                height="63"

                  <img src="./public/img/twitter.svg" alt="twitter" />                alt=""

                </a>              />

              </li>            </a>

            </ul>

          </div>            <p class="footer-text">

              Somos un taller automotriz especializado en brindar servicios

          <ul class="footer-list">  de calidad en traccionamiento, planchado, mantenimiento y

            <li>  pintura. Nuestro compromiso es el cuidado y rendimiento

              <p class="h3">Horario de Atención</p>  óptimo de tu vehículo.

            </li>            </p>



            <li>            <ul class="social-list">

              <p class="p">Lunes - Viernes</p>              <li>

                <a href="https://www.facebook.com/p/Xtreme-Performance-Automotive-EIRL-100057547266000/" class="social-link" target="_blank">

              <span class="span">8:00 a.m - 6:00 p.m</span>                  <img src="./public/img/facebook.svg" alt="facebook" />

            </li>                </a>

              </li>

            <li>

              <p class="p">Sábado</p>              <li>

                <a href="#" class="social-link">

              <span class="span">8:00 a.m - 2:00 p.m </span>                  <img src="./public/img/instagram.svg" alt="instagram" />

            </li>                </a>

              </li>

          

          </ul>              <li>

                <a href="#" class="social-link">

          <ul class="footer-list">                  <img src="./public/img/twitter.svg" alt="twitter" />

            <li>                </a>

              <p class="h3">Información de Contacto</p>              </li>

            </li>            </ul>

          </div>

            <li>

              <a href="tel:+01234567890" class="footer-link">          <ul class="footer-list">

                <span class="material-symbols-rounded">call</span>            <li>

              <p class="h3">Horario de Atención</p>

                <span class="span">+51 998 980 547</span>            </li>

              </a>

            </li>            <li>

              <p class="p">Lunes - Viernes</p>

            <li>

              <a href="mailto:info@autofix.com" class="footer-link">              <span class="span">8:00 a.m - 6:00 p.m</span>

                <span class="material-symbols-rounded">mail</span>            </li>



                <span class="span">xtremeperformance@gmail.com</span>            <li>

              </a>              <p class="p">Sábado</p>

            </li>

              <span class="span">8:00 a.m - 2:00 p.m </span>

            <li>            </li>

              <address class="footer-link address">

                <span class="material-symbols-rounded">location_on</span>          

          </ul>

                <span class="span"

                  >Jr Nemesio Raez 2241 El tambo, Huancayo, Peru</span          <ul class="footer-list">

                >            <li>

              </address>              <p class="h3">Información de Contacto</p>

            </li>            </li>

          </ul>

        </div>            <li>

              <a href="tel:+01234567890" class="footer-link">

        <img                <span class="material-symbols-rounded">call</span>

          src="./public/img/LogoLow.png"

          width="637"                <span class="span">+51 998 980 547</span>

          height="173"              </a>

          loading="lazy"            </li>

          alt="Shape"

          class="shape shape-3 move-anim"            <li>

        />              <a href="mailto:info@autofix.com" class="footer-link">

      </div>                <span class="material-symbols-rounded">mail</span>



      <div class="footer-bottom">                <span class="span">xtremeperformance@gmail.com</span>

        <div class="container">              </a>

          <p class="copyright">            </li>

            Copyright 2025, Todos los derechos pertenecen a Xtreme Performance.

          </p>            <li>

              <address class="footer-link address">

          <img                <span class="material-symbols-rounded">location_on</span>

            src="./public/img/footer-shape-2.png"

            width="778"                <span class="span"

            height="335"                  >Jr Nemesio Raez 2241 El tambo, Huancayo, Peru</span

            loading="lazy"                >

            alt="Shape"              </address>

            class="shape shape-2"            </li>

          />          </ul>

        </div>

          <img

            src="./public/img/footer-shape-1.png"        <img

            width="805"          src="./public/img/LogoLow.png"

            height="652"          width="637"

            loading="lazy"          height="173"

            alt="Red Car"          loading="lazy"

            class="shape shape-1 move-anim"          alt="Shape"

          />          class="shape shape-3 move-anim"

        </div>        />

      </div>      </div>

    </footer>

      <div class="footer-bottom">

    <!--         <div class="container">

    - custom js link          <p class="copyright">

  -->            Copyright 2025, Todos los derechos pertenecen a Xtreme Performance.

    <script src="./public/js/script.js"></script>          </p>

  </body>

</html>          <img
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
    <script src="./public/js/script.js?v=20251102"></script>
  </body>
</html>
