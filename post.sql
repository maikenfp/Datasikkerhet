CREATE TABLE `emne` (
  `emne_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `emnenavn` varchar(40) NOT NULL,
  `emnekode` int(11) NOT NULL,
  `pinkode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `foreleser` (
  `forelser_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `navn` varchar(30) NOT NULL,
  `passord` varchar(30) NOT NULL,
  `epost` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `foreleser_emne` (
  `forelser_id` int(11) NOT NULL,
  `emne_id` int(11) NOT NULL,
  FOREIGN KEY (forelser_id) REFERENCES foreleser(forelser_id),
  FOREIGN KEY (emne_id) REFERENCES emne(emne_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `passord` varchar(30) NOT NULL,
  `studieretning` varchar(30) NOT NULL,
  `studiekull` int(11) NOT NULL,
  `epost` varchar(30) NOT NULL,
  `navn` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `student_emne` (
  `student_id` int(11) NOT NULL,
  `emne_id` int(11) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES student(student_id),
    FOREIGN KEY (emne_id) REFERENCES emne(emne_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `melding` (
  `melding_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `dato` date NOT NULL,
  `tid` time NOT NULL,
  `spørsmål` varchar(1000) NOT NULL,
  `svar` varchar(1000) NOT NULL,
  `student_id` int(11) NOT NULL,
  `forelser_id` int(11) NOT NULL,
  `emne_id` int(11) NOT NULL,
  FOREIGN KEY (forelser_id) REFERENCES foreleser(forelser_id),
  FOREIGN KEY (student_id) REFERENCES student(student_id),
  FOREIGN KEY (emne_id) REFERENCES emne(emne_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `kommentar` (
  `kommentar_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `kommentar` varchar(1000) NOT NULL,
  `melding_id` int(11) NOT NULL,
  FOREIGN KEY (melding_id) REFERENCES melding(melding_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;