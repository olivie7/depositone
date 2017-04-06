<?php
require_once 'inc/connect.php';
require_once 'inc/functions.php';

$product =[];

if(isset($_GET['id']) && !empty($_GET['id'])) {
	$id_get = (int) $_GET['id'];

	$res = $bdd->prepare('SELECT * FROM products WHERE id =:id ');
	$res->bindValue(':id', $id_get, PDO::PARAM_INT);
	
	if($res->execute()) {
		$product = $res->fetch(PDO::FETCH_ASSOC);
	} else {
		var_dump($res->errorInfo());
		die;
	}

} 



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<title>Details produit</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
	<?php
	if (empty($product)) {
		echo '<p class="text-danger">Ce produit n\'existe pas</p>';
	}
	else {

	?>
		
		<div class="jumbotron">
			<p><i class="fa fa-address-card" aria-hidden="true"></i>DETAIL DU PRODUIT </p>
		</div>
		
		<h2>Nom du produit<?=$product['libelle'];?> </h2>
		<p><strong>Description : </strong><?=$product['description'];?></p>
		<p><strong>Référence : </strong><?=$product['reference'];?></p>
		<p><strong>Prix HT: </strong><?=$product['price'];?></p>
		<p><strong>TVA : </strong><?=$product['taxes'];?></p>
		<p><strong>Prix TTC: </strong><?=calculttc($product['price'], $product['taxes']);?></p>
		<img class="img-responsive img-thumbnail" src="<?=$product['picture_url'];?>" alt="<?=$product['libelle'];?>">
	<?php } ?>
</div>
</body>
</html>