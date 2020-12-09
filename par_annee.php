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
	.button {margin: 15px 25px;}
	.button span {cursor: pointer; border: 1px solid gray; padding: 5px 10px; background-color: rgb(208, 208, 208)}
	#button_row {display: flex; flex-direction: row; }
	#conversion1, #conversion2 {display: none;}
	#dataTable thead tr td {min-width: 100px; max-width:170px}
	#calculTable tr td {min-width: 100px; max-width:170px}
body {
margin:0;
  padding:0;
  background: url(14116.jpg) no-repeat center fixed; 
  -webkit-background-size: cover; /* pour anciens Chrome et Safari */
  background-size: cover; /* version standardisée */
}
</STYLE>

<SCRIPT>
	var index
	var sortOrder = {
		column: '',
		dec: 0
	};
	var myChart;
	var displayMoyenneOnGraph = false;
	var displayEcartTypeOnGraph = false;

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

		var nom = $('select[name="nom"]').val()[0]; 

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
		return Math.round((somme * 100) / tableau.length ) / 100 ;
	}

	/*
	*
	* Fonction qui calcule l'écart-type du tableau passé en paramètre
	*
	*/ 
	function getStandardDeviation (tableau) {
		var r = {mean: 0, variance: 0, deviation: 0}, t = tableau.length;
		for (var m, s = 0, l = t; l--; s += tableau[l]);
		for (m = r.mean = s / t, l = t, s = 0; l--; s += Math.pow(tableau[l] - m, 2));
		return Math.round(Math.sqrt(r.variance = s / t) * 100 ) / 100;
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

		results = results.filter(function(row) {
			if (row.nom === "Moyenne" || row.nom === "Ecart-type") 
				return false;
			else 
				return true
		})
		
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
			            yAxisID: 'y-axis-0'
					};
				}
				dataSets[index].data.push(row[key])
			});
		});

		// On enlève les colonnes population et nom
		dataSets = dataSets.map(function(row, index) {
			if (row.label === 'population' || row.label === 'nom') {
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
			if (index === 0) {
				yAxes.push({
					type: 'linear',
					display: true,
					position: index > 0 ? 'right' : 'left',
					id: dataSet.yAxisID,
					ticks: {
						fontColor: colors[dataSet.label],
					}
				})
			}
		})

		// Création du graphique
		var labelsArray = results.map(function(row) {
			return row.nom;
		});

		if (displayMoyenneOnGraph) {
			let moyennes = $('#Moyenne td');
			for (var i = 2 ; i < moyennes.length ; i++) {
				let color = '#'+((1<<24)*Math.random()|0).toString(16);
				let moyenne = moyennes.eq(i).text(); 
				dataSets.push({
					label: "Moyenne "+dataSets[i-2].label, 
					data: dataSets[0].data.map(i => moyenne),
					fill: false,
					backgroundColor: color,
					borderColor: color,
					borderWidth: 1,
					yAxisID: 'y-axis-0',
					pointRadius: 0
				});
			}
		}
		if (displayEcartTypeOnGraph) {
			let ecartTypes = $('#EcartType td')
			for (var i = 2 ; i < ecartTypes.length ; i++) {
				let color = '#'+((1<<24)*Math.random()|0).toString(16);
				let ecartType = ecartTypes.eq(i).text(); 
				dataSets.push({
					label: "Ecart-Type "+dataSets[i-2].label, 
					data: dataSets[0].data.map(i => ecartType),
					fill: false,
					backgroundColor: color,
					borderColor: color,
					borderWidth: 1,
					yAxisID: 'y-axis-0',
					pointRadius: 0
				});
			}
		}

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
		let keySorted = ["nom","population"]
		Object.keys(data[0]).forEach(key => {
			if ($.isNumeric(key))
				keySorted.push(key)
		})

		

		// On met à jour les données du tableau
		$('#dataTable thead').remove();
		$('#dataTable tbody').remove();
		var titles = '';
		titles = titles + '<thead><tr>';
		keySorted.forEach(function(key) {
				titles = titles + "<td style='text-transform: capitalize;' id='"+key+"''>" + key ;
				titles = titles + "<span class='tri' id='"+key+"-croissant' onclick=TableOrder('"+key+"-croissant',1)>&#9660;</span>";
				titles = titles + "<span class='tri' id='"+key+"-decroissant' onclick=TableOrder('"+key+"-decroissant',0)>&#9650;</span></td>";
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
			keySorted.forEach(function(col) {
				html = html + "<td>" + row[col] + "</td>";
			})
			html = html + "</tr>"
		})
		html = html + "</tbody>"
		// On ajoute les lignes générées après l'entête du tableau
		$('#dataTable').append(html)

		// Calcul des différentes moyennes
		$('#calculTable tr').remove()
		let moyenneHTML = '<tr id=Moyenne style="background-color:lightgray;"><td style="font-weight: bold">Moyenne</td>';
		let ecartTypeHTML = '<tr id=EcartType style="background-color:lightgray;"><td style="font-weight: bold">Ecart-type</td>'

		keySorted.forEach(function(key) {
			if (key !== 'nom') {
				let array = data.map(function(item) {
					return item[key]
				})
				let moyenne = getAverage(array)
				let ecartType = getStandardDeviation(array.map(i => parseInt(i)));
				moyenneHTML = moyenneHTML + '<td>' + moyenne + '</td>';
				ecartTypeHTML = ecartTypeHTML + '<td>' + ecartType + '</td>';
			}
		});
		moyenneHTML = moyenneHTML + '</tr>';
		ecartTypeHTML = ecartTypeHTML + '</tr>';

		// On sélectionne l'objet HTML identifié par l'id "moyenne" et on le met à jour		
		$('#calculTable').append(moyenneHTML);
		$('#calculTable').append(ecartTypeHTML);
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
					[item["annee"]]: item["valeur"] 
				});
			}
			else {
				cleanData[index][item["annee"]] = item["valeur"]
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
		
		var d1  = $('select[name="d1"]').val(); 
		/*var d2 = $('select[name="d2"]').val()[0]; 
		var d3 = $('select[name="d3"]').val()[0];*/
		var etape = $('select[name="etape"]').val()[0];
		var strate = $('select[name="strate"]').val()[0]; 
		$.post(
			'./ajax.php',
			{
				action: "update_data_by_year3",
				strate: strate,
				d1: d1,
				//d2: d2,
				//d3: d3,
				etape: etape,
			}, function(data) {

				if (data === "[]") {
					alert("Rien à afficher")
					return ;
				}
				// Permet d'utiliser les données récupérées
				results = $.parseJSON(data);
				results = convertResultingData(results)
				
				updateTable(results)

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

		
		// Récupération du contenu de chaque ligne
		$('#dataTable tbody tr').each(function(lineIndex) {
			$(this).find('td').each(function(colIndex) {
				if (!results[lineIndex]) 
					results[lineIndex] = {}; // Si ça n'est pas encore fait, on définit l'object de la ligne
				
				results[lineIndex][columnNames[colIndex]] = $(this).text()  // Ca donne {nom: valeur, population: valeur, etc.. }
			})
		})

		results.forEach(function(row, index) {
			Object.keys(row).forEach(key => {
				if ($.isNumeric(key)) {
					results[index][key] = Math.round(results[index][key] / results[index].population * 100) / 100;
				}
			})
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

	/*
	*
	* Fonction qui affiche la moyenne sur le graphique
	*
	*/
	function showMoyenneOnGraph() {
		displayMoyenneOnGraph = !displayMoyenneOnGraph; 
		createGraphic()
	}

	/*
	*
	* Fonction qui affiche l'écart type sur le graphique
	*
	*/
	function showEcartTypeOnGraph() {
		displayEcartTypeOnGraph = !displayEcartTypeOnGraph;
		createGraphic()
	}

</SCRIPT>

<div style="display: flex; flex-direction: row; justify-content: space-around; flex-wrap: wrap">
	<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Votre strate démographique : </span>
		<select name="strate" size =5 multiple="multiple">
			<option = value="strate_1"> De 0 à 7 500 habitants </option>	
			<option = value="strate_2"> De 7 500 à 12 500 habitants </option>
			<option = value="strate_3"> De 12 500 à 20 000 habitants </option>
			<option = value="strate_4"> De 20 000 à 25 000 habitants  </option>
			<option = value="strate_5"> De 25 000 à 30 000 habitants  </option>
			<option = value="strate_6"> De 30 000 à 35 000 habitants  </option>
			<option = value="strate_7"> De 35 000 à 50 000 habitants  </option>
			<option = value="strate_8"> De 50 000 à 75 000 habitants </option>
			<option = value="strate_9"> De 75 000 à 100 000 habitants </option>
			<option = value="strate_10"> De 100 000 à 250 000 habitants </option>
			<option = value="strate_11"> Communautés urbaines (de 250 000 à 500 000 habitants) </option>
			<option = value="strate_12"> Métropole (>50 000) </option>
		</select>
	</div>

	<div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center;">
		<span style="font-weight: bold;">Etape budgétaire</span>
		<select name="etape" size =5 multiple="multiple"> 
			<option = value="CA"> CA </option>
			<option = value="BP"> BP </option>
			
		</select>
	</div>
	
	
	
	<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Indicateurs financiers :</span>
		<select name="d1" size =5 multiple="multiple"> 
			<option = value="CVAE"> CVAE </option>
			<option = value="dcrtp"> DCRTP </option>
			<option = value="dcrtp_ecart"> DCRTP - Ecart</option>
			<option = value="DGF"> DGF </option>
			<option = value="forfaitaire"> DGF - Part forfaitaire </option>
			<option = value="dpu"> DGF - DPU </option>
			<option = value="dfm"> DGF - DFM </option>
			<option = value="compensation"> DGF - Part compensation </option>
			<option = value="base_foncier"> Bases taxe foncière </option>
			<option = value="tauxFB"> Taux de taxe foncière </option>
			<option = value="recettes_investissement"> Recettes investissement </option>
			<option = value="recettes_fonctionnement"> Recettes de fonctionnement </option>
			<option = value="recettes_investissement"> Recettes investissement </option>
			<option = value="cnsa_mdph"> Recettes CNSA MDPH </option>
			<option = value="cnsa_apa"> Recettes CNSA APA </option>
			<option = value="cnsa_pch"> Recettes CNSA PCH </option>
			<option = value="dp_fonctionnement"> Dépenses fonctionnement test </option>
			<option = value="VFDMTO"> Versement au FDMTO </option>
			<option = value="fondssolidarite_versement"> Fonds de solidarité </option>
			<option = value="dgd"> DGD </option>
			<option = value="fngir"> FNGIR </option>
			<option = value="fctva"> FCTVA </option>
			<option = value="DepTotales"> Dépenses totales </option>
			<option = value="dp_investissement"> Dépenses inv </option>
			<option = value="dp_fonctionnement"> Dépenses de fonctionnement </option>
			<option = value="dp_sociale_totale"> Dépenses sociales totales </option>
			<option = value="dp_apa"> Dépenses APA </option>
			<option = value="dp_pch"> Dépenses PCH </option>
			<option = value="RSA"> Dépenses RSA </option>
			<option = value="PFDMTO"> Prélèvement au FPDMTO </option>
			<option = value="SDIS_FONCT"> SDIS FONCTIONNEMENT </option>
			<option = value="dp_personnels"> Dépenses de personnel </option>
			<option = value="fondssolidarite_prelevement"> Prélèvement fonds de solidarité </option>
			<option = value="dp_apa_domicile"> APA à domicile </option>
			<option = value="dp_apa_etablissement"> APA en établissement </option>
			<option = value="revenu"> Revenu/Hab </option>
			<option = value="epargne_gestion"> Epargne de gestion </option>
			<option = value="epargne_brute"> Epargne brute </option>
			<option = value="epargne_nette"> Epargne nette </option>
			<option = value="interets_financiers"> Intérêts financiers </option>
			<option = value="remboursement_capital"> Remboursement capital </option>
			<option = value="endettement"> Encours de dette </option>
			<option = value="interets_financiers"> Intérêts financiers </option>
			<option = value="taux"> Taux de taxe foncière </option>
			<option = value="potentiel_fiscal"> Potentiel fiscal </option>
			<option = value="potentiel_financier"> Potentiel financier </option>
			<option = value="desendettement"> Désendettement </option>
			<option = value="racapa"> RAC APA </option>
			<option = value="racpch"> RAC PCH </option>
			<option = value="racrsa"> RAC RSA </option>
			<option = value="fondssolidarite_versement"> Fonds de solidarité </option>
			<option = value="superficie"> Superficie </option>
			<option = value="densite"> Densité </option>
			<option = value="dcrtp"> DCRTP </option>
			
		</select>
	</div>
	
	
	<!--<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Indicateurs financiers :</span>
		<select name="d2" size =5 multiple="multiple"> 
			<option = value="DMTO"> DMTO </option>
			<option = value="CVAE"> CVAE </option>
			
		</select>
	</div>
	
	<div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
		<span style="font-weight: bold;">Indicateurs financiers :</span>
		<select name="d3" size =5 multiple="multiple"> 
			<option = value="DMTO"> DMTO </option>
			<option = value="CVAE"> CVAE </option>
			
		</select>
	</div>-->
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
	<div class="button">
		<span onclick=showMoyenneOnGraph()>
			Afficher moyenne
		</span>
	</div>
	<div class="button">
		<span onclick=showEcartTypeOnGraph()>
			Afficher écart type
		</span>
	</div>
</div>

<a href="par_annee.php"><button style="width:85px;
	height:85px;
	background:#fafafa;
	box-shadow:2px 2px 8px #aaa;
	font:bold 13px Arial;
	border-radius:50%;
	color:#555;">Mise à zéro</button></a>


<div style="display: flex; justify-content: space-between;">	
	<div>
		<table id=trier>
			<tr class=title>
				<table border=1 id="dataTable">
				</table>
				<table border=1 id="calculTable">
				</table>
			</tr>
		</table>
	</div>
	

	<div draggable="true" ondragstart="event.dataTransfer.setData('text/plain', 'Ce texte peut être glissé')">
	<div style="width: 100%; height: 500px; margin: 20px 50px;">
		<canvas id="myChart" ></canvas>
	</div>
</div>
</div>


</header>	

<div draggable="true" ondragstart="event.dataTransfer.setData('text/plain', 'Ce texte peut être glissé')">


<img 
     src="juge.jpg"
     alt="Grapefruit slice atop a pile of other slices">

</div>


<div id="drop-area">
  <form class="my-form">
    <p>Upload multiple files with the file dialog or by dragging and dropping images onto the dashed region</p>
    <input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)">
    <label class="button" for="fileElem">Select some files</label>
  </form>
  <progress id="progress-bar" max=100 value=0></progress>
  <div id="gallery" /></div>
</div>
<div class="note"><strong>Note: I've Removed the ability to actually upload files (it will error out silently since there is no error handler, so it'll still appear to work) because you guys were constantly filling up my Cloudinary account. Please <a href="https://cloudinary.com/invites/lpov9zyyucivvxsnalc5/j6iiupngdmwwwqspjtml">create your own account</a> and replace the "joezim007" and "ujpu6gyk" bits in the JavaScript with your own account's information.</strong></div>
<!-- partial -->
 



<style>

a {
  color: #369;
}
.note {
  width: 500px;
  margin: 50px auto;
  font-size: 1.1em;
  color: #333;
  text-align: justify;
}
#drop-area {
  border: 2px dashed #ccc;
  border-radius: 20px;
  width: 780px;
  margin: 50px auto;
  padding: 20px;
}
#drop-area.highlight {
  border-color: purple;
}
p {
  margin-top: 0;
}
.my-form {
  margin-bottom: 10px;
}
#gallery {
  margin-top: 10px;
}
#gallery img {
  width: 150px;
  margin-bottom: 10px;
  margin-right: 10px;
  vertical-align: middle;
}
.button {
  display: inline-block;
  padding: 10px;
  background: #ccc;
  cursor: pointer;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.button:hover {
  background: #ddd;
}
#fileElem {
  display: none;
}

</style>



</html>