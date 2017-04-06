<?php

require_once 'inc/connect.php';

$errors = [];
$post = []; // Contiendra les données épurées <3 <3

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	$user_id = (int) $_GET['id'];

	// Soumission du formulaire
	if(!empty($_POST)){

		// équivalent au foreach de nettoyage
		$post = array_map('trim', array_map('strip_tags', $_POST)); 

		if(strlen($post['lastname']) < 2) {
			$errors[] = "Le champ Nom doit avoir au minimum 2 caractères";
		}

		if(strlen($post['firstname']) < 2) {
			$errors[] = "Le champ Prénom doit avoir au minimum 2 caractères";
		}

		if(strlen($post['password']) < 8 || strlen($post['password']) > 20) {
			$errors[] = "Le champ Password doit avoir au minimum 8 caractères";
		}

		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Le champ Email n'est pas conforme";
		}

		if(!is_numeric($post['phone']) || strlen($post['phone']) != 10) {
			$errors[] = "Le champ Téléphone doit avoir 10 chiffres";
		}

		if(strlen($post['address']) < 2) {
			$errors[] = "Le champ Adresse doit avoir au minimum 2 caractères";
		}

		if(!is_numeric($post['zipcode']) || strlen($post['zipcode']) != 5) {
			$errors[] = "Le champ Code Postal doit avoir 5 chiffres";
		}

		if(strlen($post['city']) < 2) {
			$errors[] = "Le champ Ville doit avoir au minimum 2 caractères";
		}

		if(count($errors) === 0)
		{

			$update = $bdd->prepare('UPDATE users SET lastname = :lastname, firstname = :firstname, password = :password, email = :email, phone = :phone, street = :street, zipcode = :zipcode, city = :city WHERE id = :idUser');

			$update->bindValue(':idUser', $my_user['id'], PDO::PARAM_INT);
			$update->bindValue(':lastname', $post['lastname']);
			$update->bindValue(':firstname', $post['firstname']);
			$update->bindValue(':password', password_hash($post['password'], PASSWORD_DEFAULT));
			$update->bindValue(':email', $post['email']);
			$update->bindValue(':phone', $post['phone']);
			$update->bindValue(':street', $post['address']);
			$update->bindValue(':zipcode', $post['zipcode']);
			$update->bindValue(':city', $post['city']);

			if($update->execute())
			{
				$success = 'Félicitations votre compte a été modifié';
			}
			else
			{
				var_dump($update->errorInfo());
			}
		}
		else
		{
			$textErrors = implode('<br>', $errors);
		}

	}


	// On sélectionne l'utilisateur pour être sur qu'il existe et remplir le formulaire
	$select = $bdd->prepare('SELECT * FROM users WHERE id = :idUser');
	$select->bindValue(':idUser', $user_id, PDO::PARAM_INT);

	if($select->execute()){
		$my_user = $select->fetch(PDO::FETCH_ASSOC);
	}



} // endif $_GET['id']



?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Mettre à jour un utilisateur</title>
<style>
label {
	display: inline-block;
	min-width: 200px;
	margin-bottom: 7px;
}
input, select, textarea {
	margin-bottom: 7px;
}
</style>
</head>
<body>

<?php if(isset($my_user) && !empty($my_user)): ?>
	<?php
		if(isset($textErrors)){
			echo '<p style="color:red">'.$textErrors.'</p>';
		}

		if(isset($success)){
			echo '<p style="color:green">'.$success.'</p>';
		}
	?>
	<form method="post">
		<label for="firstname">Prénom</label>
		<input type="text" name="firstname" id="firstname" value="<?=$my_user['firstname'];?>">

		<br>
		<label for="lastname">Nom</label>
		<input type="text" name="lastname" id="lastname" value="<?=$my_user['lastname'];?>">

		<br>
		<label for="password">Mot de passe</label>
		<input type="password" name="password" id="password">

		<br>
		<label for="email">Adresse email</label>
		<input type="email" name="email" id="email" value="<?=$my_user['email'];?>">

		<br>
		<label for="phone">Téléphone</label>
		<input type="text" name="phone" id="phone" value="<?=$my_user['phone'];?>">

		<br>
		<label for="address">Adresse</label>
		<input type="text" name="address" id="address" value="<?=$my_user['street'];?>">

		<br>
		<label for="zipcode">Code postal</label>
		<input type="text" name="zipcode" id="zipcode" value="<?=$my_user['zipcode'];?>">

		<br>
		<label for="city">Ville</label>
		<input type="text" name="city" id="city" value="<?=$my_user['city'];?>">


		<br>
		<input type="submit" value="Mettre à jour">

	</form>
<?php else: ?>
	<p style="color:red">Désolé, aucun utilisateur correspondant</p>
<?php endif; ?>
</body>
</html>