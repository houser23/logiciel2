<?php 
/**
 	*Ce script permet de charger les données depuis la base de données que ce soit au chargement de la page ou bien à tout moment lorsqu'un bouton est cliqué
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
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8");
			$bdd = new PDO('mysql:host=localhost;dbname=houser', 'root', '', $options);
		}
		catch(Exception $e)	{
			die('Erreur : '.$e->getMessage());
		}

		if ($_POST['action'] == 'update_data') {
			
			
		if ($_POST['strate']=='strate_1') 
				$w = "POPULATION > 0 AND POPULATION < 7500" ;
			else 
				$w = "POPULATION >= 7500 AND POPULATION < 12500";
			
			if ($_POST['strate']=='strate_3') 
				$w = "population > 12500 AND POPULATION < 20000" ;
			if ($_POST['strate']=='strate_4') 
				$w = "POPULATION > 20000 AND POPULATION < 25000" ;
			
			if ($_POST['strate']=='strate_5') 
				$w = "POPULATION > 25000 AND POPULATION < 30000" ;
			
			if ($_POST['strate']=='strate_6') 
				$w = "POPULATION > 30000 AND POPULATION < 35000" ;
			
			if ($_POST['strate']=='strate_7') 
				$w = "POPULATION > 35000 AND POPULATION < 50000" ;
			
			if ($_POST['strate']=='strate_8') 
				$w = "POPULATION > 50000 AND POPULATION < 75000" ;
			
			if ($_POST['strate']=='strate_9') 
				$w = "POPULATION > 75000 AND POPULATION < 100000" ;
			
			if ($_POST['strate']=='strate_10') 
				$w = "POPULATION > 100000 AND POPULATION < 250000" ;
			
			if ($_POST['strate']=='strate_11') 
				$w = "POPULATION > 250000 AND POPULATION < 500000" ;
			
			if ($_POST['strate']=='strate_12') 
				$w = "POPULATION > 500000" ;

			if ($_POST['strate']=='strate_13') 
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
				$b = '"ca"' ;   // !!!
			else 
				$b= '"bp"';   // !!!

			

	
				
			


			$r = 'SELECT epciter.id, nom, population, donneesepcibis.annee, donneesepcibis.indicateur, valeur
			FROM donneesepcibis, epciter
			WHERE epciter.id = donneesepcibis.id			
				AND donneesepcibis.etape_budgetaire = "'.$b.'" 	
				AND donneesepcibis.annee = "'.$a.'" 						
				AND donneesepcibis.indicateur IN ('.$d.') 
				AND '.$w; 
				
				
				

			//echo $r; 
			$reponse = $bdd->query($r) ;

			$donnees = $reponse->fetchAll();
			//echo $donnees;
			echo json_encode($donnees);
			
		}
	}   // !!!
?>
