-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 04, 2020 at 02:40 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizzagang`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password_salt` text NOT NULL COMMENT 'generated password',
  `updated_log_time` datetime NOT NULL COMMENT 'login/logout datetime'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password_salt`, `updated_log_time`) VALUES
(1, 'susan', '0594878b8a356267767bbac277bf2820', '2020-10-11 00:00:00'),
(2, 'nickole', '9bf41308ad014167bcbdbc27662eae2b', '2020-10-13 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `memberid` int(11) NOT NULL,
  `username` text,
  `password_salt` text COMMENT 'encrypted-password',
  `session_ids` text,
  `token` text COMMENT 'encrypted-message with session and real time credential',
  `address` text NOT NULL,
  `phonenumber` int(11) DEFAULT NULL,
  `email_address` text NOT NULL,
  `acc_no` varchar(100) DEFAULT NULL,
  `member_pizza_point` int(11) NOT NULL COMMENT 'pizza point'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`memberid`, `username`, `password_salt`, `session_ids`, `token`, `address`, `phonenumber`, `email_address`, `acc_no`, `member_pizza_point`) VALUES
(1, 'testing', 'testing@123', 'n2nf9r7s6s5t7rcst024uk82h3', '2d511cbbad7ae0032001cfd9c3f7f879', '', 143012116, '0', '967235076', 0),
(2, 'testing', 'testing@123', 'n2nf9r7s6s5t7rcst024uk82h3', 'bb51bb1ea0f7afac93b85a37b963d8da', '', 143012116, 'nickoletan13@gmail.com', '1543266968', 0),
(3, 'allan', 'bc61dbbad9f795368cf28268e3447df2', 'vln5bin8b8ho5cg8528s37fuu7', '630dc06de6f6ea9b02453c24d92d24d8', '', 142257755, 'allanng92@gmail.com', '1978005798', 0),
(4, 'west', 'sdfsdfsdfsdfsdf', 'n2nf9r7s6s5t7rcst024uk82h3', '3bc7f6c4010e66f0f24a4d02ed93d3cc', '', 92873, 'sdfsdf', '41493829', 0),
(5, 'emily', 'emily@123', 'n2nf9r7s6s5t7rcst024uk82h3', 'cfe48b93cb56fb910af7009eba3986bb', '', 145657882, 'emilyyin@gmail.com', '957098613', 0),
(6, 'nickole', 'c68860b2c2d5199b442569b241e8479b', 'vln5bin8b8ho5cg8528s37fuu7', '2a3d58a2326f5d9981293896c9fb9ca2', '', 145560272, 'nickoletan12@gmail.com', '2038115141', 112),
(7, 'Susan', 'Susan@123', 'i7psk00nql2av43pvd547dff63', NULL, '', 134452864, 'susan1223@gmail.com', '1868343904', 0),
(8, 'Susan01', '32a089e691dd1aab4b54d343f9158d50', 'i7psk00nql2av43pvd547dff63', '0af5258759b68c7f633195fca3aaf3f2', '', 184452864, 'susan1111223@gmail.com', '1825193239', 0),
(9, 'Francies', '93e1e792b1735e6b4fee36deb1ed1fa1', 'i7psk00nql2av43pvd547dff63', '67130dfbbfdd7d22abcac01beacd6938', '', 169452864, 'f1411223@gmail.com', '1604612708', 0),
(10, '', '25524343f656768ed8909126ad6e980c', 'jud2na4gq84t4nhnnlail2dvd7', '432c5bb255f1ae6d7dc282ae936a7bb3', '', 0, '', '1916967127', 0),
(11, 'Kirin', 'e5a50f07a58d057bc192ddc52af6d9d1', 'jud2na4gq84t4nhnnlail2dvd7', '6ca1820793faee8760bebeda65e061bc', '', 1355427753, 'kirin12@gmail.com', '863463351', 0),
(12, 'Emily', '3054fb33fe8653bb7d1c37a5c3e221cd', 'jud2na4gq84t4nhnnlail2dvd7', '0242956388a86cdcd65a3a20e57d9e60', '', 144365736, 'emilyyin', '495843630', 0),
(13, 'Catty', '64205d599761af7c9b8cd98b978831a4', 'jud2na4gq84t4nhnnlail2dvd7', '332e08d4864e3bb5ab4c95d62f82ca8f', '', 146678876, 'yin@gmail.com', '1655019646', 0),
(14, 'Catty2', 'cc9d57f1753f866b1ae770c0e5ecd8fa', 'jud2na4gq84t4nhnnlail2dvd7', 'dd4ef65825d861935697811efc1d523d', '', 1426678876, 'yin2@gmail.com', '1460453861', 0),
(15, 'Jeffery', '2ffd6e0207b53f7020a051afc97c6f72', 'jud2na4gq84t4nhnnlail2dvd7', '19c6acf8eccdb055287330aedec2637b', '', 1999973, 'nickoolej@gmai.com', '189259801', 0),
(16, 'pizzagang', '7018278e10c95f788a615cb64ed583e6', 'qnfu38vk8eg7g01fdhlqbi2m76', '3f129ceedfa04708ff0f017270026436', '', 1928349384, 'nickoletan19@gmail.com', '625804130', 0),
(17, 'pizzagang2', '7018278e10c95f788a615cb64ed583e6', 'qnfu38vk8eg7g01fdhlqbi2m76', 'fb2e13aed38f38210b2f68377e556680', '', 1928349385, 'nickoletan50@gmail.com', '797855944', 0),
(18, 'pizzagang2', '7018278e10c95f788a615cb64ed583e6', 'qnfu38vk8eg7g01fdhlqbi2m76', 'b4e85df3ed48b07c6b5b6ad5d0cc66de', '', 1928349381, 'nickoletan53@gmail.com', '145682559', 0),
(19, 'default', '6c954755367d4acd9ef5b223a3c094cb', 'vln5bin8b8ho5cg8528s37fuu7', '1c6955b2a3e31fdd82ab20b1c760cadb', '', 1234567, 'default@gmail.com', '1325531154', 0),
(20, 'stitch', '03d2220d5e7aed456ba8931078136e8e', 'vln5bin8b8ho5cg8528s37fuu7', '3e461127aece05893a0e1378e9777094', '', 626437927, 'stitch@gmail.com', '1130804697', 0),
(21, 'lilo', '06b6d90a0b7fd00d293d425cf0cc6a94', 'vln5bin8b8ho5cg8528s37fuu7', '9199cba5699948aa0e10316693a2f8f5', '', 677548685, 'lilo@gmail.com', '1999972626', 0),
(22, 'shen', 'fed8d13b12700c22a30a6bf49abe0a9b', 'vln5bin8b8ho5cg8528s37fuu7', 'bbb54dea64669d688c5aec870e618382', '', 736547879, 'shen@gmail.com', '1635443395', 0),
(23, 'nicholas', '26bc4f64c36a7acdeb4995013b438808', 'vln5bin8b8ho5cg8528s37fuu7', 'e29e2a923716b8496feec720b23ad553', '', 1892387, 'nicholas@gmail.com', '379667437', 0),
(24, 'mile', '36aa1d4757dd71a71f5d6ca46aac81ae', 'vln5bin8b8ho5cg8528s37fuu7', 'beb266a5dc45ff19640be13a81c59e6b', '', 2147483647, 'mile@gmail.com', '5577679', 0),
(25, 'lala', '6621c532584ad707a8f3b069597f5cf1', 'vln5bin8b8ho5cg8528s37fuu7', '4af1bde306bcae380be1b85fd4cdf64c', '', 2147483647, 'lala@gmail.com', '1577460365', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `ref_id` varchar(255) NOT NULL COMMENT 'for receipt purposes ',
  `delivery_name` varchar(100) DEFAULT NULL,
  `order_packages` text NOT NULL,
  `email_address` text NOT NULL,
  `phone_number` text NOT NULL,
  `address` text NOT NULL,
  `order_created_date` datetime NOT NULL,
  `amounts` decimal(10,2) NOT NULL,
  `taxs` decimal(10,2) NOT NULL COMMENT '6% services taxes',
  `descriptions` text NOT NULL,
  `token` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `ref_id`, `delivery_name`, `order_packages`, `email_address`, `phone_number`, `address`, `order_created_date`, `amounts`, `taxs`, `descriptions`, `token`) VALUES
(1, '', NULL, '', '', '', '', '0000-00-00 00:00:00', '0.00', '0.00', '', NULL),
(2, '5f954fb8ddd4d', 'nickole', '1,2,4,1,2', 'nickoletan12@gmail.com', '145560272', '3344', '2020-10-25 18:13:12', '121.16', '6.86', '123', '2a3d58a2326f5d9981293896c9fb9ca2'),
(3, '5f95515cf148b', 'nickole', '1,2,4,1,2', 'nickoletan12@gmail.com', '145560272', '33', '2020-10-25 18:20:12', '121.16', '6.86', '123', '2a3d58a2326f5d9981293896c9fb9ca2'),
(4, '5f955e70be435', 'nickole', '1,2,4,1,2', 'nickoletan12@gmail.com', '145560272', 'undefined', '2020-10-25 19:16:00', '121.16', '6.86', '12322w', '2a3d58a2326f5d9981293896c9fb9ca2'),
(5, '5f95661aa66b4', 'test', ',1,2,3,4', '', '01463573898', 'none', '2020-10-25 19:48:42', '107.59', '6.09', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(6, '5f9581a862ed7', 'nickole', '1,2,5,5', 'nickoletan12@gmail.com', '145560272', 'no33', '2020-10-25 21:46:16', '93.81', '5.31', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(7, '5f9582551118e', 'nickole', ',1,2', 'nickoletan12@gmail.com', '145560272', 'NO33', '2020-10-25 21:49:09', '40.26', '2.56', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(8, '5f9586a602ab8', 'nickole', ',1,2', 'nickoletan12@gmail.com', '145560272', 'no2134e', '2020-10-25 22:07:34', '40.26', '2.56', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(9, '5f95dcc264520', 'nickole', ',1,2', 'nickoletan12@gmail.com', '145560272', 'bagan serai', '2020-10-26 04:14:58', '45.26', '2.56', 'more cheese', '2a3d58a2326f5d9981293896c9fb9ca2'),
(10, '5f95e637726ff', 'nickole', ',1,2', 'nickoletan12@gmail.com', '145560272', 'note', '2020-10-26 04:55:19', '45.26', '2.56', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(11, '5f95e7488c336', 'nickole', ',1,2', 'nickoletan12@gmail.com', '145560272', 'test', '2020-10-26 04:59:52', '45.26', '2.56', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(12, '5f9702ea2f3ae', 'nickole', ',1,2', 'nickoletan12@gmail.com', '145560272', 'your heart', '2020-10-27 01:10:02', '45.26', '2.56', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(13, '5f9705df3ae3c', 'NICKOLE.T', '3,3', 'Nickole02@gmail.com', '0143012116', 'no8875jalantahu', '2020-10-27 01:22:39', '63.39', '3.59', '', '9644ed0e47b7004761eb51f12887bcd9'),
(14, '5f9707d4d731f', 'nickole002', ',1,2', 'nickole16@gmail.com', '0187726637', 'no3746hdsgdu', '2020-10-27 01:31:00', '45.26', '2.56', '', '9644ed0e47b7004761eb51f12887bcd9'),
(15, '5f9708372f1d4', 'TT', ',1,2', 'ERWER', '13123', 'GG', '2020-10-27 01:32:39', '45.26', '2.56', '', '9644ed0e47b7004761eb51f12887bcd9'),
(16, '5f97148f4a044', 'tyy', ',1,2,1,2', 'nnn', '123', 'iii', '2020-10-27 02:25:19', '90.52', '5.12', '123', '9644ed0e47b7004761eb51f12887bcd9'),
(17, '5f9715ff6ed3b', 'yyy', ',1,2', 'fff', '111', '12', '2020-10-27 02:31:27', '45.26', '2.56', '123', '9644ed0e47b7004761eb51f12887bcd9'),
(18, '5f9ab6db3d8d6', 'nickoletan', '1,2,1,2,1,2,1,2,1,2,1,2,88,1,2,1,2,1,2', 'nickoletabn12@gmail.com', '0143012116', 'no331775bsghhs', '2020-10-29 20:34:35', '407.36', '23.06', '', '09a21d72609a24163a550ffe0e117615'),
(19, '5f9ac09f3098f', 'nickole', ',1,2', 'nickoletan12@gmail.com', '014', 'nodgdg', '2020-10-29 21:16:15', '45.26', '2.56', '', '09a21d72609a24163a550ffe0e117615'),
(20, '5f9ac160748ad', 'sam', ',1,2,1,2,1,2', 'jsdhjsa', '01775356', 'sss', '2020-10-29 21:19:28', '135.79', '7.69', '', '09a21d72609a24163a550ffe0e117615'),
(21, '5f9acc2cd61f8', 'nickole', '1,2,5,5', 'nickoletan12@gmail.com', '145560272', 'oiajsdhi', '2020-10-29 22:05:32', '93.81', '5.31', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(22, '5f9bd45977664', 'Jeffery', ',1,2', 'nickoolej@gmai.com', '1999973', '7777', '2020-10-30 16:52:41', '120.63', '6.83', '', '19c6acf8eccdb055287330aedec2637b'),
(23, '5fa2bad5bf293', 'nickole', '1,2,5,5', 'nickoletan12@gmail.com', '145560272', 'SSSSSSSSSS', '2020-11-04 22:29:41', '93.92', '5.32', '', '2a3d58a2326f5d9981293896c9fb9ca2'),
(24, '5fbd3cb4f3e65', 'Nickole Tan', '3,2,3,2,2,2,2,2,2', 'nickoletan14@gmail.com', '0143387799', 'my home', '2020-11-25 01:02:44', '260.58', '1.60', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(25, '5fbd3d3e6bfd3', 'Nickole Tan', '3,2,3,2,2,2,2,2,2', 'nickoletan14@gmail.com', '0143387799', 'my home', '2020-11-25 01:05:02', '260.58', '1.60', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(26, '5fbd3d4c0f802', 'Nickole Tan', '3,2,3,2,2,2,2,2,2', 'nickoletan14@gmail.com', '0143387799', 'my home', '2020-11-25 01:05:16', '260.58', '1.60', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(27, '5fbd3de476ae4', 'Nickole Tan', '3,2,3,2,2,2,2,2,2', 'nickoletan14@gmail.com', '0143387799', 'my home', '2020-11-25 01:07:48', '260.58', '1.60', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(28, '5fc4975b6f52a', 'niiiii', '4,4,9,2,2,2,2,2', 'nickoletan12@gmail.com', '0166537358', 'niiiiii', '2020-11-30 14:55:23', '187.23', '10.88', '-', 'a56892b597b15de81e0916762b108fbf'),
(29, '5fc49b1d4d0ec', 'NICKO', '4,4,9,2,2,2,2,2', 'NI', '014333588762', 'HOME SY', '2020-11-30 15:11:25', '187.23', '10.88', '-', 'a56892b597b15de81e0916762b108fbf'),
(30, '5fc49bb714078', 'LAST TEST', '4,4,9,2,2,2,2,2', 'nickoletan124@gmail.com', '01556267386', 'BE KIND', '2020-11-30 15:13:59', '192.23', '10.88', '-', 'a56892b597b15de81e0916762b108fbf'),
(31, '5fc49d9830acd', 'SAM TAN', '3,6', 'TEST@GMAIL.COM', '0143012116', 'TAN TAN', '2020-11-30 15:22:00', '45.67', '2.87', '-', 'a56892b597b15de81e0916762b108fbf'),
(32, '5fc530e24d845', 'NICKOLE T', '3,2,2,9,8', 'nickole#@gmail.com', '999973667', 'MEMBER TEST', '2020-12-01 01:50:26', '93.32', '5.28', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(33, '5fc7a09eca8af', 'TESTTT', '3,19', 'nickoletan12@gmail.com', '0127765678', 'TESTTT', '2020-12-02 22:11:42', '42.59', '2.69', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(34, '5fc7ba43711d5', 'tttttt', '3,2,3,5,6', 'ttttt', '1234', 'ttttt', '2020-12-03 00:01:07', '131.86', '7.46', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(35, '5fc7bb02cc186', 'yyyy', '24,6', 'nickole@gamildh.com', '016357', 'yyyy', '2020-12-03 00:04:18', '31.69', '1.79', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(36, '5fc7bbc22f772', '', '3', '', '', '', '2020-12-03 00:07:30', '31.69', '1.79', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(37, '5fc7bc764fcc8', 'ttttt', '4', 'sdcsd', '01873668', 'pdf', '2020-12-03 00:10:30', '30.63', '1.73', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(38, '5fc7bce96cab6', 'sdfsdf', '1', 'asdsdfsdf', '', 'sdsdfsdf', '2020-12-03 00:12:25', '20.14', '1.14', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(43, '5fc8553701cc0', 'wdfdwfd', '24', 'sadsdf', '', 'wdfswdf', '2020-12-03 11:02:15', '12.72', '0.72', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(44, '5fc85592c6546', 'sdfsdf', '24', 'sdfsdf', '', 'sdfsdf', '2020-12-03 11:03:46', '12.72', '0.72', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(45, '5fc856dd0ba8d', 'sdfsdf', '3', 'sdfsdf', '', 'sdfsdf', '2020-12-03 11:09:17', '31.69', '1.79', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(46, '5fc85724ed862', 'sdfsdf', '5', 'sdfsdf', '', 'sdfsdf', '2020-12-03 11:10:28', '24.27', '1.37', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(47, '5fc85756f2ac1', 'SDFVSCV', '11,10,9', 'SVCV', '', 'SDCFSV', '2020-12-03 11:11:18', '13.09', '0.74', '-', '2a3d58a2326f5d9981293896c9fb9ca2'),
(48, '5fc87d1f31f32', 'def', '4', 'df', '3433434', 'pdf', '2020-12-03 13:52:31', '30.63', '1.73', '-', 'a56892b597b15de81e0916762b108fbf'),
(49, '5fc87d9a68cc3', 'sdfsdf', '3', 'sdvfdfv@isjdhf.com', '123', 'sdfsdf', '2020-12-03 13:54:34', '31.69', '1.79', '-', 'a56892b597b15de81e0916762b108fbf'),
(50, '5fc87e9bb806b', 'NEW', '24,16', 'NEW', '113', 'NEW', '2020-12-03 13:58:51', '22.25', '1.26', '-', 'a56892b597b15de81e0916762b108fbf');

-- --------------------------------------------------------

--
-- Table structure for table `orders_trx`
--

CREATE TABLE `orders_trx` (
  `orders_trx_id` int(11) NOT NULL,
  `tracking_number` varchar(20) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `session_id` text NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-received, 2-pending, 3-delivering, 4-completed'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_trx`
