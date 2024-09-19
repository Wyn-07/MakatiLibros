-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2024 at 03:25 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `librodb`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `author_id` int(11) NOT NULL,
  `author` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`author_id`, `author`) VALUES
(1, 'L. Malabed'),
(2, 'T. Kuroyanagi'),
(3, 'Time life student lib.'),
(4, 'A. Aragon'),
(5, 'R. Rogacion'),
(6, 'I. Carino'),
(7, 'J. Loves'),
(8, 'E. Ortega'),
(9, 'Unknown Author'),
(10, 'F. Lynch'),
(11, 'R. Regacion'),
(12, 'Illus. of Science & Nature'),
(13, 'A.Hufana Heil'),
(14, 'W.Scott'),
(15, 'J.leveriza'),
(16, 'T. Maceda'),
(17, 'Cruz,Patricia Melendrez'),
(18, 'J. Zimmerman'),
(19, 'Nakpil,Carmen Guerrero'),
(20, 'Patanne,E.P.'),
(21, 'M.Sison-Duque'),
(22, 'Salazar,Z.A.'),
(23, 'Cruz G.'),
(24, 'Joaquin,Nick'),
(25, 'Carino,Isidro D.'),
(26, 'T.Meng'),
(27, 'Osorio,Susan Ople'),
(28, 'Johnson,Marilyn'),
(29, 'Rizal,Jose P.'),
(30, 'Alma Rio'),
(31, 'Sevilla,Fred'),
(32, 'Alvarez,Santiago V.'),
(33, 'Schumacher,John N.'),
(34, 'Casper,Linda Ty'),
(35, 'Retana,Wenceslao E.'),
(36, 'Owen,Norma G.'),
(37, 'Crisostomo,Isabelo'),
(38, 'Kintanar,Thelma B.'),
(39, 'Hearn,Alan P.'),
(40, 'John Wiley & Sons'),
(41, 'P. Vidal'),
(42, 'Ellis, Audrey'),
(43, 'Aurora Publication'),
(44, 'Clifford, Martin'),
(45, 'Norman, Arthur'),
(46, 'Engliish, Leo'),
(47, 'Kemp, Harren'),
(48, 'Slavin, Robert E.'),
(49, 'Huizer, Gerrit'),
(50, 'Wehrle, Paul F.'),
(51, 'Sanders, Donald'),
(52, 'Teasdale, George'),
(53, 'Dela Funtecha, Myrna'),
(54, 'Serdef'),
(55, 'Cassens, Brett J.'),
(56, 'White, Ellen G.'),
(57, 'Zimmerman, Jovita R.'),
(58, 'Kitey,Henry'),
(59, 'Time-Life Books'),
(60, 'Reader\'s Digest'),
(61, 'Kahn, John Ellison'),
(62, 'Johnson, Marilyn'),
(63, 'Harnella, Raymond'),
(64, 'Lambing, Peggy'),
(65, 'Chalkley, Alan B.'),
(66, 'Field, Edwin'),
(67, 'Gillespie, James R.'),
(68, 'Philips, Bill'),
(69, 'United Nations Pub.'),
(70, 'Romero, Flerida Ruth P.'),
(71, 'Santos, Antonio M.'),
(72, 'Mendoza, Vicente V.'),
(73, 'Tolentino amado S. , Jr.'),
(74, 'Bacungan, Froilan M.'),
(75, 'Arcangel, Jose R.'),
(76, 'Baviera, Araceli'),
(77, 'Rivera, Juan F.'),
(78, 'Blanco, Ambrosio R. ed.'),
(79, 'Santiago, Esteya M.'),
(80, 'Chico, Leon V. ed.'),
(81, 'Mendieta, Mariano'),
(82, 'Cabildo, Jorge L.ed.'),
(83, 'Campos, Maria Clara L.'),
(84, 'Philippines'),
(85, 'Dela Gironeire, Paul'),
(86, 'Vallerano, Antonio'),
(87, 'Rizal, Alonzo Y.'),
(88, 'Villaroel, Hector'),
(89, 'Marcos, Ferdinand E.'),
(90, 'Ferdinand, Marcos'),
(91, 'Wood, R.P.'),
(92, 'Ylanan, Regino R.'),
(93, 'MEC'),
(94, 'Romulo, Carlos P.'),
(95, 'DECS'),
(96, 'MECS'),
(97, 'Teacher\'s Guide'),
(98, 'Ministry Education Culture'),
(99, 'De Vega, Guillermo'),
(100, 'Hufana, A.G'),
(101, 'Quirino, Carlos'),
(102, 'Castro, Joula'),
(103, 'Gianakos, Perry');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `acc_number` varchar(255) DEFAULT NULL,
  `class_number` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `copyright` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `acc_number`, `class_number`, `title`, `author_id`, `copyright`, `category_id`, `image`) VALUES
