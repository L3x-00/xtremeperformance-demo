<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="../../public/css/styles.css" />
    <title>Inicio - Xtreme Performance</title>
  </head>
  <body>
    <header class="header">
      <nav>
        <div class="nav__bar">
          <div class="logo nav__logo">
            <a href="index.html"><img src="assets/logo.png" alt="logo" /></a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-3-line"></i>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home">INICIO</a></li>
          <li><a href="#about">SOBRE NOSOTROS</a></li>
          <li><a href="#service">SERVICIOS</a></li>
          <li><a href="#client">NUESTROS CLIENTES</a></li>
          
          <button class="btn"><a href="<?php print RUTA; ?>login">
  <span class="span">INICIAR SESIÓN</span>
  </a></button>
        </ul>
      </nav>
      <div class="section__container header__container" id="home">
        <div class="header__content">
          <h1>Somos líderes en la región Junín</h1>
          <div class="header__btn">
            <button class="btn"><a href="#about">Leer más</a></button>
          </div>
        </div>
      </div>
    </header>

    <section class="banner__container">
      <div class="banner__card">
        <h4>Satisfacción garantizada siempre encontrará.</h4>
      </div>
      <div class="banner__card">
        <h4>Cuidaremos su coche como lo haría usted.</h4>
      </div>
      <div class="banner__image">
        <img src="assets/banner.jpg" alt="banner" />
      </div>
    </section>

    <section class="section__container experience__container" id="about">
      <div class="experience__image">
        <img src="assets/experience.jpg" alt="experience" />
      </div>
      <div class="experience__content">
        <p class="section__subheader">¿QUIENES SOMOS?</p>
        <h2 class="section__header">
          Nosotros tenemos 10 años de experiencia en este campo
        </h2>
        <p class="section__description">
          Con un rico legado de 10 años, nuestro compromiso con la excelencia en
          el mantenimiento de automóviles es inquebrantable. Nuestro
          experimentado equipo aporta una gran experiencia para garantizar que
          su vehículo reciba un cuidado de primera categoría. Confíe en nuestra
          experiencia para que su coche funcione sin problemas y de forma
          segura.
        </p>
      </div>
    </section>

    <section class="service" id="service">
      <div class="section__container service__container">
        <p class="section__subheader">¿POR QUÉ ELEGIRNOS?</p>
        <h2 class="section__header">Grandiosos Servicios Automotrices</h2>
        <p class="section__description">
          Confíe en nosotros para mantener su automóvil funcionando sin
          problemas y de forma fiable.
        </p>
        <div class="service__grid">
          <div class="service__card">
            <img src="assets/service-1.jpg" alt="service" />
            <h4>Planchado</h4>
            <p>
              Servicio de planchado especializado para componentes automotrices,
              garantizando una apariencia impecable y profesional
            </p>
          </div>
          <div class="service__card">
            <img src="assets/service-2.jpg" alt="service" />
            <h4>Pintura</h4>
            <p>
              Servicio de pintura automotriz de alta calidad, ofreciendo
              acabados duraderos y profesionales para mejorar la estética y
              protección de tu vehículo.
            </p>
          </div>
          <div class="service__card">
            <img src="assets/service-3.jpg" alt="service" />
            <h4>Traccionamiento</h4>
            <p>
              Servicio de traccionamiento especializado para mejorar el
              rendimiento y la estabilidad de tu vehículo, asegurando una
              conducción más eficiente y segura
            </p>
          </div>
          <div class="service__card">
            <img src="assets/service-4.jpg" alt="service" />
            <h4>Mantenimiento</h4>
            <p>
              Servicio de mantenimiento integral para vehículos, garantizando su
              óptimo funcionamiento y prolongando su vida útil con revisiones y
              ajustes especializados.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="customisation">
      <div class="section__container customisation__container">
        <p class="section__subheader">NUESTRO EQUIPO</p>
        <h2 class="section__header">
          El servicio de coches se combina con una gran mano de obra
        </h2>
        <p class="section__description">
          Nuestro equipo de técnicos y mecánicos especializados se enorgullece
          de ofrecer el mejor servicio para su querido vehículo.
        </p>
        <div class="customisation__grid">
          <div class="customisation__card">
            <h4>+10</h4>
            <p>Proyectos Totales</p>
          </div>
          <div class="customisation__card">
            <h4>+10</h4>
            <p>Convenios</p>
          </div>
          <div class="customisation__card">
            <h4>+500</h4>
            <p>Proyectos Realizados</p>
          </div>
          <div class="customisation__card">
            <h4>+500</h4>
            <p>Clientes Satisfechos</p>
          </div>
        </div>
      </div>
    </section>

    <section class="contact">
      <div class="section__container contact__container">
        <div class="contact__content">
          <p class="section__subheader">CONTÁCTANOS</p>
          <h2 class="section__header">
            Imagine que su coche vuelve a ser nuevo
          </h2>
          <p class="section__description">
            Experimente la magia de una conducción rejuvenecida mientras mimamos
            su coche con un cuidado de precisión, dejándolo como nuevo.
          </p>
          <div class="contact__btns">
            <button class="btn">
              <a href="#service">Nuestros Servicios</a>
            </button>
            <button class="btn">
              <a
                href="https://www.facebook.com/profile.php?id=100057547266000"
                target="_blank"
                style="color: red"
                ><b>Contáctanos</b></a
              >
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="section__container testimonial__container" id="client">
      <p class="section__subheader">TESTIMONIOS DE CLIENTES</p>
      <h2 class="section__header">100% Aprobados Por Los Clientes</h2>

      <div class="swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="testimonial__card">
              <img src="assets/testimonial-1.jpg" alt="testimonial" />
              <p>
                No podía creer lo que veían mis ojos cuando me devolvieron el
                coche de la servicio. Se veía y conducía como si acabara de
                salir de la línea de montaje. El equipo hizo un trabajo
                increíble, y yo soy un ¡cliente de por vida!
              </p>
              <h4>- Verónica Quispe</h4>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="testimonial__card">
              <img src="assets/testimonial-2.jpg" alt="testimonial" />
              <p>
                He estado trayendo mi coche aquí durante años, y nunca
                decepcionan. Su atención al detalle y el compromiso con la
                calidad calidad es incomparable. Mi coche siempre se siente como
                nuevo visita.
              </p>
              <h4>- Jonh Asparrín</h4>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="testimonial__card">
              <img src="assets/testimonial-3.jpg" alt="testimonial" />
              <p>
                Como entusiasta de los coches, soy muy exigente con quien toca
                mi preciada posesión. La experiencia de su equipo y su pasión
                por de su equipo por los coches. Mi coche nunca ha mejor.
              </p>
              <h4>- Micaelo Mamani</h4>
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <footer class="footer">
      <div class="section__container footer__container">
        <div class="footer__col">
          <div class="logo footer__logo">
            <a href="#"><img src="assets/logo.png" alt="logo" /></a>
          </div>
          <p class="section__description">
            Con un rico legado de 10 años, nuestro compromiso con la excelencia
            en el mantenimiento de automóviles es inquebrantable.
          </p>
          <ul class="footer__socials">
            <li>
              <a
                href="https://www.facebook.com/profile.php?id=100057547266000"
                target="_blank"
                ><i class="ri-facebook-fill"></i
              ></a>
            </li>
            <li>
              <a href="#"><i class="ri-google-fill"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-instagram-line"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-youtube-line"></i></a>
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Nuestros Servicios</h4>
          <ul class="footer__links">
            <li><a href="#">Pintura</a></li>
            <li><a href="#">Planchado</a></li>
            <li><a href="#">Traccionamiento</a></li>
            <li><a href="#">Mantenimiento</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Información de Contacto</h4>
          <ul class="footer__links">
            <li>
              <p>
                Experimente la magia de una conducción rejuvenecida mientras
                mimamos su coche con un cuidado de precisión
              </p>
            </li>
            <li>
              <p>Teléfono <span>+51 991 550 564</span></p>
            </li>
            <li>
              <p>Email: <span>info@xtremeperformance.com</span></p>
            </li>
          </ul>
        </div>
      </div>
    </footer>
    <div class="footer__bar">
      Copyright © 2025 Xtreme Performance. Todos los derechos son reservados.
    </div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="main.js"></script>
  </body>
</html>
