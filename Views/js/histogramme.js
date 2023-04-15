// Fonction pour récupérer les données via AJAX
function getData(idEglise) {

    var xhr = new XMLHttpRequest();
    
    xhr.open('GET', 'http://localhost/E_glizy/index.php?route=stat&id='+idEglise, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var response=xhr.response.slice(5,-6)
         data = JSON.parse(response);
         console.log(data);
        //createChart(data);
      }
    };
    
    // Envoi de la requête avec les données à récupérer
    xhr.send(null);
  }
  
  // Fonction pour créer le graphique avec Chart.js
  
  function createChart(data) {
    // Préparation des données pour les entrées

    if(data.entree){
        
        var data_entree = {
            label: 'Entrée',
            data: [],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        };

        var labels_entree = [];
        data.entree.forEach(l => {
            data_entree.data.push(l.montant)
            labels_entree.push(l.date)
        });
        // for (var i = 0; i < data.entree.length; i++) {
        //     data_entree.data.push(data.entree[i].montant);
        //     labels_entree.push(data.entree[i].date);
        // }

    }
    
    
    // Préparation des données pour les sorties
    if(data.sortie){

        var data_sortie = {
            label: 'Sortie',
            data: [],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        };
        var labels_sortie = [];

        data.sortie.forEach(l=>{
            data_sortie.data.push(l.montant);
            labels_sortie.push(l.date);
        })
            
        
        // for (var i = 0; i < data.sortie.length; i++) {
        //     data_sortie.data.push(data.sortie[i].montant);
        //     labels_sortie.push(data.sortie[i].date);
        // }
    }
    var ctx = document.getElementById('myChart').getContext('2d');

    
  }
  
  // Appel de la fonction pour récupérer les données via AJAX
  var id=document.querySelector(".id").textContent
  console.log(id)