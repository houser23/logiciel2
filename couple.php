<HTML>
<header>	
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script   src="https://code.jquery.com/jquery-3.2.1.min.js"   integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="   crossorigin="anonymous"></script>
<STYLE>
	#trier { background-color:white; color:black; border-collapse:collapse; BORDER:white 1px solid; FONT:12 Arial; TEXT-ALIGN:center }
	#trier TR { background-color:#ffefd9 }
	#trier .title { background-color:#bf2b2f; FONT:14 Arial; color:#ffffff; font-weight:bold }
	.tri { FONT:bold 12 Arial; CURSOR:pointer }
	BODY { background-color:#FFFFFF }
	#trier TD { BORDER:white 1px solid; }
	.button {margin: 15px 50px;}
	.button span {cursor: pointer; border: 1px solid gray; padding: 5px 10px; background-color: rgb(208, 208, 208)}
	#button_row {display: flex; flex-direction: row; }
	#conversion1, #conversion2 {display: none;}
</STYLE>

<SCRIPT>
	var index
	var sortOrder = {
		column: '',
		dec: 0
	};
	var myChart;
	/* 
	* Fonction pour trier les nombres
	*/ 
	function sort_int (p1, p2) {  
		return p1[index]-p2[index]; 
	}			
	
	/* 
	* Fonction pour trier les strings
	*/ 
	function sort_char (p1, p2) { 
		return ((p1[index]>=p2[index])<<1)-1; 
	}	

	/* 
	* Fonction pour trier le tableau quand une flèche est cliquée
	* Paramètres: 
	*   - e : event
	* 	- Dec : 0 = Croissant ; 1 = Décroissant
	*/ 
	function TableOrder(element,Dec) { 
	 	var FntSort = new Array();

		var nom = $('select[name="dept"]').val()[0]; 

		//determine la cellule sélectionnée
		oCell = $('#'+element)[0]

		sortOrder = {
			column: $(oCell).attr('id'),
			dec: Dec
		};

		//determine l'objet table parent
		oTable = $('#dataTable')[0]
		
		//determine l'index de la cellule		
		var tableHeader = ($('#dataTable thead tr td').toArray())
		for(var i = 0 ; i < tableHeader.length ; i++) {
			if ($(tableHeader[i]).attr('id') === element.substr(0, element.search('-')))
				index = i;
		}

	 	//---- Copier Tableau Html dans Table JavaScript ----//
		var Table = new Array()
		
		for (r = 1 ; r < oTable.rows.length ; r++) 
			Table[r-1] = new Array()

		//Sur toutes les cellules
		for (c = 0 ; c < oTable.rows[0].cells.length ; c++)	{
			var Type;
			objet = oTable.rows[1].cells[c].innerHTML.replace(/<\/?[^>]+>/gi,"")

			if (objet.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d$/)) { 
				FntSort[c]=sort_char; Type=0;   //date jj/mm/aaaa
			} 
			else if (objet.match(/^[0-9£?$\.\s-]+$/)) { 
				FntSort[c]=sort_int;  Type=1;   //nombre, numéraire
			} 
			else { 
				FntSort[c]=sort_char; Type=2; //Chaine de caractère
			}

			//De toutes les rangées
			for ( r=1 ; r<oTable.rows.length ; r++)	{
				objet=oTable.rows[r].cells[c].innerHTML.replace(/<\/?[^>]+>/gi,"");
				switch(Type) {		
					case 0: Table[r-1][c]=new Date(objet.substring(6),objet.substring(3,5),objet.substring(0,2)); break; //date jj/mm/aaaa
					case 1: Table[r-1][c]=parseFloat(objet.replace(/[^0-9.-]/g,'')); break; //nombre
					case 2: Table[r-1][c]=objet.toLowerCase(); break; //Chaine de caractère
				}
				Table[r-1][c+oTable.rows[0].cells.length] = oTable.rows[r].cells[c].innerHTML
			}
		}

	 	//--- Tri Table ---//
		Table.sort(FntSort[index]);
		if (Dec) 
			Table.reverse();

	 	//---- Copier Table JavaScript dans Tableau Html ----//
	 	//Sur toutes les cellules
		var html = '<tbody>';
		for (r = 0 ; r < oTable.rows.length - 1; r++) { 		//De toutes les rangées 
			if (nom && Table[r][0].toLowerCase() === nom.toLowerCase())
					html = html + "<tr style='color: red'>";
				else
					html = html + "<tr>"

			for (c = 0 ; c < oTable.rows[0].cells.length ; c++)					
				html = html + "<td>" + Table[r][c+oTable.rows[0].cells.length] + "</td>"
			
			html = html + "</tr>"
		}
		html = html + '</tbody>';

		// On ajoute les lignes générées après l'entête du tableau
		$('#dataTable tbody').remove();
		$('#dataTable').append(html)
		createGraphic()
	}

	/*
	*
	* Fonction qui calcule la moyenne du tableau passé en paramètre
	*
	*/
	function getAverage (tableau) {
		var somme = 0 ;
		tableau.forEach(function(value) {
			somme = somme + parseInt(value) ;
		})
		return somme / tableau.length ;
	}

	/*
	*
	* Fonction qui crée le graphique en fonction de ce qu'il y a dans le tableau (tient compte de l'ordre)
	*
	*/
	function createGraphic () {
		// On récupère les datas qui proviennent du tableau (par exemple, suite au tri)
		let results = [];
		let columnNames = [];

		var graphType = $('select[name="graphType"]').val(); 

		// Récupération des entêtes
		$('#dataTable thead tr td').each(function() {
			columnNames.push($(this).attr('id'))
		})
		
		// Récupération du contenu de chaque ligne
		$('#dataTable tbody tr').each(function(lineIndex) {
			$(this).find('td').each(function(colIndex) {
				if (!results[lineIndex]) 
					results[lineIndex] = {}; // Si ça n'est pas encore fait, on définit l'object de la ligne
				
				results[lineIndex][columnNames[colIndex]] = $(this).text()  // Ca donne {nom: valeur, population: valeur, etc.. }
			})
		})

		// Calcul des informations à mettre dans le graphique
		var dataSets = [];
		var colors = [];
		results.forEach(function(row) {
			Object.keys(row).forEach(function(key,index) {
				if (typeof dataSets[index] === 'undefined') {
					let color = '#'+((1<<24)*Math.random()|0).toString(16);
					colors[key] = color;
					dataSets[index] = {
						label: key, 
						data: [],
						fill: false,
						backgroundColor: color,
			            borderColor: color,
			            borderWidth: 1,
			            yAxisID: 'y-axis-'+index
					};
				}
				dataSets[index].data.push(row[key])
			});
		});

		// On enlève les colonnes population et nom
		dataSets = dataSets.map(function(row, index) {
			if ($.isNumeric(row.label) || row.label === 'population' || row.label === 'nom') {
				return false
			}
			else {
				return row	
			} 
		})
		dataSets = dataSets.filter(function(row) {
			if (row === false)
				return false;
			else 
				return true;
		})
		
		// Définition des ordonnées
		let yAxes = [];
		dataSets.forEach(function (dataSet, index) {
			yAxes.push({
				type: 'linear',
				display: true,
				position: index > 0 ? 'right' : 'left',
				id: dataSet.yAxisID,
				ticks: {
					fontColor: colors[dataSet.label],
				}
			})
		})

		// Création du graphique
		var labelsArray = results.map(function(row) {
			return row.nom;
		});

		var ctx = document.getElementById("myChart").getContext('2d');
		if (myChart) myChart.destroy()
		myChart = new Chart(ctx, {
		    type: graphType,
		    data: {
		        labels: labelsArray,
		        datasets: dataSets
		    },
		    options: {
		    	stacked: false,
		        scales: {
		            yAxes: yAxes,
					xAxes: [{
						ticks: {
							autoSkip: false
						}
					}]
		        }
		    }
		});
		
	}

	/* 
	*
	* Cette fonction rafraichit le tableau avec les données transmises en paramètre
	*
	*/
	function updateTable (data) {
		var nom = $('select[name="dept"]').val()[0]; 

		// On met à jour les données du tableau
		$('#dataTable thead').remove();
		$('#dataTable tbody').remove();
		var titles = '';
		titles = titles + '<thead><tr>';
		Object.keys(data[0]).forEach(function(key) {
			if (!$.isNumeric(key)) {
				titles = titles + "<td style='text-transform: capitalize;' id='"+key+"''>" + key ;
				titles = titles + "<span class='tri' id='"+key+"-croissant' onclick=TableOrder('"+key+"-croissant',1)>&#9660;</span>";
				titles = titles + "<span class='tri' id='"+key+"-decroissant' onclick=TableOrder('"+key+"-decroissant',0)>&#9650;</span></td>";
			}
		});
		titles = titles + '</tr></thead>'
		$('#dataTable').append(titles)
		var html = '<tbody>';
		data.forEach(function(row) {
			if (row.nom === nom) {
				html = html + "<tr style='color: red'>";
			}
			else 
				html = html + "<tr>";
			Object.keys(row).forEach(function(col) {
				if (!$.isNumeric(col))
					html = html + "<td>" + row[col] + "</td>";
			})
			html = html + "</tr>"
		})
		html = html + "</tbody>"
		// On ajoute les lignes générées après l'entête du tableau
		$('#dataTable').append(html)
	}

	/**
	*
	* Fonction appelée quand on clique sur "Valider"
	*
	**/
	function validate () {
		sortOrder = {
			column: '',
			dec: 0
		};

		queryServer()
	}

	/**
	*
	* Fonction appelée permettant de convertir les données reçues en tableau exploitable
	*
	**/
	function convertResultingData (data) {
		let cleanData = [];
		data = data.forEach(item => {
			let index = cleanData.findIndex(cleanRow => cleanRow["nom"] === item["nom"]);
			if (index < 0) {
				cleanData.push({
					nom: item["nom"],
					population: item["population"],
					[item["indicateur"]]: item["valeur"] 
				});
			}
			else {
				cleanData[index][item["indicateur"]] = item["valeur"]
			}
		})
		return cleanData;
	}

	/*
	*
	* Cette fonction est appelée quand le bouton "Valider" est pressé : permet la mise à jour des données
	*
	*/
	
	
	
	
	
	
	
	function queryServer () {
		
		
		
		var d1  = $('select[name="d1"]').val()[0]; 
		var d2 = $('select[name="d2"]').val()[0]; 
		var d3 = $('select[name="d3"]').val()[0];
		var an = $('select[name="an"]').val()[0];
		var etape = $('select[name="etape"]').val()[0];
		var dept = $('select[name="dept"]').val();
		


		
		$.post(
			'./ajax4.php',
			{
				action: "update_data",
				d1: d1,
				d2: d2,
				d3: d3,
				an: an,
				dept:dept,
				etape: etape,
			}, function(data) {
				if (data === "[]") {
					alert("Rien à afficher")
					return ;
				}
				console.log("data", data);
				// Permet d'utiliser les données récupérées
				results = $.parseJSON(data);
				
				console.log(results);
				
			
				results = convertResultingData(results)
				updateTable(results)

				// Calcul de la moyenne
				var populationArray = results.map(function(item) {
					return item.population;
				});
				var moyenne = getAverage(populationArray);
				// On sélectionne l'objet HTML identifié par l'id "moyenne" et on le met à jour
				$('#moyenne').text(moyenne);

				createGraphic()

				$('#conversion1').show();
				$('#conversion2').hide();
			}
		)		
	}

	/*
	*
	* Fonction de conversion € => € / hab
	*
	*/
	function convert1 () {

		let columnNames = [];

		// Récupération des entêtes
		$('#dataTable thead tr td').each(function() {
			columnNames.push($(this).attr('id'))
		})

		// S'il n'y a pas Potentiel_fiscal, DMTO ou CVAE, il n'y a rien à convertir
		if (columnNames.indexOf("potentiel_fiscal") < 0 && columnNames.indexOf("DMTO") < 0 && columnNames.indexOf("cif") < 0 && columnNames.indexOf("rrf") < 0 && columnNames.indexOf("CVAE") < 0) {
			alert("Il n'y a rien à convertir");
			return ;
		}

		
		// Récupération du contenu de chaque ligne
		$('#dataTable tbody tr').each(function(lineIndex) {
			$(this).find('td').each(function(colIndex) {
				if (!results[lineIndex]) 
					results[lineIndex] = {}; // Si ça n'est pas encore fait, on définit l'object de la ligne
				
				results[lineIndex][columnNames[colIndex]] = $(this).text()  // Ca donne {nom: valeur, population: valeur, etc.. }
			})
		})

		results.forEach(function(row, index) {
			if (row.potentiel_fiscal)
				results[index].potentiel_fiscal = Math.round(results[index].potentiel_fiscal / results[index].population * 100) / 100;
			if (row.DMTO)
				results[index].DMTO = Math.round(results[index].DMTO / results[index].population * 100 ) / 100 ;
			if (row.CVAE)
				results[index].CVAE = Math.round(results[index].CVAE / results[index].population * 100 ) / 100 ;
			if (row.cif)
				results[index].cif = Math.round(results[index].cif / results[index].population * 100 ) / 100 ;
			if (row.rrf)
				results[index].rrf = Math.round(results[index].rrf / results[index].population * 100 ) / 100 ;
		})
		
		updateTable(results)
		createGraphic()

		// On change le bouton apparant
		$('#conversion1').hide();
		$('#conversion2').show();
	}

	/*
	*
	* Fonction de conversion € / hab => €
	*
	*/
	function convert2() {
		// Ca revient à relancer la mise à jour 
		queryServer();
		setTimeout(function() {
			// On remet l'ordre de tri
			if (sortOrder.column !== '')
				TableOrder(sortOrder.column, sortOrder.dec)
			
			// On change le bouton apparant
			$('#conversion2').hide();
			$('#conversion1').show();
		}, 200)
		
	}

