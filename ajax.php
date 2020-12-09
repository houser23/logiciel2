


<?php 
/**
 	Ce script permet de charger les données depuis la base de données que ce soit au chargement de la page ou bien à tout moment lorsqu'un bouton est cliqué
**/

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


	if (!empty($_POST['action'])) {
		try	{
			$bdd = new PDO('mysql:host=localhost;dbname=houser', 'root', '');

		}
		catch(Exception $e)	{
			die('Erreur : '.$e->getMessage());
		}

		if ($_POST['action'] == 'update_data') {
			if ($_POST['strate']=='strate_1') 
				$w = "POPULATION > 0 AND POPULATION < 250000" ;
			else 
				$w = "POPULATION >= 250000 AND POPULATION < 500000";
			
			if ($_POST['strate']=='strate_3') 
				$w = "population > 500000 AND POPULATION < 1000000" ;
			if ($_POST['strate']=='strate_4') 
				$w = "POPULATION > 1000000" ;
			
			if ($_POST['strate']=='strate_5') 
				$w = "POPULATION > 0" ;
			
		$d='';
		if (isset($_POST['d1'])) { 
			$d = '"'.$_POST['d1'].'"';
			$sep = ',';
		}
		
		else $sep = '';
		
		if (isset($_POST['d2'])) { 
			$d = $d.$sep.' "'.$_POST['d2'].'"';
			$sep = ',';

		}
		
		
		if (isset($_POST['d3']))
			$d = $d.$sep.' "'.$_POST['d3'].'"'; 
			
		
		
			
			$a = $_POST['an'];
			
			if ($_POST['etape']=='CA') 
				$b = "ca" ;
			else 
				$b= "bp";
			
			

			$r = 'SELECT nom, population, donnees.indicateur, valeur 
				FROM donnees, departement 
				WHERE departement.iddepartement = donnees.iddepartement 
					AND donnees.etape_budgetaire = "'.$b.'" 
					AND donnees.annee = '.$a.' 
					AND departement.iddepartement < 96
					AND donnees.indicateur IN ('.$d.') AND '.$w; 
		
			$reponse = $bdd->query($r) ;

			$donnees = $reponse->fetchAll();
			echo json_encode($donnees);
			
		}

	if ($_POST['action'] == 'update_data_by_year') {
			
			
		$sep = '';
			$d = '';
			if (isset($_POST['d1']))
			$d = $d.$sep.' "'.implode('","', $_POST['d1']).'"'; 

			
			
			/*if (isset($_POST['d2'])) 
				$d = $d.', "'.$_POST['d2'].'"'; 
			
			if (isset($_POST['d3'])) 
				$d = $d.', "'.$_POST['d3'].'"'; */
			
			if ($_POST['etape']=='CA') 
				$b = "ca" ;
			else 
				$b= "bp";
			
	
			$r = 'SELECT nom, population, donnees.annee, donnees.indicateur, valeur 
				FROM donnees, departement 
				WHERE departement.iddepartement = donnees.iddepartement 
					AND donnees.etape_budgetaire = "'.$b.'" 
					AND donnees.indicateur IN ('.$d.') 
					
					AND nom = "'.$_POST['nom'].'"';
		
			$reponse = $bdd->query($r) ;

			$donnees = $reponse->fetchAll();
			echo json_encode($donnees);
		}
	
	if ($_POST['action'] == 'update_data_by_year3') {
			if ($_POST['strate']=='strate_1') 
				$w = "POPULATION > 0 AND POPULATION < 250000" ;
			else 
				$w = "POPULATION >= 250000 AND POPULATION < 500000";
			
			if ($_POST['strate']=='strate_3') 
				$w = "population > 500000 AND POPULATION < 1000000" ;
			if ($_POST['strate']=='strate_4') 
				$w = "POPULATION > 1000000" ;
			
			if ($_POST['strate']=='strate_5') 
				$w = "POPULATION > 0" ;
			
		$sep = '';
			$d = '';
			if (isset($_POST['d1']))
			$d = $d.$sep.' "'.implode('","', $_POST['d1']).'"'; 

			
			
				/*		if (isset($_POST['d2'])) 
				$d = $d.', "'.$_POST['d2'].'"'; 
			
if (isset($_POST['d3'])) 
				$d = $d.', "'.$_POST['d3'].'"'; */
			
			if ($_POST['etape']=='CA') 
				$b = "ca" ;
			else 
				$b= "bp";
	
			
			$r = 'SELECT depthorsparisetdom.iddepartement, nom, population, donnees.annee, donnees.indicateur, valeur 
                FROM donnees, depthorsparisetdom 
                WHERE depthorsparisetdom.iddepartement = donnees.iddepartement 
                    AND donnees.etape_budgetaire = "'.$b.'" 
                    AND donnees.indicateur IN ('.$d.')				
                    AND '.$w; 
				
		
			$reponse = $bdd->query($r) ;

			$donnees = $reponse->fetchAll();
			echo json_encode($donnees);
		}
	if ($_POST['action'] == 'update_data_by_year2') {
			
			
			if ($_POST['strate']=='strate_1') 
				$w = "POPULATION > 0 AND POPULATION < 250000" ;
			else 
				$w = "POPULATION >= 250000 AND POPULATION < 500000";
			
			if ($_POST['strate']=='strate_3') 
				$w = "population > 500000 AND POPULATION < 1000000" ;
			if ($_POST['strate']=='strate_4') 
				$w = "POPULATION > 1000000" ;
			
			
			
			
			if (isset($_POST['d1'])) 
				$d = '"'.$_POST['d1'].'"'; 
			
			/*if (isset($_POST['d2'])) 
				$d = $d.', "'.$_POST['d2'].'"'; 
			
			if (isset($_POST['d3'])) 
				$d = $d.', "'.$_POST['d3'].'"'; */
			
			if ($_POST['etape']=='CA') 
				$b = "ca" ;
			else 
				$b= "bp";
			
			
			
			
			
			
			$r = 'SELECT nom, population, donnees.annee, donnees.indicateur, valeur 
				FROM donnees, departement 
				WHERE departement.iddepartement = donnees.iddepartement 
					AND donnees.etape_budgetaire = "'.$b.'" 
					AND donnees.indicateur IN ('.$d.') 
					AND '.$w;
				
		
			$reponse = $bdd->query($r) ;

			$donnees = $reponse->fetchAll();
			echo json_encode($donnees);
		}
	}
?>