(1, '35603 plfd', '31.02', '100 Questions Filipino Kids Ask', 1, 2005, 1, 'book_sample.png'),
(2, '00891 plfd', '808.8', 'Totto Chan : Ang batang babae sa bintana', 2, 1981, 1, 'book_sample.png'),
(3, '755489 pl', '28.5', 'Ancient Civilization', 3, 2002, 1, 'book_sample.png'),
(4, '755490 pl', '28.5', 'Human Body', 3, 2002, 1, 'book_sample.png'),
(5, '755491 pl', '28.6', 'Mammals', 3, 2002, 1, 'book_sample.png'),
(6, '755492 pl', '28.7', 'Ocean Life', 3, 2002, 1, 'book_sample.png'),
(7, '755493 pl', '28.8', 'Our Environment', 3, 2002, 1, 'book_sample.png'),
(8, '755494 pl', '28.8', 'Physical Science', 3, 2002, 1, 'book_sample.png'),
(9, '755495 pl', '28.8', 'Plan Earth', 3, 2002, 1, 'book_sample.png'),
(10, '755496 pl', '28.8', 'World History', 3, 2002, 1, 'book_sample.png'),
(11, '755497 pl', '28.8', 'The Universe', 3, 2002, 1, 'book_sample.png'),
(12, '755498 pl', '28.8', 'World Geography', 3, 2002, 1, 'book_sample.png'),
(13, '469435 pl', '398.2', 'Mga Alamat at Iba Pang Mga Kwento', 4, 1986, 1, 'book_sample.png'),
(14, '470105 pl', '372.41', 'Mga Dakilang Pilipino sa Iba\'t Ibang Larangan', 4, 1987, 1, 'book_sample.png'),
(15, '495806 pl', '920.0599', 'Great Kids: A Retelling of Stories About Fil.', 5, 1993, 1, 'book_sample.png'),
(16, '542745 pl', '303.3', 'Reviving & Developing Desirable Values In..', 6, 1995, 1, 'book_sample.png'),
(17, '576345 pl', '808.83', 'A Treasury of stories for happy and?', 6, 1966, 1, 'book_sample.png'),
(18, '584195 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.1', 7, 1995, 1, 'book_sample.png'),
(19, '584196 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.2', 7, 1995, 1, 'book_sample.png'),
(20, '584197 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.3', 7, 1995, 1, 'book_sample.png'),
(21, '584198 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.4', 7, 1995, 1, 'book_sample.png'),
(22, '584199 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.5', 7, 1995, 1, 'book_sample.png'),
(23, '584200 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.6', 7, 1995, 1, 'book_sample.png'),
(24, '584201 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.7', 7, 1995, 1, 'book_sample.png'),
(25, '584202 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.8', 7, 1995, 1, 'book_sample.png'),
(26, '584203 pl', '32.02', 'The Macmillan Illus. Encyclopedia v.9', 7, 1995, 1, 'book_sample.png'),
(27, '607030 pl', '611.314', 'The changes in my mouth', 8, 1996, 1, 'book_sample.png'),
(28, '612780 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 1', 9, 1996, 1, 'book_sample.png'),
(29, '612781 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 2', 9, 1996, 1, 'book_sample.png'),
(30, '612782 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 3', 9, 1996, 1, 'book_sample.png'),
(31, '612783 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 4', 9, 1996, 1, 'book_sample.png'),
(32, '612784 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 5', 9, 1996, 1, 'book_sample.png'),
(33, '612785 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 6', 9, 1996, 1, 'book_sample.png'),
(34, '612786 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 7', 9, 1996, 1, 'book_sample.png'),
(35, '612787 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 8', 9, 1996, 1, 'book_sample.png'),
(36, '612788 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 9', 9, 1996, 1, 'book_sample.png'),
(37, '612789 pl', '30', 'The 21st Century Junior World Encyclopedia. V. 10', 9, 1996, 1, 'book_sample.png'),
(38, '614808 pl', '808.8037', 'The Aswang Inquiry', 10, 1998, 1, 'book_sample.png'),
(39, '637395 pl', '899.21', 'Great kid\'s Retelling of stories about fil.', 11, 1993, 1, 'book_sample.png'),
(40, '640181 pl', '500', 'Animal Behavior', 12, 1994, 1, 'book_sample.png'),
(41, '310520 e', '808.813', 'Sieg Heil', 13, 1975, 2, 'book_sample.png'),
(42, '418303 e', '959.9', 'Prehispanic Source materials', 14, 1984, 2, 'book_sample.png'),
(43, '423482 e', '350.1', 'Personnel Administration in the gov\'t.', 15, 1980, 2, 'book_sample.png'),
(44, '426349 e', '899.212301', 'Marcel M. Navarra', 16, 1986, 2, 'book_sample.png'),
(45, '499655 pl', '899.211', 'P.M.C.: filipinong pananaw sa wika?', 17, 1994, 2, 'book_sample.png'),
(46, '518279 pl', '899.103', 'Before the Night Descends', 18, 1994, 2, 'book_sample.png'),
(47, '557837 pl', '959.9', 'History today', 19, 1996, 2, 'book_sample.png'),
(48, '565108 pl', '959.9', 'The Phils. In the 6th to 16th centuries', 20, 1996, 2, 'book_sample.png'),
(49, '572837 pl', '959.9', 'In the Beginning', 21, 1994, 2, 'book_sample.png'),
(50, '574047 pl', '959.9', 'Himagsikang Pilipino:digma ng mga?', 22, 1996, 2, 'book_sample.png'),
(51, '574538 pl', '899.21\'03', 'Sentimiento:Fiction and nostaglia', 23, 1995, 2, 'book_sample.png'),
(52, '575219 pl', '350.0009', 'One woman\'s liberating:the life and..', 24, 1996, 2, 'book_sample.png'),
(53, '575523 pl', '808.88', 'Filipino calendar of quotations', 9, 1990, 2, 'book_sample.png'),
(54, '577942 pl', '510', 'Mathematics I:Gen. mathematics ?', 25, 1996, 2, 'book_sample.png'),
(55, '584396 pl', '650', 'Business Opportunities in the Phils.', 26, 1996, 2, 'book_sample.png'),
(56, '594651 pl', '615', 'Hugs Not Drugs', 27, 1997, 2, 'book_sample.png'),
(57, '607068 pl', '614.4', 'Sickeningly Sweet', 28, 1997, 2, 'book_sample.png'),
(58, '614020 pl', '920', 'In Excelsis', 29, 1996, 2, 'book_sample.png'),
(59, '637090 pl', '899.21101', 'Una kong milenyum', 30, 1998, 2, 'book_sample.png'),
(60, '637981 pl', '899.2101', 'Poet of the people: F. Balagtas', 31, 1997, 2, 'book_sample.png'),
(61, '642166 pl', '959.906', 'The Katipunan and the revolution', 32, 1992, 2, 'book_sample.png'),
(62, '642832 pl', '959.9025', 'The propaganda movement,1880-1895', 33, 1997, 2, 'book_sample.png'),
(63, '643447 pl', '899.2103', 'Ten Thousands seeds', 34, 1987, 2, 'book_sample.png'),
(64, '643569 pl', '959.9092', 'The trial of Rizal', 35, 1961, 2, 'book_sample.png'),
(65, '674706 pl', '959.915', 'The Bikol blend: Bicolanos and their..', 36, 1998, 2, 'book_sample.png'),
(66, '675082 pl', '920', 'Twilight of a her and other their?', 37, 1999, 2, 'book_sample.png'),
(67, '675647 pl', '704.9424', 'Self-portraits:twelve filipino artist?', 38, 1999, 2, 'book_sample.png'),
(68, '676358 pl', '899.2104', 'Woman enough and other essay', 19, 1998, 2, 'book_sample.png'),
(69, '678588 pl', '910.92', 'Magellan,a voyage of life', 39, 1996, 2, 'book_sample.png'),
(70, '701123 pl', '513.26', 'Applied Math skills : fraction', 9, 0, 2, 'book_sample.png'),
(71, '701187 pl', '513.24', 'Applied Math skills : percents', 9, 0, 2, 'book_sample.png'),
(72, '701198 pl', '513.2', 'Applied Math skills : whole numbers', 9, 0, 2, 'book_sample.png'),
(73, '702044 pl', '658.1141', 'Entrepreneur magazines:', 40, 1996, 2, 'book_sample.png'),
(74, '704674 pl', '362.29', 'War against drug abuse', 41, 1998, 2, 'book_sample.png'),
(75, '366607 e', '641.8653', 'Modern Cake Decorating', 42, 1976, 3, 'book_sample.png'),
(76, '420921 e', '50.9', 'Philippine Almanac Book of Facts', 43, 1986, 3, 'book_sample.png'),
(77, '423292 e', '604.2', 'Basic Drafting', 44, 1980, 3, 'book_sample.png'),
(78, '54702 ed', '808', 'Written Words', 45, 1962, 3, 'book_sample.png'),
(79, '58071 ed', '499.2103', 'English-Tagalog Dictionary', 46, 1965, 3, 'book_sample.png'),
(80, '1031 pl', '248', 'The Secret Teachings', 47, 1989, 3, 'book_sample.png'),
(81, '438986 pl', '370.78', 'Research Methods in Education a Practical?', 48, 1985, 3, 'book_sample.png'),
(82, '448644 pl', '615.852', 'Exploration in Folk Religion and Healing', 49, 1989, 3, 'book_sample.png'),
(83, '450708 pl', '616.9', 'Communicable and Infectious Diseases', 50, 1981, 3, 'book_sample.png'),
(84, '450797 pl', '4', 'Computers Today with Basic', 51, 1988, 3, 'book_sample.png'),
(85, '457910 pl', '613', 'Nature Heal Why Be Sick', 52, 0, 3, 'book_sample.png'),
(86, '458868 pl', '912', 'Time Life Illustrated Atlas of the World', 9, 0, 3, 'book_sample.png'),
(87, '469324 pl ', '959.9092', 'The Bridge to Understand', 53, 1988, 3, 'book_sample.png'),
(88, '471334 pl ', '338.642', 'You Too, Can Start Your Own Business', 54, 1990, 3, 'book_sample.png'),
(89, '474484 pl', '614.076', 'Preventive Medicine and Public Health', 55, 1987, 3, 'book_sample.png'),
(90, '492180 pl', '232.901', 'The Story of Jesus', 56, 1949, 3, 'book_sample.png'),
(91, '492394 pl', '234.3', 'The Story of Redemption', 56, 1989, 3, 'book_sample.png'),
(92, '518219 pl', '808.3', 'Before the Night Descends', 57, 1994, 3, 'book_sample.png'),
(93, '566908 pl', '305.89921', 'Filipino Tribal Religious Experiences', 58, 0, 3, 'book_sample.png'),
(94, '596633 pl', '616', 'The Medical Advisor :The Complete Guide to Alternative and Conventional Treatments', 59, 0, 3, 'book_sample.png'),
(95, '596669 pl', '610.3', 'The Medical Advisor :The Complete Guide to Alternative and Conventional Treatments', 59, 0, 3, 'book_sample.png'),
(96, '599283 pl', '912', 'Geographical Atlas of the World', 9, 0, 3, 'book_sample.png'),
(97, '606012 pl', '415', 'How to Write and Speak Better', 60, 1993, 3, 'book_sample.png'),
(98, '606114 pl', '415', 'How to Write and Speak Better', 61, 1993, 3, 'book_sample.png'),
(99, '607124 pl', '614.4', 'Sickening Sweet', 62, 1997, 3, 'book_sample.png'),
(100, '607680 pl', '697', 'Heating, Ventilating, and Air Conditioning fundamentals', 63, 1995, 3, 'book_sample.png'),
(101, '607854 pl', '658.4\'21', 'Enterpreneurship', 64, 1997, 3, 'book_sample.png'),
(102, '610806 pl', '413', 'Longman Handy Guide to Business & Economics', 65, 1996, 3, 'book_sample.png'),
(103, '611006 pl', '698', 'Professional Tiling', 66, 1993, 3, 'book_sample.png'),
(104, '611299 pl', '636', 'Modern Livestock & Poultry Production', 67, 1997, 3, 'book_sample.png'),
(105, '611313 pl', '683.13', 'The Complete Book of Locks and Locksmething', 68, 1995, 3, 'book_sample.png'),
(106, '611738 pl', '341', 'Basic Facts About the United Nations', 69, 1995, 3, 'book_sample.png'),
(107, '3816 md', '331.26', 'Aspects of Philippine Labor ?', 70, 1973, 4, 'book_sample.png'),
(108, '3817 md', '340', 'Trends in Remedial Law', 71, 1982, 4, 'book_sample.png'),
(109, '3818 md', '342', 'The SC Under the Constitution', 72, 1977, 4, 'book_sample.png'),
(110, '3819 md', '344.4', 'Medicine and Law', 73, 1981, 4, 'book_sample.png'),
(111, '3820 md', '323.4', 'Ferdinand E. Marcos on Human Rights', 74, 1977, 4, 'book_sample.png'),
(112, '3821 md', '342', 'Government Prosecutors', 75, 1975, 4, 'book_sample.png'),
(113, '3822 md', '658.8', 'Sales', 76, 1981, 4, 'book_sample.png'),
(114, '3823 md', '351.04', 'The Towards of the Philippine President', 74, 1983, 4, 'book_sample.png'),
(115, '3824 md', '347.1', 'The Father if the First Race Civil code', 77, 1978, 4, 'book_sample.png'),
(116, '3825 md', '378.053', 'Governance of State Universities ?', 78, 1977, 4, 'book_sample.png'),
(117, '3826 md', '333.3', 'Institute on the Legal Aspects of?', 79, 1984, 4, 'book_sample.png'),
(118, '3827 md', '340', 'Law and Small Scale Industries', 80, 1976, 4, 'book_sample.png'),
(119, '3828 md', '342', 'Trial Problems in City and Municipal ?', 81, 1974, 4, 'book_sample.png'),
(120, '3829 md', '368.5', 'Legal Aspects of the Insurance Industry', 82, 1977, 4, 'book_sample.png'),
(121, '3830 md', '368', 'Insurance', 83, 1983, 4, 'book_sample.png'),
(122, '3831 md ', '342.2', 'Perspectives in the New Constitution', 72, 1974, 4, 'book_sample.png'),
(123, '3832 md', '342.062', 'Vital Documents vol. 1-4', 84, 1900, 4, 'book_sample.png'),
(124, '3833 md', '342.062', 'Vital Documents vol. 5-8', 84, 1973, 4, 'book_sample.png'),
(125, '3834 md', '342.062', 'Vital Documents vol. 13-16', 84, 1973, 4, 'book_sample.png'),
(126, '3835 md', '342.062', 'Vital Documents vol. 13-16', 84, 1974, 4, 'book_sample.png'),
(127, '3836 md', '342.062', 'Vital Documents vol. 17-20', 84, 1975, 4, 'book_sample.png'),
(128, '3837 md', '342.062', 'Vital Documents vol. 21-24', 84, 1975, 4, 'book_sample.png'),
(129, '3839 md', '342.062', 'Vital Documents vol. 25-28', 84, 1975, 4, 'book_sample.png'),
(130, '3840 md', '342.062', 'Vital Legal Documents vol. 29-32', 84, 1975, 4, 'book_sample.png'),
(131, '3841 md', '342.062', 'Vital Documents vol. 33-36', 84, 1975, 4, 'book_sample.png'),
(132, '3842 md', '342.062', 'Vital Legal Documents vol 37-40', 84, 1976, 4, 'book_sample.png'),
(133, '3843 md', '342.062', 'Vital Legal Documents vol. 41-44', 84, 1976, 4, 'book_sample.png'),
(134, '3844 md', '342.062', 'Vital legal Documents vol 45-48', 84, 1977, 4, 'book_sample.png'),
(135, '3845 md', '342.062', 'Vital Legal Documents vol. 49-52', 84, 1977, 4, 'book_sample.png'),
(136, '3846 md', '342.062', 'Vital Legal Documents vol. 53-56', 84, 1900, 4, 'book_sample.png'),
(137, '3847 md', '342.062', 'Vital Legal Documents vol. 57-60', 84, 1900, 4, 'book_sample.png'),
(138, '3848 md', '342.062', 'Vital Legal Documents vol. 61-64', 84, 1900, 4, 'book_sample.png'),
(139, '3849 md', '342.062', 'Vital Legal Documents vol. 65-68', 84, 1900, 4, 'book_sample.png'),
(140, '3850 md', '342.062', 'Vital Legal Documents vol. 69-72', 84, 1900, 4, 'book_sample.png'),
(141, '3851 md', '342.062', 'Vital Legal Documents vol. 73-76', 84, 1900, 4, 'book_sample.png'),
(142, '3852 md', '342.062', 'Vital Legal Documents vol. 77-80', 84, 1900, 4, 'book_sample.png'),
(143, '3853 md', '342.062', 'Vital Legal Documents vol. 81-84', 84, 1900, 4, 'book_sample.png'),
(144, '3854 md', '342.062', 'Vital Legal Documents vol. 85-88', 84, 1900, 4, 'book_sample.png'),
(145, '3855 md', '342. 062', 'Vital Legal Documents vol. 89-92', 84, 1900, 4, 'book_sample.png'),
(146, '3856 md', '342.062', 'Vital Legal Documents vol. 93-96', 84, 1900, 4, 'book_sample.png'),
(147, '5975 eb', '912.599', 'Journey to Majayjay', 85, 1983, 5, 'book_sample.png'),
(148, '6552 eb', '959.90924', 'Marcelo H. Del Pilar', 86, 1984, 5, 'book_sample.png'),
(149, '12641 ed', '921', 'Rennaisance Travels of J Rizal', 87, 1961, 5, 'book_sample.png'),
(150, '13718 ed', '921', 'Pakikipagsulatan sa iba\'t ibang tao', 9, 1961, 5, 'book_sample.png'),
(151, '44388 ed', '920', 'Eminent Filipinos', 88, 0, 5, 'book_sample.png'),
(152, '46714 ed', '308', 'Challenge, Liberation and Hope', 89, 1970, 5, 'book_sample.png'),
(153, '46803 ed', '308', 'Challenge, Liberation and Hope', 89, 0, 5, 'book_sample.png'),
(154, '46805 ed', '308', 'Challenge, Liberation and Hope', 90, 1970, 5, 'book_sample.png'),
(155, '48020 ed', '308', 'Challenge, Liberation and Hope', 89, 1970, 5, 'book_sample.png'),
(156, '49158 ed', '382.0959', 'Under Four Flags', 91, 1970, 5, 'book_sample.png'),
(157, '49228  ed', '382.09599', 'Under Four Flags', 91, 0, 5, 'book_sample.png'),
(158, '50781 ed', '808.851', 'Self-Reliance in Freedom, Speeches and', 9, 1975, 5, 'book_sample.png'),
(159, '58916 ed', '613.709', 'The History & Development of P.E.', 92, 1974, 5, 'book_sample.png'),
(160, '61267 ed', '463', 'Hispanismo En El Tagalo', 9, 1972, 5, 'book_sample.png'),
(161, '61310 ed', '460', 'Hispanismo En El Tagalo', 9, 0, 5, 'book_sample.png'),
(162, '61396 ed', '463', 'Hispanismo En El Tagalo', 9, 0, 5, 'book_sample.png'),
(163, '62820 ed', '372.7', 'Moving on in Math', 93, 1978, 5, 'book_sample.png'),
(164, '62831 ed', '372.7', 'Moving on in Math', 93, 1978, 5, 'book_sample.png'),
(165, '66096 ed', '959.9046', 'Carlos P. Romulo on the New Society', 94, 0, 5, 'book_sample.png'),
(166, '66604 ed', '499.211', 'Patnubay ng Guro sa Pilipino', 95, 1984, 5, 'book_sample.png'),
(167, '67188 ed', '372.6', 'Teacher\'s Guide for Math', 96, 1984, 5, 'book_sample.png'),
(168, '67191 ed', '372.7', 'Teacher\'s Guide for Mathematics', 96, 1980, 5, 'book_sample.png'),
(169, '67199 ed', '372.6', 'Teacher\'s Guide for Math', 96, 1984, 5, 'book_sample.png'),
(170, '67329 ed', '372.83', 'Manual ng Guro', 93, 1984, 5, 'book_sample.png'),
(171, '67603 ed', '372.6', 'Teacher\'s guide for english 2', 97, 1984, 5, 'book_sample.png'),
(172, '67616 ed', '372.6', 'Teacher\'s Guidefor English', 96, 1984, 5, 'book_sample.png'),
(173, '67791 ed', '372.83', 'Manual ng Guro', 98, 1984, 5, 'book_sample.png'),
(174, '68356 ed', '15.599', 'Philippine National Bibliography', 9, 1983, 5, 'book_sample.png'),
(175, '68362 ed', '15.599', 'Philippine National Bibliography', 9, 1983, 5, 'book_sample.png'),
(176, '69192 ed', '959.9056', 'Ferdinand E. Marcos: Epiko', 99, 1974, 5, 'book_sample.png'),
(177, '69220 ed', '899.211', 'Epiko', 9, 1974, 5, 'book_sample.png'),
(178, '69678 ed', '959.9056', 'Imelda Romualdez Marcos', 100, 1975, 5, 'book_sample.png'),
(179, '69686 ed', '959', 'Imelda Romualdez Marcos', 100, 1978, 5, 'book_sample.png'),
(180, '70388 ed', '959.902', 'Why the Revolution Failed', 101, 1986, 5, 'book_sample.png'),
(181, '71038 ed', '899.2101', 'Anthology of Asean Literature', 102, 1984, 5, 'book_sample.png'),
(182, '71169 ed', '899.2111', 'Antolohiya ng mga Panitikang', 102, 1984, 5, 'book_sample.png'),
(183, '418232 ed', '899.2103', 'George Ade\'s Stories of Benevolent Assimilation', 103, 1985, 5, 'book_sample.png');

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `borrow_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `patrons_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `borrow_date` varchar(255) DEFAULT NULL,
  `return_date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`borrow_id`, `book_id`, `patrons_id`, `status`, `borrow_date`, `return_date`) VALUES
(1, 41, 1, 'Returned', '12/1/2023', '12/5/2023'),
(2, 42, 2, 'Returned', '12/2/2023', '12/6/2023'),
(3, 43, 3, 'Returned', '12/3/2023', '12/7/2023'),
(4, 44, 4, 'Returned', '12/4/2023', '12/8/2023'),
(5, 45, 5, 'Returned', '12/5/2023', '12/9/2023'),
(6, 46, 6, 'Returned', '12/6/2023', '12/10/2023'),
(7, 47, 7, 'Returned', '12/7/2023', '12/11/2023'),
(8, 48, 8, 'Returned', '12/8/2023', '12/12/2023'),
(9, 49, 9, 'Returned', '12/9/2023', '12/13/2023'),
(10, 50, 10, 'Returned', '12/10/2023', '12/14/2023'),
(11, 51, 11, 'Returned', '12/11/2023', '12/15/2023'),
(12, 52, 12, 'Returned', '12/12/2023', '12/16/2023'),
(13, 53, 13, 'Returned', '12/13/2023', '12/17/2023'),
(14, 54, 14, 'Returned', '12/14/2023', '12/18/2023'),
(15, 55, 15, 'Returned', '12/15/2023', '12/19/2023'),
(16, 56, 16, 'Returned', '12/16/2023', '12/20/2023'),
(17, 57, 17, 'Returned', '12/17/2023', '12/21/2023'),
(18, 58, 18, 'Returned', '12/18/2023', '12/22/2023'),
(19, 59, 19, 'Returned', '12/19/2023', '12/23/2023'),
(20, 60, 20, 'Returned', '12/20/2023', '12/24/2023'),
(21, 61, 21, 'Returned', '12/21/2023', '12/25/2023'),
(22, 62, 22, 'Returned', '12/22/2023', '12/26/2023'),
(23, 63, 23, 'Returned', '12/23/2023', '12/27/2023'),
(24, 64, 24, 'Returned', '12/24/2023', '12/28/2023'),
(25, 65, 25, 'Returned', '12/25/2023', '12/29/2023'),
(26, 66, 26, 'Returned', '12/26/2023', '12/30/2023'),
(27, 67, 27, 'Returned', '12/27/2023', '12/31/2023'),
(28, 68, 28, 'Returned', '12/28/2023', '1/1/2024'),
(29, 69, 29, 'Returned', '12/29/2023', '1/2/2024'),
(30, 70, 30, 'Returned', '12/30/2023', '1/3/2024'),
(31, 71, 31, 'Returned', '12/31/2023', '1/4/2024'),
(32, 72, 32, 'Returned', '1/1/2024', '1/5/2024'),
(33, 73, 33, 'Returned', '1/2/2024', '1/6/2024'),
(34, 74, 34, 'Returned', '1/3/2024', '1/7/2024'),
(35, 41, 35, 'Returned', '1/4/2024', '1/8/2024'),
(36, 42, 36, 'Returned', '1/5/2024', '1/9/2024'),
(37, 43, 37, 'Returned', '1/6/2024', '1/10/2024'),
(38, 44, 38, 'Returned', '1/7/2024', '1/11/2024'),
(39, 45, 39, 'Returned', '1/8/2024', '1/12/2024'),
(40, 46, 40, 'Returned', '1/9/2024', '1/13/2024'),
(41, 47, 41, 'Returned', '1/10/2024', '1/14/2024'),
(42, 48, 42, 'Returned', '1/11/2024', '1/15/2024'),
(43, 49, 43, 'Returned', '1/12/2024', '1/16/2024'),
(44, 50, 44, 'Returned', '1/13/2024', '1/17/2024'),
(45, 51, 1, 'Returned', '1/14/2024', '1/18/2024'),
(46, 52, 2, 'Returned', '1/15/2024', '1/19/2024'),
(47, 53, 3, 'Returned', '1/16/2024', '1/20/2024'),
(48, 54, 4, 'Returned', '1/17/2024', '1/21/2024'),
(49, 55, 5, 'Returned', '1/18/2024', '1/22/2024'),
(50, 56, 6, 'Returned', '1/19/2024', '1/23/2024'),
(51, 57, 7, 'Returned', '1/20/2024', '1/24/2024'),
(52, 58, 8, 'Returned', '1/21/2024', '1/25/2024'),
(53, 59, 9, 'Returned', '1/22/2024', '1/26/2024'),
(54, 60, 10, 'Returned', '1/23/2024', '1/27/2024'),
(55, 61, 11, 'Returned', '1/24/2024', '1/28/2024'),
(56, 62, 12, 'Returned', '1/25/2024', '1/29/2024'),
(57, 63, 13, 'Returned', '1/26/2024', '1/30/2024'),
(58, 64, 14, 'Returned', '1/27/2024', '1/31/2024'),
(59, 65, 15, 'Returned', '1/28/2024', '2/1/2024'),
(60, 66, 16, 'Returned', '1/29/2024', '2/2/2024'),
(61, 67, 17, 'Returned', '1/30/2024', '2/3/2024'),
(62, 68, 18, 'Returned', '1/31/2024', '2/4/2024'),
(63, 69, 19, 'Returned', '2/1/2024', '2/5/2024'),
(64, 70, 20, 'Returned', '2/2/2024', '2/6/2024'),
(65, 71, 21, 'Returned', '2/3/2024', '2/7/2024'),
(66, 72, 22, 'Returned', '2/4/2024', '2/8/2024'),
(67, 73, 23, 'Returned', '2/5/2024', '2/9/2024'),
(68, 74, 24, 'Returned', '2/6/2024', '2/10/2024'),
(69, 41, 25, 'Returned', '2/7/2024', '2/11/2024'),
(70, 42, 26, 'Returned', '2/8/2024', '2/12/2024'),
(71, 43, 27, 'Returned', '2/9/2024', '2/13/2024'),
(72, 44, 28, 'Returned', '2/10/2024', '2/14/2024'),
(73, 45, 29, 'Returned', '2/11/2024', '2/15/2024'),
(74, 46, 30, 'Returned', '2/12/2024', '2/16/2024'),
(75, 47, 31, 'Returned', '2/13/2024', '2/17/2024'),
(76, 48, 32, 'Returned', '2/14/2024', '2/18/2024'),
(77, 49, 33, 'Returned', '2/15/2024', '2/19/2024'),
(78, 50, 34, 'Returned', '2/16/2024', '2/20/2024'),
(79, 51, 35, 'Returned', '2/17/2024', '2/21/2024'),
(80, 52, 36, 'Returned', '2/18/2024', '2/22/2024'),
(81, 53, 37, 'Returned', '2/19/2024', '2/23/2024'),
(82, 54, 38, 'Returned', '2/20/2024', '2/24/2024'),
(83, 55, 39, 'Returned', '2/21/2024', '2/25/2024'),
(84, 56, 40, 'Returned', '2/22/2024', '2/26/2024'),
(85, 57, 41, 'Returned', '2/23/2024', '2/27/2024'),
(86, 58, 42, 'Returned', '2/24/2024', '2/28/2024'),
(87, 59, 43, 'Returned', '2/25/2024', '2/29/2024'),
(88, 60, 44, 'Returned', '2/26/2024', '3/1/2024'),
(89, 61, 1, 'Borrowed', '2/27/2024', ''),
(90, 62, 2, 'Borrowed', '2/28/2024', ''),
(91, 63, 3, 'Borrowed', '2/29/2024', ''),
(92, 64, 4, 'Borrowed', '3/1/2024', ''),
(93, 65, 5, 'Borrowed', '3/2/2024', ''),
(94, 66, 6, 'Borrowed', '3/3/2024', ''),
(95, 67, 7, 'Borrowed', '3/4/2024', ''),
(96, 68, 8, 'Borrowed', '3/5/2024', ''),
(97, 69, 9, 'Borrowed', '3/6/2024', ''),
(98, 70, 10, 'Borrowed', '3/7/2024', ''),
(99, 71, 11, 'Borrowed', '3/8/2024', ''),
(100, 72, 12, 'Borrowed', '3/9/2024', ''),
(101, 73, 13, 'Borrowed', '3/10/2024', ''),
(102, 74, 14, 'Borrowed', '3/11/2024', ''),
(103, 41, 15, 'Borrowed', '3/12/2024', ''),
(104, 42, 16, 'Borrowed', '3/13/2024', ''),
(105, 43, 17, 'Borrowed', '3/14/2024', ''),
(106, 44, 18, 'Borrowed', '3/15/2024', ''),
(107, 45, 19, 'Borrowed', '3/16/2024', ''),
(108, 46, 20, 'Borrowed', '3/17/2024', ''),
(109, 47, 21, 'Borrowed', '3/18/2024', ''),
(110, 48, 22, 'Borrowed', '3/19/2024', ''),
(111, 49, 23, 'Borrowed', '3/20/2024', ''),
(112, 50, 24, 'Borrowed', '3/21/2024', ''),
(113, 51, 25, 'Borrowed', '3/22/2024', ''),
(114, 52, 26, 'Borrowed', '3/23/2024', ''),
(115, 53, 27, 'Borrowed', '3/24/2024', ''),
(116, 54, 28, 'Borrowed', '3/25/2024', ''),
(117, 55, 29, 'Borrowed', '3/26/2024', ''),
(118, 56, 30, 'Borrowed', '3/27/2024', ''),
(119, 57, 31, 'Borrowed', '3/28/2024', ''),
(120, 58, 32, 'Borrowed', '3/29/2024', ''),
(121, 59, 33, 'Borrowed', '3/30/2024', ''),
(122, 60, 34, 'Borrowed', '3/31/2024', ''),
(123, 61, 35, 'Borrowed', '4/1/2024', ''),
(124, 62, 36, 'Borrowed', '4/2/2024', ''),
(125, 63, 37, 'Borrowed', '4/3/2024', ''),
(126, 64, 38, 'Borrowed', '4/4/2024', ''),
(127, 65, 39, 'Borrowed', '4/5/2024', ''),
(128, 66, 40, 'Borrowed', '4/6/2024', ''),
(129, 67, 41, 'Borrowed', '4/7/2024', ''),
(130, 68, 42, 'Borrowed', '4/8/2024', ''),
(131, 69, 43, 'Borrowed', '4/9/2024', ''),
(132, 70, 44, 'Borrowed', '4/10/2024', ''),
(133, 71, 1, 'Borrowed', '4/11/2024', ''),
(134, 72, 2, 'Borrowed', '4/12/2024', ''),
(135, 73, 3, 'Borrowed', '4/13/2024', ''),
(136, 74, 4, 'Borrowed', '4/14/2024', ''),
(137, 41, 5, 'Borrowed', '4/15/2024', ''),
(138, 42, 6, 'Borrowed', '4/16/2024', ''),
(139, 43, 7, 'Borrowed', '4/17/2024', ''),
(140, 44, 8, 'Borrowed', '4/18/2024', ''),
(141, 45, 9, 'Borrowed', '4/19/2024', ''),
(142, 46, 10, 'Borrowed', '4/20/2024', ''),
(143, 47, 11, 'Borrowed', '4/21/2024', ''),
(144, 48, 12, 'Borrowed', '4/22/2024', ''),
(145, 49, 13, 'Borrowed', '4/23/2024', ''),
(146, 50, 14, 'Borrowed', '4/24/2024', ''),
(147, 51, 15, 'Borrowed', '4/25/2024', ''),
(148, 52, 16, 'Borrowed', '4/26/2024', ''),
(149, 53, 17, 'Borrowed', '4/27/2024', ''),
(150, 54, 18, 'Borrowed', '4/28/2024', ''),
(151, 55, 19, 'Borrowed', '4/29/2024', ''),
(152, 56, 20, 'Borrowed', '4/30/2024', ''),
(153, 57, 21, 'Borrowed', '5/1/2024', ''),
(154, 58, 22, 'Borrowed', '5/2/2024', ''),
(155, 59, 23, 'Borrowed', '5/3/2024', ''),
(156, 60, 24, 'Borrowed', '5/4/2024', ''),
(157, 61, 25, 'Borrowed', '5/5/2024', ''),
(158, 62, 26, 'Borrowed', '5/6/2024', ''),
(159, 63, 27, 'Borrowed', '5/7/2024', ''),
(160, 64, 28, 'Borrowed', '5/8/2024', ''),
(161, 65, 29, 'Borrowed', '5/9/2024', ''),
(162, 66, 30, 'Borrowed', '5/10/2024', ''),
(163, 67, 31, 'Borrowed', '5/11/2024', ''),
(164, 68, 32, 'Borrowed', '5/12/2024', ''),
(165, 69, 33, 'Borrowed', '5/13/2024', ''),
(166, 70, 34, 'Borrowed', '5/14/2024', ''),
(167, 71, 35, 'Borrowed', '5/15/2024', ''),
(168, 72, 36, 'Borrowed', '5/16/2024', ''),
(169, 73, 37, 'Borrowed', '5/17/2024', ''),
(170, 74, 38, 'Borrowed', '5/18/2024', ''),
(171, 41, 39, 'Borrowed', '5/19/2024', ''),
(172, 42, 40, 'Borrowed', '5/20/2024', ''),
(173, 43, 41, 'Borrowed', '5/21/2024', ''),
(174, 44, 42, 'Borrowed', '5/22/2024', ''),
(175, 45, 43, 'Borrowed', '5/23/2024', ''),
(176, 46, 44, 'Borrowed', '5/24/2024', ''),
(179, 41, 1, 'Pending', '09/18/2024 23:16', '');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category`) VALUES
(1, 'Children'),
(2, 'Circulation'),
(3, 'Reference'),
(4, 'Law'),
(5, 'Filipiniana');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `patrons_id` int(11) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `book_id`, `patrons_id`, `date`, `status`) VALUES
(1, 41, 1, 'September 18, 2024', 'Remove'),
(2, 42, 2, '12/6/2023', 'Added'),
(3, 43, 3, '12/7/2023', 'Added'),
(4, 44, 4, '12/8/2023', 'Added'),
(5, 45, 5, '12/9/2023', 'Added'),
(6, 46, 6, '12/10/2023', 'Added'),
(7, 47, 7, '12/11/2023', 'Added'),
(8, 48, 8, '12/12/2023', 'Added'),
(9, 49, 9, '12/13/2023', 'Added'),
(10, 50, 10, '12/14/2023', 'Added'),
(11, 51, 11, '12/15/2023', 'Added'),
(12, 52, 12, '12/16/2023', 'Added'),
(13, 53, 13, '12/17/2023', 'Added'),
(14, 54, 14, '12/18/2023', 'Added'),
(15, 55, 15, '12/19/2023', 'Added'),
(16, 56, 16, '12/20/2023', 'Added'),
(17, 57, 17, '12/21/2023', 'Added'),
(18, 58, 18, '12/22/2023', 'Added'),
(19, 59, 19, '12/23/2023', 'Added'),
(20, 60, 20, '12/24/2023', 'Added'),
(21, 61, 21, '12/25/2023', 'Added'),
(22, 62, 22, '12/26/2023', 'Added'),
(23, 63, 23, '12/27/2023', 'Added'),
(24, 64, 24, '12/28/2023', 'Added'),
(25, 65, 25, '12/29/2023', 'Added'),
(26, 66, 26, '12/30/2023', 'Added'),
(27, 67, 27, '12/31/2023', 'Added'),
(28, 68, 28, '1/1/2024', 'Added'),
(29, 69, 29, '1/2/2024', 'Added'),
(30, 70, 30, '1/3/2024', 'Added'),
(31, 71, 31, '1/4/2024', 'Added'),
(32, 72, 32, '1/5/2024', 'Added'),
(33, 73, 33, '1/6/2024', 'Added'),
(34, 74, 34, '1/7/2024', 'Added'),
(35, 41, 35, '1/8/2024', 'Added'),
(36, 42, 36, '1/9/2024', 'Added'),
(37, 43, 37, '1/10/2024', 'Added'),
(38, 44, 38, '1/11/2024', 'Added'),
(39, 45, 39, '1/12/2024', 'Added'),
(40, 46, 40, '1/13/2024', 'Added'),
(41, 47, 41, '1/14/2024', 'Added'),
(42, 48, 42, '1/15/2024', 'Added'),
(43, 49, 43, '1/16/2024', 'Added'),
(44, 50, 44, '1/17/2024', 'Added'),
(45, 51, 1, 'September 18, 2024', 'Remove'),
(46, 52, 2, '1/19/2024', 'Added'),
(47, 53, 3, '1/20/2024', 'Added'),
(48, 54, 4, '1/21/2024', 'Added'),
(49, 55, 5, '1/22/2024', 'Added'),
(50, 56, 6, '1/23/2024', 'Added'),
(51, 57, 7, '1/24/2024', 'Added'),
(52, 58, 8, '1/25/2024', 'Added'),
(53, 59, 9, '1/26/2024', 'Added'),
(54, 60, 10, '1/27/2024', 'Added'),
(55, 61, 11, '1/28/2024', 'Added'),
(56, 62, 12, '1/29/2024', 'Added'),
(57, 63, 13, '1/30/2024', 'Added'),
(58, 64, 14, '1/31/2024', 'Added'),
(59, 65, 15, '2/1/2024', 'Added'),
(60, 66, 16, '2/2/2024', 'Added'),
(61, 67, 17, '2/3/2024', 'Added'),
(62, 68, 18, '2/4/2024', 'Added'),
(63, 69, 19, '2/5/2024', 'Added'),
(64, 70, 20, '2/6/2024', 'Added'),
(65, 71, 21, '2/7/2024', 'Added'),
(66, 72, 22, '2/8/2024', 'Added'),
(67, 73, 23, '2/9/2024', 'Added'),
(68, 74, 24, '2/10/2024', 'Added'),
(69, 41, 25, '2/11/2024', 'Added'),
(70, 42, 26, '2/12/2024', 'Added'),
(71, 43, 27, '2/13/2024', 'Added'),
(72, 44, 28, '2/14/2024', 'Added'),
(73, 45, 29, '2/15/2024', 'Added'),
(74, 46, 30, '2/16/2024', 'Added'),
(75, 47, 31, '2/17/2024', 'Added'),
(76, 48, 32, '2/18/2024', 'Added'),
(77, 49, 33, '2/19/2024', 'Added'),
(78, 50, 34, '2/20/2024', 'Added'),
(79, 51, 35, '2/21/2024', 'Added'),
(80, 52, 36, '2/22/2024', 'Added'),
(81, 53, 37, '2/23/2024', 'Added'),
(82, 54, 38, '2/24/2024', 'Added'),
(83, 55, 39, '2/25/2024', 'Added'),
(84, 56, 40, '2/26/2024', 'Added'),
(85, 57, 41, '2/27/2024', 'Added'),
(86, 58, 42, '2/28/2024', 'Added'),
(87, 59, 43, '2/29/2024', 'Added'),
(88, 60, 44, '3/1/2024', 'Added'),
(89, 42, 1, 'September 17, 2024', 'Remove'),
(90, 3, 1, 'September 18, 2024', 'Added'),
(91, 2, 1, 'September 18, 2024', 'Added'),
(92, 1, 1, 'September 18, 2024', 'Added'),
(93, 4, 1, 'September 18, 2024', 'Added'),
(94, 5, 1, 'September 18, 2024', 'Added');

