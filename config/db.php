<?php

// FOR DATABASE
define("DB_DSN", "mysql:host=localhost;dbname=p5-blog");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_OPTIONS", array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// FOR MAIL
define("MAIL_HOST", "");
define("MAIL_PORT", 000); 
define("MAIL_FROM", ""); 
define("MAIL_PASSWORD", "");
define("MAIL_USERNAME", "Admin Blog Annaig Jégourel");
define("MAIL_TO", "");
define("MAIL_TO_NAME", "Annaig Yahoo");
define("MAIL_CC", "");
define("MAIL_CC_NAME", "Annaig Ensam");