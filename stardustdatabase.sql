-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 28, 2024 at 03:38 PM
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
-- Database: `stardustdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentID` int(11) NOT NULL,
  `postID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `commentText` text NOT NULL,
  `dateCommented` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentID`, `postID`, `userID`, `commentText`, `dateCommented`) VALUES
(38, 47, 70, 'i lvoe jackie chan', '2024-06-27 13:47:55');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `postID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `dateLiked` datetime NOT NULL DEFAULT current_timestamp(),
  `likeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`postID`, `userID`, `dateLiked`, `likeID`) VALUES
(5, 69, '2024-06-21 20:02:24', 14),
(7, 69, '2024-06-21 20:02:34', 17),
(13, 70, '2024-06-27 13:48:00', 26),
(5, 70, '2024-06-27 13:48:01', 27);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `postID` int(11) NOT NULL,
  `imageFilePath` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  `datePosted` datetime NOT NULL DEFAULT current_timestamp(),
  `caption` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`postID`, `imageFilePath`, `userID`, `datePosted`, `caption`) VALUES
(5, 'test4.jpg', 69, '2024-06-20 09:30:32', 'how i sleep after trying to find a bug for 3 hours'),
(13, 'jjrangzx-1-tester4.jpg', 69, '2024-06-20 13:30:32', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore '),
(47, 'jjrangzx_69_667b2847b0c7e.jpg', 69, '2024-06-25 15:27:51', '');

-- --------------------------------------------------------

--
-- Table structure for table `relationships`
--

CREATE TABLE `relationships` (
  `relationshipID` int(11) NOT NULL,
  `followerID` int(11) NOT NULL,
  `followingID` int(11) NOT NULL,
  `followedDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relationships`
--

INSERT INTO `relationships` (`relationshipID`, `followerID`, `followingID`, `followedDate`) VALUES
(4, 69, 70, '2024-06-27 15:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(40) NOT NULL,
  `profilePic` varchar(255) DEFAULT 'defaultProfilePic.png',
  `biography` text DEFAULT NULL,
  `dateJoined` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `email`, `profilePic`, `biography`, `dateJoined`) VALUES
(69, 'jjrangzx', '$2y$10$KhCzfOg0v/R0DYK4BIZfWuyx6w6ha1SWHaXovVEjvXPRRkN9QXWjG', 'jjrangzx@gmail.com', 'jjrangzx-69-profilePicture.png', 'i love banana!!!', '2024-06-19 16:22:10'),
(70, 'sillygirl24', '$2y$10$hB/lcZOa2GJva3UcrDhPc.k2vqeTpjpRdKKJQ7dQyprIkC0acsir2', 'tester@gmail.com', 'defaultProfilePic.png', 'testing account for comments', '2024-06-27 16:22:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `fk_comments_users` (`userID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`),
  ADD KEY `fk_likes_users` (`userID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postID`),
  ADD KEY `fk_user` (`userID`);

--
-- Indexes for table `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`relationshipID`),
  ADD KEY `fk_follower` (`followerID`),
  ADD KEY `fk_following` (`followingID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `postID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `relationships`
--
ALTER TABLE `relationships`
  MODIFY `relationshipID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_users` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_users` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `relationships`
--
ALTER TABLE `relationships`
  ADD CONSTRAINT `fk_follower` FOREIGN KEY (`followerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_following` FOREIGN KEY (`followingID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
