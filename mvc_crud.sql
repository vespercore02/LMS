-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2020 at 02:01 AM
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
  `term_id` int(11) NOT NULL,
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

INSERT INTO `borrow_records` (`id`, `user_id`, `belonging_group`, `term_id`, `date`, `date_borrow`, `principal`, `payment`, `remaining`, `int_acquired`, `interest_rate`, `months_to_pay`) VALUES
(1, 11, '3', 2019, '2019-01-15', '2019-01-15', '10000', '11500', 'Paid', '1500', '5', '12'),
(2, 13, '3', 0, '2019-01-15', '2019-01-15', '20000', '21000', 'Paid', '1000', '5', '12'),
(3, 14, '3', 0, '2019-01-15', '2019-01-15', '15000', '', '24000', '', '5', '12'),
(4, 14, '3', 0, '2019-01-15', '2019-01-15', '15000', '', '24000', '', '5', '12');

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
(250, 11, 3, 2019, '2019-01-15', '10000', '10000', '1222.2222222222', '1222.2222222222', '11222.222222222'),
(251, 11, 3, 2019, '2019-01-31', '15000', '25000', '', '', ''),
(252, 11, 3, 2019, '2019-02-15', '0', '25000', '', '', ''),
(253, 11, 3, 2019, '2019-02-28', '0', '25000', '', '', ''),
(254, 11, 3, 2019, '2019-03-15', '0', '25000', '', '', ''),
(255, 11, 3, 2019, '2019-03-31', '0', '25000', '', '', ''),
(256, 11, 3, 2019, '2019-04-15', '0', '25000', '', '', ''),
(257, 11, 3, 2019, '2019-04-30', '0', '25000', '', '', ''),
(258, 11, 3, 2019, '2019-05-15', '0', '25000', '', '', ''),
(259, 11, 3, 2019, '2019-05-31', '0', '25000', '', '', ''),
(260, 11, 3, 2019, '2019-06-15', '0', '25000', '', '', ''),
(261, 11, 3, 2019, '2019-06-30', '0', '25000', '', '', ''),
(262, 11, 3, 2019, '2019-07-15', '0', '25000', '', '', ''),
(263, 11, 3, 2019, '2019-07-31', '0', '25000', '', '', ''),
(264, 11, 3, 2019, '2019-08-15', '0', '25000', '', '', ''),
(265, 11, 3, 2019, '2019-08-31', '0', '25000', '', '', ''),
(266, 11, 3, 2019, '2019-09-15', '0', '25000', '', '', ''),
(267, 11, 3, 2019, '2019-09-30', '0', '25000', '', '', ''),
(268, 11, 3, 2019, '2019-10-15', '0', '25000', '', '', ''),
(269, 11, 3, 2019, '2019-10-31', '0', '25000', '', '', ''),
(270, 11, 3, 2019, '2019-11-15', '0', '25000', '', '', ''),
(271, 11, 3, 2019, '2019-11-30', '0', '25000', '', '', ''),
(272, 11, 3, 2019, '2019-12-15', '0', '25000', '', '', ''),
(273, 11, 3, 2019, '2019-12-31', '0', '25000', '', '', ''),
(274, 12, 3, 2019, '2019-01-15', '10000', '10000', '1222.2222222222', '1222.2222222222', '11222.222222222'),
(275, 12, 3, 2019, '2019-01-31', '0', '10000', '', '', ''),
(276, 12, 3, 2019, '2019-02-15', '0', '10000', '', '', ''),
(277, 12, 3, 2019, '2019-02-28', '0', '10000', '', '', ''),
(278, 12, 3, 2019, '2019-03-15', '0', '10000', '', '', ''),
(279, 12, 3, 2019, '2019-03-31', '0', '10000', '', '', ''),
(280, 12, 3, 2019, '2019-04-15', '0', '10000', '', '', ''),
(281, 12, 3, 2019, '2019-04-30', '0', '10000', '', '', ''),
(282, 12, 3, 2019, '2019-05-15', '0', '10000', '', '', ''),
(283, 12, 3, 2019, '2019-05-31', '0', '10000', '', '', ''),
(284, 12, 3, 2019, '2019-06-15', '0', '10000', '', '', ''),
(285, 12, 3, 2019, '2019-06-30', '0', '10000', '', '', ''),
(286, 12, 3, 2019, '2019-07-15', '0', '10000', '', '', ''),
(287, 12, 3, 2019, '2019-07-31', '0', '10000', '', '', ''),
(288, 12, 3, 2019, '2019-08-15', '0', '10000', '', '', ''),
(289, 12, 3, 2019, '2019-08-31', '0', '10000', '', '', ''),
(290, 12, 3, 2019, '2019-09-15', '0', '10000', '', '', ''),
(291, 12, 3, 2019, '2019-09-30', '0', '10000', '', '', ''),
(292, 12, 3, 2019, '2019-10-15', '0', '10000', '', '', ''),
(293, 12, 3, 2019, '2019-10-31', '0', '10000', '', '', ''),
(294, 12, 3, 2019, '2019-11-15', '0', '10000', '', '', ''),
(295, 12, 3, 2019, '2019-11-30', '0', '10000', '', '', ''),
(296, 12, 3, 2019, '2019-12-15', '0', '10000', '', '', ''),
(297, 12, 3, 2019, '2019-12-31', '0', '10000', '', '', ''),
(298, 13, 3, 2019, '2019-01-15', '10000', '10000', '1222.2222222222', '1222.2222222222', '11222.222222222'),
(299, 13, 3, 2019, '2019-01-31', '0', '10000', '', '', ''),
(300, 13, 3, 2019, '2019-02-15', '0', '10000', '', '', ''),
(301, 13, 3, 2019, '2019-02-28', '0', '10000', '', '', ''),
(302, 13, 3, 2019, '2019-03-15', '0', '10000', '', '', ''),
(303, 13, 3, 2019, '2019-03-31', '0', '10000', '', '', ''),
(304, 13, 3, 2019, '2019-04-15', '0', '10000', '', '', ''),
(305, 13, 3, 2019, '2019-04-30', '0', '10000', '', '', ''),
(306, 13, 3, 2019, '2019-05-15', '0', '10000', '', '', ''),
(307, 13, 3, 2019, '2019-05-31', '0', '10000', '', '', ''),
(308, 13, 3, 2019, '2019-06-15', '0', '10000', '', '', ''),
(309, 13, 3, 2019, '2019-06-30', '0', '10000', '', '', ''),
(310, 13, 3, 2019, '2019-07-15', '0', '10000', '', '', ''),
(311, 13, 3, 2019, '2019-07-31', '0', '10000', '', '', ''),
(312, 13, 3, 2019, '2019-08-15', '0', '10000', '', '', ''),
(313, 13, 3, 2019, '2019-08-31', '0', '10000', '', '', ''),
(314, 13, 3, 2019, '2019-09-15', '0', '10000', '', '', ''),
(315, 13, 3, 2019, '2019-09-30', '0', '10000', '', '', ''),
(316, 13, 3, 2019, '2019-10-15', '0', '10000', '', '', ''),
(317, 13, 3, 2019, '2019-10-31', '0', '10000', '', '', ''),
(318, 13, 3, 2019, '2019-11-15', '0', '10000', '', '', ''),
(319, 13, 3, 2019, '2019-11-30', '0', '10000', '', '', ''),
(320, 13, 3, 2019, '2019-12-15', '0', '10000', '', '', ''),
(321, 13, 3, 2019, '2019-12-31', '0', '10000', '', '', ''),
(322, 14, 3, 2019, '2019-01-15', '15000', '15000', '1833.3333333333', '1833.3333333333', '16833.333333333'),
(323, 14, 3, 2019, '2019-01-31', '0', '15000', '', '', ''),
(324, 14, 3, 2019, '2019-02-15', '0', '15000', '', '', ''),
(325, 14, 3, 2019, '2019-02-28', '0', '15000', '', '', ''),
(326, 14, 3, 2019, '2019-03-15', '0', '15000', '', '', ''),
(327, 14, 3, 2019, '2019-03-31', '0', '15000', '', '', ''),
(328, 14, 3, 2019, '2019-04-15', '0', '15000', '', '', ''),
(329, 14, 3, 2019, '2019-04-30', '0', '15000', '', '', ''),
(330, 14, 3, 2019, '2019-05-15', '0', '15000', '', '', ''),
(331, 14, 3, 2019, '2019-05-31', '0', '15000', '', '', ''),
(332, 14, 3, 2019, '2019-06-15', '0', '15000', '', '', ''),
(333, 14, 3, 2019, '2019-06-30', '0', '15000', '', '', ''),
(334, 14, 3, 2019, '2019-07-15', '0', '15000', '', '', ''),
(335, 14, 3, 2019, '2019-07-31', '0', '15000', '', '', ''),
(336, 14, 3, 2019, '2019-08-15', '0', '15000', '', '', ''),
(337, 14, 3, 2019, '2019-08-31', '0', '15000', '', '', ''),
(338, 14, 3, 2019, '2019-09-15', '0', '15000', '', '', ''),
(339, 14, 3, 2019, '2019-09-30', '0', '15000', '', '', ''),
(340, 14, 3, 2019, '2019-10-15', '0', '15000', '', '', ''),
(341, 14, 3, 2019, '2019-10-31', '0', '15000', '', '', ''),
(342, 14, 3, 2019, '2019-11-15', '0', '15000', '', '', ''),
(343, 14, 3, 2019, '2019-11-30', '0', '15000', '', '', ''),
(344, 14, 3, 2019, '2019-12-15', '0', '15000', '', '', ''),
(345, 14, 3, 2019, '2019-12-31', '0', '15000', '', '', '');

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
(3, 'Groupie', 4, 0);

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
(1, 1, '2020-01-31', '5000', '5500', '', '', ''),
(2, 1, '2020-02-15', '', '5500', '', '', ''),
(3, 1, '2020-02-29', '', '6000', '', '', ''),
(4, 1, '2020-03-15', '', '6000', '', '', ''),
(5, 1, '2020-03-31', '6500', 'Paid', '', '', ''),
(6, 1, '2020-04-15', '', 'Paid', '', '', ''),
(7, 1, '2020-04-30', '', 'Paid', '', '', ''),
(8, 1, '2020-05-15', '', 'Paid', '', '', ''),
(9, 1, '2020-05-31', '', 'Paid', '', '', ''),
(10, 1, '2020-06-15', '', 'Paid', '', '', ''),
(11, 1, '2020-06-30', '', 'Paid', '', '', ''),
(12, 1, '2020-07-15', '', 'Paid', '', '', ''),
(13, 1, '2020-07-31', '', 'Paid', '', '', ''),
(14, 1, '2020-08-15', '', 'Paid', '', '', ''),
(15, 1, '2020-08-31', '', 'Paid', '', '', ''),
(16, 1, '2020-09-15', '', 'Paid', '', '', ''),
(17, 1, '2020-09-30', '', 'Paid', '', '', ''),
(18, 1, '2020-10-15', '', 'Paid', '', '', ''),
(19, 1, '2020-10-31', '', 'Paid', '', '', ''),
(20, 1, '2020-11-15', '', 'Paid', '', '', ''),
(21, 1, '2020-11-30', '', 'Paid', '', '', ''),
(22, 1, '2020-12-15', '', 'Paid', '', '', ''),
(23, 1, '2020-12-31', '', 'Paid', '', '', ''),
(24, 1, '2021-01-15', '', 'Paid', '', '', ''),
(25, 2, '2019-01-31', '21000', 'Paid', '', '', ''),
(26, 2, '2019-02-15', '', 'Paid', '', '', ''),
(27, 2, '2019-02-28', '', 'Paid', '', '', ''),
(28, 2, '2019-03-15', '', 'Paid', '', '', ''),
(29, 2, '2019-03-31', '', 'Paid', '', '', ''),
(30, 2, '2019-04-15', '', 'Paid', '', '', ''),
(31, 2, '2019-04-30', '', 'Paid', '', '', ''),
(32, 2, '2019-05-15', '', 'Paid', '', '', ''),
(33, 2, '2019-05-31', '', 'Paid', '', '', ''),
(34, 2, '2019-06-15', '', 'Paid', '', '', ''),
(35, 2, '2019-06-30', '', 'Paid', '', '', ''),
(36, 2, '2019-07-15', '', 'Paid', '', '', ''),
(37, 2, '2019-07-31', '', 'Paid', '', '', ''),
(38, 2, '2019-08-15', '', 'Paid', '', '', ''),
(39, 2, '2019-08-31', '', 'Paid', '', '', ''),
(40, 2, '2019-09-15', '', 'Paid', '', '', ''),
(41, 2, '2019-09-30', '', 'Paid', '', '', ''),
(42, 2, '2019-10-15', '', 'Paid', '', '', ''),
(43, 2, '2019-10-31', '', 'Paid', '', '', ''),
(44, 2, '2019-11-15', '', 'Paid', '', '', ''),
(45, 2, '2019-11-30', '', 'Paid', '', '', ''),
(46, 2, '2019-12-15', '', 'Paid', '', '', ''),
(47, 2, '2019-12-31', '', 'Paid', '', '', ''),
(48, 2, '2020-01-15', '', 'Paid', '', '', ''),
(49, 4, '2019-01-31', '', '15750', '', '', ''),
(50, 4, '2019-02-15', '', '15750', '', '', ''),
(51, 4, '2019-02-28', '', '16500', '', '', ''),
(52, 4, '2019-03-15', '', '16500', '', '', ''),
(53, 4, '2019-03-31', '', '17250', '', '', ''),
(54, 4, '2019-04-15', '', '17250', '', '', ''),
(55, 4, '2019-04-30', '', '18000', '', '', ''),
(56, 4, '2019-05-15', '', '18000', '', '', ''),
(57, 4, '2019-05-31', '', '18750', '', '', ''),
(58, 4, '2019-06-15', '', '18750', '', '', ''),
(59, 4, '2019-06-30', '', '19500', '', '', ''),
(60, 4, '2019-07-15', '', '19500', '', '', ''),
(61, 4, '2019-07-31', '', '20250', '', '', ''),
(62, 4, '2019-08-15', '', '20250', '', '', ''),
(63, 4, '2019-08-31', '', '21000', '', '', ''),
(64, 4, '2019-09-15', '', '21000', '', '', ''),
(65, 4, '2019-09-30', '', '21750', '', '', ''),
(66, 4, '2019-10-15', '', '21750', '', '', ''),
(67, 4, '2019-10-31', '', '22500', '', '', ''),
(68, 4, '2019-11-15', '', '22500', '', '', ''),
(69, 4, '2019-11-30', '', '23250', '', '', ''),
(70, 4, '2019-12-15', '', '23250', '', '', ''),
(71, 4, '2019-12-31', '', '24000', '', '', ''),
(72, 4, '2020-01-15', '', '24000', '', '', '');

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
(3, '', '2019', '2019-01-15', '45000', '60000', '95500', '80000', '5500', '', ''),
(5, '', '2019', '2019-01-31', '15000', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `term` int(11) NOT NULL,
  `month_start` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`term`, `month_start`) VALUES
(2019, '2019-01-15'),
(2019, '2019-01-31'),
(2019, '2019-02-15'),
(2019, '2019-02-28'),
(2019, '2019-03-15'),
(2019, '2019-03-31'),
(2019, '2019-04-15'),
(2019, '2019-04-30'),
(2019, '2019-05-15'),
(2019, '2019-05-31'),
(2019, '2019-06-15'),
(2019, '2019-06-30'),
(2019, '2019-07-15'),
(2019, '2019-07-31'),
(2019, '2019-08-15'),
(2019, '2019-08-31'),
(2019, '2019-09-15'),
(2019, '2019-09-30'),
(2019, '2019-10-15'),
(2019, '2019-10-31'),
(2019, '2019-11-15'),
(2019, '2019-11-30'),
(2019, '2019-12-15'),
(2019, '2019-12-31');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contribution_records`
--
ALTER TABLE `contribution_records`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

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
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_records`
--
ALTER TABLE `payment_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `summary_records`
--
ALTER TABLE `summary_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
