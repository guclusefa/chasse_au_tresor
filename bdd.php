<?php
//start de la session et connexion à la bdd via pdo
session_start();
try {
    $pdo= new PDO("mysql:host=localhost;dbname=jeutresor;charset=utf8",'root','root');
} catch(Exception $e){
    die("erreur : ". $e->getMessage());
}
