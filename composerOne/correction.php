<?php

require_once 'vendor/autoload.php';
//-Declaration des diff plugins de validation
use Respect\Validation\Validator as v;
use Intervention\Image\ImageManagerStatic as Image;
use Behat\Transliterator\Transliterator;

//-Declaration des diff variables
$post = [];
$upload_dir = 'upload/';
$maxSize = (1024 * 1000) * 2;

// si le post n'est pas vide, on récupère les données "nettoyées"
if(!empty($_POST)) {
    $post = array_map('trim', array_map('strip_tags', $_POST));
    
	$err = [
		//-On verifie si l'input n'est pas vide, si il ne comporte pas de caracteres qu'on ne veut pas, et si la taille de la chaine est comprise entre 2 et 30 caracteres.
        (!v::notEmpty()->alpha('-.')->length(2, 30)->validate($post['lastname'])) ? 'Le nom de famille est invalide' : null,
        (!v::notEmpty()->alpha('-.')->length(2, 30)->validate($post['firstname'])) ? 'Le prénom est invalide' : null,
		//-On verifie si l'email est valide
        (!v::notEmpty()->email()->validate($post['email'])) ? 'L\'adresse email est invalide' : null,
        (!v::notEmpty()->length(8, 30)->validate($post['password'])) ? 'Le mot de passe est invalide' : null,
    ];
    
	$errors = array_filter($err);
    //-On verifie si la super Global $_FILES est definie et qu'elle ne comporte pas d'erreurs.
	if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0){
        if(!is_dir($upload_dir)){  //-Si le fichier n'existe pas 
            mkdir($upload_dir, 0755); // on le cree
        }
        if($img->filesize() > $maxSize){  //-Si la taille de l'image est superieure à la dimension donnée
            $errors[] = 'Image trop lourde, 2 Mo maximum';
        }
        if(!v::image()->validate($_FILES['avatar']['tmp_name'])){ //-On verifie si l'image est valide en verifiant son mimetype
            $errors[] = 'L\'avatar est une image invalide';
        }
        else {
            $img = Image::make($_FILES['avatar']['tmp_name']); //- créer une nouvelle ressource d'image à partir du fichier
            switch ($img->mime()) {
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    $ext = '.jpg';
                break;
                
                case 'image/png':
                    $ext = '.png';
                break;
                case 'image/gif':
                    $ext = '.gif';
                break;
            }
            $save_name = Transliterator::transliterate(time().'-'. preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['avatar']['name']));
            $img->save($upload_dir.$save_name.$ext);
        }
    }
    if(count($errors) === 0){
        $success = true;
    }
}
?><!DOCTYPE html>
<html>
<head>
    <title>Composer avec Composer</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <h1 class="text-center">Composer.. avec composer !</h1>
        <br>
        <?php if(isset($errors) && count($errors) > 0): ?>
            <div class="alert alert-danger"><?=implode('<br>', $errors);?></div>
        <?php elseif(isset($success) && $success == true): ?>
            <div class="alert alert-success">Le formulaire a été validé.</div>
        <?php endif; ?>
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-md-4 control-label" for="lastname">Nom de famille</label>
                <div class="col-md-4">
                    <input type="text" id="lastname" name="lastname" class="form-control input-md">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="firstname">Prénom</label>
                <div class="col-md-4">
                    <input type="text" id="firstname" name="firstname" class="form-control input-md">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Email</label>
                <div class="col-md-4">
                    <input type="email" id="email" name="email" class="form-control input-md">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="password">Mot de passe</label>
                <div class="col-md-4">
                    <input type="password" id="password" name="password" class="form-control input-md">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="avatar">Avatar</label>
                <div class="col-md-4">
                    <input id="avatar" name="avatar" class="input-file" type="file">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-4 col-md-offset-4">
                    <button type="submit">M'inscrire maintenant</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>