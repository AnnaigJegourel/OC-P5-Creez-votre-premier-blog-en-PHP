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
 * 
 * Manages PHPMailer
 * 
 * @package App\Controller
 */
class MailerController extends MainController
{
    private $mail = [];

    /**
     * PHPMailer constructor
     * 
     * Creates a PHPMailer object
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    /**
     * Sends the e-mail & renders the view message
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
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

            $this->mail->CharSet = "utf-8";

            $this->mail->setFrom(MAIL_FROM, MAIL_USERNAME); 
            $this->mail->addAddress(MAIL_TO, MAIL_TO_NAME);

            $this->mail->addReplyTo($data["email"], $data["name"]);

            $this->mail->Subject = $data["subject"];
            $this->mail->Body    = $data["message"];
            $this->mail->AltBody = $data["message"];
            
            $this->mail->send();

            $message = "Votre e-mail a bien été envoyé.";     

        } catch(Exception) {
            $message = "Votre e-mail n'a pas été envoyé. Erreur: {$this->mail->ErrorInfo}";   
        }

        $this->setMessage($message);
        $this->redirect("home"); 
    }
}