-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 05, 2010 at 06:37 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `exp_dat`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`comment` longtext NOT NULL,
`profile_post` int(11) NOT NULL,
`creator` int(11) NOT NULL,
`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
`approved` tinyint(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`ID`),
KEY `profile_post` (`profile_post`,`creator`,`approved`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`ID`, `comment`, `profile_post`, `creator`, `created`, `approved`) VALUES
(1, 'This is a test comment', 1, 1, '2010-05-13 18:01:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE IF NOT EXISTS `controllers` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`controller` varchar(255) NOT NULL,
`active` tinyint(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`ID`),
UNIQUE KEY `controller` (`controller`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`ID`, `controller`, `active`) VALUES
(1, 'authenticate', 1),
(2, 'members', 1),
(3, 'relateController', 1),
(4, 'relationships', 1),
(5, 'profile', 1),
(6, 'calendar', 1),
(7, 'streamController', 1),
(8, 'messages', 1),
(9, 'groups', 1),
(10, 'group', 1),
(11, 'api', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`creator` int(11) NOT NULL,
`name` varchar(255) NOT NULL,
`description` longtext NOT NULL,
`event_date` date NOT NULL,
`start_time` time NOT NULL,
`end_time` time NOT NULL,
`type` enum('public','private') NOT NULL,
`active` tinyint(1) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `events`
--


-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

CREATE TABLE IF NOT EXISTS `event_attendees` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`event_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`status` enum('invited','going','not going','maybe') NOT NULL,
PRIMARY KEY (`ID`),
KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `event_attendees`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL,
`description` longtext NOT NULL,
`creator` int(11) NOT NULL,
`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`type` enum('public','private','private-member-invite','private-self-invite') NOT NULL,
`active` tinyint(1) NOT NULL DEFAULT '1',
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`ID`, `name`, `description`, `creator`, `created`, `type`, `active`) VALUES
(1, 'test group', 'test group about xyz', 1, '2010-08-02 01:15:53', 'public', 1),
(2, 'Dinosaur Activities in the North East', 'Group dedicated to the promotion of dinosaur friendly activities in the North East of England', 1, '2010-08-02 02:14:24', 'public', 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_membership`
--

