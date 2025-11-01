SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `piezas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombrePieza` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `costo` decimal(8,2) NOT NULL,
  `baja` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO piezas VALUES
("1","Pistones","5","100.00","0"),
("2","Bielas","9","200.00","0"),
("3","Válvulas","8","300.00","0"),
("4","Embragues","10","350.00","0"),
("5","Caja de cambio","10","500.00","0"),
("6","Transmisión","10","1000.00","0"),
("7","Pastillas","11","250.00","0"),
("8","Discos","10","320.00","0"),
("9","Tambores","10","350.00","0"),
("10","Amortiguadores","10","600.00","0");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;