--

INSERT INTO `orders_trx` (`orders_trx_id`, `tracking_number`, `member_id`, `session_id`, `order_id`, `status`) VALUES
(1, 'DOT-1556414298', 6, 'i7psk00nql2av43pvd547dff63', 4, 4),
(2, 'DOT-1743932597', 6, 'i7psk00nql2av43pvd547dff63', 5, 4),
(3, 'DOT-19295007', 6, 'i7psk00nql2av43pvd547dff63', 6, 4),
(4, 'DOT-1164832542', 6, 'i7psk00nql2av43pvd547dff63', 7, 4),
(5, 'DOT-1810701828', 6, 'i7psk00nql2av43pvd547dff63', 8, 4),
(6, 'DOT-147583914', 6, 'i7psk00nql2av43pvd547dff63', 9, 1),
(7, 'DOT-288587536', 6, 'i7psk00nql2av43pvd547dff63', 10, 1),
(8, 'DOT-1236966832', 6, 'i7psk00nql2av43pvd547dff63', 11, 1),
(9, 'DOT-1066222189', 6, 'i7psk00nql2av43pvd547dff63', 12, 1),
(10, 'DOT-1292080047', NULL, 'i7psk00nql2av43pvd547dff63', 13, 1),
(11, 'DOT-615441733', NULL, 'i7psk00nql2av43pvd547dff63', 14, 1),
(12, 'DOT-337984971', NULL, 'i7psk00nql2av43pvd547dff63', 15, 1),
(13, 'DOT-1753221873', NULL, 'i7psk00nql2av43pvd547dff63', 16, 1),
(14, 'DOT-1997116745', NULL, 'i7psk00nql2av43pvd547dff63', 17, 1),
(15, 'DOT-722019542', NULL, 'jud2na4gq84t4nhnnlail2dvd7', 18, 1),
(16, 'DOT-163483836', NULL, 'jud2na4gq84t4nhnnlail2dvd7', 19, 2),
(17, 'DOT-1957172237', NULL, 'jud2na4gq84t4nhnnlail2dvd7', 20, 1),
(18, 'DOT-293681251', 6, 'jud2na4gq84t4nhnnlail2dvd7', 21, 1),
(19, 'DOT-1771111445', 15, 'jud2na4gq84t4nhnnlail2dvd7', 22, 1),
(20, 'DOT-1393928455', 6, 'et5srp1isih98aoporsvcpo2l4', 23, 1),
(21, 'DOT-59576081', 6, 'qnfu38vk8eg7g01fdhlqbi2m76', 27, 1),
(22, 'DOT-1204472394', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 28, 1),
(23, 'DOT-862393943', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 29, 4),
(24, 'DOT-1605813611', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 30, 1),
(25, 'DOT-1998396755', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 31, 2),
(26, 'DOT-1970773120', 6, 'vln5bin8b8ho5cg8528s37fuu7', 32, 2),
(27, 'DOT-2042788785', 6, 'vln5bin8b8ho5cg8528s37fuu7', 33, 1),
(28, 'DOT-23597475', 6, 'vln5bin8b8ho5cg8528s37fuu7', 34, 1),
(29, 'DOT-1323408724', 6, 'vln5bin8b8ho5cg8528s37fuu7', 35, 1),
(30, 'DOT-91398161', 6, 'vln5bin8b8ho5cg8528s37fuu7', 36, 1),
(31, 'DOT-11996232', 6, 'vln5bin8b8ho5cg8528s37fuu7', 37, 1),
(32, 'DOT-1107595986', 6, 'vln5bin8b8ho5cg8528s37fuu7', 38, 1),
(37, 'DOT-1859099462', 6, 'vln5bin8b8ho5cg8528s37fuu7', 43, 1),
(38, 'DOT-1571201615', 6, 'vln5bin8b8ho5cg8528s37fuu7', 44, 1),
(39, 'DOT-1614108689', 6, 'vln5bin8b8ho5cg8528s37fuu7', 45, 1),
(40, 'DOT-887461012', 6, 'vln5bin8b8ho5cg8528s37fuu7', 46, 1),
(41, 'DOT-1516940380', 6, 'vln5bin8b8ho5cg8528s37fuu7', 47, 1),
(42, 'DOT-1301502556', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 48, 1),
(43, 'DOT-721642484', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 49, 1),
(44, 'DOT-656179523', NULL, 'vln5bin8b8ho5cg8528s37fuu7', 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` text NOT NULL,
  `product_attachs` text,
  `product_description` text NOT NULL,
  `product_type` tinyint(4) NOT NULL COMMENT '1-small,2-medium,3-large',
  `product_price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0-nonpublish, 1-publish'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_attachs`, `product_description`, `product_type`, `product_price`, `category_id`, `status`) VALUES
(1, 'Aloha Chicken Pizza (S)', '5585f0b8-9291-44e0-af3a-35e541793fd5.jpg', 'pdf', 1, '19.00', 1, 1),
(2, 'Aloha Pizza (M)', '10cff22e-1515-4c72-af5e-4bdbdf760601.jpg', 'Chicken Roll, Pineapple & Double Cheese ', 2, '23.80', 1, 1),
(3, 'Aloha Pizza (L)', '939a855b-3e72-4dcf-abc0-e2985a6658eb.jpg', 'Chicken Roll, Pineapple & Double Cheese', 3, '29.90', 1, 1),
(4, 'Chicken Classic Pizza (S)', '1d6e7f94-27d9-4e78-acd4-66fe7ffd4ed8.jpg', 'Chicken, Mushroom & Double Cheese', 3, '28.90', 1, 1),
(5, 'Chicken Classic Pizza (M)', '4bbb9921-c6ff-48d7-b14f-aca2129d29d6.jpg', 'Chicken, Mushroom & Double Cheese', 2, '22.90', 1, 1),
(6, 'Chicken Classic Pizza (L)', '1d6e7f94-27d9-4e78-acd4-66fe7ffd4ed8.jpg', 'Chicken, Mushroom & Double Cheese', 1, '17.90', 1, 1),
(7, 'Blueberry Cheesecake', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'SOFT DRINK', 2, '12.00', 3, 1),
(8, 'Chocolate Lava Cake', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'SOFT DRINK', 2, '5.99', 3, 1),
(9, '7-UP SOFT', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'SOFT DRINK', 2, '4.55', 4, 1),
(10, 'Lipton Ice Tea-Lemon', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'SOFT DRINK', 2, '3.55', 4, 1),
(11, 'Twister Orange', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'SOFT DRINK', 2, '4.25', 4, 1),
(16, 'Fruit Salads Side', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'Included Fruited Style and Mayo', 2, '8.99', 2, 1),
(17, 'Dessert Salads', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'Random Fruited Style Leaf', 2, '10.99', 2, 1),
(18, 'Potato Salads Sides', '00b6e100-ac90-4fcf-8b88-be256192caed.jpg', 'Full of Potato and Indian Style Sources', 2, '7.99', 2, 1),
(24, 'new', '1976e8af870d919a6b5ad59aaea98fc1.gif', 'oo', 1, '12.00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `category_id` int(11) NOT NULL,
  `category_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`category_id`, `category_name`) VALUES
(1, 'Pizzas'),
(2, 'Salads'),
(3, 'Desserts'),
(4, 'Beverages '),
(5, 'Sides');

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE `promotion` (
  `promotion_id` int(11) NOT NULL,
  `promotion_name` text NOT NULL,
  `promotion_start` datetime NOT NULL,
  `promotion_end` datetime NOT NULL,
  `promo_code` varchar(100) NOT NULL,
  `promo_attachment` text,
  `require_quanlity` int(11) DEFAULT NULL COMMENT 'limits for certain cust. quantity',
  `require_roles` text COMMENT 'Member/Public',
  `descriptions` text NOT NULL,
  `promotion_rate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`promotion_id`, `promotion_name`, `promotion_start`, `promotion_end`, `promo_code`, `promo_attachment`, `require_quanlity`, `require_roles`, `descriptions`, `promotion_rate_id`) VALUES
(1, 'MERDEKA LINE 25% DISCOUNT ', '2020-10-01 00:00:00', '2020-12-16 00:00:00', 'XXY-PANDA', 'IMG/ATTACH.PNG', NULL, 'MEMBER', 'MIN SPEND RM20.T$C APPLY', 1),
(2, 'RM5 DELIVERY DISCOUNT COUPON', '2020-10-01 00:00:00', '2021-10-31 00:00:00', 'PAN-U20', 'IMG/ATTACH.PNG', NULL, NULL, 'MIN RM20 SPEND AND ETC.', 1),
(3, 'RM5 SUPRICES DELIVERY DISCOUNT COUPON', '2020-09-01 00:00:00', '2020-11-18 00:00:00', 'NOV-BB', 'IMG/ATTACH.PNG', NULL, 'MEMBER', 'MIN RM20 SPEND AND ETC.', 1),
(4, 'RM5 SUPRICES DELIVERY DISCOUNT ', '2020-10-12 00:00:00', '2020-12-12 00:00:00', 'XXT-223', NULL, 0, 'MEMBER', 'MIN RM20 SPEND AND ETC.', 2),
(7, 'Double 12 Birthday ', '2020-12-01 00:00:00', '2020-12-31 00:00:00', 'D12-2020', NULL, NULL, NULL, 'TESTING ', 1),
(8, 'Test', '2020-12-01 00:00:00', '2020-12-31 00:00:00', 'WOW2020', NULL, NULL, NULL, 'TEST', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotion_log`
--

CREATE TABLE `promotion_log` (
  `promotion_log_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `promotion_code` text NOT NULL,
  `promotion_used_dated` datetime NOT NULL,
  `token` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_log`
--

INSERT INTO `promotion_log` (`promotion_log_id`, `order_id`, `promotion_code`, `promotion_used_dated`, `token`) VALUES
(1, 8, 'XXY-PANDA', '2020-10-25 22:07:34', '2a3d58a2326f5d9981293896c9fb9ca2'),
(2, 13, 'XF827', '2020-10-27 01:22:39', '9644ed0e47b7004761eb51f12887bcd9'),
(3, 14, 'XYY-PANDA', '2020-10-27 01:31:00', '9644ed0e47b7004761eb51f12887bcd9'),
(4, 15, 'DD12DD', '2020-10-27 01:32:39', '9644ed0e47b7004761eb51f12887bcd9'),
(5, 16, 'YIXX-98', '2020-10-27 02:25:19', '9644ed0e47b7004761eb51f12887bcd9'),
(6, 17, 'XY77', '2020-10-27 02:31:27', '9644ed0e47b7004761eb51f12887bcd9'),
(7, 18, 'XXY-PAANDA', '2020-10-29 20:34:35', '09a21d72609a24163a550ffe0e117615'),
(8, 22, 'XXX', '2020-10-30 16:52:41', '19c6acf8eccdb055287330aedec2637b'),
(9, 23, 'AAA', '2020-11-04 22:29:41', '2a3d58a2326f5d9981293896c9fb9ca2'),
(10, 29, 'XXY-PANDA', '2020-11-30 15:11:25', 'a56892b597b15de81e0916762b108fbf'),
(11, 31, 'AAA', '2020-11-30 15:22:00', 'a56892b597b15de81e0916762b108fbf'),
(12, 33, 'testtt', '2020-12-02 22:11:42', '2a3d58a2326f5d9981293896c9fb9ca2');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_rate`
--

CREATE TABLE `promotion_rate` (
  `promo_rate_id` int(11) NOT NULL,
  `promo_rate_type` varchar(10) NOT NULL,
  `rate_value` int(11) NOT NULL,
  `min_limit_spend` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotion_rate`
--

INSERT INTO `promotion_rate` (`promo_rate_id`, `promo_rate_type`, `rate_value`, `min_limit_spend`) VALUES
(1, 'RM', 5, 20),
(2, 'RM', 10, 30),
(3, 'RM', 15, 20),
(4, 'RM', 20, 50),
(5, 'RM', 30, 20),
(6, 'RM', 9, 30);

-- --------------------------------------------------------

--
-- Table structure for table `visitor_log`
--

CREATE TABLE `visitor_log` (
  `visitor_id` int(11) NOT NULL,
  `session_ids` text NOT NULL,
  `is_members` tinyint(4) NOT NULL COMMENT '0-nonmember, 1-member, 2-admin',
  `acc_no` text,
  `log_status` tinyint(4) NOT NULL COMMENT '0-logout,1-login,2-active,3-admin_manage',
  `modified_log_time` datetime NOT NULL COMMENT 'latest modified time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visitor_log`
--

INSERT INTO `visitor_log` (`visitor_id`, `session_ids`, `is_members`, `acc_no`, `log_status`, `modified_log_time`) VALUES
(1, 't2mjbj5e1f6pi1u4m6sm38667b0', 1, '', 2, '2020-10-12 18:09:12'),
(2, 't5mjbj5e1f6bi1u4m6kf3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(3, 't2mjbj5jfri6pi1u4m6sm3866b2', 1, '', 2, '2020-10-12 17:56:44'),
(4, 't2mmbj5e1f6si1u4m6dsm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(5, 't2mjbj5e1f6pi1um6sk3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(6, 't2mtbj5e1f7pi1u4m8sm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(7, 'm9mjbj5g1f6pi1u4m6sm3166b0', 1, '', 2, '2020-10-12 17:56:44'),
(8, 't2mjbj5e1f6pi1d4u4m6sadsm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(9, 't2mjbj5jdn1f6pi1u49svm6sm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(10, 'a2mjbj5e1f6pizal1u4m6sm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(11, 't2mjbj5e1f6plis1u4m6sms3d8d6d6b0', 1, '', 2, '2020-10-12 17:56:44'),
(12, 't2mjbj5e1f6pdi1u4ms6sm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(13, 'p2dmjbj5e1f6pi1u4m6sm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(14, 't2mjbqj5e1f6pid1u4m6ssm3866b0', 1, '', 2, '2020-10-12 17:56:44'),
(15, 'awdjh24bh23hb2bh1bhj4bhji8b2b', 1, '', 2, '2020-10-12 17:56:44'),
(16, 't2mjbj5e1f6pi1u4m6sm3866b0', 1, '', 2, '2020-10-12 19:27:58'),
(17, 't2mjbj5e1f6pi1u4m6sm3866b0', 1, '', 2, '2020-10-12 19:27:58'),
(18, 't2mjbj5e1f6pi1u4m6sm3866b0', 1, '', 2, '2020-10-12 19:27:58'),
(19, 't2mjbj5e1f6pi1u4m6sm3866b0', 1, '', 2, '2020-10-12 19:27:58'),
(20, 'n2nf9r7s6s5t7rcst024uk82h3s', 0, '', 2, '2020-10-14 00:34:05'),
(21, 'n2nf9r7s6s5t7rcst024uk82h3e', 0, '', 2, '2020-10-14 00:35:11'),
(22, 'n2nf9r7s6s5t7rcst024uk82h3', 1, '1978005798', 1, '2020-10-14 13:47:48'),
(23, 'i7psk00nql2av43pvd547dff632', 0, '', 2, '2020-10-22 11:26:57'),
(24, 'i7psk00nql2av43pvd547dff63', 2, NULL, 3, '2020-10-27 17:16:11'),
(25, 'jud2na4gq84t4nhnnlail2dvd7', 0, NULL, 2, '2020-10-30 18:25:01'),
(26, 'et5srp1isih98aoporsvcpo2l4', 1, '2038115141', 1, '2020-11-04 22:38:15'),
(27, 'qnfu38vk8eg7g01fdhlqbi2m76', 1, '2038115141', 1, '2020-11-24 16:07:14'),
(28, 'qnfu38vk8eg7g01fdhlqbi2m76', 1, '2038115141', 1, '2020-11-24 16:07:14'),
(29, 'qnfu38vk8eg7g01fdhlqbi2m76', 1, '2038115141', 1, '2020-11-24 16:07:14'),
(30, 'qnfu38vk8eg7g01fdhlqbi2m76', 1, '2038115141', 1, '2020-11-24 16:07:14'),
(31, 'vln5bin8b8ho5cg8528s37fuu7', 1, '2038115141', 1, '2020-12-04 21:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `cart_id` int(11) NOT NULL,
  `acc_no_encrypted` text,
  `session_id` text NOT NULL,
  `package_ids` text NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`cart_id`, `acc_no_encrypted`, `session_id`, `package_ids`, `updated_time`) VALUES
(1, '2a3d58a2326f5d9981293896c9fb9ca2', 'i7psk00nql2av43pvd547dff63', '11,10,9,3', '2020-12-03 13:36:42'),
(2, NULL, 'i7psk00nql2av43pvd547dff63', '1,2', '2020-10-25 22:17:21'),
(3, NULL, 'i7psk00nql2av43pvd547dff63', '1,2', '2020-10-25 22:17:30'),
(4, NULL, 'i7psk00nql2av43pvd547dff63', '1,2', '2020-10-26 23:10:57'),
(5, NULL, 'i7psk00nql2av43pvd547dff63', '1,2', '2020-10-26 23:13:00'),
(6, NULL, 'i7psk00nql2av43pvd547dff63', '1,2', '2020-10-26 23:13:25'),
(7, '9644ed0e47b7004761eb51f12887bcd9', 'i7psk00nql2av43pvd547dff63', '', '2020-10-26 23:19:17'),
(8, '09a21d72609a24163a550ffe0e117615', 'jud2na4gq84t4nhnnlail2dvd7', ',1,2', '2020-10-29 20:05:58'),
(9, NULL, 'jud2na4gq84t4nhnnlail2dvd7', '1,2', '2020-10-30 16:16:36'),
(10, NULL, 'jud2na4gq84t4nhnnlail2dvd7', '1,2', '2020-10-30 16:16:43'),
(11, NULL, 'jud2na4gq84t4nhnnlail2dvd7', '1,2', '2020-10-30 16:16:54'),
(12, NULL, 'jud2na4gq84t4nhnnlail2dvd7', '4,7,8', '2020-10-30 16:17:04'),
(13, '19c6acf8eccdb055287330aedec2637b', 'jud2na4gq84t4nhnnlail2dvd7', '1,2,5,5', '2020-10-30 16:39:20'),
(14, NULL, 'qnfu38vk8eg7g01fdhlqbi2m76', '1,2', '2020-11-24 17:18:14'),
(15, 'd41d8cd98f00b204e9800998ecf8427e', 'qnfu38vk8eg7g01fdhlqbi2m762', '1,2,2,5', '2020-11-24 14:06:37'),
(16, NULL, 'vln5bin8b8ho5cg8528s37fuu7', '', '2020-12-03 13:58:51'),
(17, '630dc06de6f6ea9b02453c24d92d24d8', 'vln5bin8b8ho5cg8528s37fuu7', '24,6,5', '2020-12-03 13:24:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`memberid`),
  ADD UNIQUE KEY `acc_no` (`acc_no`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `orders_trx`
--
ALTER TABLE `orders_trx`
  ADD PRIMARY KEY (`orders_trx_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `promotion_log`
--
ALTER TABLE `promotion_log`
  ADD PRIMARY KEY (`promotion_log_id`);

--
-- Indexes for table `promotion_rate`
--
ALTER TABLE `promotion_rate`
  ADD PRIMARY KEY (`promo_rate_id`);

--
-- Indexes for table `visitor_log`
--
ALTER TABLE `visitor_log`
  ADD PRIMARY KEY (`visitor_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`cart_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `memberid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `orders_trx`
--
ALTER TABLE `orders_trx`
  MODIFY `orders_trx_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `promotion_log`
--
ALTER TABLE `promotion_log`
  MODIFY `promotion_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `promotion_rate`
--
ALTER TABLE `promotion_rate`
  MODIFY `promo_rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `visitor_log`
--
ALTER TABLE `visitor_log`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
