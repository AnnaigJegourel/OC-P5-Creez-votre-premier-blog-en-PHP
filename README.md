# OC-P5-Créez votre premier blog en PHP

Training program "Back-end Developer: PHP/Symfony" (OpenClassrooms)<br>
Project 5:  Blog made from scratch, using PHP, OO paradigm & model-view-controller pattern.

<a href="https://codeclimate.com/github/AnnaigJegourel/OC-P5-Creez-votre-premier-blog-en-PHP/maintainability"><img src="https://api.codeclimate.com/v1/badges/45cffc5f39efdfb0c1b5/maintainability" /></a>
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/67632e5883c04645a5864846ade98bab)](https://www.codacy.com/gh/AnnaigJegourel/OC-P5-Creez-votre-premier-blog-en-PHP/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=AnnaigJegourel/OC-P5-Creez-votre-premier-blog-en-PHP&amp;utm_campaign=Badge_Grade)

## Configuration / Technologies

xamppserver<br>
10.4.21-MariaDB<br>
PHP 8.1.6<br>
Composer 2.3.0<br>

## Installation:

1. Clone the repository
2. Upload & install xamppserver: [https://www.wampserver.com/en/download-wampserver-64bits/](https://www.apachefriends.org)
3. Launch xamppserver, configure your php version to 8.1.6
4. Go to localhost/phpmyadmin/
5. Create a new database & name it "p5-blog"
6. Import the database using db.sql (file at the root of this project)
7. Launch a terminal at the root of the project & run the command "composer intall"

Your project is ready!

## Contexte / Mise en situation :

Vous avez besoin de visibilité pour pouvoir convaincre vos futurs employeurs/clients en un seul regard. 
Vous êtes développeuse PHP, il est donc temps de montrer vos talents au travers d’un blog à vos couleurs.

### Description :

Vous allez développer votre blog professionnel, qui se décomposera en deux grands groupes de pages :

👥 les pages utiles à tous les visiteurs ;
<br>
👤 les pages permettant d’administrer votre blog.

Voici la liste des pages qui devront être accessibles depuis votre site web :

📄 la page d'accueil ;
<br>
📄 la page listant l’ensemble des blog posts ;
<br>
📄 la page affichant un blog post ;
<br>
📄 la page permettant d’ajouter un blog post ;
<br>
📄 la page permettant de modifier un blog post ;
<br>
📑 les pages permettant de modifier/supprimer un blog post ;
<br>
📑 les pages de connexion/enregistrement des utilisateurs.

### Contraintes :

➡️ Nous n’utiliserons pas WordPress : tout sera développé par vos soins. 
<br>
➡️ Il est autorisé d’utiliser un thème Bootstrap ainsi. qu'une ou plusieurs librairies externes, 
à condition qu’elles soient intégrées grâce à Composer.
<br>
➡️ Votre blog doit être navigable aisément sur un mobile (téléphone mobile, phablette, tablette…). 
<br>
➡️ Vous développerez une partie administration qui devra être accessible uniquement aux utilisateurs inscrits et validés,
et vous veillerez à sa sécurité. Les autres utilisateurs pourront uniquement commenter les articles (avec validation avant publication).
<br>
➡️ Vous vous assurerez qu’il n’y a pas de failles de sécurité 
(XSS, CSRF, SQL Injection, session hijacking, upload possible de script PHP…).
<br>
➡️ Votre projet doit être poussé et disponible sur GitHub. Il est conseillé de travailler avec des pull requests. 
<br>
➡️ Il faut que vos commits soient en anglais.
<br>
➡️ Vous devrez créer l’ensemble des issues (tickets) correspondant aux tâches que vous aurez à effectuer.
<br>
➡️ Votre projet devra être suivi via SymfonyInsight, ou Codacy pour la qualité du code. 
<br>
➡️ Vous veillerez à obtenir une médaille d'argent au minimum (pour SymfonyInsight). 
<br>
➡️ En complément, le respect des PSR est recommandé afin de proposer un code compréhensible et facilement évolutif.
<br>
➡️ Ce qui doit prévaloir doit être les délais.
