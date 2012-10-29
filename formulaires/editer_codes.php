<?php

function formulaires_editer_codes_charger_dist($id_code_promo=''){
	include_spip('boutique_mes_fonctions');
	
	$valeurs = array(
		'id_code_promo'=>$id_code_promo,	
		'titre'=>'',				
		'relative'=>'',
		'montant'=>'',
		'reutilisable'=>'',		
		'date_fin_jour'=>'',
		'date_fin_mois'=>'',
		'date_fin_annee'=>'',		
		);
		
	// Crée le code promo en tenant compte les indications de cfg s'il y en a
		
	$nombre_base= 40;
	$nombre_config=lire_config('boutique/nombre_code');
	$code_perso=lire_config('boutique/code_perso');
	$code_promo =strtoupper(sha1(date("YmdGis")));	
	
	$nombre_code_perso = strlen($code_perso);
	
	if($nombre_config>$nombre_code_perso)$nombre_perso=$nombre_config-$nombre_code_perso;
	else $nombre_perso=$nombre_base-$nombre_code_perso;

	$nombre_aplique=$nombre_base-$nombre_perso;

	
	
	if($nombre_perso AND $nombre_base>$nombre_perso){
		$code_promo= substr($code_promo,0,strlen($code_promo)-$nombre_aplique);		
		}
	elseif($nombre_code_perso AND $nombre_base>$nombre_code_perso){
			$code_promo= substr($code_promo,0,strlen($code_promo)-$nombre_code_perso);
			}	
	
	$valeurs['code_promo'] = $code_perso.$code_promo;
	
		
	//preparation des valeurs pour les montants absolus
	
	$devises=lire_config('boutique/devises');
	
	//Valeur utilisé pour la construction des champs
	$valeurs['devises']=array();
	
	if($devises){	
		foreach($devises as $k=>$val){
		$valeurs['devises'][$k]=$val;
		$valeurs['code_'.$val]=_request('code_'.$val);		
		}
	}
	else{$valeurs['devises'][0]=devise_defaut();}	
			

return $valeurs;
}

function formulaires_editer_codes_verifier_dist($id_code_promo=''){

	$date_fin_annee=_request('date_fin_annee');
	$date_fin_jour=_request('date_fin_jour');
	$date_fin_mois=_request('date_fin_mois');
	$erreur_montant='montant';
	
	$erreurs = array();
    	foreach(array('titre','code_promo') as $champ) {
        	if (!_request($champ)) {
            		$erreurs[$champ] = _T('spip:info_obligatoire');
        		}
    		}
	
	// test si montant relative ou absolu	
	if(_request('relative')==1){
		$erreur_montant='montant_absolue';
		$montant=array();
		$devises=lire_config('boutique/devises');
		$valeurs['devises']=array();
		if($devises){	
			foreach($devises as $k=>$val){
				$var="code_$val";
				$$var=_request($var);
				if(_request($var))$montant[$val]=_request($var);		
			}
		}
		else{
			$devise_defaut=devise_defaut();
			$montant[$devise_defaut]=_request('code_'.$devise_defaut);
			}	
	
		}	
	else $montant=_request('montant');
		
	if(!$montant)$erreurs[$erreur_montant] = _T('spip:info_obligatoire');
	
	
	if (!checkdate($date_fin_mois ,$date_fin_jour,$date_fin_annee)){		
 		$erreurs['date_fin_annee'] = _T('codeprom:date_non_valide');
 			}
 			
			
	if (count($erreurs)) {
        	$erreurs['message_erreur'] = _T('spip:avis_erreur');
   		 }		
			
    return $erreurs;



}
function formulaires_editer_codes_traiter_dist($id_code_promo=''){
   	refuser_traiter_formulaire_ajax(); 
   	$retour = array();

	$date_fin = _request('date_fin_annee').'-'._request('date_fin_mois').'-'._request('date_fin_jour');
	


		include_spip('inc/cookie');
	 	spip_setcookie('spip_boutique_session',$token, time()+2*3600 );
		
		$valeurs = array(
			'titre'=>_request('titre'),
			'code_promo'=>_request('code_promo'),
			'relative'=>_request('relative'),		
			'date_fin'=>$date_fin,		
			'reutilisable'=>_request('reutilisable'),						
		);
		
		// test si montant relative ou absolu	
	if(_request('relative')==1){
		$montant=array();
		$devises=lire_config('boutique/devises');
		if($devises){	
			foreach($devises as $k=>$val){
				$var="code_$val";
				if(_request($var))$montant[$val]=_request($var);		
				}
			$valeurs['montant']=serialize($montant);	
			}
		}
	else	$valeurs['montant']=_request('montant');				
	  
	 	$id_code_promo=sql_insertq('spip_codeprom_codes_promo',$valeurs);
	 	
		$retour['message_ok']='ok';
		
 	 	header('Location:?exec=codes_promo');
	 
    return $retour;
}


?>