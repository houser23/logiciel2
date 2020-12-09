<html>


<select type='number' id="item1" name="item 1">
      <option>Type de collectivité</option>
      <option value="1">Communes</option>
      <option value="2">EPCI</option>
      <option value="3">Département</option>
      <option value="3">Région</option>
    </select>


<select type='number' id="item2" name="item 2">
      <option>Type d'approche</option>
      <option value="1">Approche par catégorie</option>
      <option value="2">Approche intégrée</option>
 </select>


<select type='number' id="item3" name="item 3">
      
      <option>Année</option>
      <option value="1">Chronologique</option>
      <option value="2">Travail sur une année</option>
    
     
    </select>

<select type='number' id="item4" name="item 4">
      
      <option>Choix de l'échelle</option>
      <option value="1">Echelle similaire</option>
      <option value="2">Echelle différente</option>
    
     
    </select>


    <button onclick="message()">Envoi</button>

  </br>


 

</br>


    <SCRIPT language = javascript>

function message() {

var s = document.getElementById('item1');
var item1 = s.options[s.selectedIndex].value;

var t = document.getElementById('item2');
var item2 = t.options[t.selectedIndex].value;

var u = document.getElementById('item3');
var item3 = u.options[u.selectedIndex].value;

var v = document.getElementById('item4');
var item4 = v.options[v.selectedIndex].value;



 



if (item1 == 1) {
     document.getElementById('formulaire').style.display = "block";
}
else if (item1 == 2) {
     document.getElementById('test1').style.display = "block";
}





else if (item1 == 3 && item2 == 1 && item3 == 2 && item4 == 2) {
 document.getElementById('donchr1bisplusieursechelle').style.display = "block";
                                          }


else if (item1 == 3 && item2 == 1 && item3 == 2 && item4 == 1) {
 document.getElementById('donchr1bis').style.display = "block";
                                          }

else if (item1 == 3 && item2 == 1 && item3 == 1 && item4 == 2) {
 window.location = 'par_annee.php';
                                          }

else if (item1 == 3 && item2 == 1 && item3 == 1 && item4 == 1) {
 window.location = 'par_annee.php';
                                          }


else if (item1 == 3 && item2 == 2 && item3 == 1 && item4 == 1) {
 window.location = 'couple.php';
                                          }

else if (item1 == 3 && item2 == 2 && item3 == 1 && item4 == 2) {
 window.location = 'couple.php';
                                          }



else if (item2 == 1) {
     document.getElementById('formulaire').style.display = "block";
}
else if (item2 == 2) {
     document.getElementById('test1').style.display = "block";
}



else if (item3 == 1) {
     document.getElementById('').style.display = "block";
}
else if (item3 == 2) {
     document.getElementById('').style.display = "block";
}





}




</SCRIPT>





        <style>

.donchr1bis{

  display: none;
}

.departement{
    display : none;
}

.donchr1bisplusieursechelle{

    display: none

}


.echelle{
    display : none;
}

</style>

