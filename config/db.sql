-- WARNING : add the file name inside the .gitignore file if adding sensible datas here 

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- For Development only ! 
DROP DATABASE IF EXISTS `p5-blog`
CREATE DATABASE `p5-blog` CHARACTER SET utf8

-- Needs to be replaced in Production with the db name of the online server
USE `p5-blog`


-- -----------------------------------------------------
-- Table User
-- -----------------------------------------------------
CREATE TABLE `User` (
    `id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `date_created` DATETIME NOT NULL,
    `role` TINYINT(1) NOT NULL
) ENGINE = InnoDB

-- Inserts the User data
-- WARNING : Never store real passwords in a commit file
INSERT INTO `User`
(`name`, `email`, `password`, `role`)
VALUES
('John', 'john@doe.com', 'john465', 0),
('Jane', 'jane@doe.com', 'jane465', 0)


-- -----------------------------------------------------
-- Table Post
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Post` (
    `id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `date_created` DATETIME NOT NULL,
    `date_updated` DATETIME NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `intro` TINYTEXT NOT NULL,
    `content` MEDIUMTEXT NOT NULL,
    `user_id` SMALLINT UNSIGNED NOT NULL,
    CONSTRAINT `post_fk_user_id`
        FOREIGN KEY (`user_id`)
        REFERENCES `User` (`id`)
) ENGINE = InnoDB


-- -----------------------------------------------------
-- Table Comment
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Comment` (
    `id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `date_created` DATETIME NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `content` MEDIUMTEXT NOT NULL,
    `user_id` SMALLINT UNSIGNED NOT NULL,
    `post_id` SMALLINT UNSIGNED NOT NULL,
    CONSTRAINT `comment_fk_user_id`
        FOREIGN KEY (`user_id`)
        REFERENCES `User` (`id`),
    CONSTRAINT `comment_fk_post_id`
        FOREIGN KEY (`post_id`)
        REFERENCES `Post` (`id`)
) ENGINE = InnoDB;