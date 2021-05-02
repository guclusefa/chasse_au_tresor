//tableau pour les placer les events good et bad de la classe Evenement
tab_events_good = []
tab_events_bad = []
//tableau pour voir les id déjà cliqué
tab_clique = []
//variable pour définir si la partie est fini
fin = false
//variable pour définir si la partie est perdu
perdu = false
//variable pour les bonus
bonus = 0

//description pour les events bad et good, on peux ajouter autant de description que l'on souhaite
listeDescGood = ["Vous avez rencontrez Chuck Norris !", "Walter White vous a donné 11millions de dollars !","Vous avez trouvé de l'or !", "Vous avez trouvé du ruby !", "Vous avez trouvé des bananes !"]
listeDescBad = ["Vous êtes tombé dans le trou !", "Vous êtes tombé dans l'eau !", "Vous êtes tombé dans de la boue !", "Vous avez encontrez Dark VADOR !"]
//tableau pour mettre tout les id non disponible, utilisé pour vérifier doublon
lesID = []

//fonction qui retourne un nombre aléatoire entre min et max, utilisé pour les id et ebonus
function alea(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min)
}

//10% (0.1) de chance de finir la partie, utilisé pour déinir la chance de finir la partie pour les events bad
function pourcentFin() {
    p = Math.random();
    if (p < 0.1) {
        return true
    }
    return false
}

//genere les id des cases, sans doublon
function aleaID() {
    x = alea(0, 9)
    y = alea(0, 9)
    idAlea = String(x) + "-" + String(y)
    if (!lesID.includes(String(idAlea))) {
        lesID.push(idAlea)
        return idAlea
    } else {
        return aleaID()
    }
}

tresor = aleaID() //genere id du tresor en premier

//style message d'erreur
function msg_erreur(couleur, fond, erreur, type) {
    document.getElementById("boite").style.borderColor = couleur
    document.getElementById("boite").style.backgroundColor = fond;
    document.getElementById("erreur").innerHTML = "<font color=" + couleur + "><b>" + type + " </b>" + erreur + "</font>";
}
fondVert = 'rgba(43, 84, 44, 0.1)'
fondRouge = 'rgba(217, 83, 79, 0.1)'
fondOrange = 'rgba(240, 173, 78, 0.1)'
fondBleu = 'rgba(16, 29, 255, 0.2)'
fondGris = 'rgb(211, 210, 210)'

//fonction verifie id dans les liste events, si un id se trouve dans un event, elle retourne sa place dans son tableau, afin d'avoir sa desc, bonus...
function verifID(liste, id) {
    for (var i = 0; i < liste.length; i++) {
        if (String(id) == liste[i].position) {
            return liste[i]
        }
    }
    return false
}

//fonction de l'utilsateur qui clique
afficherDernierBonus = true //pour fin de partie, si trésor ou event bad avec fin de partie
function choix(position) {
    verif(position) //on vérifie ou se trouve l'id
    if (!verif(position) && !fin) { //si ce n'est pas un fin de partie, et que on a pas déjà cliqué sur la case
        tab_clique.push(position)
        document.getElementById("cliqueid").innerHTML = tab_clique.length //compteur case creusé
        document.getElementById("scoreid").innerHTML = Number(document.getElementById("scoreid").innerHTML) + bonus //score brut
        document.getElementById("finalid").innerHTML = Number(document.getElementById("scoreid").innerHTML) - Number(document.getElementById("cliqueid").innerHTML) * 10 //score net
        document.cookie = `highscore=${document.getElementById("finalid").innerHTML}`; //pour php
    }
    if (fin && afficherDernierBonus && !perdu) { //si fin de partie, que le dernier bonus n'a pas été affiché et que perdu n'est pas vrai
        document.getElementById("scoreid").innerHTML = Number(document.getElementById("scoreid").innerHTML) + bonus
        document.getElementById("rejouerid").innerHTML = "<a href='index.php'><br>Rejouer !</a>"
        document.getElementById("finalid").innerHTML = Number(document.getElementById("scoreid").innerHTML) - Number(document.getElementById("cliqueid").innerHTML) * 10
        document.cookie = `highscore=${document.getElementById("finalid").innerHTML}`;//pour php
        perdu = true
    }
}

