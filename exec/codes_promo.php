<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/presentation');
include_spip('public/assembler');

function exec_codes_promo_dist(){
	$contexte = Array();
	$contexte = calculer_contexte();
	
	// si pas autorise : message d'erreur
	if (!autoriser('modifier', 'article')) {
		include_spip('inc/minipres');
		echo minipres();
		exit;
	}
	
	// pipeline d'initialisation
	pipeline('exec_init', array('args'=>array('exec'=>'codes_promo'),'data'=>''));
	// entetes
	$commencer_page = charger_fonction('commencer_page', 'inc');


	// titre, partie, sous_partie (pour le menu)
	echo $commencer_page(_T('codeprom:titre_code_promotionnels'), "editer", "editer");
	
	
	// colonne gauche
	echo debut_gauche('', true);
	include_spip('ecrire/inc/plugin');
	$plugins = liste_chemin_plugin_actifs();
	
	if($plugins['BOUTIQUE']){
	
		include_spip('exec/inc-boutique_boite_info');

		include_spip('exec/inc-boutique_shortcuts');
		}
	else{
	     echo debut_boite_info(true);
     
    		 echo '<div class="infos"><h4>'._T('codeprom:codes_promotion').'</h4></div>';			
	     
	     if(_request('voir')){
	     echo '<div class="numero">'._T('boutique:commande');
		     echo '<p>'._request('id_commande').'</p></div>';
		;}
	     
	echo fin_boite_info(true);
	
	echo
	debut_cadre_relief('',true,'', _T('spip:titre_cadre_raccourcis'));
	echo	'<div><a href="?exec=cfg&cfg=boutique"><img src="'.chemin('cfg-22.png').'" alt="'._T('spip:icone_configuration_site').'" align="absmiddle" />&nbsp;'._T('spip:icone_configuration_site').'</a>';
	echo pipeline('bt_affiche_gauche_shortcuts', array('args'=>array('exec'=>'boutique'),'data'=>''));
			echo '</div>';	
	echo fin_cadre_relief(true);
	
	
		}
			

	// colonne droite
	echo creer_colonne_droite('', true);
	echo pipeline('affiche_droite', array('args'=>array('exec'=>'boutique'),'data'=>''));
	
	// centre
	echo debut_droite('', true);
	// contenu
	// ...
		$contexte=array(
		'editer'=>$editer,	
		);

	if($editer=_request('editer_promo')){
		echo recuperer_fond('prive/editer/code_promo',$contexte,Array("ajax"=>true));
		}
	else{
		echo recuperer_fond('prive/afficher/code_promo',$contexte,Array("ajax"=>true));
		}

	// ...
	// fin contenu
	echo pipeline('affiche_milieu', array('args'=>array('exec'=>'boutique'),'data'=>''));
	
	echo fin_gauche(), fin_page();		
	

}
?>
