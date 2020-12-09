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
		//if (!empty($_POST['action'])) {
		try	{
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8");
			$bdd = new PDO('mysql:host=localhost;dbname=houser', 'root', '');
		}
		catch(Exception $e)	{
			die('Erreur : '.$e->getMessage());
		}
		
		
		if ($_POST['action'] == 'update_data') {
			
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

			$z = implode(",",$_POST['dept']);  //// ICI

			$r = 'SELECT departement, nom, valeur , population, indicateur
                FROM donneesepcibis, epciter
                WHERE donneesepcibis.id = epciter.id
                    AND donneesepcibis.etape_budgetaire = '.$b.' 
                    AND donneesepcibis.annee = '.$a.' 
                    AND epciter.departement IN ('.$z.') 
                    AND donneesepcibis.indicateur IN ('.$d.') 
                    GROUP BY departement, nom' ;


			//echo $r; 
			$reponse = $bdd->query($r) ;

			$donnees = $reponse->fetchAll();
			//var_dump($donnees);

			echo json_encode(utf8ize($donnees));
			
		}
	}   // !!!
?>
