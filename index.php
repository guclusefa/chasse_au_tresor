<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chasse au trésor</title>
</head>

<?php
require "bdd.php";
require "menu.php";
require 'mesfonctions.php';

try {
    if (isset($_SESSION['id'])) {
        if (isset($_COOKIE['highscore']) && isset($_COOKIE['compteur'])) {
            $highscore = $_COOKIE['highscore'];
            $compteur= $_COOKIE['compteur'];
            $pdo->query("INSERT INTO parties(id_membre, score, compteur) VALUES($id, $highscore,$compteur)");
            setcookie("highscore", "", time() - 3600);
            setcookie("compteur", "", time() - 3600);
            header('Location: index.php');
        }
    } else {
        if (isset($_COOKIE['highscore'])) {
            if (isset($_SESSION['highscore'])) {
                if ($_COOKIE['highscore'] > $_SESSION['highscore']) {
                    $_SESSION['highscore'] = $_COOKIE['highscore'];
                }
            } else {
                $_SESSION['highscore'] = $_COOKIE['highscore'];
            }
        }
    }
?>

    <body>
        <div>
            <h1>Chasse au trésor</h1>
        </div>

        <div class="cadre">
            <h2 style="float: right;">
                Compteur : <span id="cliqueid">0</span><br><br>
                Score : <span id="scoreid">0</span><br><br>
                Score final : <span id="finalid">0</span><br><br>
                <span id="highscoreid">Meilleur score :
                    <?php
                    if (isset($_SESSION['id'])) {
                        echo $maxScore;
                    } else if (isset($_SESSION['highscore'])) {
                        echo $_SESSION['highscore']. "<br><br>Connectez ou inscrivez<br>vous pour<br>enregistrez votre<br>meilleur score!";;
                    } else{
                        echo 0 . "<br><br>Connectez ou inscrivez<br>vous pour<br>enregistrez votre<br>meilleur score!";
                    }
                    ?>
                </span><br>
                <span id="rejouerid"></span>
            </h2>
            <table id="tableau" style="float:left;background-image: url('map.jpg');background-size: cover;margin-left:-100px;">
            </table>
        </div>

        <div class="msg">
            <div id="boite" class="alert">
                <p id="erreur"><b>Le jeu est très simple :</b> un trésor est caché sur une île et il faut le trouver.<br>
                    Le score se calcule en fonction du nombre de case creusées, chaque clique vous coûte 10points !<br>
                    Bonne chance !
            </div>
        </div>
    </body>
    <script src="index.js"></script>
<?php
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
?>

</html>