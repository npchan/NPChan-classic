CREATE TABLE `user_info` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(150) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `browser_info` TEXT NOT NULL,
  `last_active` INT NOT NULL,
  `trip_code` VARCHAR(10) NOT NULL,
  `username` VARCHAR(45) NULL DEFAULT 'Anonymous',
  `password` VARCHAR(150) NULL,
  `language` VARCHAR(15) NOT NULL DEFAULT 'English',
  `theme` VARCHAR(15) NOT NULL DEFAULT 'Default',
  `nsfw` TINYINT NULL DEFAULT 1,
  `agree_terms` TINYINT NULL DEFAULT 0,
  `site_intro` TINYINT NULL DEFAULT 1,
  `board_intro` TINYINT NULL DEFAULT 1,
  `trending` TINYINT NULL DEFAULT 1,
  `thread_box` TINYINT NULL DEFAULT 1,
  `blotter` TINYINT NULL DEFAULT 1,
  `show_spoiler` TINYINT NULL DEFAULT 0,
  `show_adult` TINYINT NULL DEFAULT 0,
  `linkify` TINYINT NULL DEFAULT 1,
  `slang_filter` TINYINT NULL DEFAULT 0,
  `npchan_x` TINYINT NULL DEFAULT 0,
  `js` TINYINT NULL DEFAULT 1,
  `save_history` TINYINT NULL DEFAULT 1,
  `notify` TINYINT NULL DEFAULT 1,
  `random_image` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`id`));


CREATE TABLE `ip_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `country` VARCHAR(45) NOT NULL,
  `timestamp` INT NOT NULL,
  PRIMARY KEY (`id`));


CREATE TABLE `boards` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `board` VARCHAR(15) NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  `category` VARCHAR(15) NOT NULL,
  `safe` TINYINT NOT NULL DEFAULT 1,
  `locked` TINYINT NOT NULL DEFAULT 0,
  `require_image` TINYINT NOT NULL DEFAULT 0,
  `bump_time` INT(3) NULL DEFAULT 60,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `board_UNIQUE` (`board` ASC));


CREATE TABLE `threads` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `board` VARCHAR(15) NULL,
  `title` VARCHAR(150) NULL,
  `body` LONGTEXT NULL,
  `body_raw` LONGTEXT NULL,
  `time_stamp` INT NULL,
  `options` VARCHAR(150) NULL,
  `thread_start` VARCHAR(1) NULL DEFAULT 'N',
  `thread_reply` INT NULL DEFAULT 0,
  `user_id` INT NULL,
  `files` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `browser_info` TEXT NULL,
  `bumped_on` INT NULL DEFAULT 0,
  `country` VARCHAR(45) NULL DEFAULT 'Nepal',
  `has_image` VARCHAR(1) NULL DEFAULT 'N',
  `pinned` VARCHAR(1) NULL DEFAULT 'N',
  `locked` VARCHAR(1) NULL DEFAULT 'N',
  `archived` VARCHAR(1) NULL DEFAULT 'N',
  `replies` INT(3) NULL DEFAULT 0,
  `images` INT(3) NULL DEFAULT 0,
  PRIMARY KEY (`id`));


CREATE TABLE `random_image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(100) NOT NULL,
  `board` VARCHAR(15) NULL DEFAULT 'All',
  PRIMARY KEY (`id`));


CREATE TABLE `mod_login`(
  `id` int primary key auto_increment,
  `username` varchar(30) not null,
  `password` varchar(260) not null,
  `cookie` varchar(260) not null default 'first_login',
  `last_seen` int default 0,
  `rank` int default 0,
  `login_attempt` int default 0);


CREATE TABLE `mod_activity`(
  `id` int primary key auto_increment,
  `username` varchar(30) not null,
  `ip_address` varchar(50) not null,
  `browser_info` TEXT not null,
  `activity_short` varchar(20) not null,
  `activity` TEXT,
  `time_stamp` int);


CREATE TABLE `mod_chat`(
  `id` int primary key auto_increment,
  `username` varchar(30),
  `msg` TEXT,
  `time_stamp` int);