<div id="donchr1bis" class="donchr1bis">

 
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
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
  background: url(1.jpg) no-repeat center fixed; 
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
  *   - Dec : 0 = Croissant ; 1 = Décroissant
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
    for (c = 0 ; c < oTable.rows[0].cells.length ; c++) {
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
      for ( r=1 ; r<oTable.rows.length ; r++) {
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
    for (r = 0 ; r < oTable.rows.length - 1; r++) {     //De toutes les rangées 
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
    var nom = $('select[name="nom"]').val()[0]; 

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

    // Calcul des différentes moyennes
    $('#calculTable tr').remove()
    let moyenneHTML = '<tr id=Moyenne style="background-color:lightgray;"><td style="font-weight: bold">Moyenne</td>';
    let ecartTypeHTML = '<tr id=EcartType style="background-color:lightgray;"><td style="font-weight: bold">Ecart-type</td>'

    Object.keys(data[0]).forEach(function(key) {
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
    var strate = $('select[name="strate"]').val()[0]; 
    $.post(
      './ajax.php',
      {
        action: "update_data",
        strate: strate,
        d1: d1,
        d2: d2,
        d3: d3,
        an: an,
        etape: etape,
      }, function(data) {

        if (data === "[]") {
          alert("Rien à afficher")
          return ;
        }
        // Permet d'utiliser les données récupérées
        results = $.parseJSON(data);
        
        results = convertResultingData(results)
        console.log("results", results);
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

    // S'il n'y a pas Potentiel_fiscal, DMTO ou CVAE, il n'y a rien à convertir
    if (columnNames.indexOf("cvae") < 0 && columnNames.indexOf("ecretement") < 0 && columnNames.indexOf("potentiel_fiscal") < 0 && columnNames.indexOf("dp_personnels") < 0 && columnNames.indexOf("fondssolidarite_versement") < 0 && columnNames.indexOf("dp_apa_etablissement") < 0 && columnNames.indexOf("dp_apa_domicile") < 0 && columnNames.indexOf("Dp_apa") < 0 && columnNames.indexOf("DepTotales") < 0 && columnNames.indexOf("base_foncier") < 0 && columnNames.indexOf("fondssolidarite_prelevement") < 0 && columnNames.indexOf("dp_apa") < 0 && columnNames.indexOf("DMTO") < 0 && columnNames.indexOf("CVAE") < 0 && columnNames.indexOf("dp_investissement") < 0 && columnNames.indexOf("cnsa_apa") < 0 && columnNames.indexOf("cnsa_mdph") < 0 && columnNames.indexOf("cnsa_pch") < 0 && columnNames.indexOf("dp_fonctionnement") < 0 && columnNames.indexOf("epargne_gestion") < 0 && columnNames.indexOf("dp_pch") < 0 && columnNames.indexOf("DGF") < 0 && columnNames.indexOf("forfaitaire") < 0 && columnNames.indexOf("compensation")< 0 && columnNames.indexOf("dpu")< 0 && columnNames.indexOf("dfm")< 0 && columnNames.indexOf("epargne_brute")< 0 && columnNames.indexOf("epargne_nette") < 0 && columnNames.indexOf("interets_financiers") < 0 && columnNames.indexOf("remboursement_capital") < 0 && columnNames.indexOf("endettement") < 0 && columnNames.indexOf("VFDMTO") < 0 && columnNames.indexOf("RSA") < 0 && columnNames.indexOf("racsansmecanisme") < 0 && columnNames.indexOf("dp_apa") < 0 && columnNames.indexOf("soldefondssolidarite") < 0 && columnNames.indexOf("rac") < 0 && columnNames.indexOf("ractousmecanisme") < 0 && columnNames.indexOf("pcvae") < 0 && columnNames.indexOf("vcvae") < 0) {
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
      if (row.rac)
        results[index].rac = Math.round(results[index].rac / results[index].population * 100) / 100;
      if (row.potentiel_fiscal)
        results[index].potentiel_fiscal = Math.round(results[index].potentiel_fiscal / results[index].population * 100) / 100;
      if (row.DMTO)
        results[index].DMTO = Math.round(results[index].DMTO / results[index].population * 100 ) / 100 ;
      if (row.CVAE)
        results[index].CVAE = Math.round(results[index].CVAE / results[index].population * 100 ) / 100 ;
      if (row.dp_investissement)
        results[index].dp_investissement = Math.round(results[index].dp_investissement / results[index].population * 100 ) / 100 ;
      if (row.cnsa_apa)
        results[index].cnsa_apa = Math.round(results[index].cnsa_apa / results[index].population * 100 ) / 100 ;
      if (row.cnsa_pch)
        results[index].cnsa_pch = Math.round(results[index].cnsa_pch / results[index].population * 100 ) / 100 ;  
      if (row.cnsa_mdph)
        results[index].cnsa_mdph = Math.round(results[index].cnsa_mdph / results[index].population * 100 ) / 100 ;
      if (row.dp_fonctionnement)
        results[index].dp_fonctionnement = Math.round(results[index].dp_fonctionnement / results[index].population * 100 ) / 100 ;
      if (row.epargne_gestion)
        results[index].epargne_gestion = Math.round(results[index].epargne_gestion / results[index].population * 100 ) / 100 ;
      if (row.dp_pch)
        results[index].dp_pch = Math.round(results[index].dp_pch / results[index].population * 100 ) / 100 ;
      if (row.DGF)
        results[index].DGF = Math.round(results[index].DGF / results[index].population * 100 ) / 100 ;
      if (row.forfaitaire)
        results[index].forfaitaire = Math.round(results[index].forfaitaire / results[index].population * 100 ) / 100 ;
      if (row.compensation)
        results[index].compensation = Math.round(results[index].compensation / results[index].population * 100 ) / 100 ;
      if (row.dpu)
        results[index].dpu = Math.round(results[index].dpu / results[index].population * 100 ) / 100 ;
      if (row.dfm)
        results[index].dfm = Math.round(results[index].dfm / results[index].population * 100 ) / 100 ;
      if (row.epargne_brute)
        results[index].epargne_brute = Math.round(results[index].epargne_brute / results[index].population * 100 ) / 100 ;
      if (row.epargne_nette)
        results[index].epargne_nette = Math.round(results[index].epargne_nette / results[index].population * 100 ) / 100 ;
      if (row.interets_financiers)
        results[index].interets_financiers = Math.round(results[index].interets_financiers / results[index].population * 100 ) / 100 ;
      if (row.remboursement_capital)
        results[index].remboursement_capital = Math.round(results[index].remboursement_capital / results[index].population * 100 ) / 100 ;
      if (row.endettement)
        results[index].endettement = Math.round(results[index].endettement / results[index].population * 100 ) / 100 ;
      if (row.VFDMTO)
        results[index].VFDMTO = Math.round(results[index].VFDMTO / results[index].population * 100 ) / 100 ;
      if (row.RSA)
        results[index].RSA = Math.round(results[index].RSA / results[index].population * 100 ) / 100 ;
      if (row.desendettement)
        results[index].desendettement = Math.round(results[index].desendettement / results[index].population * 100 ) / 100 ;
      if (row.fondssolidarite_versement)
        results[index].fondssolidarite_versement = Math.round(results[index].fondssolidarite_versement / results[index].population * 100 ) / 100 ;
      if (row.fondssolidarite_prelevement)
        results[index].fondssolidarite_prelevement = Math.round(results[index].fondssolidarite_prelevement / results[index].population * 100 ) / 100 ;
      if (row.base_foncier)
        results[index].base_foncier = Math.round(results[index].base_foncier / results[index].population * 100 ) / 100 ;
      if (row.DepTotales)
        results[index].DepTotales = Math.round(results[index].DepTotales / results[index].population * 100 ) / 100 ;
      if (row.Dp_apa)
        results[index].Dp_apa = Math.round(results[index].Dp_apa / results[index].population * 100 ) / 100 ;
      if (row.dp_apa_domicile)
        results[index].dp_apa_domicile = Math.round(results[index].dp_apa_domicile / results[index].population * 100 ) / 100 ;
      if (row.dp_apa_etablissement)
        results[index].dp_apa_etablissement = Math.round(results[index].dp_apa_etablissement / results[index].population * 100 ) / 100 ;
      if (row.dp_personnels)
        results[index].dp_personnels = Math.round(results[index].dp_personnels / results[index].population * 100 ) / 100 ;
      if (row.racsansmecanisme)
        results[index].racsansmecanisme = Math.round(results[index].racsansmecanisme / results[index].population * 100 ) / 100 ;
      if (row.dp_apa)
        results[index].dp_apa = Math.round(results[index].dp_apa / results[index].population * 100 ) / 100 ;
      if (row.soldefondssolidarite)
        results[index].soldefondssolidarite = Math.round(results[index].soldefondssolidarite / results[index].population * 100 ) / 100 ;
      if (row.ecretement)
        results[index].ecretement = Math.round(results[index].ecretement / results[index].population * 100 ) / 100 ;
      if (row.cvae)
        results[index].cvae = Math.round(results[index].cvae / results[index].population * 100 ) / 100 ;
      if (row.ractousmecanisme)
        results[index].ractousmecanisme = Math.round(results[index].ractousmecanisme / results[index].population * 100 ) / 100 ;
      if (row.pcvae)
        results[index].pcvae = Math.round(results[index].pcvae / results[index].population * 100 ) / 100 ;
      if (row.vcvae)
        results[index].vcvae = Math.round(results[index].vcvae / results[index].population * 100 ) / 100 ;

      
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
  <div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center; ">
    <span style="font-weight: bold;">Votre nom de département :</span>
    <select name="nom" size =5 multiple="multiple"> 
        <?php
        $strate_1 = 'population' > 500000 AND 'population' < 1000000 ;
        $strate_2 = 'population' > 1000000 ;
        try
        {
          $bdd = new PDO('mysql:host=localhost;dbname=houser', 'root', '');
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        $reponse = $bdd->query('SELECT nom, population FROM `departement` ');

        while ($donnees = $reponse->fetch())
        {
          echo '<option id='.$donnees['population'].'>'.$donnees['nom']."\n"; 
        }
      ?>
    </select>
  </div>



  <div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center; ">
    <span style="font-weight: bold;">Année</span>
    <select name="an" size =5 multiple="multiple">
      <option = value="2013"> 2013 </option>
      <option = value="2014"> 2014 </option>
      <option = value="2015"> 2015 </option>
      <option = value="2016"> 2016 </option>
      <option = value="2017"> 2017 </option>
      <option = value="2018"> 2018 </option>
      <option = value="2019"> 2019 </option>
      
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
    <span style="font-weight: bold;">Votre strate démographique : </span>
    <select name="strate" size =5 multiple="multiple">
      <option = value="strate_1"> De 0 à 250 000 habitants </option>  
      <option = value="strate_2"> De 250 000 à 500 000 habitants </option>
      <option = value="strate_3"> De 500 000 à 1 000 000 habitants </option>
      <option = value="strate_4"> Au-delà de 1 000 000 habitants </option>
      <option = value="strate_5"> Tous les départements </option>
    </select>
  </div>
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Ressources </span>
    <select name="d1" size =5 multiple="multiple"> 
      <option = value="DMTO"> DMTO </option>
      <option = value="CVAE"> CVAE </option>
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
      <option = value="vcvae"> Versement au CVAE </option>      
      <option = value="fondssolidarite_vers"> Fonds de solidarité </option>
      <option = value="dgd"> DGD </option>
      <option = value="fngir"> FNGIR </option>
      <option = value="rac"> Reste à charge sans mécanisme </option>
      <option = value="ecartrac"> Ecart entre RAC avec et sans mécanismes </option>
      
    </select>
  </div>
  
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Dépenses</span>
    <select name="d2" size =5 multiple="multiple"> 
      <option = value="DepTotales"> Dépenses totales </option>
      <option = value="dp_investissement"> Dépenses investissement </option>
      <option = value="dp_fonctionnement"> Dépenses de fonctionnement </option>
      <option = value="dp_sociale_totale"> Dépenses sociales totales </option>
      <option = value="dp_apa"> Dépenses APA </option>
      <option = value="dp_pch"> Dépenses PCH </option>
      <option = value="RSA"> Dépenses RSA </option>
      <option = value="PFDMTO"> Prélèvement au FPDMTO </option>
      <option = value="pcvae"> Prélèvement au FCVAE </option>
      <option = value="SDIS_FONCT"> SDIS FONCTIONNEMENT </option>
      <option = value="dp_personnels"> Dépenses de personnel </option>
      <option = value="fondssolidarite_prelevement"> Prélèvement fonds de solidarité </option>
      <option = value="dp_apa_domicile"> APA à domicile </option>
      <option = value="dp_apa_etablissement"> APA en établissement </option>
      <option = value="ecretement"> Ecrêtement part forfaitaire </option>
      <option = value="potentiel_financier"> Potentiel financier </option>
      <option = value="ractousmecanisme"> RAC tous mécanisme </option>
      
    </select>
  </div>
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Indicateurs financiers </span>
    <select name="d3" size =5 multiple="multiple"> 
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
      <option = value="75ans"> Personne de + 75 ans </option>
      <option = value="soldefondssolidarite"> Solde fonds de solidarité </option>

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
      <option value="pie">Camembert</option>
      <option value="radar">Radar</option>
      <option value="polarArea">Zone polaire</option>
      <option value="bubble">Bulles</option>      
    </select>
  </div>
  
</div>

  

<style>
  .button37 {
  border:none;
  padding:6px 0 6px 0;
  border-radius:8px;
  background:blue;
  font:bold 13px Arial;
  color:#fff;
}
  
  </style>
  
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

<a href="index.php"><button style="width:85px;
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
  
  <div style="width: 100%; height: 300px; margin: 20px 50px;">
    <canvas id="myChart" ></canvas>
  </div>
</div>
  
</header> 
</html>


</div>



<div id="donchr1bisplusieursechelle" class="donchr1bisplusieursechelle">


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
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
  *   - Dec : 0 = Croissant ; 1 = Décroissant
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
    for (c = 0 ; c < oTable.rows[0].cells.length ; c++) {
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
      for ( r=1 ; r<oTable.rows.length ; r++) {
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
    for (r = 0 ; r < oTable.rows.length - 1; r++) {     //De toutes les rangées 
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

    if (displayMoyenneOnGraph) {
      let moyennes = $('#Moyenne td');
      console.log("moy", moyennes);
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
          yAxisID: 'y-axis-'+i,
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
          yAxisID: 'y-axis-'+i,
          pointRadius: 0
        });
      }
    }

    console.log(dataSets);

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
    var nom = $('select[name="nom"]').val()[0]; 

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

    // Calcul des différentes moyennes
    $('#calculTable tr').remove()
    let moyenneHTML = '<tr id=Moyenne style="background-color:lightgray;"><td style="font-weight: bold">Moyenne</td>';
    let ecartTypeHTML = '<tr id=EcartType style="background-color:lightgray;"><td style="font-weight: bold">Ecart-type</td>'

    Object.keys(data[0]).forEach(function(key) {
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
    var strate = $('select[name="strate"]').val()[0]; 
    $.post(
      './ajax.php',
      {
        action: "update_data",
        strate: strate,
        d1: d1,
        d2: d2,
        d3: d3,
        an: an,
        etape: etape,
      }, function(data) {

        if (data === "[]") {
          alert("Rien à afficher")
          return ;
        }
        // Permet d'utiliser les données récupérées
        results = $.parseJSON(data);
        
        results = convertResultingData(results)
        console.log("results", results);
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

    // S'il n'y a pas Potentiel_fiscal, DMTO ou CVAE, il n'y a rien à convertir
    if (columnNames.indexOf("ecartrac") < 0 && columnNames.indexOf("potentiel_fiscal") < 0 && columnNames.indexOf("dmto") < 0 && columnNames.indexOf("dp_personnels") < 0 && columnNames.indexOf("fondssolidarite_versement") < 0 && columnNames.indexOf("dp_apa_etablissement") < 0 && columnNames.indexOf("dp_apa_domicile") < 0 && columnNames.indexOf("Dp_apa") < 0 && columnNames.indexOf("DepTotales") < 0 && columnNames.indexOf("base_foncier") < 0 && columnNames.indexOf("fondssolidarite_prelevement") < 0 && columnNames.indexOf("dp_apa") < 0 && columnNames.indexOf("dmto") < 0 && columnNames.indexOf("CVAE") < 0 && columnNames.indexOf("dp_investissement") < 0 && columnNames.indexOf("cnsa_apa") < 0 && columnNames.indexOf("cnsa_mdph") < 0 && columnNames.indexOf("cnsa_pch") < 0 && columnNames.indexOf("dp_fonctionnement") < 0 && columnNames.indexOf("epargne_gestion") < 0 && columnNames.indexOf("dp_pch") < 0 && columnNames.indexOf("DGF") < 0 && columnNames.indexOf("forfaitaire") < 0 && columnNames.indexOf("compensation")< 0 && columnNames.indexOf("dpu")< 0 && columnNames.indexOf("dfm")< 0 && columnNames.indexOf("epargne_brute")< 0 && columnNames.indexOf("epargne_nette") < 0 && columnNames.indexOf("interets_financiers") < 0 && columnNames.indexOf("remboursement_capital") < 0 && columnNames.indexOf("endettement") < 0 && columnNames.indexOf("VFDMTO") < 0 && columnNames.indexOf("RSA") < 0 && columnNames.indexOf("racsansmecanisme") < 0 && columnNames.indexOf("dp_apa") < 0 && columnNames.indexOf("soldefondssolidarite") < 0 && columnNames.indexOf("racavecdispositif") < 0 && columnNames.indexOf("rac_rsa") < 0 && columnNames.indexOf("rsa") < 0 && columnNames.indexOf("dcrtp_ecart") < 0 && columnNames.indexOf("rac") < 0 && columnNames.indexOf("dcrtp") < 0 && columnNames.indexOf("ractousmecanisme") < 0 && columnNames.indexOf("ractousmecanisme") < 0 && columnNames.indexOf("cvae") < 0 && columnNames.indexOf("Pfvolume") < 0) {
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

      if (row.ractousmecanisme)
        results[index].ractousmecanisme = Math.round(results[index].ractousmecanisme / results[index].population * 100 ) / 100 ;
      if (row.racsansmecanisme)
        results[index].racsansmecanisme = Math.round(results[index].racsansmecanisme / results[index].population * 100 ) / 100 ;
      if (row.potentiel_fiscal)
        results[index].potentiel_fiscal = Math.round(results[index].potentiel_fiscal / results[index].population * 100) / 100;
      if (row.dmto)
        results[index].dmto = Math.round(results[index].dmto / results[index].population * 100) / 100;
      if (row.CVAE)
        results[index].CVAE = Math.round(results[index].CVAE / results[index].population * 100) / 100 ;
      if (row.dp_investissement)
        results[index].dp_investissement = Math.round(results[index].dp_investissement / results[index].population * 100 ) / 100 ;
      if (row.cnsa_apa)
        results[index].cnsa_apa = Math.round(results[index].cnsa_apa / results[index].population * 100 ) / 100 ;
      if (row.cnsa_pch)
        results[index].cnsa_pch = Math.round(results[index].cnsa_pch / results[index].population * 100 ) / 100 ;  
      if (row.cnsa_mdph)
        results[index].cnsa_mdph = Math.round(results[index].cnsa_mdph / results[index].population * 100 ) / 100 ;
      if (row.dp_fonctionnement)
        results[index].dp_fonctionnement = Math.round(results[index].dp_fonctionnement / results[index].population * 100 ) / 100 ;
      if (row.epargne_gestion)
        results[index].epargne_gestion = Math.round(results[index].epargne_gestion / results[index].population * 100 ) / 100 ;
      if (row.dp_pch)
        results[index].dp_pch = Math.round(results[index].dp_pch / results[index].population * 100 ) / 100 ;
      if (row.DGF)
        results[index].DGF = Math.round(results[index].DGF / results[index].population * 100 ) / 100 ;
      if (row.forfaitaire)
        results[index].forfaitaire = Math.round(results[index].forfaitaire / results[index].population * 100 ) / 100 ;
      if (row.compensation)
        results[index].compensation = Math.round(results[index].compensation / results[index].population * 100 ) / 100 ;
      if (row.dpu)
        results[index].dpu = Math.round(results[index].dpu / results[index].population * 100 ) / 100 ;
      if (row.dfm)
        results[index].dfm = Math.round(results[index].dfm / results[index].population * 100 ) / 100 ;
      if (row.epargne_brute)
        results[index].epargne_brute = Math.round(results[index].epargne_brute / results[index].population * 100 ) / 100 ;
      if (row.epargne_nette)
        results[index].epargne_nette = Math.round(results[index].epargne_nette / results[index].population * 100 ) / 100 ;
      if (row.interets_financiers)
        results[index].interets_financiers = Math.round(results[index].interets_financiers / results[index].population * 100 ) / 100 ;
      if (row.remboursement_capital)
        results[index].remboursement_capital = Math.round(results[index].remboursement_capital / results[index].population * 100 ) / 100 ;
      if (row.endettement)
        results[index].endettement = Math.round(results[index].endettement / results[index].population * 100 ) / 100 ;
      if (row.VFDMTO)
        results[index].VFDMTO = Math.round(results[index].VFDMTO / results[index].population * 100 ) / 100 ;
      if (row.RSA)
        results[index].RSA = Math.round(results[index].RSA / results[index].population * 100 ) / 100 ;
      if (row.fondssolidarite_versement)
        results[index].fondssolidarite_versement = Math.round(results[index].fondssolidarite_versement / results[index].population * 100 ) / 100 ;
      if (row.fondssolidarite_prelevement)
        results[index].fondssolidarite_prelevement = Math.round(results[index].fondssolidarite_prelevement / results[index].population * 100 ) / 100 ;
      if (row.base_foncier)
        results[index].base_foncier = Math.round(results[index].base_foncier / results[index].population * 100 ) / 100 ;
      if (row.dp_apa)
        results[index].dp_apa = Math.round(results[index].dp_apa / results[index].population * 100 ) / 100 ;
      if (row.DepTotales)
        results[index].DepTotales = Math.round(results[index].DepTotales / results[index].population * 100 ) / 100 ;
      if (row.dp_apa_domicile)
        results[index].dp_apa_domicile = Math.round(results[index].dp_apa_domicile / results[index].population * 100 ) / 100 ;
      if (row.dp_apa_etablissement)
        results[index].dp_apa_etablissement = Math.round(results[index].dp_apa_etablissement / results[index].population * 100 ) / 100 ;
      if (row.dp_personnels)
        results[index].dp_personnels = Math.round(results[index].dp_personnels / results[index].population * 100 ) / 100 ;
    if (row.racsansmecanisme)
        results[index].racsansmecanisme = Math.round(results[index].racsansmecanisme / results[index].population * 100 ) / 100 ;
      if (row.dp_apa)
        results[index].dp_apa = Math.round(results[index].dp_apa / results[index].population * 100 ) / 100 ;
      if (row.soldefondssolidarite)
        results[index].soldefondssolidarite = Math.round(results[index].soldefondssolidarite / results[index].population * 100 ) / 100 ;
      if (row.racavecdispositif)
        results[index].racavecdispositif = Math.round(results[index].racavecdispositif / results[index].population * 100 ) / 100 ;
      if (row.rac_rsa)
        results[index].rac_rsa = Math.round(results[index].rac_rsa / results[index].population * 100 ) / 100 ;
      if (row.rsa)
        results[index].rsa = Math.round(results[index].rsa / results[index].population * 100 ) / 100 ;
      if (row.dcrtp_ecart)
        results[index].dcrtp_ecart = Math.round(results[index].dcrtp_ecart / results[index].population * 100 ) / 100 ;
      if (row.dcrtp)
        results[index].dcrtp = Math.round(results[index].dcrtp / results[index].population * 100 ) / 100 ;
      if (row.rac)
        results[index].rac = Math.round(results[index].rac / results[index].population * 100 ) / 100 ;
      if (row.ecartrac)
        results[index].ecartrac = Math.round(results[index].ecartrac / results[index].population * 100 ) / 100 ;
      if (row.cvae)
        results[index].cvae = Math.round(results[index].cvae / results[index].population * 100 ) / 100 ;  
      if (row.Pfvolume)
        results[index].Pfvolume = Math.round(results[index].Pfvolume / results[index].population * 100 ) / 100 ;  


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
  <div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center; ">
    <span style="font-weight: bold;">Votre nom de département :</span>
    <select name="nom" size =5 multiple="multiple"> 
        <?php
        $strate_1 = 'population' > 500000 AND 'population' < 1000000 ;
        $strate_2 = 'population' > 1000000 ;
        try
        {
          $bdd = new PDO('mysql:host=localhost;dbname=houser', 'root', '');
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        $reponse = $bdd->query('SELECT nom, population FROM `departement` ');

        while ($donnees = $reponse->fetch())
        {
          echo '<option id='.$donnees['population'].'>'.$donnees['nom']."\n"; 
        }
      ?>
    </select>
  </div>



  <div style="display: flex; flex-direction: column; margin: 10px 15px; align-items: center; ">
    <span style="font-weight: bold;">Année</span>
    <select name="an" size =5 multiple="multiple">
      <option = value="2006"> 2006 </option>
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
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Votre strate démographique : </span>
    <select name="strate" size =5 multiple="multiple">
      <option = value="strate_1"> De 0 à 250 000 habitants </option>  
      <option = value="strate_2"> De 250 000 à 500 000 habitants </option>
      <option = value="strate_3"> De 500 000 à 1 000 000 habitants </option>
      <option = value="strate_4"> Au-delà de 1 000 000 habitants </option>
      <option = value="strate_5"> Tous les départements </option>
    </select>
  </div>
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Ressources</span>
    <select name="d1" size =5 multiple="multiple"> 
      <option = value="DMTO"> DMTO </option>
      <option = value="dcrtp"> DCRTP </option>
      <option = value="CVAE"> CVAE </option>
      <option = value="dcrtp_ecart"> dcrtp - Ecart</option>
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
      <option = value="racsansmecanisme"> Reste à charge sans mécanisme </option>
      <option = value="potentiel_financier"> Potentiel financier </option>
      <option = value="revenu"> Revenu </option>
      
      
    </select>
  </div>
  
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Dépenses</span>
    <select name="d2" size =5 multiple="multiple"> 
      <option = value="DepTotales"> Dépenses totales </option>
      <option = value="dp_investissement"> Dépenses inv </option>
      <option = value="dp_fonctionnement"> Dépenses de fonctionnement </option>
      <option = value="dp_sociale_totale"> Dépenses sociales totales </option>
      <option = value="Dp_apa"> Dépenses APA </option>
      <option = value="dp_pch"> Dépenses PCH </option>
      <option = value="rsa"> Dépenses RSA </option>
      <option = value="PFDMTO"> Prélèvement au FPDMTO </option>
      <option = value="SDIS_FONCT"> SDIS FONCTIONNEMENT </option>
      <option = value="dp_personnels"> Dépenses de personnel </option>
      <option = value="fondssolidarite_prelevement"> Prélèvement fonds de solidarité </option>
      <option = value="dp_apa_domicile"> APA à domicile </option>
      <option = value="dp_apa_etablissement"> APA en établissement </option>
      <option = value="dp_apa_etablissement"> APA en établissement </option>
      <option = value="racavecdispositif"> Reste à charge avec dispositifs </option>
      <option = value="benef_rsa"> Nombre de bénéficiaires du RSA </option>
      <option = value="ecretement"> Ecrêtement part forfaitaire </option>
      <option = value="rac"> Reste à charge </option>
      <option = value="ecartrac"> Ecart entre RAC avec et sans mécanismes </option>
        <option = value="ractousmecanisme"> RAC tous mécanisme </option>
    </select>
  </div>
  
  <div style="display: flex; flex-direction: column; margin: 5px 10px; align-items: center;">
    <span style="font-weight: bold;">Indicateurs financiers :</span>
    <select name="d3" size =5 multiple="multiple"> 
      <option = value="Pfvolume"> Potentiel financier volume </option>
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
      <option = value="desendettement"> Desendettement </option>
      <option = value="racapa"> RAC APA </option>
      <option = value="racpch"> RAC PCH </option>
      <option = value="rac_rsa"> RAC RSA </option>
      <option = value="fondssolidarite_versement"> Fonds de solidarité </option>
      <option = value="superficie"> Superficie </option>
      <option = value="densite"> Densité </option>
      <option = value="dcrtp"> DCRTP </option>
      <option = value="75ans"> Personne de + 75 ans </option>
      <option = value="soldefondssolidarite"> Solde fonds de solidarité </option>
      <option = value="benef_rsa"> Nombre de bénéficiaires du RSA </option>
      <option = value="rac"> Reste à charge </option>

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
  
  <div style="width: 100%; height: 300px; margin: 20px 50px;">
    <canvas id="myChart" ></canvas>
  </div>
</div>
  




</div>



</html>