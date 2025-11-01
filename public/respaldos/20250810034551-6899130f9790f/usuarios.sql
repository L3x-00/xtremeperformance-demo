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
("1","1","Francisco Javier","Arce Anguiano","Conocida","5555555555","admin@taller.com","e2e09a9a2d3ce79d4a82272d546b00d9c1e9dcb7163034f4f48893e43ea1c3b1e3aeda74d1581dceaa20d4c46f2f003b1a32b716fb4af18ded2121525e958e2b","1","1","0","2025-08-01 08:32:32","2025-07-20 21:44:45","2025-07-20 21:44:45","2025-07-20 21:44:45"),
("2","2","Pedro","Picapiedra","Conocida","55666666","pedro@taller.com","8d6cdb0dd514b183176804fef24da83ea8c5062f182faadedf53a23297c4584fd31aa7b649335da05f62bbccc43051ed93c591e50873c3161bfe18a6f6de5c73","1","1","0","0000-00-00 00:00:00","2025-07-21 11:16:41","2025-07-21 16:07:01","2025-07-21 14:35:53"),
("3","2","Pablo","M&aacute;rmol","Conocida","55777777","pablo@taller.com","942989fda8e56aec550342ab37cef7cf6d6c77306ba3a75e0fcb2effc79183a5cb737af32c2c1da15fa743f84cd6d7065d7dcb4d477aa075866834543c4375d4","1","1","0","0000-00-00 00:00:00","2025-07-21 19:11:04","2025-07-22 17:16:56","0000-00-00 00:00:00"),
("4","2","Juan","Camaney Gonz&aacute;lez","Conocida","55888888","juan@taller.com","768e18800b141eb11422752dda2fb15f19b2e9a1839b0395f5cd5e9fcf70ccd5df4344d642b475ffd6663653990de45ac1afa07a60017d47e91bf6faf88982ad","1","1","0","0000-00-00 00:00:00","2025-07-22 13:28:19","0000-00-00 00:00:00","2025-07-22 17:20:01"),
("5","2","Juan","Mec&aacute;nico","Conocida","559999999","juan.mecanico@taller.com","VMoFdhSMUj","1","2","0","0000-00-00 00:00:00","2025-07-22 13:29:53","0000-00-00 00:00:00","0000-00-00 00:00:00");

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;