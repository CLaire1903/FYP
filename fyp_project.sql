-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2022 at 02:10 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_email` varchar(100) NOT NULL,
  `admin_pword` varchar(20) NOT NULL,
  `admin_fname` varchar(20) NOT NULL,
  `admin_lname` varchar(20) NOT NULL,
  `admin_address` text NOT NULL,
  `admin_phnumber` varchar(12) NOT NULL,
  `admin_gender` enum('male','female') NOT NULL,
  `admin_bday` date NOT NULL,
  `admin_position` enum('director','manager','senior','junior','intern') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_email`, `admin_pword`, `admin_fname`, `admin_lname`, `admin_address`, `admin_phnumber`, `admin_gender`, `admin_bday`, `admin_position`) VALUES
('abc@gmail.com', 'Abc_12345', 'Andrew', 'Donald', 'abcdefghijklmnopqrstuvwxyz', '012-3456789', 'male', '1996-04-02', 'director'),
('alex@abc.com', 'Abc_1234567', 'Fiona', 'Windy', '13142 lorong bongok taman melawati 23888 chansi johor', '012-7824243', 'female', '2005-01-24', 'senior'),
('john@abc.com', 'john_123', 'John', 'Donald', '123 lorong abc kampung rambutan, 51234, jonker Melaka ', '012-3456789', 'male', '1994-08-10', 'intern');

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `designer_email` varchar(100) NOT NULL,
  `award_name` varchar(100) NOT NULL,
  `award_year` year(4) NOT NULL,
  `award_country` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `award`
--

INSERT INTO `award` (`designer_email`, `award_name`, `award_year`, `award_country`) VALUES
('Abc@google.com', 'Designer Excellent Award', 2021, 'Malaysia'),
('Abc@google.com', 'Designer Excellent Award', 2022, 'Malaysia'),
('abc@google.com', 'Vienna Award', 2021, 'Budapest'),
('alex@hotmail.com', 'IDA Design Awards', 2022, 'Budapest');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `product_id` int(5) NOT NULL,
  `cus_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(5) NOT NULL,
  `category_image` varchar(50) DEFAULT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_image`, `category_name`, `category_description`) VALUES
