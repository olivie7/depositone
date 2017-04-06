<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajout d'un utilisateur</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

<main class="container">

	<h1 class="text-center">Ajout d'un utilisateur</h1>
	<br>

	<div id="result"></div>

	<form method="post" id="addUser" class="form-horizontal">
		<div class="form-group">
			<label class="col-md-4 control-label" for="firstname">Prénom</label>
			<div class="col-md-4">
				<input type="text" id="firstname" name="firstname" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label" for="lastname">Nom</label>
			<div class="col-md-4">
				<input type="text" id="lastname" name="lastname" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label" for="email">Email</label>
			<div class="col-md-4">
				<input type="email" id="email" name="email" class="form-control" placeholder="votre@email.fr">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-4 col-md-offset-4">
				<button type="submit" id="submitForm" class="btn btn-primary">Ajouter l'utilisateur</button>
			</div>
		</div>

	</form>

</main>

<!--script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script-->
<script src="js/jquery-3.2.0.min.js"></script>
<script>
$(function(){
	$('#submitForm').click(function(el){
		el.preventDefault(); // On bloque l'action par défaut

		var form_user = $('#addUser'); // On récupère le formulaire
		$.ajax({
			method: 'post',
			url: 'inc/ajax_add_user.php',
			data: form_user.serialize(), // On récupère les données à envoyer
			success: function(resultat){
				$('#result').html(resultat);
				form_user.find('input').val(''); // Permet de vider les champs du formulaire.. 
			}
		});
	});
});
</script>
</body>
</html>