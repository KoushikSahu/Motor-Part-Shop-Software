-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3325
-- Generation Time: Apr 30, 2020 at 08:24 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(5) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `Username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(5, 'user1@fachione.com', '24c9e15e52afc47c225b757e7bee1f9d'),
(6, 'test2@fachione.com', 'ad0234829205b9033196ba818f7a872b'),
(7, 'user3@fachione.com', '92877af70a45fd6a2ed7fe81e1236b78');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(5) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `Username`, `password`) VALUES
(3, 'admin1@fachione.com', 'e00cf25ad42683b3df678c61f42c6bda'),
(4, 'admin2@fachione.com', 'c84258e9c39059a89ab77d846ddab909');

-- --------------------------------------------------------

--
-- Table structure for table `admininfo`
--

CREATE TABLE `admininfo` (
  `id` int(50) NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `zip` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admininfo`
--

INSERT INTO `admininfo` (`id`, `firstName`, `lastName`, `city`, `state`, `zip`) VALUES
(3, 'admin1', 'test', 'delhi', '...', 12142),
(4, 'admin2', 'asdasd', 'bangalore', '...', 3123);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `orderID` int(255) NOT NULL,
  `userID` int(255) NOT NULL,
  `pdetail` varchar(255) NOT NULL,
  `purchasedate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`orderID`, `userID`, `pdetail`, `purchasedate`) VALUES
(1, 6, '', '2020-04-29'),
(2, 6, '', '2020-04-29'),
(3, 7, '', '2020-04-29'),
(4, 7, '', '2020-04-30');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `gender` text NOT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(5) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category`, `gender`, `pname`, `image`, `quantity`, `price`, `added`) VALUES
(1, 'clothing', 'women', 'Orange Dress', 'img/single-product/1/', 10, 20, '2020-04-24'),
(2, 'footwear', 'women', 'Flat Heels', 'img/single-product/2/', 10, 1999, '2020-04-24'),
(3, 'clothing', 'women', 'Maroon Dress', 'img/single-product/3/', 5, 999, '2020-04-24'),
(4, 'footwear', 'women', 'Grey Heels', 'img/single-product/4/', 5, 1449, '2020-04-25'),
(5, 'clothing', 'women', 'Black Dress', 'img/single-product/5/', 7, 899, '2020-04-25'),
(6, 'footwear', 'women', 'Black Heels', 'img/single-product/6/', 15, 1199, '2020-04-26'),
(7, 'clothing', 'women', 'Stripe Shirt', 'img/single-product/7/', 20, 2499, '2020-04-26'),
(8, 'footwear', 'women', 'Sport Shoe', 'img/single-product/8/', 0, 2499, '2020-04-27'),
(9, 'clothing', 'women', 'Red Dress', 'img/single-product/9/', 4, 1999, '2020-04-27'),
(10, 'footwear', 'women', 'Black Heels', 'img/single-product/10/', 10, 799, '2020-04-28'),
(11, 'footwear', 'women', 'Maroon Heels', 'img/single-product/11/', 4, 1299, '2020-04-28'),
(12, 'clothing', 'men', 'green t-shirt', 'img/single-product/12/', 30, 999, '2020-04-29'),
(36, 'jewellery', 'women', 'damru jhumka', 'img/single-product/36/', 10, 199, '2020-04-29'),
(37, 'clothing', 'women', 'Dark Denim Jacket', 'img/single-product/37/', 19, 999, '2020-04-29'),
(38, 'clothing', 'men', 'Yellow T-shirt', 'img/single-product/38/', 22, 1399, '2020-04-29'),
(39, 'clothing', 'men', 'Pink T-shirt', 'img/single-product/39/', 15, 799, '2020-04-29'),
(40, 'jewellery', 'women', 'Bangles', 'img/single-product/40/', 25, 499, '2020-04-29'),
(41, 'footwear', 'men', 'Sneakers', 'img/single-product/41/', 10, 1499, '2020-04-29'),
(42, 'footwear', 'men', 'Cassedrals', 'img/single-product/42/', 12, 2499, '2020-04-29'),
(43, 'footwear', 'men', 'Woodlands', 'img/single-product/43/', 10, 3325, '2020-04-29');

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `id` int(255) NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text DEFAULT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `zip` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`id`, `firstName`, `lastName`, `city`, `state`, `zip`) VALUES
(5, 'user1', 'test', 'Bhubaneswar', 'Odisha', 123451),
(6, 'user2', 'test', 'cuttack', 'Odisha', 12314),
(7, 'user3', 'asdad', 'bhadrak', 'Odisha', 1231);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`);

--
-- Indexes for table `admininfo`
--
ALTER TABLE `admininfo`
  ADD KEY `id` (`id`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`orderID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admininfo`
--
ALTER TABLE `admininfo`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `orderID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