(1, '/fyp/image/category/category1.jpg', 'Mermaid Gown', 'The mermaid style is yet another timeless one and is still very trendy this season. Mermaid gown style is fitted till the knee or calves and then tapers to become a full skirt or trail. This style looks extremely elegant with a low-cut back that emphasizes the figure and the curvature of the back and hips. That’s why it’s perfect for hourglass women and even some pear-shaped women. Mermaid gowns have always been a party favourite, so choose for engagement, galas or black-tie events.\r\n\r\n'),
(2, '/fyp/image/category/category2.jpg', 'Empire Waist Gowns', 'Empire-waist dresses are extremely trendy and give a royal look. Empire-waist gowns have a high waistline, just below the breasts. They completely hide the tummy and work great for diamond and pear-shaped women. Even pregnant women prefer this style. If you want to enhance your body shape and look flawless, go for this mesmerizing trend.'),
(3, '/fyp/image/category/category3.jpg', 'A-Line Gowns', 'A-line gown is simple and elegant and suits all body shapes. It has fitted bodice until the waist and flows out to the ground in an A-line. They are the perfect choice when you want a minimalist silhouette like for new year’s parties or as bridesmaid dresses.'),
(4, '/fyp/image/category/category4.jpg', 'Modified A-Line Gowns', 'This style is just a modified version of the A-line dress. It’s fitted through the bodice and hips and gradually flares to the hem forming an ‘A’ shape. The modified A-line dress fits closer to the body than a traditional A-line. The curve-hugging silhouette is perfect for those with shapely waists, like an hourglass, or pear-shaped women who have a toned belly! Strawberry shapes can rock it, too! Rectangular-shaped ladies can also find these flattering with a belt.'),
(5, '/fyp/image/category/category5.jpg', 'Trumpet Gowns', 'This style is fitted through the body, and flares at the thighs. A great option for women who have small waists, such as hourglass and petite. Not a good option for pear-shaped bodies. It’s great for strawberry-shaped women too because the flare will give a balanced effect.'),
(6, '/fyp/image/category/category6.jpg', 'Sheath Gowns', 'This style of gown has a straight silhouette. It goes straight down from the hip to the hem with little or no flare. Sometimes a slight flare is added, tapering at the heels to become a long trail. A great option for Petites and hourglass shapes. Even rectangle-shaped women can rock belted sheath gown styles.'),
(7, '/fyp/image/category/category7.jpg', 'Ball Gown', 'Ball gowns are dresses with a fitted bodice, which flair at the waist with a floor-touching skirt. Ideal for most body types but looks great on pears since it hides the lower body. The poofy skirts may be too overpowering on petite women’s frames. So if you’re petite, select a ball gown with less volume.\r\nChoose a classic colour with lustrous fabrics and rich details. A ball gown is a great option for evening wear that’ll never go out of style. Plus they’re great if you want all eyes on you – like your wedding and engagement.\r\n\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `checkout_id` int(4) NOT NULL,
  `cus_email` varchar(100) NOT NULL,
  `checkout_totalamount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `checkout_detail`
--

CREATE TABLE `checkout_detail` (
  `checkout_id` int(4) NOT NULL,
  `product_id` int(6) NOT NULL,
  `product_totalamount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cus_email` varchar(100) NOT NULL,
  `cus_pword` varchar(30) NOT NULL,
  `cus_cpword` varchar(30) NOT NULL,
  `cus_fname` varchar(30) NOT NULL,
  `cus_lname` varchar(30) NOT NULL,
  `cus_address` text NOT NULL,
  `cus_phnumber` varchar(12) NOT NULL,
  `cus_gender` enum('male','female') NOT NULL,
  `cus_bday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cus_email`, `cus_pword`, `cus_cpword`, `cus_fname`, `cus_lname`, `cus_address`, `cus_phnumber`, `cus_gender`, `cus_bday`) VALUES
('claire.t.j.h.1903@gmail.com', 'Claire_123', 'Claire_123', 'Claire', 'Johnny', '63 jalan abc taman matahari', '012-9876543', 'male', '2003-01-21'),
('clairetang1903@hotmail.com', 'Claire_12345', 'Claire_12345', 'Claire', 'William', '123 Jalan abc Taman Berjaya', '012-3456789', 'female', '1999-03-16'),
('johnny@gmail.com', 'Johnny_123', 'Johnny_123', 'Johnny', 'Ong', '123 lorong abc taman cde 12300 fgh sabah', '012-3456789', 'male', '2000-12-12'),
('tanghuey@hotmail.com', 'Claire_123', 'Claire_123', 'Tang ', 'Jia Huey', '910 new era university college student hostel block b&amp;c blok 5 seksyen 10 jalan bukit 43000 kajang selangor', '012-9876543', 'female', '1999-03-19');

-- --------------------------------------------------------

--
-- Table structure for table `customized`
--

CREATE TABLE `customized` (
  `customized_id` int(7) NOT NULL,
  `customized_image` varchar(100) DEFAULT NULL,
  `cus_email` varchar(50) NOT NULL,
  `cus_name` varchar(50) NOT NULL,
  `cus_phnumber` varchar(12) NOT NULL,
  `customized_detail` text NOT NULL,
  `customized_collectdate` date NOT NULL,
  `designer_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customized`
--

INSERT INTO `customized` (`customized_id`, `customized_image`, `cus_email`, `cus_name`, `cus_phnumber`, `customized_detail`, `customized_collectdate`, `designer_email`) VALUES
(1000001, '../image/customized/default.jpg', 'abc@gmail.com', 'Abc', '012-3456789', 'Ball gown', '2023-07-26', 'alex@hotmail.com'),
(1000007, '../image/customized/ID1000007_1661681708.jpg', 'clairetang1903@gmail.com', 'Claire', '012-3456789', 'A ball gown with flowers and is pink in colour', '2023-02-28', 'jamie@hotmail.com'),
(1000008, '../image/customized/default.jpg', 'clairetang1903@gmail.com', 'Claire', '011-23456789', 'A ball gown with flower and is blue in colour', '2023-07-11', NULL),
(1000009, '../image/customized/default.jpg', 'claire.t.j.h.1903@gmail.com', 'Claire', '012-34567890', 'Black gown', '2023-04-26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `designer`
--

CREATE TABLE `designer` (
  `designer_image` varchar(100) DEFAULT NULL,
  `designer_email` varchar(100) NOT NULL,
  `designer_pword` varchar(30) NOT NULL,
  `designer_fname` varchar(20) NOT NULL,
  `designer_lname` varchar(20) NOT NULL,
  `designer_gender` enum('male','female') NOT NULL,
  `designer_phnumber` varchar(12) NOT NULL,
  `designer_qualification` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `designer`
--

INSERT INTO `designer` (`designer_image`, `designer_email`, `designer_pword`, `designer_fname`, `designer_lname`, `designer_gender`, `designer_phnumber`, `designer_qualification`) VALUES
('../image/designer/abc@google.com_1661672312.jpg', 'abc@google.com', 'Abc_123456', 'Alice', 'Backhem', 'female', '012-3456789', 'Bachelor of (HONS) in fashion designing'),
('../image/designer/alex@hotmail.com_1661761968.jpg', 'alex@hotmail.com', 'Abc_12345678', 'Alex', 'Johnson', 'male', '012-3456789', 'Master in Fashion Design'),
('../image/designer/default.png', 'andy@hotmail.com', 'Andy_123456', 'Andy', 'Howard', 'male', '012-3456789', 'Master in Fashion Design (HONS)'),
('../image/designer/jamie@hotmail.com_1661349330.jpg', 'jamie@hotmail.com', 'Jamie_123456', 'Jamie', 'Foo', 'female', '012-3456789', 'Master in Fashion Design (HONS)'),
(NULL, 'jane@google.com', 'Jane_12345', 'Jane', 'Backhem', 'female', '012-3456789', 'Master in Fashion Design (HONS)'),
('../image/designer/default.png', 'john@abc.com', 'abc_123', 'John', 'Medson', 'male', '012-3456789', 'Master of Fashion Design');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `faq_id` int(5) NOT NULL,
  `faq_question` text NOT NULL,
  `faq_answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`faq_id`, `faq_question`, `faq_answer`) VALUES
