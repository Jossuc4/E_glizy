//functions

function create(elem){
    return document.createElement(elem)
}

function formatDate(date){
    var newDate=date.split('-')
    var taille=newDate.length
    var table=[]
    let compte=0
    for(i=taille-1;i>=0;i--){
        table[compte]=newDate[i]
        compte++
    }
    return table[0]+"/"+table[1]+"/"+table[2]
}

function formatNumber(number){
    return new Intl.NumberFormat("fr-FR").format(number)
}


//code pour renommer l'eglise
var renommer = document.getElementById('renommer')
renommer.addEventListener('click',function(event) {
    if (renommer.innerHTML=="Renommer") {

        var idEglise=document.getElementById("idEglise").value
        var input=document.getElementById('nom');
        input.readOnly=false;
        input.focus();

        renommer.innerHTML="Confirmer"
        
    }else{
   
            
        var idEglise=document.getElementById("idEglise").value
        var link=document.createElement("a")   
        var input=document.getElementById('nom');
        input.readOnly=true;

        link.innerHTML="Confirmer"
        link.setAttribute("style","text-decoration:none;color:white")
        renommer.innerHTML=""
        renommer.appendChild(link)
        link.setAttribute("href","?route=modif&idEglise="+idEglise+"&new_name="+input.value)

        link.click()
        console.log(input.value)

    }
})


//Intégration des données

document.body.addEventListener('load',integration());

function integration(){

    var nom_eglise=document.getElementById("nom").value;
    var cadre=document.querySelector(".section")
    
    //console.log(nom_eglise)
    nom_eglise=nom_eglise.split(' ').join("_")

    console.log(nom_eglise)
   
    const req=new XMLHttpRequest();
    var url="?route=info&design="+nom_eglise

    req.open("GET", url,true);
    req.send(null);

    req.onreadystatechange=function(){
        if(req.readyState<4){
            document.querySelector("#loading").setAttribute("style","display:block")
            console.log("en attente")
        }
        if(req.readyState===4 && req.status===200){
            document.querySelector("#loading").setAttribute("style","display:none")
            var data=req.response.slice(5,-6)
            data=JSON.parse(data)

            if(data.length==0){
                document.querySelector(".date").innerHTML="Aucun enregistrement pour le moment"
                console.log("Data vide")
                document.querySelector(".section").setAttribute("style","height:90vh")
            }else{
                var entree=data["entree"]
                var detailEntree=data["detailEntree"]

                if(data["detailSortie"]){
                    var detailSortie=data["detailSortie"]
                }
                if(data["sortie"]){
                    var sortie=data["sortie"]
                }
                //Entree

                
                var racine=create("div")
                racine.setAttribute("class","mv_entree")
                cadre.appendChild(racine)

                var titre=create("h3")
                titre.innerHTML="Mouvement d'entree en caisse"
                racine.appendChild(titre)

                var date=create("div")
                if( detailEntree.dateInitial === detailEntree.dateFinal){
                    date.innerHTML="Le "+detailEntree.dateInitial
                }else{
                    date.innerHTML="Entre "+detailEntree.dateInitial +" et "+detailEntree.dateFinal
                }
                racine.appendChild(date)

                var total_en=create("div")
                total_en.setAttribute("class","total_en")
                total_en.innerHTML="Total entrant: "+new Intl.NumberFormat("fr-FR").format(detailEntree.totalEntree)+" Ar"
                racine.appendChild(total_en)

                var table=create("table")
                racine.appendChild(table)

                var head=create("thead")
                table.appendChild(head)

                var tr=create("tr")
                head.appendChild(tr)

                var th_date=create("th")
                th_date.innerHTML="Date d'entrée"
                var th_motif=create("th")
                th_motif.innerHTML="Motif"
                var th_montant=create("th")
                th_montant.innerHTML="Montant"
                tr.appendChild(th_date)
                tr.appendChild(th_motif)
                tr.appendChild(th_montant)
                
                var tbody=create("tbody")
                table.appendChild(tbody)

                entree.forEach(data => {
                    let tr=create("tr")
                    let td_date=create("td")
                    let td_motif=create("td")
                    let td_montant=create("td")

                    td_date.innerHTML=formatDate(data.dateEntree)
                    td_motif.innerHTML=data.motif
                    td_montant.innerHTML=formatNumber(data.montantEntree)+" Ar"

                    tr.appendChild(td_date)
                    tr.appendChild(td_motif)
                    tr.appendChild(td_montant)

                    tbody.appendChild(tr)
                });
                //console.log(detailEntree)

                //Sortie
                if(sortie){

                    var racine=create("div")
                    racine.setAttribute("class","mv_sortie")
                    cadre.appendChild(racine)

                    var titre=create("h3")
                    titre.innerHTML="Mouvement de sortie de caisse"
                    racine.appendChild(titre)

                    var date=create("div")
                    if( detailSortie.dateSInitial === detailSortie.dateSFinal){
                        date.innerHTML="Le "+detailSortie.dateSInitial
                    }else{
                        date.innerHTML="Entre "+detailSortie.dateSInitial +" et "+detailSortie.dateSFinal
                    }
                    racine.appendChild(date)

                    var total_en=create("div")
                    total_en.setAttribute("class","total_so")
                    total_en.innerHTML="Total sortant: "+new Intl.NumberFormat("fr-FR").format(detailSortie.totalSortie) + " Ar"
                    racine.appendChild(total_en)

                    var table=create("table")
                    racine.appendChild(table)

                    var head=create("thead")
                    table.appendChild(head)

                    var tr=create("tr")
                    head.appendChild(tr)

                    var th_date=create("th")
                    th_date.innerHTML="Date de sortie"
                    var th_motif=create("th")
                    th_motif.innerHTML="Motif"
                    var th_montant=create("th")
                    th_montant.innerHTML="Montant"
                    tr.appendChild(th_date)
                    tr.appendChild(th_motif)
                    tr.appendChild(th_montant)
                    
                    var tbody=create("tbody")
                    table.appendChild(tbody)

                    sortie.forEach(data => {
                        let tr=create("tr")
                        let td_date=create("td")
                        let td_motif=create("td")
                        let td_montant=create("td")
    
                        td_date.innerHTML=formatDate(data.dateSortie)
                        td_motif.innerHTML=data.motif
                        td_montant.innerHTML=formatNumber(data.montantSortie)+" Ar"
    
                        tr.appendChild(td_date)
                        tr.appendChild(td_motif)
                        tr.appendChild(td_montant)
    
                        tbody.appendChild(tr)
                    });
                    
                }

            }

        }
    } 
}