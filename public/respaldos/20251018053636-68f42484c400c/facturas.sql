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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO facturas VALUES
("1","1","1","300","47","100","71.52","518.52","Quedó perfecta","0","2025-10-18 18:20:55","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("2","1","1","300","47","100","71.52","518.52","Quedó perfecta","0","2025-10-18 18:23:46","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("3","1","1","300","47","100","71.52","518.52","Quedó perfecta","0","2025-10-18 18:26:43","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("4","1","1","300","47","100","71.52","518.52","Quedó perfecta","0","2025-10-18 18:26:44","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("5","1","1","300","47","100","71.52","518.52","Quedó perfecta","0","2025-10-18 18:26:47","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("6","1","2","300","0","100","64","464","linda","0","2025-10-18 18:29:10","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("7","1","3","300","0","123","67.68","490.68","FACHERA","0","2025-10-18 18:32:59","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("8","1","4","300","0","121","67.36","488.36","facha","0","2025-10-18 18:34:23","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;