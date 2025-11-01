SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `facturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) NOT NULL,
  `idOrdenReparacion` int(11) NOT NULL,
  `manoObra` float NOT NULL,
  `materiales` float NOT NULL,
  `otro` float NOT NULL,
  `iva` float NOT NULL,
  `total` float NOT NULL,
  `observacion` varchar(500) NOT NULL,
  `baja` int(11) NOT NULL,
  `alta_dt` datetime NOT NULL,
  `baja_dt` datetime NOT NULL,
  `cambio_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO facturas VALUES
("1","1","1","6000","700","200","1104","8004","Quedo como nueva","0","2025-08-04 18:55:57","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("2","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 10:49:32","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("3","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 10:49:57","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("4","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 10:50:20","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("5","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 12:29:03","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("6","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 13:06:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("7","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 13:42:52","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("8","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 13:47:58","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("9","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 13:49:12","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("10","1","1","6000","700","200","1104","8004","Qued&oacute; muy bien","0","2025-08-05 15:20:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("11","1","1","6000","700","200","1104","8004","Quedó muy bien","0","2025-08-05 15:23:45","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("12","1","1","6000","700","200","1104","8004","Quedó muy bien","0","2025-08-05 15:24:41","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("13","1","2","6000","0","200","992","7192","Quedó hermosa","0","2025-08-10 15:41:37","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;