(1002, 'How much is the deposit should pay for each gown?', 'At least 30% for the ready stock gown.'),
(1004, 'Where is the physical store?', 'The physical store location can be found on the &quot;Contact Us&quot; page.'),
(1005, 'How long it should take for a custom made gown?', 'Depends on the gown requirement and the complexity of the gown. We recommend our customers to order at least 6 months before the collection date.'),
(1006, 'How many designer will work on a custom made order\'s design?', 'Currently, due to a lack of designers, only one designer will be assigned to each custom made order');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(5) NOT NULL,
  `cus_email` varchar(50) NOT NULL,
  `feedback_detail` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `cus_email`, `feedback_detail`) VALUES
(1001, 'abc@gmail.com', 'I love your design.'),
(1007, 'clairetang1903@hotmail.com', 'abc'),
(1009, 'clairetang1903@hotmail.com', 'I like your designer\'s style. Good service.'),
(1010, 'clairetang1903@hotmail.com', 'I like your designer\'s design, and good service.'),
(1011, 'clairetang1903@hotmail.com', 'I love your service.'),
(1012, 'clairetang1903@hotmail.com', 'I like all of the designs.'),
(1013, 'tanghuey@hotmail.com', 'I like your services');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(5) NOT NULL,
  `order_datentime` datetime NOT NULL,
  `cus_email` varchar(100) NOT NULL,
  `order_totalamount` double NOT NULL,
  `order_depositpaid` double NOT NULL,
  `shipping_name` varchar(100) NOT NULL,
  `shipping_phnumber` varchar(12) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_postcode` int(5) NOT NULL,
  `order_status` enum('new order','payment received','payment failed','processing','shipping','cancelled','completed') NOT NULL,
  `order_paymethod` enum('online','ewallet','','') NOT NULL,
  `payment_reference` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_datentime`, `cus_email`, `order_totalamount`, `order_depositpaid`, `shipping_name`, `shipping_phnumber`, `shipping_address`, `shipping_postcode`, `order_status`, `order_paymethod`, `payment_reference`) VALUES
