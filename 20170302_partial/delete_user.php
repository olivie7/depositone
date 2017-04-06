<?php

require_once 'inc/connect.php';
// Permet de vérifier que mon id est présent et de type numérique
if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	$user_id = (int) $_GET['id'];

	// On sélectionne l'utilisateur pour être sur qu'il existe et faire un rappel
	$select = $bdd->prepare('SELECT * FROM users WHERE id = :idUser');
	$select->bindValue(':idUser', $user_id, PDO::PARAM_INT);

	if($select->execute()){
		$my_user = $select->fetch(PDO::FETCH_ASSOC);
	}
	if(!empty($_POST)){
		// Si la valeur du champ caché ayant pour name="action" est égale a delete, alors je supprime
		if(isset($_POST['action']) && $_POST['action'] === 'delete'){
			$delete = $bdd->prepare('DELETE FROM users WHERE id = :idUser');
			$delete->bindValue(':idUser', $user_id, PDO::PARAM_INT);

			if($delete->execute()){
				$success = 'L\'utilisateur a bien été supprimé';
			}
			else {
				var_dump($delete->errorInfo()); 
				die;
			}
		}
	}
}


?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Supprimer un utilisateur</title>
</head>
<body>

	<?php if(!isset($my_user) || empty($my_user)): ?>
		<p style="color:red">Désolé, aucun utilisateur correspondant</p>
	
	<?php elseif(isset($success)): ?>
		<?php echo $success; ?>

	<?php else: ?>
		<p>Voulez-vous supprimer : <?=$my_user['firstname'].' '.$my_user['lastname']. ' - '.$my_user['email'];?>

	<form method="post">
		
		<input type="hidden" name="action" value="delete">

		<!-- history.back() permet de revenir à la page précédente -->
		<button type="button" onclick="javascript:history.back();">Annuler</button>
		<input type="submit" value="Supprimer cet utilisateur">
	</form>
	<?php endif; ?>



	

</body>
</html>