</SCRIPT>



	<div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center; ">
		<span style="font-weight: bold;">Année</span>
		<select name="an" size =5 multiple="multiple">
			<option = value="2013"> 2013 </option>
			<option = value="2014"> 2014 </option>
			<option = value="2015"> 2015 </option>
			<option = value="2016"> 2016 </option>
			<option = value="2017"> 2017 </option>
			<option = value="2018"> 2018 </option>
			
		</select>
	</div>
	<div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center;">
		<span style="font-weight: bold;">Etape budgétaire</span>
		<select name="etape" size =5 multiple="multiple"> 
			<option = value="CA"> CA </option>
			<option = value="BP"> BP </option>
			
		</select>
	</div>
	
	
	
	<div style="display: flex; flex-direction: row; justify-content: space-around; flex-wrap: wrap">
	<div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center; ">
		<span style="font-weight: bold;">Nom de département</span>
		<select name="dept" size =5 multiple="multiple"> 
    		<?php
				
				try
				{
					$bdd = new PDO('mysql:host=localhost;dbname=houser', 'root', '');
				}
				catch(Exception $e)
				{
						die('Erreur : '.$e->getMessage());
				}

				$reponse = $bdd->query('SELECT DISTINCT departement FROM `epciter` ');

				
				
				while ($donnees = $reponse->fetch())
				{
					echo '<option value='.$donnees['departement'].'>'.$donnees['departement'].'</option>'."\n";	
					
					
				}
				$reponse->closeCursor();
				
			?>
			
			
		</select>
	</div>
	
	
	<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Bases fiscales</span>
		<select name="d1" size =5 multiple="multiple"> 
			<option = value="forfaitaire"> Part forfaitaire </option>
			<option = value="baseTH"> Base TH </option>
			<option = value="CVAE"> CVAE </option>
			<option = value="rrf"> RRF </option>
			<option = value="vfpic"> Versement FPIC </option>
			<option = value="cif"> Cif </option>
			<option = value="dotation_interco"> Dotation d'intercommunalité </option>
			<option = value="pfa"> Potentiel financier agrégé </option>
			<option = value="soldefpic"> Solde FPIC </option>

		</select>
	</div>
	
	
	
	<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Taux</span>
		<select name="d2" size =5 multiple="multiple"> 
			<option = value="taux_th"> Taux TH </option>
			<option = value="taux_fb"> Taux FB </option>
			<option = value="taux_cfe"> Taux CFE </option>
			<option = value="pfpic"> Prélèvement FPIC</option>
			<option = value="soldefpic"> Solde FPIC </option>
			<option = value="potentiel_financier"> Potentiel financier </option>
			<option = value="revenu"> Revenu </option>
			<option = value="cif"> CIF </option>
			
		</select>
	</div>
	
	<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Indicateurs financiers :</span>
		<select name="d3" size =5 multiple="multiple"> 
			<option = value="potentiel_financier"> Potentiel financier </option>
			<option = value="potentiel_fiscal"> Potentiel fiscal </option>
			<option = value="vfpic"> Versement FPIC </option>
			
			
			
		</select>
	</div>
