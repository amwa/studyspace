-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 09, 2015 at 12:28 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fall15small`
--

-- --------------------------------------------------------

--
-- Table structure for table `awang_bandnames_generated`
--

CREATE TABLE `awang_bandnames_generated` (
  `bandname` text NOT NULL,
  `user` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_bandnames_generated`
--

INSERT INTO `awang_bandnames_generated` (`bandname`, `user`) VALUES
('Red Hot Chili Peppers', 'Amy'),
(' Uncle Bluegrass', 'Amy'),
(' Movie Mars', 'Amy'),
(' Gold Twelve Sweethearts', 'Amy'),
(' Ultra Epic Fishing  Good Year Salt Raw', 'Flowes'),
(' Horses', 'Katie'),
(' American Years', 'Amy'),
(' Brothers Amps', 'Amy');

-- --------------------------------------------------------

--
-- Table structure for table `awang_groups`
--

CREATE TABLE `awang_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `creator` varchar(20) NOT NULL,
  `about` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_groups`
--

INSERT INTO `awang_groups` (`id`, `name`, `creator`, `about`, `active`) VALUES
(1, 'CS', 'amy', 'a simple CS group', 1),
(5, 'group1', 'test', 'test', 1),
(6, 'amy''s group', 'test', 'test', 1),
(9, 'test group', 'test', 'test description 2', 1),
(10, 'lol', 'test', 'why', 1),
(13, 'group1', 'test', 'description', 1),
(14, 'amy''s group 2', 'test', 'test description 2', 1),
(15, 'Flowes Fan Club', 'test', 'Fan Club for Flowes', 1),
(16, 'test group 3', 'test', 'another test group!', 1),
(17, 'Test group 4', 'test', 'Yet another test group', 1);

-- --------------------------------------------------------

--
-- Table structure for table `awang_group_reminders`
--

CREATE TABLE `awang_group_reminders` (
  `id` int(11) NOT NULL,
  `reminder` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `done` tinyint(1) NOT NULL,
  `added_by` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_group_reminders`
--

INSERT INTO `awang_group_reminders` (`id`, `reminder`, `group_id`, `due_date`, `done`, `added_by`) VALUES
(1, 'hello', 6, '2015-12-24 00:33:00', 0, 'test'),
(2, 'hi', 6, '2015-12-15 00:00:00', 1, 'test'),
(3, 'hi', 6, '2015-12-15 00:00:00', 1, 'test'),
(4, 'hi', 6, '2015-12-15 00:00:00', 1, 'test'),
(5, 'howdy', 9, '2015-12-18 00:00:00', 0, 'test'),
(6, 'Do something', 14, '2015-12-18 00:34:00', 1, 'amy');

-- --------------------------------------------------------

--
-- Table structure for table `awang_reminders`
--

CREATE TABLE `awang_reminders` (
  `reminder` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_reminders`
--

INSERT INTO `awang_reminders` (`reminder`, `group_id`, `due_date`, `done`) VALUES
('finish cs project', 1, '2015-11-29 03:00:00', 0),
('finish cs project', 1, '2015-11-29 03:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `awang_students`
--

CREATE TABLE `awang_students` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_students`
--

INSERT INTO `awang_students` (`id`, `name`, `username`, `password`) VALUES
(2, 'Test', 'test_user', 'test_password'),
(4, 'test', 'test', '8b7df143d91c716ecfa5fc1730022f6b421b05cedee8fd52b1fc65a96030ad52'),
(5, 'amy', 'amy', '2c26b46b68ffc68ff99b453c1d30413413422d706483bfa0f98a5e886266e7ae'),
(8, 'test', 'newuser', '8b7df143d91c716ecfa5fc1730022f6b421b05cedee8fd52b1fc65a96030ad52'),
(9, 'amy', 'test3', '8b7df143d91c716ecfa5fc1730022f6b421b05cedee8fd52b1fc65a96030ad52'),
(16, 'amy', 'test4', '8b7df143d91c716ecfa5fc1730022f6b421b05cedee8fd52b1fc65a96030ad52');

-- --------------------------------------------------------

--
-- Table structure for table `awang_student_groups`
--

CREATE TABLE `awang_student_groups` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_student_groups`
--

INSERT INTO `awang_student_groups` (`id`, `student_id`, `group_id`) VALUES
(1, 4, 11),
(24, 5, 14),
(31, 4, 17),
(32, 4, 6),
(34, 5, 1),
(39, 4, 9);

-- --------------------------------------------------------

--
-- Table structure for table `awang_test`
--

CREATE TABLE `awang_test` (
  `name` text,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awang_test`
--

INSERT INTO `awang_test` (`name`, `age`) VALUES
('Spot', 7),
('Katie', 13),
('Hi', 2),
('Blah', 231);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment` text NOT NULL,
  `user` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `topic` text NOT NULL,
  `user` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`topic`, `user`) VALUES
('Topic title', 'Name'),
('Questions?', 'Admin'),
('Topic title', 'Name'),
('Topic title', 'Name'),
('Topic title', 'Name'),
('Topic title', 'Name'),
('Topic title', 'Name'),
('hi', 'Name'),
('hi', 'Name');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `awang_groups`
--
ALTER TABLE `awang_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awang_group_reminders`
--
ALTER TABLE `awang_group_reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awang_students`
--
ALTER TABLE `awang_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `awang_student_groups`
--
ALTER TABLE `awang_student_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awang_groups`
--
ALTER TABLE `awang_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `awang_group_reminders`
--
ALTER TABLE `awang_group_reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `awang_students`
--
ALTER TABLE `awang_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `awang_student_groups`
--
ALTER TABLE `awang_student_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;