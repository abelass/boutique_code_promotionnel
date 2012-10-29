<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function action_gestion_codes_dist(){
	include_spip('inc/minipres');
	$securiser_action = charger_fonction('securiser_action', 'inc');
	$arg = $securiser_action();
	
	switch($arg){
		case 'eliminer':
    		$where = array('id_code_promo='._request('id_code_promo'));
    		sql_delete('spip_codeprom_codes_promo',$where);
    		break;
		case 'tester_code':
		include_spip('codeprom_pipelines');
		$reponse=codeprom_bt_form_commande_verifier(_request('id_code_promo'));
		include_spip('inc/headers');
		redirige_par_entete('&message='.$reponse['message_erreur']);
    		break;    		
    		
    		
    		}

}
?>