</div>
<div id="button_row">
	<div class="button" id="mettre_a_jour">
		<span onclick=validate()>
			Valider
		</span>
	</div>
	<div class="button" id="conversion1">
		<span onclick=convert1()>
			Convertir en € / hab
		</span>
	</div>
	<div class="button" id="conversion2">
		<span onclick=convert2()>
			Convertir en €
		</span>
	</div>
	<div style="display: flex; flex-direction: row; margin: 10px 15px; align-items: center; justify-items: center">
		<span style="font-weight: bold;margin-right: 10px">Type de graphique : </span>
		<select onchange=createGraphic() name="graphType" >
			<option value="line">Lignes</option>
			<option value="bar">Barres</option>				
		</select>
	</div>
</div>

<div style="display: flex; justify-content: space-between;">	
	<div>
		<table id=trier>
			<tr class=title>
				<table border=1 id="dataTable">
				</table>
			</tr>
		</table>
		
		<table>
			<table border=1>
				<TR>
					<TD>
						Moyenne
					</TD>
					<TD id="moyenne" colspan = 4>
					</TD>
				</TR>
			</table>
		</table>
		
		
	</div>
	
	<div style="width: 100%; height: 300px; margin: 20px 50px;">
		<canvas id="myChart" ></canvas>
	</div>
</div>
	
</header>	
</html>