//vérifaction sur le clique d'une case, on vérifie si l'id se trouve dans un tableau (event good, event bad, deja clique), et on affiche le résultat en fonction
function verif(positionverif) {
    //si la partie n'est pas fini
    if (!fin) { 
        //on vérifie d'abord que l'id se trouve dans un tableau
        if (tab_clique.includes(String(positionverif)) || verifID(tab_events_good, positionverif) || verifID(tab_events_bad, positionverif) || String(positionverif) == tresor || String(positionverif[0]) == tresor[0] || String(positionverif[2]) == tresor[2]) {
            //si id déjà cliqué
            if (tab_clique.includes(String(positionverif))) {
                couleur = document.getElementById(positionverif).style.backgroundColor
                fond = document.getElementById(positionverif).style.color
                type = "Erreur :"
                erreur = "Dejà cliqué !"
                bonus = 0
                msg_erreur(couleur, fond, erreur, type)
                document.getElementById(positionverif).style.backgroundColor = couleur
                document.getElementById(positionverif).style.color = fond
                return true
            }
            //si is se trouve dans event good
            else if (verifID(tab_events_good, positionverif)) {
                couleur = "green"
                fond = fondVert
                type = "Bonus :"
                erreur = verifID(tab_events_good, positionverif).desc
                bonus = verifID(tab_events_good, positionverif).bonus
                fin = verifID(tab_events_good, positionverif).end

            }
            //si id se trouve dans event bad
            else if (verifID(tab_events_bad, positionverif)) {
                couleur = "orange"
                fond = fondOrange
                type = "Malus :"
                erreur = verifID(tab_events_bad, positionverif).desc
                bonus = verifID(tab_events_bad, positionverif).bonus
                fin = verifID(tab_events_bad, positionverif).end
            }
            //si même ligne que trésor
            else if (String(positionverif[0]) == tresor[0]) {
                couleur = "blue"
                fond = fondBleu
                type = "Indice :"
                erreur = "Le trésor se trouve sur la même ligne!"
                bonus = alea(10, 100)
            }
            //si même colonne que trésor
            else if (String(positionverif[2]) == tresor[2]) {
                couleur = "blue"
                fond = fondBleu
                type = "Indice :"
                erreur = "Le trésor se trouve sur la même colonne!"
                bonus = alea(10, 100)
            }
            //si on a trouvé le tresor
            if (String(positionverif) == tresor) {
                couleur = "green"
                fond = fondVert
                type = "Trésor :"
                erreur = "Vous avez trouvé le trésor !"
                fin = true
                bonus = alea(1000, 2000)
            }
            //si on vien de finir la partie (en trouvant le trésor ou fin de partie à cause de event bad)
            if (fin) {
                //si on a trouvé le tresor
                if (String(positionverif) == tresor) {
                    erreur = erreur + "<br>Féliciation vous avez gagné !"
                } 
                //si on est tombé sur un event bad avec une fin de partie (10% de chance)
                else {
                    erreur = erreur + "<br>On vous a volé votre pelle, la partie est fini !"
                    fin = true
                }
                erreur = erreur + `<br>Nombre de tentatives : ${tab_clique.length + 1}</br>`
                document.getElementById("cliqueid").innerHTML = Number(document.getElementById("cliqueid").innerHTML) + 1
                //pour le php, le nombre de case creusé définit avec la longueur de la table déjà cliqué + 1 avec le dernier
                document.cookie = `compteur=${tab_clique.length + 1}`;
            }
            //affichage du nombre de points gagné ou perdu avec style adéquat
            bonusAfficher = bonus
            if (bonus > 0) {
                bonusAfficher = "+" + bonus
            }
            erreur = erreur + "<br> Points : " + bonusAfficher
            msg_erreur(couleur, fond, erreur, type)
            document.getElementById(positionverif).style.backgroundColor = couleur
            document.getElementById(positionverif).style.color = fond
        }
        //si l'id se trouve dans aucun tableau, alors il y rien dans cette case
        else {
            couleur = "red"
            fond = fondRouge
            type = "Erreur :"
            erreur = "Il y a rien !"
            bonus = 0
            msg_erreur(couleur, fond, erreur, type)
            document.getElementById(positionverif).style.backgroundColor = couleur
            document.getElementById(positionverif).style.color = fond
            return false
        }
    }
}

//instruction permettant la création du tableau 2d sur la page web, ici, 10*10cases
for (var i = 0; i < 10; i++) {
    texte = "<tr>"
    for (var j = 0; j < 10; j++) {
        idCase = String(i) + "-" + String(j)
        texte = texte + '<td onclick="choix(this.id);" id="' + idCase + '">' + idCase + '</td>'
    }
    texte = texte + "</tr>"
    document.getElementById("tableau").innerHTML = document.getElementById("tableau").innerHTML + texte
}

//classe evenement
class Event {
    constructor(desc, position, bonus, end) {
        this.desc = desc
        this.position = position
        this.bonus = bonus
        this.end = end
    }
}

//génération de 10 event bad et 10 events good, on peux augmenter ou diminuer si on le souhaite 
for (let i = 0; i < 10; i++) {
    tab_events_good[i] = new Event(listeDescGood[alea(0, listeDescGood.length - 1)], aleaID(), alea(100, 1000), false)
}
for (let i = 0; i < 10; i++) {
    tab_events_bad[i] = new Event(listeDescBad[alea(0, listeDescBad.length - 1)], aleaID(), alea(-1000, -100), pourcentFin())
}
