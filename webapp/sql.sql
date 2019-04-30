-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 24, 2017 at 09:31 PM
-- Server version: 5.5.52-cll-lve
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nelliwinne_easywebsearch1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `un` varchar(300) NOT NULL,
  `pw` varchar(300) NOT NULL,
  `demo` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `un`, `pw`, `demo`) VALUES
(1, 'administrator', '21232f297a57a5a743894a0e4a801fc3', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `crawl`
--

CREATE TABLE `crawl` (
  `id` int(11) NOT NULL,
  `base_url` varchar(1000) NOT NULL,
  `actual_url` varchar(2000) NOT NULL,
  `title` varchar(2500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varbinary(200) DEFAULT NULL,
  `content` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_url` varchar(2000) NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('Y','N') DEFAULT 'N',
  `block_update` enum('Y','N') DEFAULT 'N',
  `visits` int(11) DEFAULT '0',
  `manual` enum('Y','N') DEFAULT 'N',
  `crawlRun` varchar(255) DEFAULT NULL,
  `crawlRunImages` varchar(255) DEFAULT NULL,
  `pdf` enum('Y','N') DEFAULT 'N',
  `ContentType` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crawl_images`
--

CREATE TABLE `crawl_images` (
  `id` int(11) NOT NULL,
  `base_url` varchar(1000) NOT NULL,
  `actual_url` varchar(2000) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `keywords` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `current_url` varchar(2000) NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` enum('Y','N') DEFAULT 'N',
  `block_update` enum('Y','N') DEFAULT 'N',
  `visits` int(11) DEFAULT '0',
  `manual` enum('Y','N') DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `crawl_settings`
--

CREATE TABLE `crawl_settings` (
  `id` int(11) NOT NULL,
  `batch` int(11) NOT NULL DEFAULT '25',
  `image_width` int(11) NOT NULL DEFAULT '200',
  `image_height` int(11) NOT NULL DEFAULT '200',
  `body_lengh` int(11) NOT NULL DEFAULT '2000',
  `max_links_per_site` int(11) DEFAULT '20'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `crawl_settings`
--

INSERT INTO `crawl_settings` (`id`, `batch`, `image_width`, `image_height`, `body_lengh`, `max_links_per_site`) VALUES
(1, 25, 200, 200, 500, 20);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `results` int(11) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `count` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `base_url` varchar(1000) NOT NULL,
  `actual_url` varchar(2000) NOT NULL,
  `spidered_url` varchar(2000) DEFAULT 'N/A',
  `visits` int(11) DEFAULT '0',
  `demo` enum('Y','N') DEFAULT 'N',
  `crawlRun` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT 'administrator',
  `time` int(11) DEFAULT '1477728575',
  `spider_mode` enum('Y','N') DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crawl`
--
ALTER TABLE `crawl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crawl_images`
--
ALTER TABLE `crawl_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crawl_settings`
--
ALTER TABLE `crawl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `crawl`
--
ALTER TABLE `crawl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `crawl_images`
--
ALTER TABLE `crawl_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `crawl_settings`
--
ALTER TABLE `crawl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