(10001, '2022-08-14 11:44:59', 'clairetang1903@hotmail.com', 1500, 1000, 'claire', '012-3456789', '63 jln abc kampung def 34000 Taiping perak', 34000, 'new order', 'online', NULL),
(10002, '2022-08-14 11:49:28', 'clairetang1903@hotmail.com', 1000, 500, 'Tang Jia Huey', '012-3456789', '63 jalan unta kawasan rumah hijau\r\n34000 taiping perak', 34000, 'payment received', 'ewallet', 'R12345'),
(10003, '2022-08-24 12:17:44', 'clairetang1903@hotmail.com', 2500, 1500, 'Tang Jia Huey', '012-3456789', '63 jalan unta kawasan rumah hijau\r\n34000 taiping perak', 34000, 'new order', 'online', NULL),
(10004, '2022-08-28 09:51:07', 'clairetang1903@hotmail.com', 1000, 1000, 'Claire', '012-3456789', 'abd jalan ccc', 42634, 'new order', 'online', NULL),
(10005, '2022-08-28 09:57:48', 'clairetang1903@hotmail.com', 2000, 1500, 'Jane', '012-3456789', 'abc Jalan CDS', 53565, 'new order', 'online', NULL),
(10006, '2022-08-28 10:01:25', 'clairetang1903@hotmail.com', 1000, 700, 'Joaan', '012-3456789', '7143 Lorong djkd ', 23434, 'new order', 'online', NULL),
(10007, '2022-08-28 10:08:55', 'clairetang1903@hotmail.com', 1000, 700, 'JONN', '012-3456789', '234 ndfbwc jalan kwnwfdw', 14524, 'payment failed', 'online', ''),
(10008, '2022-08-28 11:25:08', 'claire.t.j.h.1903@gmail.com', 2500, 1000, 'claire', '012-3456789', '1234 jalan 1532 ', 63724, 'payment received', 'online', 'R12345'),
(10009, '2022-08-28 11:34:44', 'claire.t.j.h.1903@gmail.com', 4000, 3000, 'Jane', '012-3456789', 'Lorong advsv Taman Tikus', 42352, 'payment failed', 'online', 'R12345'),
(10010, '2022-08-29 10:17:52', 'clairetang1903@hotmail.com', 1000, 700, 'Jane', '012-3456789', '123 Jalan abc', 12344, 'new order', 'online', NULL),
(10011, '2022-08-29 10:38:36', 'clairetang1903@hotmail.com', 3500, 3000, 'Jane', '012-3456789', '123 Jalan abc', 13453, 'payment received', 'online', 'R12345'),
(10012, '2022-08-29 12:28:26', 'johnny@gmail.com', 2500, 1000, 'Tang Jia Huey', '012-3456789', '63 jalan unta kawasan rumah hijau\r\n34000 taiping perak', 34000, 'payment received', 'online', 'R12345');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `order_id` int(5) NOT NULL,
  `product_id` int(5) NOT NULL,
  `product_totalamount` double NOT NULL,
  `product_selected` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`order_id`, `product_id`, `product_totalamount`, `product_selected`) VALUES
