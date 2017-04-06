<?php
require_once 'vendor/autoload.php';

use Respect\Validation\Validator as v;
use Intervention\Image\ImageManagerStatic as Image;

//- Declarations des variables
$maxSize = (1024 * 1000) * 2; // Taille maximum du fichier
$uploadDir = 'uploads/'; // Répertoire d'upload

$errors = [];
$post = [];

if(!empty($_POST)){
	foreach($_POST as $key => $value){
		$post[$key] = trim(strip_tags($value));
	}
	if(!v::stringType()->length(3, 10)->validate($post["lastname"])) {
		echo 'Votre nom doit etre compris entre 3 et 10 caracteres<br>';
	}
	if(!v::stringType()->length(3, 10)->validate($post["firstname"])) {
		echo 'Votre prenom doit etre compris entre 3 et 10 caracteres<br>';
	}
	if(!v::email()->validate($post["email"])) {
		echo "votre email n'est pas valide<br>";
	}
	if(!v::stringType()->length(8, 20)->validate($post["password"])) {
		echo "Votre mdp doit etre compris entre 8 et 20 caracteres<br>";
	}





	// si le fichier image est défini et ne comporte pas d'erreur
	if(isset($_FILES['picture']) && $_FILES['picture']['error'] === 0){

		$finfo = new finfo();
		$mimeType = $finfo->file($_FILES['picture']['tmp_name'], FILEINFO_MIME_TYPE);

		// vérifications de contrôle de l'image
		$extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

		if(in_array($mimeType, $mimeTypeAvailable)){
			// si le fichier n'excède pas le poids maxi autorisé
			if($_FILES['picture']['size'] <= $maxSize){

				if(!is_dir($uploadDir)){
					mkdir($uploadDir, 0755); //pour la compatibilité
				}
				// on renomme le fichier
				$newPictureName = uniqid('image_').'.'.$extension;

				if(!move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir.$newPictureName)){
					$errors[] = 'Erreur lors de l\'upload du fichier';
				}
			}
			else {
				$errors[] = 'La taille du fichier excède 2 Mo';
			}
		}
		else {
			$errors[] = 'Le fichier n\'est pas une image valide';
		}
	}
	else {
		$errors[] = 'Aucune image sélectionnée';
}


}
?><!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Pour etre sur que ie utilise la derniere version du moteur de rendu -->
		<meta http-equiv="X-UA-Compatible" content="IE-Edge">

		<!-- Bootstrap CSS 3 -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Bootstrap font-awesome  -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

		<!-- Latest compiled and minified CSS For Bootstrap select -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

		<!-- Google font Kaushan Script-->
		<link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">


		<!-- Style CSS -->
		<link rel="stylesheet" href="assets/css/style.css">

		<!-- HTML5 Shiv-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" integrity="sha256-3Jy/GbSLrg0o9y5Z5n1uw0qxZECH7C6OQpVBgNFYa0g=" crossorigin="anonymous"></script>
		<meta charset="UTF-8">
		<title>Composer</title>
	</head>
	<body>
		<main class="container jumbotron">
			<h2>Composer</h2>
			<form method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="title">Nom</label>
					<input class="form-control" type="text" name="lastname" id="lastname">
				</div>

				<div class="form-group">
					<label for="title">prenom</label>
					<input class="form-control" type="text" name="firstname" id="firstname">
				</div>

				<div class="form-group">
					<label for="email">Email</label>
					<input class="form-control" type="text" id="email" name="email" placeholder="votre@email.fr">
				</div>

				<div class="form-group">
					<label for="password">Mot de passe</label>
					<input class="form-control" type="password" id="password" name="password" placeholder="Un mot de passe super compliqué">
				</div>

				<div class="form-group">
					<label for="picture">Photo</label>
					<input class="form control" type="file" name="picture" id="picture" accept="image/*">
				</div>

				<div class="text-center">
					<input class="btn btn-primary" type="submit" value="inscription">
				</div>
			</form>
		</main>
	</body>
</html>
