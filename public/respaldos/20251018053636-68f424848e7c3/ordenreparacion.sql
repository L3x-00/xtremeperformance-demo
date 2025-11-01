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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO ordenreparacion VALUES
("1","1","1","2025-10-19","2025-10-20","60000","1","0","1","0","1","1","1","1","0","0","0","0","2","0","2025-10-18 17:11:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("2","1","1","2025-10-20","2025-10-24","60000","1","0","0","0","1","0","0","0","0","0","0","0","2","0","2025-10-18 18:28:49","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("3","1","1","2025-10-19","2025-10-21","30000","1","0","0","0","1","0","0","0","1","0","0","0","2","0","2025-10-18 18:32:43","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("4","1","1","2025-10-19","2025-10-22","61000","1","0","0","0","1","0","0","0","0","0","0","0","2","0","2025-10-18 18:34:07","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;