CREATE TABLE IF NOT EXISTS `group_membership` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`group` int(11) NOT NULL,
`user` int(11) NOT NULL,
`approved` tinyint(1) NOT NULL DEFAULT '0',
`requested` tinyint(1) NOT NULL DEFAULT '0',
`invited` tinyint(1) NOT NULL DEFAULT '0',
`requested_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`invited_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`join_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`inviter` int(11) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `group_membership`
--

INSERT INTO `group_membership` (`ID`, `group`, `user`, `approved`, `requested`, `invited`, `requested_date`, `invited_date`, `join_date`, `inviter`) VALUES
(1, 2, 2, 1, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE IF NOT EXISTS `rates` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`value` float(4) NOT NULL,
`status_type` varchar(11) NOT NULL,
`status_id` int(11) NOT NULL,
`rater` varchar(255) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`ID`, `value`, `status_type`, `status_id`, `rater`) VALUES
(1, 4, 'status', 32, 1),
(2, 3, 'comments', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`sender` int(11) NOT NULL,
`recipient` int(11) NOT NULL,
`sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`read` tinyint(1) NOT NULL,
`reply` tinyint(1) NOT NULL,
`subject` varchar(255) NOT NULL,
`message` longtext NOT NULL,
`type` int(1) NOT NULL,
`URL` varchar(255) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`ID`, `sender`, `recipient`, `sent`, `read`, `reply`, `subject`, `message`, `type`, `URL`) VALUES
(1, 2, 3, '2010-06-27 23:19:41', 1, 0, 'test', 'test msg', 1, ''),
(4, 2, 1, '2010-06-04 16:26:29', 1, 0, 'Saturday?', 'Are you still up for going hill walking with Mr. Glen on Saturday; let me know if you do need to borrow my t-rex leash, as I have a spare one.\r\n
<br/>\r\nCheers,<br/>\r\nRick', 1, ''),
(3, 2, 1, '2010-06-01 16:25:57', 1, 0, 'Check out this link', '', 1, ''),
(5, 3, 1, '2010-06-10 16:26:42', 1, 0, 'Hi', '', 1, ''),
(6, 1, 2, '2010-06-30 17:12:27', 0, 0, 'Re: Saturday?', 'Yes!', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`topic` int(11) NOT NULL,
`post` longtext NOT NULL,
`creator` int(11) NOT NULL,
`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`ID`, `topic`, `post`, `creator`, `created`) VALUES
(1, 1, 'We are planning on arranging a regular walk around the riverside park with our T-Rex''s - anyone want to join us?', 1, '2010-07-15 12:20:22'),
(2, 2, 'This is another new topic', 1, '2010-07-20 02:50:52'),
(3, 0, 'this is a test reply', 1, '2010-08-02 03:08:51'),
(4, 2, 'this is a test reply', 1, '2010-07-21 02:50:52');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
`user_id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(255) NOT NULL,
`name` varchar(255) NOT NULL,
`dob` varchar(255) NOT NULL,
`location` varchar(255) NOT NULL,
`gender` varchar(255) NOT NULL,
`photo` varchar(255) NOT NULL,
`bio` longtext NOT NULL,
PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile`(`user_id`, `username`, `name`) VALUES (0,'admin','Web Administrator');
INSERT INTO `profile` (`user_id`, `name`, `username`, `gender`, `photo`, `bio`, `dob`) VALUES
(1, 'Palash Choudhury', 'zero', 'male', '1388493126_1388202599_28348_121812137842746_646493_n(1).gif', 'I''m a someone from India', '23-07-1992');
INSERT INTO `profile` (`user_id`, `name`, `username`, `gender`, `photo`, `bio`, `dob`) VALUES
(2, 'Palash Rules', 'palash', 'male', '1ngo.gif', 'I''m a another one from India', '23-07-1992');

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE IF NOT EXISTS `relationships` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`type` int(11) NOT NULL,
`usera` int(11) NOT NULL,
`userb` int(11) NOT NULL,
`accepted` tinyint(1) NOT NULL,
PRIMARY KEY (`ID`),
KEY `type` (`type`,`usera`,`userb`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `relationships`
--

INSERT INTO `relationships` (`ID`, `type`, `usera`, `userb`, `accepted`) VALUES
(1, 3, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `relationship_types`
--

CREATE TABLE IF NOT EXISTS `relationship_types` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL,
`plural_name` varchar(255) NOT NULL,
`active` tinyint(1) NOT NULL DEFAULT '1',
`mutual` tinyint(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `relationship_types`
--

INSERT INTO `relationship_types` (`ID`, `name`, `plural_name`, `active`, `mutual`) VALUES
(1, 'Friend', 'friends', 1, 1),
(2, 'Colleague', 'colleagues', 1, 1),
(3, 'subscriber', 'subscribers', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`key` varchar(255) NOT NULL,
`value` longtext NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`ID`, `key`, `value`) VALUES
(1, 'view', 'default'),
(2, 'sitename', 'GeoboxX'),
(3, 'siteurl', 'http://localhost/social/'),
(4, 'captcha.enabled', '0'),
(5, 'upload_path', 'D:/Projects/xampp/htdocs/social/uploads/'),
(6, 'baseurl', 'http://localhost');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE IF NOT EXISTS `statuses` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`update` longtext NOT NULL,
`type` int(255) NOT NULL,
`poster` int(11) NOT NULL,
`profile` int(11) NOT NULL,
`posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`ID`),
KEY `poster` (`poster`,`profile`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`ID`, `update`, `type`, `poster`, `profile`, `posted`) VALUES
(1, 'Test ABC', 1, 1, 0, '2010-05-13 17:40:52'),
(2, 'Look at this', 0, 1, 0, '2010-05-02 12:31:20'),
(3, 'Test - 1.2.3.4', 1, 1, 1, '2010-05-13 17:41:03'),
(4, 'This is an update on someones profile', 1, 2, 1, '2010-06-02 21:53:34'),
(5, 'This is another update on someones profile', 1, 1, 2, '2010-06-02 21:53:37'),
(6, 'Nice to see you on here!', 1, 3, 1, '2010-06-22 22:20:43'),
(12, 'Taking my Dino out for a walk', 1, 1, 1, '2010-06-27 21:30:10'),
(32, 'I loved this movie!', 3, 1, 1, '2010-07-02 23:38:39'),
(33, 'Really useful site!', 4, 1, 1, '2010-07-02 23:52:56'),
(30, 'I''m on stage rehearsing!', 2, 1, 1, '2010-07-02 23:02:42');

-- --------------------------------------------------------

--
-- Table structure for table `statuses_images`
--

CREATE TABLE IF NOT EXISTS `statuses_images` (
`id` int(11) NOT NULL,
`image` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statuses_images`
--

INSERT INTO `statuses_images` (`id`, `image`) VALUES
(30, 'nexus.gif');

-- --------------------------------------------------------

--
-- Table structure for table `statuses_links`
--

CREATE TABLE IF NOT EXISTS `statuses_links` (
`id` int(11) NOT NULL,
`URL` varchar(255) NOT NULL,
`description` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statuses_links`
--

INSERT INTO `statuses_links` (`id`, `URL`, `description`) VALUES
(33, 'http://www.geoboxx.com', 'Our Website Address');

-- --------------------------------------------------------

--
-- Table structure for table `statuses_videos`
--

CREATE TABLE IF NOT EXISTS `statuses_videos` (
`id` int(11) NOT NULL,
`video_id` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statuses_videos`
--

INSERT INTO `statuses_videos` (`id`, `video_id`) VALUES
(32, 'SagTkN19veU');

-- --------------------------------------------------------

--
-- Table structure for table `status_types`
--

CREATE TABLE IF NOT EXISTS `status_types` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`type_name` varchar(100) NOT NULL,
`type_reference` varchar(50) NOT NULL,
`active` tinyint(1) NOT NULL DEFAULT '1',
`type_name_other` varchar(255) NOT NULL,
PRIMARY KEY (`ID`),
UNIQUE KEY `type_reference` (`type_reference`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `status_types`
--

INSERT INTO `status_types` (`ID`, `type_name`, `type_reference`, `active`, `type_name_other`) VALUES
(1, 'Changed their status to', 'update', 1, ''),
(2, 'Shared an image', 'image', 1, ''),
(3, 'Shared a video', 'video', 1, ''),
(4, 'Shared a link', 'link', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(255) NOT NULL,
`creator` int(11) NOT NULL,
`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`active` tinyint(1) NOT NULL DEFAULT '1',
`group` int(11) NOT NULL,
PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`ID`, `name`, `creator`, `created`, `active`, `group`) VALUES
(1, 'Walk through Riverside Park, Chester-le-Street', 1, '2010-07-15 12:20:22', 1, 2),
(2, 'This is another new topic', 1, '2010-07-20 02:50:52', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`ID` int(20) NOT NULL AUTO_INCREMENT,
`username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`password_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
`password_salt` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
`email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`active` tinyint(1) NOT NULL DEFAULT '0',
`admin` tinyint(1) NOT NULL DEFAULT '0',
`banned` tinyint(1) NOT NULL DEFAULT '0',
`reset_key` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
`reset_expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
`deleted` tinyint(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `password_hash`, `password_salt`, `email`, `active`, `admin`, `banned`, `reset_key`, `reset_expires`, `deleted`) VALUES
(1, 'zero', '3bad6af0fa4b8b330d162e19938ee9814aw2r', '4aw2r', 'maxzeroedge@gmail.com', 1, 0, 0, '', '0000-00-00 00:00:00', 0);