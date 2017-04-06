<?php

require_once 'inc/connect.php';

// On selectionne les colonnes id & title de la table recettes
$select = $bdd->prepare('SELECT id,title FROM recettes ORDER BY id DESC');
if($select->execute()){
	$recettes = $select->fetchAll(PDO::FETCH_ASSOC);
}
else {
	// Erreur de développement
	var_dump($select->errorInfo());
	die; // alias de exit(); => die('Hello world');
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Liste de recettes</title>
</head>
<body>
	<h1>Les recettes existantes</h1>

	<br>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>titre</th>
				<th>détails</th>
			</tr>
		</thead>

		<tbody>
			<!-- foreach permettant d'avoir une ligne <tr> par ligne SQL -->
			<?php foreach($recettes as $recette): ?>
				<tr>
					<td><?=$recette['id']; ?></td>
					<td><?=$recette['title']; ?></td>
					<td>
						<!-- view_menu.php?id=6 -->
						<a href="view_menu.php?id=<?=$recette['id']; ?>">
							Visualiser
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</body>
</html>