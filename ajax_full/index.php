<?php
require_once 'inc/connect.php';

$users = [];

$select = $bdd->prepare('SELECT * FROM users');
if($select->execute()){
	$users = $select->fetchAll(PDO::FETCH_ASSOC);
}
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Liste des utilisateurs</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

<main class="container">
	<h1 class="text-center">Liste des utilisateurs</h1>


	<br>
	<div class="clearfix">
		<a href="add_user.php" class="btn btn-default pull-right">Ajouter un utilisateur</a>
	</div>
	<br>

	<div id="mon_resultat"><!-- contiendra le résultat ajax --></div>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Prénom</th>
				<th>Nom</th>
				<th>Email</th>
				<th>Action</th>
			</tr>
		</thead>

		<tbody id="usersAjax">
		<?php if(empty($users)): ?>
			<tr>
				<td class="danger text-danger text-center" colspan="5">Aucun utilisateur inscrit...</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
</main>

<!--script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script-->
<script src="js/jquery-3.2.0.min.js"></script>
<script src="js/script.js"></script>
<script>
$(function(){
	// Permet d'appeler la fonction lors de la premiere arrivée sur la page
	loadUsers();
});
</script>
</body>
</html>