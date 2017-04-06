<?php

function calculttc($prixht, $tauxtva = 20){
	if(!is_numeric($prixht) || empty($prixht)){
		trigger_error('Le prixht doit être de type numérique ou non vide !', E_USER_WARNING);
		return false;
	}

	$tauxdispo = [20, 10, 5.5];
	if(!in_array($tauxtva, $tauxdispo)){
		trigger_error('Le taux de tva n\'est pas valide !', E_USER_WARNING);
		return false;
	}

	$prixttc = $prixht * (1 + $tauxtva / 100);
	return $prixttc;
}

/*
Exemples d'utilisations de la fonction :
echo calculttc(100);
echo '<br>';
echo calculttc(100, 5.5);
echo '<br>';
echo calculttc(55, 5.5);*/