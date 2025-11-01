SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- Database: `taller`
--



CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoUsuario` int(11) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `clave` varchar(500) NOT NULL,
  `genero` int(11) NOT NULL,
  `estadoUsuario` int(11) NOT NULL,
  `baja` tinyint(4) NOT NULL,
  `login_dt` datetime DEFAULT NULL,
  `alta_dt` datetime NOT NULL,
  `baja_dt` datetime NOT NULL,
  `cambio_dt` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO usuarios VALUES
("1","1","Francisco","Arcee","Av. Progreso","991332123","martha777xd@gmail.com","e2e09a9a2d3ce79d4a82272d546b00d9c1e9dcb7163034f4f48893e43ea1c3b1e3aeda74d1581dceaa20d4c46f2f003b1a32b716fb4af18ded2121525e958e2b","1","1","0","2025-10-18 18:17:10","2025-10-17 05:35:58","2025-10-17 05:35:58","2025-10-18 15:23:54"),
("2","2","Pedro Francisco","Suarez","Av. Tahuantinsuyo 123","982742875","pedro@gmail.com","8d6cdb0dd514b183176804fef24da83ea8c5062f182faadedf53a23297c4584fd31aa7b649335da05f62bbccc43051ed93c591e50873c3161bfe18a6f6de5c73","1","1","0","2025-10-18 17:24:48","2025-10-17 20:20:24","0000-00-00 00:00:00","2025-10-18 13:28:17"),
("3","2","Pablo","Tarso","Av. Los Heroes 31","923233232","pablo@gmail.com","942989fda8e56aec550342ab37cef7cf6d6c77306ba3a75e0fcb2effc79183a5cb737af32c2c1da15fa743f84cd6d7065d7dcb4d477aa075866834543c4375d4","1","1","0","0000-00-00 00:00:00","2025-10-17 21:25:43","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("4","2","Tula","Rodriguez","Av. Las Colmenas","911233455","tula@taller.com","ce9cd818c8ac9b33edf20d59f868099759b877dbb46149a2520574a63ca9922c1e9580a975764b1efcac353814f70df159bebebdc2e61e29a2773be6254932b2","2","1","0","0000-00-00 00:00:00","2025-10-17 21:44:49","0000-00-00 00:00:00","0000-00-00 00:00:00"),
("5","2","Juan","Mecanico","Av. Tahuantinsuyo 124","982742877","juan.mecanico@taller.com","QpIcF8_SpK","1","2","0","0000-00-00 00:00:00","2025-10-17 21:56:39","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;