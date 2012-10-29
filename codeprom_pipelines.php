<?php
if (!defined("_ECRIRE_INC_VERSION")) return;

function codeprom_bt_affiche_gauche_shortcuts($flux){
	
	$exec=$flux['args']['exec'];

	if($exec!='codes_promo'){
		$flux['data'].='<div><a href="?exec=codes_promo"><img src="'.chemin('img/logo_promo.png').'" alt="'._T('spip:icone_configuration_site').'" align="absmiddle" />&nbsp;'._T('codeprom:gestion_promotions').'</a></div>';
		}
	else{
		$flux['data'].=	'<div><a href="?exec=boutique"><img src="'.chemin('img/logo_boutique_22.png').'" alt="'._T('spip:icone_configuration_site').'" align="absmiddle" />&nbsp;'._T('boutique:boutique').'</a></div>';
		if(!_request('editer_promo'))
			$flux['data'].=	'<div><a href="?exec=codes_promo&editer_promo=new"><img src="'.chemin('img/logo_promo.png').'" alt="'._T('spip:icone_configuration_site').'" align="absmiddle" />&nbsp;'._T('codeprom:editer_code').'</a></div>';
		else $flux['data'].='<div><a href="?exec=codes_promo"><img src="'.chemin('img/logo_promo.png').'" alt="'._T('spip:icone_configuration_site').'" align="absmiddle" />&nbsp;'._T('codeprom:gestion_promotions').'</a></div>';		
		}

	return $flux;
}

function codeprom_bt_form_commande_afficher_general($flux){

	$code_promo=_request('code_promo');
	$prix=_request('prix');	

	$prix=$prix-$row['montant'];

	$contexte=array(
		'code_promo'=>_request('code_promo'),
		'erreurs'=>$flux['args']['erreurs'],
		'prix'=>$prix		
		);
		

 	$flux['data'].=recuperer_fond('prive/formulaires/champ_code_promo',$contexte);


	return $flux;

}

function codeprom_bt_form_commande_afficher_prev_prix($flux){

	if($code_promo=_request('code_promo')){
		$prix=_request('prix');	
		$id_produit = $flux['args']['id_produit'];
		$prix=$prix-$row['montant'];

	
			$row = sql_fetsel('montant,relative,montant','spip_codeprom_codes_promo','code_promo="'.$code_promo.'"');
			
			if(_request('code_devise'))$prix=traduire_code_devise(_request('code_devise'),$id_produit,'prix');
	
			$montant=$row['montant'];
			
			//Réduction en pourcentage
			if($row['relative']==0){
				$reduction=$prix/100*$montant;
	
				}
			//Réduction en montant pour chaque devise	
			else{
				$reduction=unserialize($montant);
				
				foreach($devises_choisis as $code_devise => $p){
					if(_request('code_devise')==$code_devise)$reduction=$reduction[$code_devise];	
	
					}
				}
						
			$prix_reduit=$prix-$reduction;		
	
		$flux['data'].='<div><b>'
				.	'<span class="promotion">'._T('codeprom:prix_promotion').' :</span>'
				. 	'<span class="valeur">'.$prix_reduit.'&nbsp;'.traduire_devise(_request('code_devise')).'</span>'	
				.'</b></div>	';
		}
	return $flux;

}

function codeprom_bt_form_commande_charger($flux){

	$flux['data']=array('code_promo'=>'','test_code'=>'');

	$code_promo = _request('code_promo');
	$code_devise = _request('code_devise');	
	$test_code=_request('test_code');
	$id_produit = $flux['args']['id_produit'];
	$devises_choisis=$flux['args']['devises_choisis'] ;
	

	//Modification du prix dans si un code promo valide est entré
	
	if ($code_promo){

		$flux['data']['devises_choisis']=array();

		$row = sql_fetsel('montant,relative,montant','spip_codeprom_codes_promo','code_promo="'.$code_promo.'"');
		
		$prix=traduire_code_devise(_request('code_devise'),$id_produit,'prix');

		$montant=$row['montant'];
		
		//Réduction en pourcentage
		if($row['relative']==0){
			$reduction=$prix/100*$montant;
			
			foreach($devises_choisis as $code_devise => $p){
				$flux['data']['devises_choisis'][$code_devise] = $p-$reduction.' '.traduire_devise($code_devise) ;
				}
			}
		//Réduction en montant pour chaque devise	
		else{
			$reduction=unserialize($montant);
			
			foreach($devises_choisis as $code_devise => $p){
				$reduction=$reduction[$code_devise];	
				$flux['data']['devises_choisis'][$code_devise] = $p-$reduction.' '.traduire_devise($code_devise) ;
				}
			}
		if(_request('prev'))$flux['data']['_hidden']['prix']=$prix-$reduction;

					
		$flux['data']['prix']=$prix-$reduction;		

	}	
	return $flux;

}

function codeprom_bt_form_commande_verifier($flux){
	include_spip('codeprom_mes_fonctions');
	
	$code_promo=_request('code_promo');	
	$flux['data']=verifier_code_promo($code_promo);
	return $flux;

}

function codeprom_formulaire_verifier($flux){
    if ($flux['args']['form'] == 'commande'){
    	$test_code=_request('test_code');
	if($test_code){$flux['data']='';}
    	}
    	
    return $flux;
}



function codeprom_bt_form_commande_traiter($flux){
	$code_promo=_request('code_promo');
	
	$row = sql_fetsel('reutilisable,utilise','spip_codeprom_codes_promo','code_promo="'.$code_promo.'"');
	
	$valeurs=array();
	
	if($row['reutilisable'])$valeurs['utilise']='oui';
	else $valeurs['utilise']=$row['utilise']+1;

	sql_updateq('spip_codeprom_codes_promo',$valeurs,'code_promo="'.$code_promo.'"');

	return $flux;

}



function codeprom_bt_form_cfg($flux){

 	$flux['data'].=recuperer_fond('formulaires/cfg_boutique',$flux);

	return $flux;
}


?>
