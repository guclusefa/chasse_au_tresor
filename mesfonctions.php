<?php
try {
    //extraire une seule colonne d'une requete sql
    function requeteSQL($pdo, $sql, $colonne)
    {
        $requete = $pdo->prepare($sql);
        $requete->execute();
        $requete = $requete->fetch();
        $resultat = $requete[$colonne];
        return $resultat;
    }
    //information du joueur connecté, ici pour éviter la redondance
    if (isset($_SESSION['id'])) {
        $maxScore = requeteSQL($pdo, "SELECT MAX(score) AS maxscore FROM parties WHERE id_membre = $id", "maxscore");
        $nbParties = requeteSQL($pdo, "SELECT COUNT(*) AS nb FROM parties WHERE id_membre = $id", "nb");
        $avgScore = requeteSQL($pdo, "SELECT AVG(score) AS score FROM parties WHERE id_membre = $id", "score");
        $avgCompteur = requeteSQL($pdo, "SELECT AVG(compteur) AS compteur FROM parties WHERE id_membre = $id", "compteur");
        $win = requeteSQL($pdo, "SELECT COUNT(*) AS nbWin FROM parties WHERE id_membre = $id AND score > 0", "nbWin");
    }

    //lire toute les lignes d'une requete sql
    function lireLesUsers($pdo, $sql)
    {
        return ($pdo->query($sql)->fetchAll());
    }

    function dateFrToMySQL($date)
    { // jj/mm/aaaa vers aaaa-mm-jj
        if (strlen($date) != 10) die("Format de date incorrect");
        return substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
    };

    function dateMySQLToFr($date)
    { // aaaa-mm-jj vers jj/mm/aaaa
        return date("d/m/Y", strtotime($date));
    };

    function dateMySQLToFrLong($date)
    {
        //--- Les noms des jours en français 
        $jour[0] = "Dimanche";
        $jour[1] = "Lundi";
        $jour[2] = "Mardi";
        $jour[3] = "Mercredi";
        $jour[4] = "Jeudi";
        $jour[5] = "Vendredi";
        $jour[6] = "Samedi";
        //--- Les noms des mois en français 
        $mois[1] = "janvier";
        $mois[2] = "février";
        $mois[3] = "mars";
        $mois[4] = "avril";
        $mois[5] = "mai";
        $mois[6] = "juin";
        $mois[7] = "juillet";
        $mois[8] = "août";
        $mois[9] = "septembre";
        $mois[10] = "octobre";
        $mois[11] = "novembre";
        $mois[12] = "décembre";

        $d1 = date("w/j/n/Y", strtotime($date));
        $d2 = explode("/", $d1);
        return ($jour[$d2[0]] . " " . $d2[1] . " " . $mois[$d2[2]] . " " . $d2[3]);
    };

    function formaterDateFr($date)
    { // j/m/aa vers jj/mm/aaaa
        if (strpos($date, "/") < 2) $date = "0" . $date;
        $lg = strlen($date);
        $result = substr($date, 0, 3);
        $date = substr($date, 3, $lg);
        if (strpos($date, "/") < 2) $date = "0" . $date;
        $lg = strlen($date);
        $result = $result . substr($date, 0, 3);
        $date = substr($date, 3, $lg);
        if (strlen($date) == 2) $date = "20" . $date;
        $result = $result . $date;
        return $result;
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
