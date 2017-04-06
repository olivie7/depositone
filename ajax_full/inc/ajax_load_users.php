<?php

require_once 'connect.php';


$users = [];

$select = $bdd->prepare('SELECT * FROM users');
if($select->execute()){
	$users = $select->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($users);