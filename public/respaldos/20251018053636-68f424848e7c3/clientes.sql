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
  `idVehiculo` int(11) NOT NULL,
  `id_estado_cliente` int(11) NOT NULL,
  `baja` int(11) NOT NULL,
  `login_dt` datetime NOT NULL,
  `alta_dt` datetime NOT NULL,
  `baja_dt` datetime NOT NULL,
  `cambio_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO clientes VALUES
("1","Fernando","Barrios Ipenza","CONTINENTAL UNIVERSIDAD","Jr santa Isabel 2065","9876534127","20604365731","fernandoipenza@taller.com","jgTH3*MLFK","1","1","0","2025-10-18 17:11:45","2025-10-17 23:23:32","0000-00-00 00:00:00","2025-10-18 15:30:34"),
("2","Lucciano","M&aacute;ximo","NATALI LILIANA BARZOLA OLIVARES","Jr santa Isabel 2065","982742876","10415909202","natalybarzola18@gmail.com","0wEECqmen+","0","1","1","0000-00-00 00:00:00","0000-00-00 00:00:00","2025-10-18 15:26:28","0000-00-00 00:00:00"),
("3","Gianlucca","Villar Barzola","GIANLUCCA VILLAR BARZOLA","Av. Tahuantinsuyo 234","991234564","10608444571","lucca@taller.com","J8zmMN*wHH","0","1","0","0000-00-00 00:00:00","0000-00-00 00:00:00","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;