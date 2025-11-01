SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `mecanicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `clave` varchar(500) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `idTipoMecanico` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `baja` int(11) NOT NULL,
  `login_dt` datetime NOT NULL,
  `alta_dt` datetime NOT NULL,
  `baja_dt` datetime NOT NULL,
  `cambio_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO mecanicos VALUES
("1","Bruno Arturo","Diaz","bruno.diaz@taller.com","wr5Vawz2Vf","9876534123","1","1","0","0000-00-00 00:00:00","2025-10-17 22:26:43","0000-00-00 00:00:00","2025-10-18 13:24:42"),
("2","Ricardo","Tapia","ricardo.tapia@taller.com","b4gSg7rrC3","982742871","2","2","1","0000-00-00 00:00:00","2025-10-17 22:31:20","2025-10-17 22:38:08","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;