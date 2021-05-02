<?php
session_start();
try {
    $pdo= new PDO("mysql:host=localhost;dbname=jeutresor;charset=utf8",'root','root');
} catch(Exception $e){
    die("erreur : ". $e->getMessage());
}
