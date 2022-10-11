<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MailerController
 * manages PHPMailer
 * @package App\Controller
 */
class MailerController extends MainController
{
    private $mail = [];

    /**
     * PHPMailer constructor
     * creates a PHPMailer object
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    /**
     * Sends the e-mail & renders the view message
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        require_once "../config/db.php";

        // Encoder en utf-8? Caractères spéciaux non lus dans emails reçus
        // utf8_encode() & _decode() sont obsolètes
        $data = [       
            "name" => $this->getPost()["name"],
            "email" => $this->getPost()["email"],
            "subject" => $this->getPost()["subject"],
            "message" => $this->getPost()["message"]
        ];

        try{
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;      

            $this->mail->isSMTP();                     
            $this->mail->Host       = MAIL_HOST;    
    
            $this->mail->SMTPAuth   = true;                               
            $this->mail->Username   = MAIL_FROM;      
            $this->mail->Password   = MAIL_PASSWORD;                          
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        
        
            $this->mail->Port       = MAIL_PORT;      

            $this->mail->setFrom(MAIL_FROM, MAIL_USERNAME); 
            $this->mail->addAddress(MAIL_TO, MAIL_TO_NAME);

            $this->mail->addReplyTo($data["email"], $data["name"]);

            $this->mail->Subject = $data["subject"];
            $this->mail->Body    = $data["message"];
            $this->mail->AltBody = $data["message"];
            
            $this->mail->send();

            // Affichage sur le site en cas de succès:
            echo "Message envoyé";                      
            // OU:

            /* render = ERROR:
            ** "Call to a member function render() on null"
            ** Index l.14 / Router l.99
            $message = "Votre e-mail a bien été envoyé.";                 
            return $this->twig->render("Front/message.twig", ["message" => $message]);
            */
            
            /* redirect = ERROR: 
            ** "Cannot modify header information - 
            ** headers already sent by (output started at /...vendor/phpmailer.../src/SMTP.php:284) 
            ** in .../MainController.php:52"
            ** $this->redirect("home");     
            */   

        } catch(Exception) {
            echo "Message non envoyé. Erreur: {$this->mail->ErrorInfo}";
        }
    }
}