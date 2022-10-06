<?php

// FOR DATABASE
define("DB_DSN", "mysql:host=localhost;dbname=p5-blog");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_OPTIONS", array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// FOR MAIL
define("MAIL_HOST", "mail.annaig-jegourel.net");
define("MAIL_PORT", 465); 
define("MAIL_FROM", "admin@annaig-jegourel.net"); 
define("MAIL_PASSWORD", "rPN-WgV=38S");
define("MAIL_USERNAME", "Admin Blog Annaig Jégourel");
define("MAIL_TO", "a_jegourel@yahoo.fr");
define("MAIL_TO_NAME", "Annaig Yahoo");
define("MAIL_CC", "annaig.jegourel@ensam.eu");
define("MAIL_CC_NAME", "Annaig Ensam");