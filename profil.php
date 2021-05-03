<!DOCTYPE html>
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id'])) {
        // changement sur profil
        if (isset($_POST['form_profil'])) {

            //changement pseudo
            if ($_POST['newpseudo'] != $_SESSION['pseudo']) {
                $pseudolength = strlen($_POST['newpseudo']);
                if ($pseudolength <= 20) {
                    if (preg_match('/\s/', $_POST['newpseudo'])  == 0) {
                        $pseudo = $_POST['newpseudo'];
                        $reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE memb_pseudo = ?");
                        $reqpseudo->execute(array($pseudo));
                        $pseudoexist = $reqpseudo->rowCount();

                        if ($pseudoexist == 0) {
                            $newpseudo = htmlspecialchars($_POST['newpseudo']);
                            $insertpseudo = $pdo->prepare("UPDATE membres SET memb_pseudo = ? WHERE memb_id = ?");
                            $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
                            $_SESSION['erreur'] = "Changements effectués";
                        } else {
                            $_SESSION['erreur'] = "Pseudo indisponible";
                        }
                    } else {
                        $_SESSION['erreur'] = "Votre pseudo ne doit pas contenir d'espaces";
                    }
                } else {
                    $_SESSION['erreur'] = "votre pseudo ne doit pas dépasser 20 caractères";
                }
            } else {
                $_SESSION['erreur'] = "Aucun changement effectués";
            }

            //changement mail
            if (isset($_POST['newmail']) and !empty($_POST['newmail']) and $_POST['newmail'] != $_SESSION['mail']) {
                if (filter_var($_POST['newmail'], FILTER_VALIDATE_EMAIL)) {
                    $mail = $_POST['newmail'];
                    $reqmail = $pdo->prepare("SELECT * FROM membres WHERE memb_mail = '$mail'");
                    $reqmail->execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if ($mailexist == 0) {
                        $newmail = htmlspecialchars($_POST['newmail']);
                        $insertmail = $pdo->prepare("UPDATE membres SET memb_mail = ? WHERE memb_id = ?");
                        $insertmail->execute(array($newmail, $_SESSION['id']));
                        $_SESSION['erreur'] = "Changements effectués";
                    } else {
                        $_SESSION['erreur'] = "Mail indisponible";
                    }
                } else {
                    $_SESSION['erreur'] = "votre adresse mail n'est pas valide !";
                }
            }

            //changement mdp
            if (isset($_POST['newmdp0']) and !empty($_POST['newmdp0']) or isset($_POST['newmdp1']) and !empty($_POST['newmdp1']) or isset($_POST['newmdp2']) and !empty($_POST['newmdp2 '])) {
                $requser = $pdo->prepare("SELECT * FROM membres WHERE memb_id = ?");
                $requser->execute(array($_SESSION['id']));
                $userinfo = $requser->fetch();

                $mdp0 = sha1($_POST['newmdp0']);
                $mdp1 = sha1($_POST['newmdp1']);
                $mdp2 = sha1($_POST['newmdp2']);
                if (!empty($mdp0) and !empty($mdp1) and !empty($mdp2)) {
                    if ($mdp0 == $userinfo['memb_mdp']) {
                        if ($mdp1 == $mdp2) {
                            $mdplength = strlen($_POST['newmdp1']);
                            if ($mdplength >= 4) {
                                if (preg_match('/\s/', $_POST['newmdp1'])  == 0) {
                                    $insertmdp = $pdo->prepare("UPDATE membres SET memb_mdp = ? WHERE memb_id = ?");
                                    $insertmdp->execute(array($mdp1, $_SESSION['id']));
                                    $_SESSION['erreur'] = "Changements effectués";
                                } else {
                                    $_SESSION['erreur'] = "Votre mot de passe ne doit pas contenir d'espaces";
                                }
                            } else {
                                $_SESSION['erreur'] = "Votre mot de passe doit avoir un minimum de 4 caractères";
                            }
                        } else {
                            $_SESSION['erreur'] = "Vos deux mdp ne correspondent pas !";
                        }
                    } else {
                        $_SESSION['erreur'] = "Mauvais mot de passe actuel !";
                    }
                } else {
                    $_SESSION['erreur'] = "Tous les champs doivent être complétés !";
                }
            }
        }
