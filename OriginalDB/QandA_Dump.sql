-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 07, 2022 at 09:47 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `QandA`
--

-- --------------------------------------------------------

--
-- Table structure for table `Answer`
--

CREATE TABLE `Answer` (
  `aid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `uid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `qid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `abody` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `atime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Answer`
--

INSERT INTO `Answer` (`aid`, `uid`, `qid`, `abody`, `atime`, `likes`) VALUES
('A01', 'U04', 'Q01', 'To answer it, we first must ask ourselves, what is physics?Physics comes from the ancient Greek word physika.Physika means the science of natural things. And it is there, in ancient Greece, that our story begins...', '2009-03-22 15:44:22', 2333),
('A02', 'U03', 'Q01', 'According to wikipedia, sub-atomic particles are particles that compose atoms', '2009-03-13 18:21:22', 233),
('A03', 'U02', 'Q02', 'If you are ok with just being a low-paid programmer and eventually exiting your profession to do something else, then do not learn algorithms and data structures. If computer science really well and truly excites you, then you know what to do.', '2014-01-13 19:33:17', 622),
('A04', 'U03', 'Q02', 'The way I see it, it is not algorithms that I need in everyday life, but the ability to quickly analyze the problem and find a solution.', '2013-11-13 19:33:17', 388),
('A05', 'U04', 'Q03', 'Although this is not quite my field, but l think computer scientist are alike physicsians in that...', '2014-11-13 19:33:17', 666),
('A06', 'U02', 'Q03', ' It’s hard to measure exactly what makes a good software developer. But what we can do is explore common characteristics and traits..', '2014-12-07 14:18:54', 238);

-- --------------------------------------------------------

--
-- Table structure for table `Question`
--

CREATE TABLE `Question` (
  `qid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `uid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `tid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `qbody` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `followcount` int(11) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  `bestAid` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Question`
--

INSERT INTO `Question` (`qid`, `uid`, `tid`, `title`, `qbody`, `qtime`, `followcount`, `resolved`, `bestAid`) VALUES
('Q01', 'U01', 'T21', 'What exactly are sub-atomic particles?', NULL, '2009-03-11 13:22:33', 233, 1, 'A01'),
('Q02', 'U05', 'T11', 'If advanced algorithms and data structures are never used in industry, then why learn them?', 'From my experience, advanced... ', '2013-09-21 21:22:33', 566, 1, 'A03'),
('Q03', 'U05', 'T12', 'What are the qualities of a good software developer?', 'As a CS student, I have always wanted..', '2014-09-22 01:22:33', 333, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Topic`
--

CREATE TABLE `Topic` (
  `tid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `higher_level_tid` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Topic`
--

INSERT INTO `Topic` (`tid`, `title`, `higher_level_tid`) VALUES
('T1', 'Computer Science', NULL),
('T11', 'Data Structure', 'T1'),
('T12', 'Software Development', 'T1'),
('T2', 'Physics', NULL),
('T21', 'Theoretical Physics', 'T2');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `uid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(124) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `profile` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `points` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`uid`, `username`, `password`, `profile`, `points`, `email`, `city`, `state`, `country`) VALUES
('U01', 'elavizadeh', 'adfgj2352', 'Student of WSE University', 20, 'dgdsgds@wse.edu', 'Manchester', 'New Hampshire', 'USA'),
('U02', 'tusizi', 'dsijef00', 'Experienced programmer at FAANG', 1500, 'tusizitighe@faang.com', 'Montain View', 'California', 'USA'),
('U03', 'csy1000', '238usefd', 'Dominating the world', 898, 'sefq3344@dominator.com', NULL, NULL, 'Iceland'),
('U04', 'sheldor', 'moonpie226', 'B.S, M.S, M.A, Ph.D, Sc.D, and an I.Q. of 187', 5566, 's.cooperphd@yahoo.com', 'Pasadena', 'California', 'USA'),
('U05', 'afzal273', '123efsdhg', 'HS student', 33, 'afe22ef@someHS.edu', 'Brooklyn', 'New York', 'USA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answer`
--
ALTER TABLE `Answer`
  ADD PRIMARY KEY (`aid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `Question`
--
ALTER TABLE `Question`
  ADD PRIMARY KEY (`qid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `tid` (`tid`);

--
-- Indexes for table `Topic`
--
ALTER TABLE `Topic`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`uid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answer`
--
ALTER TABLE `Answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `User` (`uid`),
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`qid`) REFERENCES `Question` (`qid`);

--
-- Constraints for table `Question`
--
ALTER TABLE `Question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `User` (`uid`),
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `Topic` (`tid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
