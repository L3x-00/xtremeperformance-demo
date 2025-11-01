SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `razonSocial` varchar(500) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `ruc` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `clave` varchar(500) NOT NULL,
  `estado` int(11) NOT NULL,
  `baja` int(11) NOT NULL,
  `login_dt` datetime NOT NULL,
  `alta_dt` datetime NOT NULL,
  `baja_dt` datetime NOT NULL,
  `cambio_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO clientes VALUES
("1","Clark","Kent","Per&oacute;dico El Planeta","Conocida","66888888","SUPER-MAN","clark.kent@elplaneta.com","nQzpJ438xF","1","0","0000-00-00 00:00:00","2025-07-24 13:41:12","2025-07-24 16:51:39","2025-07-24 15:41:24");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;