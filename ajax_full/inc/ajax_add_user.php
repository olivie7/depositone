<?php
require_once 'connect.php';

$post = [];
$errors = [];
if(!empty($_POST)){

	$post = array_map('trim', array_map('strip_tags', $_POST));

	if(strlen($post['firstname']) < 2){
		$errors[] = 'Le prénom doit comporter au moins 2 caractères';
	}
	if(strlen($post['lastname']) < 2){
		$errors[] = 'Le nom doit comporter au moins 2 caractères';
	}
	if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = 'L\'adresse email est invalide';
	}

	if(count($errors) === 0){
		$insert = $bdd->prepare('INSERT INTO users(firstname, lastname, email) VALUES(:firstname, :lastname, :email)');
		$insert->bindValue(':firstname', $post['firstname']);
		$insert->bindValue(':lastname', $post['lastname']);
		$insert->bindValue(':email', $post['email']);

		if($insert->execute()){
			$result = '<div class="alert alert-success">L\'utilisateur a été ajouté avec succès</div>';
		}
	}
	else {
		$result = '<div class="alert alert-danger">'.implode('<br>', $errors).'</div>';
	}

	echo $result;

}