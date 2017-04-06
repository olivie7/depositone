<?php
	require_once 'inc/connect.php';

	$maxSize = 1024 * 1000 * 2;
	$uploadDir = 'uploads/';
	$mimeTypeAvailable = ['image/png', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/gif'];
	$tvaAvailable = [5.5, 10, 20];
	$post = [];
	$errors = [];


	if(!empty($_POST)) {
		//var_dump($_POST);
		//die;
		foreach($_POST as $key => $value){
			$post[$key] = trim(strip_tags($value));
		}

		if(strlen($post['libelle']) < 5) {
			$errors[] = 'Le Libellé doit avoir au minimum 5 caractères';
		}

		if(strlen($post['description']) < 5) {
			$errors[] = 'La description doit avoir au minimum 5 caractères';
		}

		if(strlen($post['reference']) < 5) {
			$errors[] = 'La Référence doit avoir au minimum 5 caractères';
		}

		if(empty($post['price']) || !is_numeric($post['price'])) {
			$errors[] = 'Le Tarif HT doit être un nombre';
		}

		if(empty($post['taxes']) || !is_numeric($post['taxes']) || !in_array($post['taxes'], $tvaAvailable)){
			$errors[] = 'La TVA doit être sélectionnée';
		}

		// UPLOAD_ERR_OK === 0
		if(isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {

			$finfo = new finfo();
			$mimeType = $finfo->file($_FILES['picture']['tmp_name'], FILEINFO_MIME_TYPE);

			$extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);  
			if(in_array($mimeType, $mimeTypeAvailable)) {

				if($_FILES['picture']['size'] < $maxSize){

					if(!is_dir($uploadDir)){
						mkdir($uploadDir, 0755);
					}
					
					$name = uniqid('img_').'.'.$extension;

					if(!move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir.$name)){
						$errors[] = 'Erreur d\'upload';
					}
				}else{
					$errors[] = 'Votre image ne doit pas excéder 2Mo';
				}
			}else{
				$errors[] ='Ce n\'est pas une image valide';
			}

		}else{
			$errors[] = 'Aucune photo selectionnée !';
		}

		if(count($errors) > 0){

			$errortext = implode('<br>',$errors); // pour afficher les erreurs dans html

		} else {

			$insert = $bdd->prepare('INSERT INTO products (libelle, description, reference, price, taxes, picture_url) VALUES (:libelle, :description, :reference, :price, :taxes, :picture_url)');

			$insert->bindValue(':libelle', $post['libelle']); // je lie le champ libelle à la variable venant du POST
			$insert->bindValue(':description', $post['description']);
			$insert->bindValue(':reference', $post['reference']);
			$insert->bindValue(':price', $post['price'], PDO::PARAM_STR);
			$insert->bindValue(':taxes', $post['taxes'], PDO::PARAM_STR);
			$insert->bindValue(':picture_url', $uploadDir.$name);

			if($insert->execute()){

				$success = "Article correctement enregistré en base !!";

			} else {
				die(var_dump($insert->errorInfo())); //retourne tableau error sql. Utile au developpement.
				   }



		}
	}



?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter un produit</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

	<main class="container">
	<?php if(isset($errortext)) {
		echo '<p class="text-danger">'.$errortext.'</p>'; //class="text-danger" class boostrap.
	} 

	?>
		<form method="post" enctype="multipart/form-data">

			<fieldset>
				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="libelle">Libellé</label>  
				  <div class="col-md-4">
				  <input id="libelle" name="libelle" type="text" placeholder="" class="form-control input-md">
				    
				  </div>
				</div>

				<!-- Textarea -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="description">Description</label>
				  <div class="col-md-4">                     
				    <textarea class="form-control" id="description" name="description"></textarea>
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="reference">Référence</label>  
				  <div class="col-md-4">
				  <input id="reference" name="reference" type="text" placeholder="" class="form-control input-md">
				    
				  </div>
				</div>

				<!-- Text input-->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="price">Tarif HT</label>  
				  <div class="col-md-4">
				  <input id="price" name="price" type="text" placeholder="" class="form-control input-md">
				    
				  </div>
				</div>

				<!-- Select Basic -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="taxes">TVA</label>
				  <div class="col-md-4">
				    <select id="taxes" name="taxes" class="form-control">
				      <?php foreach($tvaAvailable as $value) : ?>
				      	<option value="<?=$value; ?>"><?=$value; ?> %</option>
				      <?php endforeach; ?>
				    </select>
				  </div>
				</div>

				<!-- File Button --> 
				<div class="form-group">
				  <label class="col-md-4 control-label" for="picture">Photo</label>
				  <div class="col-md-4">
				    <input id="picture" name="picture" class="input-file" type="file">
				    <input type="hidden" name="MAX_FILE_SIZE" value="<?=$maxSize; ?>">
				  </div>
				</div>

				<!-- Button -->
				<div class="form-group">
				  <label class="col-md-4 control-label" for="submit">Ajouter un produit</label>
				  <div class="col-md-4">
				    <button id="submit" name="submit" class="btn btn-primary">Button</button>
				  </div>
				</div>
			</fieldset>
		</form>

	</main>
</body>
</html>