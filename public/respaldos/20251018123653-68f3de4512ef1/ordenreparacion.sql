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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;