?>
        <html lang="en">

        <head>
            <title>Mon profil</title>
        </head>

        <body>
            <div style="float:left;margin-left:250px">
                <h1 class="titreprofil">Mes information</h1><br>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="text" placeholder="Pseudo" value="<?php echo $requete['memb_pseudo'] ?>" name="newpseudo" required></br></br>
                    <input type="email" placeholder="Mail" value="<?php echo $requete['memb_mail'] ?>" name="newmail" required></br></br>
                    <input type="password" placeholder="Mdp actuel" name="newmdp0"></br></br>
                    <input type="password" placeholder="Nouveau mdp" name="newmdp1"></br></br>
                    <input type="password" placeholder="Confirmer nouveau mdp" name="newmdp2"></br></br>
                    <input type="submit" name="form_profil" value="Validez !" />
                    <?php
                    if (isset($_SESSION['erreur'])) {
                        echo "<h2><font color='red'>" . $_SESSION['erreur'] . "</font></h2>";
                        unset($_SESSION['erreur']);
                    }
                    ?>
                </form>
            </div>
            <div style="float:right;margin-right:250px;">
                <h1 class="titreprofil">Statistiques</h1><br>
                <h1>Inscrit le :
                    <?php echo dateMySQLToFrLong($requete['inscrit']);
                    $now = new DateTime();
                    $date = new DateTime($requete['inscrit']);
                    echo "<br>(" . $date->diff($now)->format("%d jours, %h heures et %i minutes") . ")"; ?>
                </h1>
                <br>
                <?php
                $requser = $pdo->prepare("SELECT * FROM parties WHERE id_membre = $id");
                $requser->execute();
                $partieexist = $requser->rowCount();
                if ($partieexist > 0) {
                ?>
                <h1>Meilleur score : <?php echo $maxScore; ?></h1>
                <br>
                <h1>Parties jouées : <?php echo $nbParties; ?></h1>
                <br>
                <h1>Score moyen : <?php echo $avgScore ?></h1>
                <br>
                <h1>Compteur moyen : <?php echo $avgCompteur ?></h1>
                <br>
                <?php $pourcentage = round(($win / $nbParties) * 100); ?>
                <h1 style="color:green">Parties gagnées : <?php echo $win . " (" . $pourcentage . "%)" ?></h1>
                <br>
                <h1 style="color:red">Parties perdues : <?php echo $nbParties - $win . " (" . (100 - $pourcentage) . "%)" ?></h1>
                <br>
                <div class="pie" style="margin-left:150px;;background-image: conic-gradient(green <?php echo $pourcentage ?>%, red <?php echo 100 - $pourcentage ?>%);"></div>
            </div>
            <div style="margin-top:1400px;">
                <h1 class="titreprofil" style="margin-left:25%;margin-right:25%;">Mes parties</h1><br>
                <table align="center" border="1" style="font-weight:bold;width: 50%;">
                    <tr>
                        <th>score</th>
                        <th>compteur</th>
                        <th>date</th>
                    </tr>
                    <?php
                    $lesUsers = lireLesUsers($pdo, "SELECT * FROM parties WHERE id_membre = $id ORDER BY 'date', score DESC");
                    $pair = false;
                    $c = 1;
                    foreach ($lesUsers as $unUser) {
                        $hs = $unUser['score'];
                        $play = $unUser['compteur'];
                        $date = $unUser['date'];
                        $date = dateMySQLToFrLong($date);
                        if ($pair) echo ("<tr style='background-color: 	#C0C0C0'>");
                        echo ("<td>$hs</td><td>$play</td><td>$date</td></tr>");
                        $pair = !$pair;
                        $c++;
                    }
                    ?>
                </table>
                <br><br><br>
            </div>
            <?php
            }else {
                echo "<h1>Aucune partie joué<h1>";
            }
            ?>
        </body>

        </html>
<?php
    } else {
        header("location: index.php");
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
?>