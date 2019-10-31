-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2019 at 01:17 AM
-- Server version: 10.1.39-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc_crud`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_rights`
--

CREATE TABLE `access_rights` (
  `access_rights_id` text NOT NULL,
  `access_name` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_rights`
--

INSERT INTO `access_rights` (`access_rights_id`, `access_name`, `description`) VALUES
('9', 'Admin', 'Access for administrator'),
('0', 'Borrower', 'Access rights for Borrower'),
('1', 'Member', 'Investor and Borrower'),
('2', 'Leader', 'Leader of a Group');

-- --------------------------------------------------------

--
-- Table structure for table `account_groups`
--

CREATE TABLE `account_groups` (
  `groups_id` int(11) NOT NULL,
  `group_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_groups`
--

INSERT INTO `account_groups` (`groups_id`, `group_name`) VALUES
(1, 'Admin'),
(2, 'Borrowers'),
(3, 'Group1'),
(5, 'Groupie');

-- --------------------------------------------------------

--
-- Table structure for table `annoucements`
--

CREATE TABLE `annoucements` (
  `id` int(5) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_by_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `annoucements`
--

INSERT INTO `annoucements` (`id`, `date`, `title`, `message`, `created_by`, `created_by_id`) VALUES
(1, '2019-07-23 01:52:30', '0', '0', '0', 2),
(2, '2019-07-23 01:53:21', '0', '0', '0', 2),
(3, '2019-07-23 01:54:24', 'Testing no 3', 'testing message no 3', 'Will', 2);

-- --------------------------------------------------------

--
-- Table structure for table `borrow_records`
--

CREATE TABLE `borrow_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `belonging_group` text NOT NULL,
  `date` date NOT NULL,
  `date_borrow` date NOT NULL,
  `principal` text NOT NULL,
  `payment` text NOT NULL,
  `remaining` text NOT NULL,
  `int_acquired` text NOT NULL,
  `interest_rate` text NOT NULL,
  `months_to_pay` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrow_records`
--

INSERT INTO `borrow_records` (`id`, `user_id`, `belonging_group`, `date`, `date_borrow`, `principal`, `payment`, `remaining`, `int_acquired`, `interest_rate`, `months_to_pay`) VALUES
(27, 13, '3', '2019-10-31', '2019-11-02', '10000', '', '10408', '', '.34', '12'),
(28, 13, '3', '2019-10-31', '2019-11-02', '10000', '', '10408', '', '.34', '12'),
(29, 3, '0', '2019-10-31', '2019-10-31', '15000', '', '15288', '', '.16', '12'),
(30, 12, '3', '2019-10-31', '2019-10-31', '20000', '', '20384', '', '.16', '12'),
(31, 12, '3', '2019-10-31', '2019-10-31', '20000', '', '20384', '', '.16', '12');

-- --------------------------------------------------------

--
-- Table structure for table `contribution_records`
--

CREATE TABLE `contribution_records` (
  `contribution_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `belonging_group` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `contri_date` date NOT NULL,
  `contri` text NOT NULL,
  `total_contri_wout_int` text NOT NULL,
  `month_int` text NOT NULL,
  `total_int` text NOT NULL,
  `total_contri_w_int` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contribution_records`
--

INSERT INTO `contribution_records` (`contribution_id`, `user_id`, `belonging_group`, `term_id`, `contri_date`, `contri`, `total_contri_wout_int`, `month_int`, `total_int`, `total_contri_w_int`) VALUES
(1, 11, 3, 0, '2019-10-15', '15000', '', '', '', ''),
(2, 13, 3, 0, '2019-10-15', '25000', '', '', '', ''),
(4, 14, 3, 0, '2019-10-15', '10000', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contribution_term_records`
--

CREATE TABLE `contribution_term_records` (
  `contribution_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `belonging_group` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `jan15` int(11) DEFAULT NULL,
  `jan31` int(11) DEFAULT NULL,
  `feb15` int(11) DEFAULT NULL,
  `feb28` int(11) DEFAULT NULL,
  `mar15` int(11) DEFAULT NULL,
  `mar31` int(11) DEFAULT NULL,
  `apr15` int(11) DEFAULT NULL,
  `apr30` int(11) DEFAULT NULL,
  `may15` int(11) DEFAULT NULL,
  `may31` int(11) DEFAULT NULL,
  `jun15` int(11) DEFAULT NULL,
  `jun30` int(11) DEFAULT NULL,
  `jul15` int(11) DEFAULT NULL,
  `jul31` int(11) DEFAULT NULL,
  `aug15` int(11) DEFAULT NULL,
  `aug31` int(11) DEFAULT NULL,
  `sep15` int(11) DEFAULT NULL,
  `sep30` int(11) DEFAULT NULL,
  `oct15` int(11) DEFAULT NULL,
  `oct31` int(11) DEFAULT NULL,
  `nov15` int(11) DEFAULT NULL,
  `nov30` int(11) DEFAULT NULL,
  `dec15` int(11) DEFAULT NULL,
  `dec31` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contribution_term_records`
--

INSERT INTO `contribution_term_records` (`contribution_id`, `name`, `user_id`, `belonging_group`, `term_id`, `jan15`, `jan31`, `feb15`, `feb28`, `mar15`, `mar31`, `apr15`, `apr30`, `may15`, `may31`, `jun15`, `jun30`, `jul15`, `jul31`, `aug15`, `aug31`, `sep15`, `sep30`, `oct15`, `oct31`, `nov15`, `nov30`, `dec15`, `dec31`) VALUES
(8, 'Ice Frozen', 12, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10000, 20000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Sand Castle', 13, 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20000, 20000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Power Ranger', 14, 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10000, 20000, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Amber Fire', 11, 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 20000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_info`
--

CREATE TABLE `group_info` (
  `group_id` int(11) NOT NULL,
  `group_name` text NOT NULL,
  `group_members` int(11) NOT NULL,
  `group_contributions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_info`
--

INSERT INTO `group_info` (`group_id`, `group_name`, `group_members`, `group_contributions`) VALUES
(0, 'Admin', 0, 0),
(1, 'Borrowers', 0, 0),
(2, 'Members', 0, 0),
(3, 'Groupie', 4, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `loan_info`
--

CREATE TABLE `loan_info` (
  `id` int(11) NOT NULL,
  `loan_user_id` varchar(5) NOT NULL,
  `loan_email` varchar(50) NOT NULL,
  `loan_date` datetime NOT NULL,
  `loan_amount` varchar(10) NOT NULL,
  `loan_payment_months` varchar(2) NOT NULL,
  `interestRate` varchar(50) NOT NULL,
  `monthlyinterest` varchar(50) NOT NULL,
  `overallmonthlyinterest` varchar(50) NOT NULL,
  `totalLoan` varchar(50) NOT NULL,
  `monthlyPayment` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_info`
--

INSERT INTO `loan_info` (`id`, `loan_user_id`, `loan_email`, `loan_date`, `loan_amount`, `loan_payment_months`, `interestRate`, `monthlyinterest`, `overallmonthlyinterest`, `totalLoan`, `monthlyPayment`, `payment_status`) VALUES
(1, '3', 'willem.leonardo@gmail.com', '2019-07-26 00:00:00', '50000', '12', '0.05', '2500', '30000', '80000', '6666.6666666667', ''),
(2, '2', 'wil@lem2.com', '2019-07-07 00:00:00', '50000', '12', '0.05', '2500', '30000', '80000', '6666.6666666667', '8 month/s and PHP 25002 left'),
(3, '2', 'wil@lem2.com', '2019-07-08 00:00:00', '50000', '12', '0.05', '2500', '30000', '80000', '6666.6666666667', '7 month/s and PHP 46668 left');

-- --------------------------------------------------------

--
-- Table structure for table `loan_records`
--

CREATE TABLE `loan_records` (
  `record_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `date_paid` datetime NOT NULL,
  `amount_paid` int(11) NOT NULL,
  `amount_left` int(11) NOT NULL,
  `month_paid` varchar(10) NOT NULL,
  `month_left` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_records`
--

INSERT INTO `loan_records` (`record_id`, `loan_id`, `date_paid`, `amount_paid`, `amount_left`, `month_paid`, `month_left`) VALUES
(1, 1, '2019-07-06 00:00:00', 5000, 45000, '2019-07', '11'),
(2, 1, '2019-07-07 00:00:00', 5000, 40000, '2019-06', '10'),
(3, 1, '2019-07-08 00:00:00', 5000, 35000, '2019-08', '9'),
(4, 2, '2019-07-08 00:00:00', 5000, 45000, '2019-07', '11'),
(5, 3, '2019-07-10 00:00:00', 6666, 73334, '2019-07', '11'),
(6, 3, '2019-07-09 00:00:00', 6666, 66668, '2019-08', '10'),
(7, 2, '2019-07-11 00:00:00', 6666, 38334, '2019-08', '10'),
(8, 3, '2019-07-12 00:00:00', 6666, 60000, '2019-08', '9'),
(9, 3, '2019-07-12 00:00:00', 6666, 53334, '2019-08', '8'),
(10, 1, '2019-07-12 00:00:00', 6666, 28334, '2019-08', '8'),
(11, 2, '2019-07-11 00:00:00', 6666, 31668, '2019-09', '9'),
(12, 3, '2019-07-11 00:00:00', 6666, 46668, '2019-09', '7'),
(13, 2, '2019-07-11 00:00:00', 6666, 25002, '2019-10', '8');

-- --------------------------------------------------------

--
-- Table structure for table `payment_records`
--

CREATE TABLE `payment_records` (
  `id` int(11) NOT NULL,
  `borrow_id` int(11) NOT NULL,
  `date_of_payment` date NOT NULL,
  `amount_paid` text NOT NULL,
  `amount_to_be_paid` text NOT NULL,
  `gained` text NOT NULL,
  `payment_status` text NOT NULL,
  `total_paid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_records`
--

INSERT INTO `payment_records` (`id`, `borrow_id`, `date_of_payment`, `amount_paid`, `amount_to_be_paid`, `gained`, `payment_status`, `total_paid`) VALUES
(217, 27, '2019-11-15', '', '34', '', '', ''),
(218, 27, '2019-11-30', '', '34', '', '', ''),
(219, 27, '2019-12-15', '', '68', '', '', ''),
(220, 27, '2019-12-31', '', '68', '', '', ''),
(221, 27, '2020-01-15', '', '102', '', '', ''),
(222, 27, '2020-01-31', '', '102', '', '', ''),
(223, 27, '2020-02-15', '', '136', '', '', ''),
(224, 27, '2020-02-29', '', '136', '', '', ''),
(225, 27, '2020-03-15', '', '170', '', '', ''),
(226, 27, '2020-03-31', '', '170', '', '', ''),
(227, 27, '2020-04-15', '', '204', '', '', ''),
(228, 27, '2020-04-30', '', '204', '', '', ''),
(229, 27, '2020-05-15', '', '238', '', '', ''),
(230, 27, '2020-05-31', '', '238', '', '', ''),
(231, 27, '2020-06-15', '', '272', '', '', ''),
(232, 27, '2020-06-30', '', '272', '', '', ''),
(233, 27, '2020-07-15', '', '306', '', '', ''),
(234, 27, '2020-07-31', '', '306', '', '', ''),
(235, 27, '2020-08-15', '', '340', '', '', ''),
(236, 27, '2020-08-31', '', '340', '', '', ''),
(237, 27, '2020-09-15', '', '374', '', '', ''),
(238, 27, '2020-09-30', '', '374', '', '', ''),
(239, 27, '2020-10-15', '', '408', '', '', ''),
(240, 27, '2020-10-31', '', '408', '', '', ''),
(241, 28, '2019-11-15', '', '34', '', '', ''),
(242, 28, '2019-11-30', '', '34', '', '', ''),
(243, 28, '2019-12-15', '', '68', '', '', ''),
(244, 28, '2019-12-31', '', '68', '', '', ''),
(245, 28, '2020-01-15', '', '102', '', '', ''),
(246, 28, '2020-01-31', '', '102', '', '', ''),
(247, 28, '2020-02-15', '', '136', '', '', ''),
(248, 28, '2020-02-29', '', '136', '', '', ''),
(249, 28, '2020-03-15', '', '170', '', '', ''),
(250, 28, '2020-03-31', '', '170', '', '', ''),
(251, 28, '2020-04-15', '', '204', '', '', ''),
(252, 28, '2020-04-30', '', '204', '', '', ''),
(253, 28, '2020-05-15', '', '238', '', '', ''),
(254, 28, '2020-05-31', '', '238', '', '', ''),
(255, 28, '2020-06-15', '', '272', '', '', ''),
(256, 28, '2020-06-30', '', '272', '', '', ''),
(257, 28, '2020-07-15', '', '306', '', '', ''),
(258, 28, '2020-07-31', '', '306', '', '', ''),
(259, 28, '2020-08-15', '', '340', '', '', ''),
(260, 28, '2020-08-31', '', '340', '', '', ''),
(261, 28, '2020-09-15', '', '374', '', '', ''),
(262, 28, '2020-09-30', '', '374', '', '', ''),
(263, 28, '2020-10-15', '', '408', '', '', ''),
(264, 28, '2020-10-31', '', '408', '', '', ''),
(265, 29, '2019-11-15', '', '24', '', '', ''),
(266, 29, '2019-11-30', '', '24', '', '', ''),
(267, 29, '2019-12-15', '', '48', '', '', ''),
(268, 29, '2019-12-31', '', '48', '', '', ''),
(269, 29, '2020-01-15', '', '72', '', '', ''),
(270, 29, '2020-01-31', '', '72', '', '', ''),
(271, 29, '2020-02-15', '', '96', '', '', ''),
(272, 29, '2020-02-29', '', '96', '', '', ''),
(273, 29, '2020-03-15', '', '120', '', '', ''),
(274, 29, '2020-03-31', '', '120', '', '', ''),
(275, 29, '2020-04-15', '', '144', '', '', ''),
(276, 29, '2020-04-30', '', '144', '', '', ''),
(277, 29, '2020-05-15', '', '168', '', '', ''),
(278, 29, '2020-05-31', '', '168', '', '', ''),
(279, 29, '2020-06-15', '', '192', '', '', ''),
(280, 29, '2020-06-30', '', '192', '', '', ''),
(281, 29, '2020-07-15', '', '216', '', '', ''),
(282, 29, '2020-07-31', '', '216', '', '', ''),
(283, 29, '2020-08-15', '', '240', '', '', ''),
(284, 29, '2020-08-31', '', '240', '', '', ''),
(285, 29, '2020-09-15', '', '264', '', '', ''),
(286, 29, '2020-09-30', '', '264', '', '', ''),
(287, 29, '2020-10-15', '', '288', '', '', ''),
(288, 29, '2020-10-31', '', '288', '', '', ''),
(289, 31, '2019-11-15', '', '20032', '', '', ''),
(290, 31, '2019-11-30', '', '20032', '', '', ''),
(291, 31, '2019-12-15', '', '20064', '', '', ''),
(292, 31, '2019-12-31', '', '20064', '', '', ''),
(293, 31, '2020-01-15', '', '20096', '', '', ''),
(294, 31, '2020-01-31', '', '20096', '', '', ''),
(295, 31, '2020-02-15', '', '20128', '', '', ''),
(296, 31, '2020-02-29', '', '20128', '', '', ''),
(297, 31, '2020-03-15', '', '20160', '', '', ''),
(298, 31, '2020-03-31', '', '20160', '', '', ''),
(299, 31, '2020-04-15', '', '20192', '', '', ''),
(300, 31, '2020-04-30', '', '20192', '', '', ''),
(301, 31, '2020-05-15', '', '20224', '', '', ''),
(302, 31, '2020-05-31', '', '20224', '', '', ''),
(303, 31, '2020-06-15', '', '20256', '', '', ''),
(304, 31, '2020-06-30', '', '20256', '', '', ''),
(305, 31, '2020-07-15', '', '20288', '', '', ''),
(306, 31, '2020-07-31', '', '20288', '', '', ''),
(307, 31, '2020-08-15', '', '20320', '', '', ''),
(308, 31, '2020-08-31', '', '20320', '', '', ''),
(309, 31, '2020-09-15', '', '20352', '', '', ''),
(310, 31, '2020-09-30', '', '20352', '', '', ''),
(311, 31, '2020-10-15', '', '20384', '', '', ''),
(312, 31, '2020-10-31', '', '20384', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `summary_records`
--

CREATE TABLE `summary_records` (
  `id` int(11) NOT NULL,
  `belonging_group` text NOT NULL,
  `term_id` text NOT NULL,
  `date` date NOT NULL,
  `contri_wout_int` text NOT NULL,
  `amount_borrow` text NOT NULL,
  `payment_rcv` text NOT NULL,
  `deficit` text NOT NULL,
  `interest_earned` text NOT NULL,
  `est_earned` text NOT NULL,
  `total` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `summary_records`
--

INSERT INTO `summary_records` (`id`, `belonging_group`, `term_id`, `date`, `contri_wout_int`, `amount_borrow`, `payment_rcv`, `deficit`, `interest_earned`, `est_earned`, `total`) VALUES
(6, '3', '', '2019-10-31', '', '60000', '', '61584', '', '', ''),
(8, '3', '', '2019-10-15', '50000', '', '', '', '', '', ''),
(9, '0', '', '2019-10-31', '', '15000', '', '15288', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_hash` varchar(64) DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `activation_hash` varchar(64) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `access_rights` int(11) NOT NULL,
  `belonging_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `password_reset_hash`, `password_reset_expires_at`, `activation_hash`, `is_active`, `access_rights`, `belonging_group`) VALUES
(1, 'wil', 'wil@lem.com', '$2y$10$9FA0KZfUlM08gw2k4NtRju2Bt4Sm..463vaHYtguscx2k0eA/cQDm', '97e97a276fe01709b433b89dd483d14420a34c452a365a425ae147d58dd84788', '2019-06-23 19:00:54', NULL, 1, 0, 0),
(2, 'Will', 'wil@lem2.com', '$2y$10$6j0LFsBO6k67koUTwKXvUOHvSouPektjtdYlzVvgbLXNhw4jiqcdS', NULL, NULL, NULL, 1, 9, 0),
(3, 'willem', 'willem.leonardo@gmail.com', '$2y$10$lfjZphPH0Ai3jgVh0n.lXeAAkk8zrgAf/VIrNT1LknZOArXvFNLMO', NULL, NULL, NULL, 1, 0, 0),
(8, 'Will', 'wee.standtogether@gmail.com', '$2y$10$qn6Uj9PjqdE99oGB7NJr0.rf3I/ad0.chpYDybwyyys1WVaN0poXW', NULL, NULL, NULL, 1, 0, 0),
(9, 'admin', 'admin@admin.com', '$2y$10$RbRT4RVp5cEPYzGEOUwt5.5J1xb5jKYEMrnG4ohBa.LhwuxHVER26', NULL, NULL, NULL, 1, 9, 0),
(10, 'user', 'user@user.com', '$2y$10$FEYCqmAa5Co5dklQW3yas.BB/ikho5jZMErZvj9eSutEf9ArZ/046', NULL, NULL, NULL, 1, 0, 0),
(11, 'Amber Fire', 'amber@fire.com', '$2y$10$FEYCqmAa5Co5dklQW3yas.BB/ikho5jZMErZvj9eSutEf9ArZ/046', NULL, NULL, NULL, 1, 1, 3),
(12, 'Ice Frozen', 'ice@frozen.com', '$2y$10$FEYCqmAa5Co5dklQW3yas.BB/ikho5jZMErZvj9eSutEf9ArZ/046', NULL, NULL, NULL, 1, 2, 3),
(13, 'Sand Castle', 'sand@castle.com', '$2y$10$1xNUhg2.ATEgMN5fHt7Ik.7ARwhPuhL.KDS1NItTK4r6HJDHPdzIy', NULL, NULL, NULL, 1, 1, 3),
(14, 'Power Ranger', 'power@ranger.com', '$2y$10$KyWZv5ygg9o1NA3j/SPn1uJAXQUcy0CzKqrjFgfcvhmmK5IpB32ou', NULL, NULL, NULL, 1, 1, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD PRIMARY KEY (`groups_id`);

--
-- Indexes for table `annoucements`
--
ALTER TABLE `annoucements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contribution_records`
--
ALTER TABLE `contribution_records`
  ADD PRIMARY KEY (`contribution_id`);

--
-- Indexes for table `contribution_term_records`
--
ALTER TABLE `contribution_term_records`
  ADD PRIMARY KEY (`contribution_id`);

--
-- Indexes for table `group_info`
--
ALTER TABLE `group_info`
  ADD PRIMARY KEY (`group_id`) USING BTREE;

--
-- Indexes for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_records`
--
ALTER TABLE `loan_records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `payment_records`
--
ALTER TABLE `payment_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `summary_records`
--
ALTER TABLE `summary_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
  ADD UNIQUE KEY `activation_hash` (`activation_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_groups`
--
ALTER TABLE `account_groups`
  MODIFY `groups_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `annoucements`
--
ALTER TABLE `annoucements`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `borrow_records`
--
ALTER TABLE `borrow_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `contribution_records`
--
ALTER TABLE `contribution_records`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contribution_term_records`
--
ALTER TABLE `contribution_term_records`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `group_info`
--
ALTER TABLE `group_info`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan_info`
--
ALTER TABLE `loan_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan_records`
--
ALTER TABLE `loan_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment_records`
--
ALTER TABLE `payment_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT for table `summary_records`
--
ALTER TABLE `summary_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
