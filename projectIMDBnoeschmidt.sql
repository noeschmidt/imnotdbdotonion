-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 05, 2024 at 04:29 PM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectIMDBnoeschmidt`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `imdb_rating` decimal(3,1) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `actors` varchar(255) DEFAULT NULL,
  `my_rating` decimal(10,0) DEFAULT NULL,
  `my_comment` text,
  `poster_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `imdb_rating`, `year`, `actors`, `my_rating`, `my_comment`, `poster_link`) VALUES
(8, 'Oppenheimer', 'Biography of the American physicist who led the U.S. effort to develop the atomic bomb during World War II, only to find himself suspected as a security risk in the 1950s.', '8.2', 1980, 'Erica Stevens', '5', 'Oppenheimer', 'https://m.media-amazon.com/images/M/MV5BNDJmYTEyODAtMDgwMS00ZDU4LWEwYTYtOTQxYmQ2NTE5ZGY5XkEyXkFqcGdeQXVyNTEwNzU0NzY@._V1_QL75_UY281_CR6,0,190,281_.jpg'),
(14, 'Her', 'In a near future, a lonely writer develops an unlikely relationship with an operating system designed to meet his every need.', '8.0', 2013, 'Guy Lewis', '7', 'Very slow but ok tier', 'https://m.media-amazon.com/images/M/MV5BMjA1Nzk0OTM2OF5BMl5BanBnXkFtZTgwNjU2NjEwMDE@._V1_QL75_UX190_CR0,0,190,281_.jpg'),
(15, 'Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', '8.3', 2023, 'Steven Houska', '9', 'Il est kooooollll', 'https://m.media-amazon.com/images/M/MV5BMDBmYTZjNjUtN2M1MS00MTQ2LTk2ODgtNzc2M2QyZGE5NTVjXkEyXkFqcGdeQXVyNzAwMjU2MTY@._V1_QL75_UX190_CR0,0,190,281_.jpg'),
(16, 'Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', '8.3', 2023, 'Steven Houska', '9', 'Oui', 'https://m.media-amazon.com/images/M/MV5BMDBmYTZjNjUtN2M1MS00MTQ2LTk2ODgtNzc2M2QyZGE5NTVjXkEyXkFqcGdeQXVyNzAwMjU2MTY@._V1_QL75_UX190_CR0,0,190,281_.jpg'),
(17, 'La soupe aux choux', '2 buddy farmers are visited by aliens who like their domestic cabbage soup.', '6.5', 1981, 'Inge Offerman', '2', 'fafw', 'https://m.media-amazon.com/images/M/MV5BNDFjMjgxYTQtYjYwYS00MmE2LTg2YWEtMWM3NWE2OTA0NGIyXkEyXkFqcGdeQXVyNjMxNDE2ODU@._V1_QL75_UY281_CR8,0,190,281_.jpg'),
(18, 'Monkey Man', 'An anonymous young man unleashes a campaign of vengeance against the corrupt leaders who murdered his mother and continue to systematically victimize the poor and powerless.', '7.0', 2024, 'Alan Jiraiya', '5', 'Mid', 'https://m.media-amazon.com/images/M/MV5BNzZlODVjMzgtZGM1Yi00MWMwLTkyYTQtMzJlZjQ4MDgwYzg4XkEyXkFqcGdeQXVyMTUzMTg2ODkz._V1_QL75_UX190_CR0,10,190,281_.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
