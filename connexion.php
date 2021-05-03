<?php
require 'bdd.php';
require "menu.php";
try {
    if (!isset($_SESSION['id'])) {
        if (isset($_POST['formconnexion'])) {
            $mailconnect = htmlspecialchars($_POST['mailconnect']);
            $mdpconnect = sha1($_POST['mdpconnect']);
            if (!empty($mailconnect) and !empty($mdpconnect)) {
                $requser = $pdo->prepare("SELECT * FROM membres WHERE memb_mail = ? AND memb_mdp = ? OR memb_pseudo = ? AND memb_mdp = ?");
                $requser->execute(array($mailconnect, $mdpconnect, $mailconnect, $mdpconnect));
                $userexist = $requser->rowCount();
                if ($userexist == 1) {
                    $userinfo = $requser->fetch();
                    $_SESSION['id'] = $userinfo['memb_id'];
                    if (isset($_SESSION['highscore'])) {
                        if ($_SESSION['highscore'] > $userinfo['highscore']) {
                            $id = $userinfo['memb_id'];
                            $highscore = $_SESSION['highscore'];
                            $pdo->query("UPDATE membres SET highscore = $highscore WHERE memb_id = $id");
                        }
                    }
                    header("Location: index.php");
                } else {
                    $erreur = "Mauvais identifiant ou mot de passe !";
                }
            } else {
                $erreur = "Tous les champs doivent être complétés !";
            }
        }

        if (isset($_POST['forminscription'])) {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $mail = htmlspecialchars($_POST['mail']);
            $mail2 = htmlspecialchars($_POST['mail2']);
            $mdp = sha1($_POST['mdp']);
            $mdp2 = sha1($_POST['mdp2']);
            if (!empty($_POST['pseudo']) and  !empty($_POST['mail']) and !empty($_POST['mail2']) and !empty($_POST['mdp']) and !empty($_POST['mdp2'])) {
                $pseudolength = strlen($pseudo);
                if ($pseudolength <= 20) {
                    if (preg_match('/\s/', $pseudo)  == 0) {
                        if ($mail == $mail2) {
                            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                                $reqmail = $pdo->prepare("SELECT * FROM membres WHERE memb_mail = ?");
                                $reqmail->execute(array($mail));
                                $mailexist = $reqmail->rowCount();

                                $reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE memb_pseudo = ?");
                                $reqpseudo->execute(array($pseudo));
                                $pseudoexist = $reqpseudo->rowCount();
                                if ($mailexist == 0 and $pseudoexist == 0) {

                                    if ($mdp == $mdp2) {
                                        $mdplength = strlen($_POST['mdp']);
                                        if ($mdplength >= 4) {
                                            if (preg_match('/\s/', $_POST['mdp'])  == 0) {
                                                $insertmbr = $pdo->prepare("INSERT INTO membres(memb_pseudo, memb_mail, memb_mdp) VALUES (?,?,?)");
                                                $insertmbr->execute(array($pseudo, $mail, $mdp));
                                                $erreur2 = "Votre compte a bien été crée !";
                                            } else {
                                                $erreur2 = "Votre mot de passe ne doit pas contenir d'espaces";
                                            }
                                        } else {
                                            $erreur2 = "Votre mot de passe doit avoir un minimum de 4 caractères";
                                        }
                                    } else {
                                        $erreur2 = "Vos mots de passe ne correspondent pas";
                                    }
                                } else {
                                    $erreur2 = "Adresse mail ou pseudo déjà utilisée !";
                                }
                            } else {
                                $erreur2 = "votre adresse mail n'est pas valide !";
                            }
                        } else {
                            $erreur2 = "Vos adresses mail ne correspondent pas !";
                        }
                    } else {
                        $erreur2 = "votre pseudo ne doit pas contenir d'espaces";
                    }
                } else {
                    $erreur2 = "votre pseudo ne doit pas dépasser 20 caractères";
                }
            } else {
                $erreur2 = "tous les champs doivent être complétés !";
            }
        }
?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Connexion</title>
        </head>




        <div style="float:left;margin-left:250px">
            <form method="POST" action="">
                <h1>Connexion</h1>
                <input type="text" name="mailconnect" placeholder="Adresse e-mail ou pseudo" required></br></br>
                <input type="password" name="mdpconnect" placeholder="Mot de passe" required></br></br>
                <input type="submit" name="formconnexion" value="Se connecter">
            </form>
            <?php
            if (isset($erreur)) {
                echo "<h2 style='color:red'>$erreur</h2>";
            }
            ?>
        </div>

        <div style="float:right;margin-right:250px;">
            <h1>Inscription</h1>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" required></br></br>
                <input type="email" placeholder="Votre mail" id="mail" name="mail" required></br></br>
                <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" required></br></br>
                <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" required></br></br>
                <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" required></br></br>
                <input type="submit" type="submit" name="forminscription" value="Je m'inscris">
            </form>
    <?php
        if (isset($erreur2)) {
            echo "<h2 style='color:red'>$erreur2</h2>";
        }
    } else {
        header("location: index.php");
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
    ?>
        </div>

        </body>

        </html>