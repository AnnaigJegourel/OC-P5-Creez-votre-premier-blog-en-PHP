<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
        $to = "a_jegourel@yahoo.fr";
        $subject= addslashes($this->getPost()["choice"]);
        /* $message = "Line 1\r\nLine 2\r\nLine 3"; */
        $text = addslashes($this->getPost()["message"]);
        $name = addslashes($this->getPost()["name"]);
        $reply_to = addslashes($this->getPost()["email"]);

        mail($to, $subject, $text, $name, $reply_to);
        $message = "Votre message a bien été envoyé. Nous vous répondrons dès que possible.";
        /* oui enfin non y a rien qui arrive dans ma boîte mail (spams compris) */
        return $this->twig->render("message.twig", ["message" => $message]);
    }
}