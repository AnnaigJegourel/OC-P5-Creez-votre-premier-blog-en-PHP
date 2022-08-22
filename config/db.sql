-- WARNING : add the file name inside the .gitignore file if adding sensible datas here 

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- For Development only ! 
DROP DATABASE IF EXISTS `p5-blog`;
CREATE DATABASE `p5-blog` CHARACTER SET utf8;

-- Needs to be replaced in Production with the db name of the online server
USE `p5-blog`;


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
) ENGINE = InnoDB;

-- Inserts the User data
-- WARNING : Never store real passwords in a commit file
INSERT INTO `User`
(`name`, `email`, `password`, `role`)
VALUES
('John', 'john@doe.com', 'john465', 0),
('Jane', 'jane@doe.com', 'jane465', 0);


-- -----------------------------------------------------
-- Table Post
-- -----------------------------------------------------
CREATE TABLE `Post` (
    `id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `date_created` DATETIME NOT NULL,
    `date_updated` DATETIME,
    `title` VARCHAR(100) NOT NULL,
    `intro` TINYTEXT NOT NULL,
    `content` MEDIUMTEXT NOT NULL,
    `user_id` SMALLINT UNSIGNED NOT NULL,
    CONSTRAINT `post_fk_user_id`
        FOREIGN KEY (`user_id`)
        REFERENCES `User` (`id`)
) ENGINE = InnoDB;

-- Inserts the posts data
INSERT INTO `Post`
(`date_created`, `date_updated`, `title`, 
`intro`, 
`content`, 
`user_id`)
VALUES
("2022-07-30", "2022-07-31", 'Premier article', 
'Ce premier article est une présentation du projet. En formation pour devenir développeuse web back-end, j apprends PHP et Symfony avec OpenClassrooms.', 
'Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
1),
("2022-08-01", "2022-08-02", 'Second article', 
'chapô chapô chapô chapi chapeau chapô chapô chapô chapi chapeau chapô chapô chapô chapi chapeau',
'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. 
Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \'de Finibus Bonorum et Malorum\' (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \'Lorem ipsum dolor sit amet...\', comes from a line in section 1.10.32.
The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \'de Finibus Bonorum et Malorum\' by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.',
2);

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