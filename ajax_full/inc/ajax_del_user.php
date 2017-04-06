<?php

require_once 'connect.php';


// $_POST['id_user'] => renommée comme cela via ajax
if(isset($_POST['id_user']) && !empty($_POST['id_user']) && is_numeric($_POST['id_user'])){

	$id_user = (int) $_POST['id_user'];

	$delete = $bdd->prepare('DELETE FROM users WHERE id = :id_user'); 
	$delete->bindValue(':id_user', $id_user, PDO::PARAM_INT);
	if($delete->execute()){
		$result = '<div class="alert alert-success">Utilisateur #'.$id_user.' supprimé</div>';
	}
}
else {
	$result = '<div class="alert alert-danger">Erreur: ID invalide</div>';
}

echo $result; // On envoi le résultat