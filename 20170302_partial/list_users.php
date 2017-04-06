<?php

require_once 'inc/connect.php';

// On selectionne les toutes les colonnes de la table users
$select = $bdd->prepare('SELECT * FROM users ORDER BY id DESC');
if($select->execute()){
	$users = $select->fetchAll(PDO::FETCH_ASSOC);
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
	<title>Liste de utilisateurs</title>
</head>
<body>
	<h1>Les utilisateurs existants</h1>

	<br>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>prénom</th>
				<th>nom</th>
				<th>email</th>
				<th>détails</th>
			</tr>
		</thead>

		<tbody>
			<!-- foreach permettant d'avoir une ligne <tr> par ligne SQL -->
			<?php foreach($users as $user): ?>
				<tr>
					<td><?=$user['id']; ?></td>
					<td><?=$user['firstname']; ?></td>
					<td><?=$user['lastname']; ?></td>
					<td><?=$user['email']; ?></td>
					<td>
						<!-- view_menu.php?id=6 -->
						<a href="view_user.php?id=<?=$user['id']; ?>">
							Visualiser
						</a>
					</td>
					
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</body>
</html>