-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 07:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Database: `comment`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
    `comment_id` int(11) NOT NULL,
    `post_id` int(11) NOT NULL,
    `parent_comment_id` int(11) DEFAULT NULL,
    `commenter_name` varchar(100) NOT NULL,
    `comment_text` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO
    `comments` (
        `comment_id`,
        `post_id`,
        `parent_comment_id`,
        `commenter_name`,
        `comment_text`,
        `created_at`
    )
VALUES (
        1,
        1,
        NULL,
        'user1',
        'This is a comment for post 1.',
        '2024-11-13 05:48:00'
    ),
    (
        2,
        1,
        NULL,
        'user2',
        'This is another comment for post 1.',
        '2024-11-13 05:48:00'
    ),
    (
        3,
        1,
        1,
        'user3',
        'This is a reply to the first comment on post 1.',
        '2024-11-13 05:48:00'
    ),
    (
        4,
        2,
        NULL,
        'user4',
        'This is a comment for post 2.',
        '2024-11-13 05:48:00'
    ),
    (
        5,
        2,
        NULL,
        'user5',
        'Another comment for post 2.',
        '2024-11-13 05:48:00'
    ),
    (
        6,
        2,
        4,
        'user6',
        'Reply to the first comment on post 2.',
        '2024-11-13 05:48:00'
    ),
    (
        7,
        3,
        NULL,
        'user7',
        'Comment for post 3.',
        '2024-11-13 05:48:00'
    ),
    (
        8,
        3,
        NULL,
        'user8',
        'Another comment for post 3.',
        '2024-11-13 05:48:00'
    ),
    (
        9,
        3,
        7,
        'user9',
        'Reply to the first comment on post 3.',
        '2024-11-13 05:48:00'
    ),
    (
        10,
        3,
        8,
        'user10',
        'Reply to the second comment on post 3.',
        '2024-11-13 05:48:00'
    );

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
    `post_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO
    `posts` (
        `post_id`,
        `title`,
        `content`,
        `created_at`
    )
VALUES (
        1,
        'Post Title 1',
        'Content for post 1',
        '2024-11-13 05:48:00'
    ),
    (
        2,
        'Post Title 2',
        'Content for post 2',
        '2024-11-13 05:48:00'
    ),
    (
        3,
        'Post Title 3',
        'Content for post 3',
        '2024-11-13 05:48:00'
    ),
    (
        4,
        'Post Title 4',
        'Content for post 4',
        '2024-11-13 05:48:00'
    ),
    (
        5,
        'Post Title 5',
        'Content for post 5',
        '2024-11-13 05:48:00'
    ),
    (
        6,
        'Post Title 6',
        'Content for post 6',
        '2024-11-13 05:48:00'
    ),
    (
        7,
        'Post Title 7',
        'Content for post 7',
        '2024-11-13 05:48:00'
    ),
    (
        8,
        'Post Title 8',
        'Content for post 8',
        '2024-11-13 05:48:00'
    ),
    (
        9,
        'Post Title 9',
        'Content for post 9',
        '2024-11-13 05:48:00'
    ),
    (
        10,
        'Post Title 10',
        'Content for post 10',
        '2024-11-13 05:48:00'
    );

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
    `user_id` int(11) NOT NULL,
    `username` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO
    `users` (
        `user_id`,
        `username`,
        `email`,
        `password`,
        `created_at`
    )
VALUES (
        1,
        'user1',
        'user1@example.com',
        'password1',
        '2024-11-13 05:48:00'
    ),
    (
        2,
        'user2',
        'user2@example.com',
        'password2',
        '2024-11-13 05:48:00'
    ),
    (
        3,
        'user3',
        'user3@example.com',
        'password3',
        '2024-11-13 05:48:00'
    ),
    (
        4,
        'user4',
        'user4@example.com',
        'password4',
        '2024-11-13 05:48:00'
    ),
    (
        5,
        'user5',
        'user5@example.com',
        'password5',
        '2024-11-13 05:48:00'
    ),
    (
        6,
        'user6',
        'user6@example.com',
        'password6',
        '2024-11-13 05:48:00'
    ),
    (
        7,
        'user7',
        'user7@example.com',
        'password7',
        '2024-11-13 05:48:00'
    ),
    (
        8,
        'user8',
        'user8@example.com',
        'password8',
        '2024-11-13 05:48:00'
    ),
    (
        9,
        'user9',
        'user9@example.com',
        'password9',
        '2024-11-13 05:48:00'
    ),
    (
        10,
        'user10',
        'user10@example.com',
        'password10',
        '2024-11-13 05:48:00'
    );

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
ADD PRIMARY KEY (`comment_id`),
ADD KEY `fk_comments_post_id` (`post_id`),
ADD KEY `fk_comments_parent_comment_id` (`parent_comment_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts` ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`user_id`),
ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
ADD CONSTRAINT `fk_comments_parent_comment_id` FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_comments_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;