<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="images/logo.ico">
	<link rel="stylesheet" href="css/style.css">
</head>

<?php 
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $requete = $pdo->prepare("SELECT * FROM membres WHERE memb_id = ?");
        $requete->execute(array($id));
        $requete = $requete->fetch();
        echo "Bonjour <b>". $requete['memb_pseudo']."</b>";
        echo "<p><a href='index.php'>accueil</a> <a href='profil.php'>mon profil</a> <a href='classement.php'>classement</a> <a href='deconnexion.php'>se deconnecter</a></p>";
    } else {
        echo "<p><a href='index.php'>accueil</a> <a href='classement.php'>classement</a> <a href='connexion.php'>se connecter</a></p>";
    }
?>