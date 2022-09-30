<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try{
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;      //Je veux des infos de debug
    
    //On configure le SMTP
    $mail->isSMTP();                     //Send using SMTP
    $mail->Host       = 'localhost';    //Set the SMTP server to send through
    
    
    /* Authentification ? 
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
   */
    // Quel port utiliser?? Apache 80, MySQL 3306 ... 
    /**
    * To send email with XAMPP, use the PEAR Mail and Net_SMTP packages, which allow you to send email using an external SMTP account (such as a Gmail account). Follow these steps:
    * Install the Mail and Net_SMTP PEAR modules: 
    */
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Charset (pas dans la doc officielle)
    $mail->CharSet = "utf-8";

    //To load the French version
    /*$mail->setLanguage('fr', '/optional/path/to/language/directory/');*/

    //Destinataires
    $mail->addAddress("a_jegourel@yahoo.fr", "Annaig Admin");     //Name is optional 
    $mail->addAddress("annaig.jegourel@ensam.eu");             //en ajouter autant qu'on veut
    //ajouter qqn en copie
    $mail->addCC("naje.3322@yahoo.fr");
    //ajouter qqn en copie cachée
    $mail->addBCC('bcc@example.com');

    //Expéditeur
    $mail->setFrom('from@example.com', 'Mailer'); 

    /*$mail->addReplyTo('info@example.com', 'Information');*/

    //Attachments
   /* $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name*/

    //Content
/*    $mail->isHTML(true); */                                 //Set email format to HTML
    $mail->Subject = "Voici le sujet";
    $mail->Body    = "Ceci est le message son body son message";
    $mail->AltBody = "This is the body in plain text for non-HTML mail clients";

    //On envoie
    $mail->send();
    echo "Message envoyé";

} catch(Exception $e) {
    echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";
}