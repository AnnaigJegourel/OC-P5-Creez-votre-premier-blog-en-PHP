<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;*/


/**
 * Class HomeController
 * manages the Homepage
 * @package App\Controller
 */
class HomeController extends MainController
{
    /**
     * Renders the View Home
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        return $this->twig->render("home.twig");
    }

    /**
     * Treats contact form data
     * Sends data to admin e-mail address
     * @return void
     */
    public function contactMethod()
    {
        /*$mail = new PHPMailer(true);*/

        $to = "a_jegourel@yahoo.fr";
        $subject= $this->putSlashes($this->getPost()["choice"]);
        $message = "Line 1\r\nLine 2\r\nLine 3";
        $text = $this->putSlashes($this->getPost()["message"]);
        $name = $this->putSlashes($this->getPost()["name"]);
        $reply_to = $this->putSlashes($this->getPost()["email"]);

        mail($to, $subject, $text, $name, $reply_to);
        $message = "Votre message a bien été envoyé. Nous vous répondrons dès que possible.";
        /* oui enfin non y a rien qui arrive dans ma boîte mail (spams compris) */
        return $this->twig->render("message.twig", ["message" => $message]);
    }
}