(10001, 100004, 1500, 1),
(10002, 100017, 1000, 1),
(10003, 100002, 2500, 1),
(10004, 100025, 1000, 1),
(10005, 100017, 1000, 1),
(10005, 100025, 1000, 1),
(10006, 100025, 1000, 1),
(10007, 100025, 1000, 1),
(10008, 100002, 2500, 1),
(10009, 100002, 2500, 1),
(10009, 100004, 1500, 1),
(10010, 100017, 1000, 1),
(10011, 100003, 1000, 1),
(10011, 100004, 1500, 1),
(10011, 100017, 1000, 1),
(10012, 100002, 2500, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(6) NOT NULL,
  `product_image` varchar(100) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` double NOT NULL,
  `category_id` int(5) NOT NULL,
  `designer_email` varchar(100) NOT NULL,
  `product_condition` enum('available','rented','sold') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_image`, `product_name`, `product_price`, `category_id`, `designer_email`, `product_condition`) VALUES
(100002, '../image/product/ID100002_1661672580.jpg', 'Green Beading Fairy Petal Skirt Long ball gown green Prom Dresses', 2500, 7, 'abc@google.com', 'available'),
(100003, '../image/product/ID100003_1661672558.jpg', 'Black v neck tulle long ball gown dress formal dress', 1000, 7, 'alex@hotmail.com', 'available'),
(100004, '../image/product/ID100004_1661672536.jpg', 'Sparkling Prom Dress Evening Gown Graduation Party Dress Formal Dress', 1500, 3, 'abc@google.com', 'available'),
(100006, '../image/product/ID100006_1661672454.jpg', 'White Red Off Shoulder Flowers Lace Mermaid Gown', 1000, 1, 'john@abc.com', 'available'),
(100017, '../image/product/ID100017_1660047005.jpg', 'White gold long sleeve mermaid wedding evening prom dress gown', 1000, 1, 'john@abc.com', 'available'),
(100025, '../image/product/ID100025_1661672635.jpg', 'Ball Gown Dress Luxury Lace Off Shoulder Empire Waist Dress Open Back Puff sleeve Dress For Wedding ', 1500, 2, 'abc@google.com', 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_email`);

--
-- Indexes for table `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`designer_email`,`award_name`,`award_year`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`product_id`,`cus_email`),
  ADD KEY `cus_username` (`cus_email`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`checkout_id`),
  ADD KEY `cus_email` (`cus_email`);

--
-- Indexes for table `checkout_detail`
--
ALTER TABLE `checkout_detail`
  ADD PRIMARY KEY (`checkout_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cus_email`),
  ADD UNIQUE KEY `cus_email` (`cus_email`);

--
-- Indexes for table `customized`
--
ALTER TABLE `customized`
  ADD PRIMARY KEY (`customized_id`),
  ADD KEY `designer_email` (`designer_email`);

--
-- Indexes for table `designer`
--
ALTER TABLE `designer`
  ADD PRIMARY KEY (`designer_email`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cus_email` (`cus_email`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `designer_email` (`designer_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `checkout`
--
ALTER TABLE `checkout`
  MODIFY `checkout_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `customized`
--
ALTER TABLE `customized`
  MODIFY `customized_id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000010;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faq_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1014;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10013;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100035;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `award`
--
ALTER TABLE `award`
  ADD CONSTRAINT `award_ibfk_1` FOREIGN KEY (`designer_email`) REFERENCES `designer` (`designer_email`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`cus_email`) REFERENCES `customer` (`cus_email`),
  ADD CONSTRAINT `cart_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `checkout`
--
ALTER TABLE `checkout`
  ADD CONSTRAINT `checkout_ibfk_1` FOREIGN KEY (`cus_email`) REFERENCES `customer` (`cus_email`);

--
-- Constraints for table `checkout_detail`
--
ALTER TABLE `checkout_detail`
  ADD CONSTRAINT `checkout_detail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `checkout_detail_ibfk_2` FOREIGN KEY (`checkout_id`) REFERENCES `checkout` (`checkout_id`);

--
-- Constraints for table `customized`
--
ALTER TABLE `customized`
  ADD CONSTRAINT `customized_ibfk_1` FOREIGN KEY (`designer_email`) REFERENCES `designer` (`designer_email`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `cus_email` FOREIGN KEY (`cus_email`) REFERENCES `customer` (`cus_email`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`designer_email`) REFERENCES `designer` (`designer_email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
