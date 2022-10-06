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

        $data = [       
            "name" => $this->putSlashes($this->getPost()["name"]),
            "email" => $this->putSlashes($this->getPost()["email"]),
            "subject" => $this->putSlashes($this->getPost()["subject"]),
            //"message" => $this->putSlashes($this->getPost()["message"])
            "message" => $this->getPost()["message"]    //ok mais pas sécu?
        ];

        try{
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;      

            $this->mail->isSMTP();                     
            $this->mail->Host       = MAIL_HOST;    
    
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = MAIL_FROM;      
            $this->mail->Password   = MAIL_PASSWORD;                          
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        
            /**
            * To send email with XAMPP, use the PEAR Mail and Net_SMTP packages, which allow you to send email using an external SMTP account (such as a Gmail account). Follow these steps:
            * Install the Mail and Net_SMTP PEAR modules: 
            */
            $this->mail->Port       = MAIL_PORT;                               //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //To load the French version
            /*$this->mail->setLanguage('fr', '/optional/path/to/language/directory/');*/

            //Expéditeur
            $this->mail->setFrom(MAIL_FROM, MAIL_USERNAME); 

            //Destinataires
            $this->mail->addAddress(MAIL_TO, MAIL_TO_NAME);     //Name is optional 
            //ajouter qqn en copie
            $this->mail->addCC(MAIL_CC, MAIL_CC_NAME);
            //ajouter qqn en copie cachée
            //$this->mail->addBCC('bcc@example.com');

            // Répondre à : email et nom saisis dans form
            $this->mail->addReplyTo($data["email"], $data["name"]);

            //Attachments
            /* $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name*/

            //Content
            /*    $this->mail->isHTML(true); */                  //Set email format to HTML
            // sujet et message saisis dans form                    
            $this->mail->Subject = $data["subject"];
            $this->mail->Body    = $data["message"];
            $this->mail->AltBody = $data["message"];
            
            //On envoie
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