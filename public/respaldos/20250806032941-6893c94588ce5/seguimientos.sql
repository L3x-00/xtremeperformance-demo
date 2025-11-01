SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `seguimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idOrdenReparacion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `observacion` varchar(500) NOT NULL,
  `baja` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO seguimientos VALUES
("1","1","2025-08-01","A&uacute;n no llegan las piezas","1"),
("2","1","2025-08-01","A&uacute;n no llegan las piezas, falta la defensa.","1"),
("3","1","2025-08-02","Hoy lleg&oacute; la defensa","0");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;