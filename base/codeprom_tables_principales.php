<?php
if (!defined("_ECRIRE_INC_VERSION")) return;
//
// Formulaires : Structure
//

function codeprom_declarer_tables_principales($tables_principales){
	$spip_codeprom_codes_promo = array(
		"id_code_promo" 		=> "bigint NOT NULL",
		"titre" 			=> "varchar(255) NOT NULL",
		"code_promo" 			=> "varchar(255) NOT NULL",
		"relative"		 	=> "bool NOT NULL",			
		"montant" 			=> "varchar(255) NOT NULL",
		"date_fin"		 	=> "date DEFAULT '0000-00-00' NOT NULL",
		"reutilisable"		 	=> "bool NOT NULL",			
		"utilise" 			=> "varchar(255) NOT NULL",		
		);
	
	$spip_codeprom_codes_promo_key = array(
		"PRIMARY KEY" 	=> "id_code_promo",
		);
		
	$spip_codeprom_codes_promo_join = array(
		"id_code_promo"	=> "id_code_promo",
		);

	$tables_principales['spip_codeprom_codes_promo'] = array(
		'field' => &$spip_codeprom_codes_promo,
		'key' => &$spip_codeprom_codes_promo_key,
		'join' => &$spip_codeprom_codes_promo_join
		);	
			
	return $tables_principales;
	
	}
?>
