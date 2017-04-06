$(function(){ // équivalent $(document).ready(function(){

	// On place dans une fonction qu'on peut appeler autant de fois qu'on le souhaite
	function loadUsers(){
		// Permet de récupérer des données au format JSON
		$.getJSON('inc/ajax_load_users.php', function(result){
			//console.log(result); // équivalent à un var_dump()


			var resHTML = '';

			$.each(result, function(key, value){
				resHTML+= '<tr>';
				resHTML+= '<td>'+value.id+'</td>';
				resHTML+= '<td>'+value.firstname+'</td>';
				resHTML+= '<td>'+value.lastname+'</td>';
				resHTML+= '<td>'+value.email+'</td>';
				resHTML+= '<td><a href="#" class="deleteUser" data-id="'+value.id+'">Supprimer</td>';
				resHTML+= '</tr>';
			});

			$('#usersAjax').html(resHTML);
		});		
	}

	// Permet d'appeler la fonction lors de la premiere arrivée sur la page
	loadUsers();

	// Pour l'ajout d'un utilisateur
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


	// Suppression utilisateur classique
	$('a.deleteUser').click(function(element){
		element.preventDefault(); // Bloque l'action par défaut de l'élement

		$.ajax({
			method: 'post',
			url: 'inc/ajax_del_user.php',
			// id_user => deviendra la clé de la superglobale en php : $_POST['id_user']
			// $(this).data('id') => récupère la valeur de l'attribut data-id du lien
			data: {id_user: $(this).data('id')}, 
			success: function(resultat){
				// 'resultat' provient de la page php.. Soit un message d'erreur, soit de réussite.. en fonction de nos éventuels "echo"
				$('#mon_resultat').html(resultat);
			}
		});
	});

	// Suppression utilisateur avec DOM modifié à la volé
	$('body').on('click', 'a.deleteUser', function(element, deux){
		element.preventDefault(); // Bloque l'action par défaut de l'élement

		$.ajax({
			method: 'post',
			url: 'inc/ajax_del_user.php',
			data: {id_user: $(this).data('id')}, 
			success: function(resultat){
				$('#mon_resultat').html(resultat);
				loadUsers();
			}
		});
	});


});