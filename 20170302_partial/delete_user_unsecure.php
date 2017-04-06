<?php

require_once 'inc/connect.php';

// Très mauvaise pratique :( 
$select = $bdd->query('SELECT * FROM users WHERE id = '.$_GET['id']);
$select->execute();

var_dump($select->fetch(PDO::FETCH_ASSOC));
