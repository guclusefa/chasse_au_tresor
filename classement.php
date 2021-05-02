<!DOCTYPE html>
<html lang="en">

<head>
<link rel="icon" href="logo.ico">
	<title>Classement</title>
</head>



<body>
	<table align="center" border="1" style="font-weight:bold;width: 50%;">
		<tr>
			<th>classement</th>
			<th>pseudo</th>
			<th>meilleur score</th>
			<th>cases creusés</th>
			<th>date</th>
		</tr>
		<?php
		require 'bdd.php';
		require 'menu.php';
		require 'mesfonctions.php';
		try {
			function lireLesUsers()
			{
				$pdo = new PDO("mysql:host=localhost;dbname=jeutresor;charset=utf8", 'root', 'root');
				$sql = "SELECT * FROM parties  GROUP BY id_membre ORDER BY score DESC";
				return ($pdo->query($sql)->fetchAll());
			}

			$lesUsers = lireLesUsers();
			$pair = false;
			$c = 1;
			foreach ($lesUsers as $unUser) {
				$id_memb = $unUser["id_membre"];
				$pseudo = requeteSQL("SELECT memb_pseudo FROM membres WHERE memb_id = $id_memb", "memb_pseudo");
				$hs = $unUser['score'];
				$play = $unUser['compteur'];
				$date = $unUser['date'];
				$date = dateMySQLToFrLong($date);
				if ($pair) echo ("<tr style='background-color: 	#C0C0C0'>");
				echo ("<td>$c</td><td>$pseudo</td><td>$hs</td><td>$play</td><td>$date</td></tr>");
				$pair = !$pair;
				$c++;
			}
		} catch (Exception $e) {
			die("Ereur grave : " . $e->getMessage());
		}
		?>
	</table>
</body>

</html>