-- --------------------------------------------------------

--
-- Table structure for table `patrons`
--

CREATE TABLE `patrons` (
  `patrons_id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthdate` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `interest` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patrons`
--

INSERT INTO `patrons` (`patrons_id`, `firstname`, `middlename`, `lastname`, `suffix`, `birthdate`, `age`, `gender`, `contact`, `address`, `interest`, `email`, `password`) VALUES
(1, 'John Lemuell', 'Bacolod', '', '', '6/7/2003', 21, 'Male', ' +63995773887', 'Makati City', 'Reference,Filipiniana', 'bacolod@gmail.com', 'bacolod123'),
(2, 'Isaaq ', 'Gavieres', '', '', '6/7/2003', 21, 'Male', '63995773887', 'Makati City', 'Reference,Filipiniana', 'gavieres@gmail.com', 'gavieres123'),
(3, 'Mark Luis', 'Yuson', '', '', '6/7/2003', 21, 'Male', ' +63995773887', 'Makati City', 'Reference,Filipiniana', 'yuson@gmail.com', 'yuson123'),
(4, 'Jerico', 'Gumahin', '', '', '6/7/2003', 21, 'Male', ' +63995773887', 'Makati City', 'Reference,Filipiniana', 'gumahin@gmail.com', 'gumahin123'),
(5, 'Emma', 'Jane', 'Doe', '', '1/15/2012', 12, 'Female', '+639967766551', '123 Elm St', 'Children, Circulation', 'emma.doe@example.com', 'pass001'),
(6, 'Liam', 'Michael', 'Smith', '', '3/22/2011', 13, 'Male', '+639967766552', '456 Maple Ave', 'Children, Reference', 'liam.smith@example.com', 'pass002'),
(7, 'Sophia', 'Marie', 'Johnson', '', '5/10/2010', 14, 'Female', '+639967766553', '789 Oak Rd', 'Children, Filipiniana', 'sophia.johnson@example.com', 'pass003'),
(8, 'Noah', 'James', 'Williams', '', '7/30/2011', 13, 'Male', '+639967766554', '101 Pine St', 'Children, Law', 'noah.williams@example.com', 'pass004'),
(9, 'Olivia', 'Rose', 'Brown', '', '9/14/2012', 12, 'Female', '+639967766555', '202 Cedar Ln', 'Children, Circulation', 'olivia.brown@example.com', 'pass005'),
(10, 'Ethan', 'Daniel', 'Davis', '', '11/23/2010', 14, 'Male', '+639967766556', '303 Birch Blvd', 'Children, Reference', 'ethan.davis@example.com', 'pass006'),
(11, 'Ava', 'Grace', 'Miller', '', '2/11/2011', 13, 'Female', '+639967766557', '404 Elm St', 'Children, Filipiniana', 'ava.miller@example.com', 'pass007'),
(12, 'Mason', 'Alexander', 'Wilson', '', '6/17/2010', 14, 'Male', '+639967766558', '505 Maple Ave', 'Children, Law', 'mason.wilson@example.com', 'pass008'),
(13, 'Isabella', 'Kate', 'Moore', '', '4/29/2012', 12, 'Female', '+639967766559', '606 Oak Rd', 'Children, Circulation', 'isabella.moore@example.com', 'pass009'),
(14, 'Lucas', 'Henry', 'Taylor', '', '8/25/2011', 13, 'Male', '+639967766560', '707 Pine St', 'Children, Reference', 'lucas.taylor@example.com', 'pass010'),
(15, 'Mia', 'Lynn', 'Anderson', '', '2/8/2006', 18, 'Female', '+639967766561', '808 Cedar Ln', 'Circulation, Reference', 'mia.anderson@example.com', 'pass011'),
(16, 'Jack', 'Thomas', 'Martinez', '', '4/16/2005', 19, 'Male', '+639967766562', '909 Birch Blvd', 'Circulation, Filipiniana', 'jack.martinez@example.com', 'pass012'),
(17, 'Emily', 'Claire', 'Thompson', '', '6/20/2004', 20, 'Female', '+639967766563', '1010 Oak Rd', 'Reference, Law', 'emily.thompson@example.com', 'pass013'),
(18, 'Ryan', 'Charles', 'Robinson', '', '8/25/2003', 21, 'Male', '+639967766564', '1111 Maple Ave', 'Reference, Filipiniana', 'ryan.robinson@example.com', 'pass014'),
(19, 'Chloe', 'Mae', 'White', '', '10/30/2002', 22, 'Female', '+639967766565', '1212 Pine St', 'Filipiniana, Law', 'chloe.white@example.com', 'pass015'),
(20, 'James', 'Lee', 'Harris', '', '12/15/2001', 23, 'Male', '+639967766566', '1313 Elm St', 'Circulation, Filipiniana', 'james.harris@example.com', 'pass016'),
(21, 'Lily', 'Ann', 'Clark', '', '1/20/2000', 24, 'Female', '+639967766567', '1414 Cedar Ln', 'Law, Reference', 'lily.clark@example.com', 'pass017'),
(22, 'Alexander', 'John', 'Lewis', '', '3/12/1999', 25, 'Male', '+639967766568', '1515 Birch Blvd', 'Filipiniana, Circulation', 'alexander.lewis@example.com', 'pass018'),
(23, 'Grace', 'Elizabeth', 'Walker', '', '5/24/1998', 26, 'Female', '+639967766569', '1616 Oak Rd', 'Reference, Law', 'grace.walker@example.com', 'pass019'),
(24, 'William', 'Joseph', 'Young', '', '7/14/1997', 27, 'Male', '+639967766570', '1717 Maple Ave', 'Law, Circulation', 'william.young@example.com', 'pass020'),
(25, 'Dorothy', 'Alice', 'Adams', '', '1/11/1949', 75, 'Female', '+639967766571', '1818 Pine St', 'Reference, Circulation', 'dorothy.adams@example.com', 'pass021'),
(26, 'George', 'Henry', 'Scott', '', '3/19/1948', 76, 'Male', '+639967766572', '1919 Elm St', 'Law, Filipiniana', 'george.scott@example.com', 'pass022'),
(27, 'Margaret', 'Helen', 'Baker', '', '5/25/1947', 77, 'Female', '+639967766573', '2020 Cedar Ln', 'Circulation, Reference', 'margaret.baker@example.com', 'pass023'),
(28, 'Charles', 'Edward', 'Green', '', '7/30/1946', 78, 'Male', '+639967766574', '2121 Maple Ave', 'Filipiniana, Reference', 'charles.green@example.com', 'pass024'),
(29, 'Eleanor', 'Mae', 'Collins', '', '9/5/1945', 79, 'Female', '+639967766575', '2222 Birch Blvd', 'Reference, Circulation', 'eleanor.collins@example.com', 'pass025'),
(30, 'Samuel', 'David', 'Morris', '', '11/10/1944', 80, 'Male', '+639967766576', '2323 Oak Rd', 'Law, Filipiniana', 'samuel.morris@example.com', 'pass026'),
(31, 'Rose', 'May', 'Rogers', '', '1/12/1943', 81, 'Female', '+639967766577', '2424 Pine St', 'Circulation, Reference', 'rose.rogers@example.com', 'pass027'),
(32, 'Edward', 'Michael', 'Murphy', '', '3/14/1942', 82, 'Male', '+639967766578', '2525 Cedar Ln', 'Law, Filipiniana', 'edward.murphy@example.com', 'pass028'),
(33, 'Clara', 'Rose', 'Bell', '', '5/18/1941', 83, 'Female', '+639967766579', '2626 Elm St', 'Reference, Circulation', 'clara.bell@example.com', 'pass029'),
(34, 'Frank', 'James', 'Wood', '', '7/21/1940', 84, 'Male', '+639967766580', '2727 Birch Blvd', 'Circulation, Law', 'frank.wood@example.com', 'pass030'),
(35, 'Emma', 'Jane', 'Smith', '', '5/20/1990', 34, 'Female', '+639967766581', '2828 Oak Rd', 'Circulation, Reference', 'emma.smith@example.com', 'pass031'),
(36, 'Michael', 'Allen', 'Johnson', '', '8/15/1985', 39, 'Male', '+639967766582', '2929 Pine St', 'Filipiniana, Law', 'michael.johnson@example.com', 'pass032'),
(37, 'Ava', 'Marie', 'Brown', '', '11/25/1975', 49, 'Female', '+639967766583', '3030 Cedar Ln', 'Reference, Law', 'ava.brown@example.com', 'pass033'),
(38, 'Daniel', 'Paul', 'Lee', '', '12/25/1978', 46, 'Male', '+639967766584', '3131 Birch Blvd', 'Circulation, Reference', 'daniel.lee@example.com', 'pass034'),
(39, 'Mia', 'Rose', 'White', '', '3/10/1975', 49, 'Female', '+639967766585', '3232 Maple Ave', 'Reference, Circulation', 'mia.white@example.com', 'pass035'),
(40, 'Jacob', 'Ryan', 'Smith', '', '6/5/1972', 52, 'Male', '+639967766586', '3333 Elm St', 'Filipiniana, Reference', 'jacob.smith@example.com', 'pass036'),
(41, 'Isabella', 'Faith', 'Turner', '', '9/20/1968', 56, 'Female', '+639967766587', '3434 Oak Rd', 'Law, Reference', 'isabella.turner@example.com', 'pass037'),
(42, 'William', 'John', 'Brown', '', '12/10/1964', 60, 'Male', '+639967766588', '3535 Pine St', 'Circulation, Filipiniana', 'william.brown@example.com', 'pass038'),
(43, 'Olivia', 'Grace', 'Martinez', '', '7/25/1959', 65, 'Female', '+639967766589', '3636 Cedar Ln', 'Reference, Circulation', 'olivia.martinez@example.com', 'pass039'),
(44, 'James', 'Edward', 'Wilson', '', '3/12/1955', 69, 'Male', '+639967766590', '3737 Maple Ave', 'Law, Filipiniana', 'james.wilson@example.com', 'pass040');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `patrons_id` int(11) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `ratings` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `book_id`, `patrons_id`, `date`, `ratings`) VALUES
(1, 41, 1, '12/6/2023', 2),
(2, 42, 2, '12/7/2023', 3),
(3, 43, 3, '12/8/2023', 2),
(4, 44, 4, '12/9/2023', 3),
(5, 45, 5, '12/10/2023', 1),
(6, 46, 6, '12/11/2023', 5),
(7, 47, 7, '12/12/2023', 4),
(8, 48, 8, '12/13/2023', 5),
(9, 49, 9, '12/14/2023', 3),
(10, 50, 10, '12/15/2023', 2),
(11, 51, 11, '12/16/2023', 5),
(12, 52, 12, '12/17/2023', 2),
(13, 53, 13, '12/18/2023', 5),
(14, 54, 14, '12/19/2023', 5),
(15, 55, 15, '12/20/2023', 4),
(16, 56, 16, '12/21/2023', 4),
(17, 57, 17, '12/22/2023', 4),
(18, 58, 18, '12/23/2023', 2),
(19, 59, 19, '12/24/2023', 5),
(20, 60, 20, '12/25/2023', 3),
(21, 61, 21, '12/26/2023', 4),
(22, 62, 22, '12/27/2023', 1),
(23, 63, 23, '12/28/2023', 2),
(24, 64, 24, '12/29/2023', 2),
(25, 65, 25, '12/30/2023', 1),
(26, 66, 26, '12/31/2023', 2),
(27, 67, 27, '1/1/2024', 4),
(28, 68, 28, '1/2/2024', 3),
(29, 69, 29, '1/3/2024', 4),
(30, 70, 30, '1/4/2024', 4),
(31, 71, 31, '1/5/2024', 3),
(32, 72, 32, '1/6/2024', 1),
(33, 73, 33, '1/7/2024', 3),
(34, 74, 34, '1/8/2024', 5),
(35, 41, 35, '1/9/2024', 5),
(36, 42, 36, '1/10/2024', 2),
(37, 43, 37, '1/11/2024', 1),
(38, 44, 38, '1/12/2024', 1),
(39, 45, 39, '1/13/2024', 1),
(40, 46, 40, '1/14/2024', 1),
(41, 47, 41, '1/15/2024', 2),
(42, 48, 42, '1/16/2024', 4),
(43, 49, 43, '1/17/2024', 1),
(44, 50, 44, '1/18/2024', 3),
(45, 51, 1, '1/19/2024', 5),
(46, 52, 2, '1/20/2024', 4),
(47, 53, 3, '1/21/2024', 4),
(48, 54, 4, '1/22/2024', 2),
(49, 55, 5, '1/23/2024', 2),
(50, 56, 6, '1/24/2024', 3),
(51, 57, 7, '1/25/2024', 2),
(52, 58, 8, '1/26/2024', 3),
(53, 59, 9, '1/27/2024', 3),
(54, 60, 10, '1/28/2024', 5),
(55, 61, 11, '1/29/2024', 1),
(56, 62, 12, '1/30/2024', 4),
(57, 63, 13, '1/31/2024', 1),
(58, 64, 14, '2/1/2024', 1),
(59, 65, 15, '2/2/2024', 3),
(60, 66, 16, '2/3/2024', 5),
(61, 67, 17, '2/4/2024', 5),
(62, 68, 18, '2/5/2024', 2),
(63, 69, 19, '2/6/2024', 5),
(64, 70, 20, '2/7/2024', 3),
(65, 71, 21, '2/8/2024', 3),
(66, 72, 22, '2/9/2024', 1),
(67, 73, 23, '2/10/2024', 5),
(68, 74, 24, '2/11/2024', 1),
(69, 41, 25, '2/12/2024', 5),
(70, 42, 26, '2/13/2024', 1),
(71, 43, 27, '2/14/2024', 5),
(72, 44, 28, '2/15/2024', 3),
(73, 45, 29, '2/16/2024', 4),
(74, 46, 30, '2/17/2024', 1),
(75, 47, 31, '2/18/2024', 2),
(76, 48, 32, '2/19/2024', 5),
(77, 49, 33, '2/20/2024', 5),
(78, 50, 34, '2/21/2024', 1),
(79, 51, 35, '2/22/2024', 1),
(80, 52, 36, '2/23/2024', 2),
(81, 53, 37, '2/24/2024', 2),
(82, 54, 38, '2/25/2024', 4),
(83, 55, 39, '2/26/2024', 3),
(84, 56, 40, '2/27/2024', 1),
(85, 57, 41, '2/28/2024', 4),
(86, 58, 42, '2/29/2024', 3),
(87, 59, 43, '3/1/2024', 5),
(88, 60, 44, '3/2/2024', 2),
(89, 1, 1, '1/1/2024', 5),
(90, 2, 2, '1/2/2024', 3),
(91, 3, 3, '1/3/2024', 2),
(92, 4, 4, '1/4/2024', 3),
(93, 5, 5, '1/5/2024', 5),
(94, 6, 6, '1/6/2024', 2),
(95, 7, 7, '1/7/2024', 3),
(96, 8, 8, '1/8/2024', 1),
(97, 9, 9, '1/9/2024', 5),
(98, 10, 10, '1/10/2024', 4),
(99, 11, 11, '1/11/2024', 2),
(100, 12, 12, '1/12/2024', 4),
(101, 13, 13, '1/13/2024', 4),
(102, 14, 14, '1/14/2024', 1),
(103, 15, 15, '1/15/2024', 3),
(104, 16, 16, '1/16/2024', 3),
(105, 17, 17, '1/17/2024', 5),
(106, 18, 18, '1/18/2024', 3),
(107, 19, 19, '1/19/2024', 5),
(108, 20, 20, '1/20/2024', 5),
(109, 21, 21, '1/21/2024', 2),
(110, 22, 22, '1/22/2024', 1),
(111, 23, 23, '1/23/2024', 2),
(112, 24, 24, '1/24/2024', 5),
(113, 25, 25, '1/25/2024', 5),
(114, 26, 26, '1/26/2024', 4),
(115, 27, 27, '1/27/2024', 1),
(116, 28, 28, '1/28/2024', 5),
(117, 29, 29, '1/29/2024', 3),
(118, 30, 30, '1/30/2024', 2),
(119, 31, 31, '1/31/2024', 3),
(120, 32, 32, '2/1/2024', 3),
(121, 33, 33, '2/2/2024', 2),
(122, 34, 34, '2/3/2024', 2),
(123, 35, 35, '2/4/2024', 5),
(124, 36, 36, '2/5/2024', 2),
(125, 37, 37, '2/6/2024', 4),
(126, 38, 38, '2/7/2024', 2),
(127, 40, 39, '2/8/2024', 4),
(128, 41, 40, '2/9/2024', 2),
(129, 75, 41, '2/10/2024', 1),
(130, 76, 42, '2/11/2024', 5),
(131, 77, 43, '2/12/2024', 2),
(132, 78, 44, '2/13/2024', 4),
(133, 79, 1, '2/14/2024', 3),
(134, 80, 2, '2/15/2024', 1),
(135, 81, 3, '2/16/2024', 1),
(136, 82, 4, '2/17/2024', 2),
(137, 83, 5, '2/18/2024', 1),
(138, 84, 6, '2/19/2024', 4),
(139, 85, 7, '2/20/2024', 4),
(140, 86, 8, '2/21/2024', 1),
(141, 87, 9, '2/22/2024', 1),
(142, 88, 10, '2/23/2024', 3),
(143, 89, 11, '2/24/2024', 5),
(144, 90, 12, '2/25/2024', 5),
(145, 91, 13, '2/26/2024', 1),
(146, 92, 14, '2/27/2024', 4),
(147, 93, 15, '2/28/2024', 5),
(148, 94, 16, '2/29/2024', 5),
(149, 95, 17, '3/1/2024', 2),
(150, 96, 18, '3/2/2024', 4),
(151, 97, 19, '3/3/2024', 3),
(152, 98, 20, '3/4/2024', 4),
(153, 99, 21, '3/5/2024', 3),
(154, 100, 22, '3/6/2024', 2),
(155, 101, 23, '3/7/2024', 5),
(156, 102, 24, '3/8/2024', 5),
(157, 103, 25, '3/9/2024', 3),
(158, 104, 26, '3/10/2024', 1),
(159, 105, 27, '3/11/2024', 4),
(160, 106, 28, '3/12/2024', 2),
(161, 107, 29, '3/13/2024', 3),
(162, 108, 30, '3/14/2024', 1),
(163, 109, 31, '3/15/2024', 1),
(164, 110, 32, '3/16/2024', 3),
(165, 111, 33, '3/17/2024', 2),
(166, 112, 34, '3/18/2024', 5),
(167, 113, 35, '3/19/2024', 2),
(168, 114, 36, '3/20/2024', 3),
(169, 115, 37, '3/21/2024', 4),
(170, 116, 38, '3/22/2024', 3),
(171, 117, 39, '3/23/2024', 2),
(172, 118, 40, '3/24/2024', 5),
(173, 119, 41, '3/25/2024', 4),
(174, 120, 42, '3/26/2024', 3),
(175, 121, 43, '3/27/2024', 3),
(176, 122, 44, '3/28/2024', 1),
(177, 123, 1, '3/29/2024', 5),
(178, 124, 2, '3/30/2024', 1),
(179, 125, 3, '3/31/2024', 3),
(180, 126, 4, '4/1/2024', 5),
(181, 127, 5, '4/2/2024', 4),
(182, 128, 6, '4/3/2024', 1),
(183, 129, 7, '4/4/2024', 2),
(184, 130, 8, '4/5/2024', 5),
(185, 131, 9, '4/6/2024', 5),
(186, 132, 10, '4/7/2024', 5),
(187, 133, 11, '4/8/2024', 3),
(188, 134, 12, '4/9/2024', 5),
(189, 135, 13, '4/10/2024', 4),
(190, 136, 14, '4/11/2024', 3),
(191, 137, 15, '4/12/2024', 1),
(192, 138, 16, '4/13/2024', 1),
(193, 139, 17, '4/14/2024', 1),
(194, 140, 18, '4/15/2024', 4),
(195, 141, 19, '4/16/2024', 5),
(196, 142, 20, '4/17/2024', 2),
(197, 143, 21, '4/18/2024', 2),
(198, 144, 22, '4/19/2024', 4),
(199, 145, 23, '4/20/2024', 1),
(200, 146, 24, '4/21/2024', 5),
(201, 147, 25, '4/22/2024', 3),
(202, 148, 26, '4/23/2024', 5),
(203, 149, 27, '4/24/2024', 4),
(204, 150, 28, '4/25/2024', 3),
(205, 151, 29, '4/26/2024', 5),
(206, 152, 30, '4/27/2024', 3),
(207, 153, 31, '4/28/2024', 2),
(208, 154, 32, '4/29/2024', 5),
(209, 155, 33, '4/30/2024', 5),
(210, 156, 34, '5/1/2024', 1),
(211, 157, 35, '5/2/2024', 2),
(212, 158, 36, '5/3/2024', 5),
(213, 159, 37, '5/4/2024', 5),
(214, 160, 38, '5/5/2024', 1),
(215, 161, 39, '5/6/2024', 3),
(216, 162, 40, '5/7/2024', 5),
(217, 163, 41, '5/8/2024', 1),
(218, 164, 42, '5/9/2024', 4),
(219, 165, 43, '5/10/2024', 3),
(220, 166, 44, '5/11/2024', 3),
(221, 167, 1, '5/12/2024', 4),
(222, 168, 2, '5/13/2024', 5),
(223, 169, 3, '5/14/2024', 4),
(224, 170, 4, '5/15/2024', 5),
(225, 171, 5, '5/16/2024', 2),
(226, 172, 6, '5/17/2024', 1),
(227, 173, 7, '5/18/2024', 2),
(228, 174, 8, '5/19/2024', 5),
(229, 175, 9, '5/20/2024', 1),
(230, 176, 10, '5/21/2024', 1),
(231, 177, 11, '5/22/2024', 2),
(232, 178, 12, '5/23/2024', 1),
(233, 179, 13, '5/24/2024', 2),
(234, 180, 14, '5/25/2024', 3),
(235, 181, 15, '5/26/2024', 5),
(236, 182, 16, '5/27/2024', 5),
(237, 183, 17, '5/28/2024', 2),
(238, 42, 1, NULL, 1),
(239, 43, 1, NULL, 5),
(240, 3, 1, NULL, 3),
(241, 2, 1, NULL, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `patrons_id` (`patrons_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `patrons_id` (`patrons_id`);

--
-- Indexes for table `patrons`
--
ALTER TABLE `patrons`
  ADD PRIMARY KEY (`patrons_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `patrons_id` (`patrons_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `patrons`
--
ALTER TABLE `patrons`
  MODIFY `patrons_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`patrons_id`) REFERENCES `patrons` (`patrons_id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`patrons_id`) REFERENCES `patrons` (`patrons_id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`patrons_id`) REFERENCES `patrons` (`patrons_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
