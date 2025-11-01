SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `ordenreparacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVehiculo` int(11) NOT NULL,
  `idMecanico` int(11) NOT NULL,
  `fechaIngreso` date NOT NULL,
  `fechaSalida` date NOT NULL,
  `kilometraje` int(11) NOT NULL,
  `gato` tinyint(1) NOT NULL,
  `herramientas` tinyint(1) NOT NULL,
  `triangulos` tinyint(1) NOT NULL,
  `refaccion` tinyint(1) NOT NULL,
  `extintor` tinyint(1) NOT NULL,
  `antena` tinyint(1) NOT NULL,
  `emblemas` tinyint(1) NOT NULL,
  `tapones` tinyint(1) NOT NULL,
  `cables` tinyint(1) NOT NULL,
  `estereo` tinyint(1) NOT NULL,
  `encendedor` tinyint(1) NOT NULL,
  `tapetes` tinyint(1) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `baja` int(11) NOT NULL,
  `alta_dt` datetime NOT NULL,
  `baja_dt` datetime NOT NULL,
  `cambio_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO ordenreparacion VALUES
("1","1","1","2025-07-26","2025-07-31","600000","1","1","1","1","1","1","1","0","1","1","0","0","2","0","2025-07-26 20:40:18","2025-07-27 13:38:38","2025-07-27 11:34:23"),
("2","1","2","2025-08-11","2025-08-18","600000","1","1","1","0","1","1","1","1","0","1","0","1","2","0","2025-08-10 14:15:13","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;