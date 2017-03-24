# Oblig1
Oblig 1 2. semester
Knut M. Riise
Martin H. Schei

PHP/mySQL
CSS/HTML
Javascript


CREATE TABLE `student` (
  `brukernavn` char(2) COLLATE utf8_swedish_ci NOT NULL,
  `fornavn` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `etternavn` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `klassekode` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`brukernavn`),
  KEY `klassekode` (`klassekode`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`klassekode`) REFERENCES `klasse` (`klassekode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

CREATE TABLE `klasse` (
  `klassekode` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `klassenavn` varchar(60) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`klassekode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
