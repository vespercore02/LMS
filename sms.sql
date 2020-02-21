-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2020 at 10:43 AM
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
-- Database: `sms`
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
(28, 15, '3', 2019, '2019-01-31', '2019-02-14', '20000', '29000', 'Paid', '9000', '5', '12'),
(29, 11, '3', 2019, '2019-02-15', '2019-02-21', '10000', '', '16000', '', '5', '6');

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
(440, 11, 3, 2019, '2019-01-15', '10000', '10000', '', '0', '0'),
(441, 11, 3, 2019, '2019-01-31', '20000', '30000', '2400.00', '2400', '32400.00'),
(442, 11, 3, 2019, '2019-02-15', '15000', '45000', '', '2400', '47400.00'),
(443, 12, 3, 2019, '2019-01-15', '15000', '15000', '', '0', '0'),
(444, 12, 3, 2019, '2019-01-31', '20000', '35000', '2400.00', '2400', '37400.00'),
(445, 12, 3, 2019, '2019-02-15', '25000', '60000', '', '2400', '62400.00'),
(446, 13, 3, 2019, '2019-01-15', '10000', '10000', '', '0', '0'),
(447, 13, 3, 2019, '2019-01-31', '15000', '25000', '1800.00', '1800', '26800.00'),
(448, 13, 3, 2019, '2019-02-15', '20000', '45000', '', '1800', '46800.00'),
(449, 14, 3, 2019, '2019-01-15', '10000', '10000', '', '0', '0'),
(450, 14, 3, 2019, '2019-01-31', '10000', '20000', '1200.00', '1200', '21200.00'),
(451, 14, 3, 2019, '2019-02-15', '10000', '30000', '', '1200', '31200.00'),
(452, 15, 3, 2019, '2019-01-15', '10000', '10000', '', '0', '0'),
(453, 15, 3, 2019, '2019-01-31', '10000', '20000', '1200.00', '1200', '21200.00'),
(454, 15, 3, 2019, '2019-02-15', '20000', '40000', '', '1200', '41200.00');

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
(649, 28, '2019-02-15', '', '21000', '', '', ''),
(650, 28, '2019-02-28', '', '21000', '', '', ''),
(651, 28, '2019-03-15', '10500', '11500', '', '', ''),
(652, 28, '2019-03-31', '', '11500', '', '', ''),
(653, 28, '2019-04-15', '', '12500', '', '', ''),
(654, 28, '2019-04-30', '', '12500', '', '', ''),
(655, 28, '2019-05-15', '', '13500', '', '', ''),
(656, 28, '2019-05-31', '', '13500', '', '', ''),
(657, 28, '2019-06-15', '', '14500', '', '', ''),
(658, 28, '2019-06-30', '', '14500', '', '', ''),
(659, 28, '2019-07-15', '', '15500', '', '', ''),
(660, 28, '2019-07-31', '', '15500', '', '', ''),
(661, 28, '2019-08-15', '', '16500', '', '', ''),
(662, 28, '2019-08-31', '', '16500', '', '', ''),
(663, 28, '2019-09-15', '', '17500', '', '', ''),
(664, 28, '2019-09-30', '', '17500', '', '', ''),
(665, 28, '2019-10-15', '18500', 'Paid', '', '', ''),
(666, 28, '2019-10-31', '', 'Paid', '', '', ''),
(667, 28, '2019-11-15', '', 'Paid', '', '', ''),
(668, 28, '2019-11-30', '', 'Paid', '', '', ''),
(669, 28, '2019-12-15', '', 'Paid', '', '', ''),
(670, 28, '2019-12-31', '', 'Paid', '', '', ''),
(671, 28, '2020-01-15', '', 'Paid', '', '', ''),
(672, 28, '2020-01-31', '', 'Paid', '', '', ''),
(673, 29, '2019-02-28', '', '10500', '', '', ''),
(674, 29, '2019-03-15', '', '10500', '', '', ''),
(675, 29, '2019-03-31', '', '11000', '', '', ''),
(676, 29, '2019-04-15', '', '11000', '', '', ''),
(677, 29, '2019-04-30', '', '11500', '', '', ''),
(678, 29, '2019-05-15', '', '11500', '', '', ''),
(679, 29, '2019-05-31', '', '12000', '', '', ''),
(680, 29, '2019-06-15', '', '12000', '', '', ''),
(681, 29, '2019-06-30', '', '12500', '', '', ''),
(682, 29, '2019-07-15', '', '12500', '', '', ''),
(683, 29, '2019-07-31', '', '13000', '', '', ''),
(684, 29, '2019-08-15', '', '13000', '', '', ''),
(685, 29, '2019-08-31', '', '13500', '', '', ''),
(686, 29, '2019-09-15', '', '13500', '', '', ''),
(687, 29, '2019-09-30', '', '14000', '', '', ''),
(688, 29, '2019-10-15', '', '14000', '', '', ''),
(689, 29, '2019-10-31', '', '14500', '', '', ''),
(690, 29, '2019-11-15', '', '14500', '', '', ''),
(691, 29, '2019-11-30', '', '15000', '', '', ''),
(692, 29, '2019-12-15', '', '15000', '', '', ''),
(693, 29, '2019-12-31', '', '15500', '', '', ''),
(694, 29, '2020-01-15', '', '15500', '', '', ''),
(695, 29, '2020-01-31', '', '16000', '', '', ''),
(696, 29, '2020-02-15', '', '16000', '', '', '');

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
(37, '', '2019', '2019-01-15', '55000', '', '', '', '', '', ''),
(38, '', '2019', '2019-01-31', '75000', '20000', '29000', '0', '9000', '', ''),
(39, '', '2019', '2019-02-15', '90000', '10000', '', '16000', '', '', '');

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
(13, 'Sand Castle', 'sand@castle.com', '$2y$10$0GGDD5HUZeCXch1I3I1xG.EQpBPasTEBTnZq6CGX6oIcCGE.4gXOi', NULL, NULL, NULL, 1, 1, 3),
(14, 'Power Ranger', 'power@ranger.com', '$2y$10$KyWZv5ygg9o1NA3j/SPn1uJAXQUcy0CzKqrjFgfcvhmmK5IpB32ou', NULL, NULL, NULL, 1, 1, 3),
(15, 'Power Fire', 'power@fire.com', '$2y$10$2cFgFNksVRMrd3SVzGkhVOXkB/e.uVYVIRJ4.asKsx21pOhbF0.x.', NULL, NULL, NULL, 1, 1, 3),
(16, 'activation link', 'activation@link.com', '$2y$10$kQgkd14WR8O8D7LF2/TS7OQb9Tkrivp3K12j2ZU7xfXlmVVDw3jxS', NULL, NULL, NULL, 1, 1, 3),
(17, 'boku hero', 'boku@hero.com', '$2y$10$Cx3ZGPHLPh5YYJluIDOFKu7g.pj7LFp/7hbM3qfaJTjqpce0PVzPi', NULL, NULL, 'e529ec4d320b87183e27dda0e954003bcefe6f152a4601f12e6506ee511b327a', 0, 1, 2);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `contribution_records`
--
ALTER TABLE `contribution_records`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455;

--
-- AUTO_INCREMENT for table `group_info`
--
ALTER TABLE `group_info`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_records`
--
ALTER TABLE `payment_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=697;

--
-- AUTO_INCREMENT for table `summary_records`
--
ALTER TABLE `summary_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
