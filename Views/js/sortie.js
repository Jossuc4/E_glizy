//Variables

var str=document.querySelector("#find_motif");
var design=document.querySelector("#nom_eglise");

//---------------Listeners

//Animations
document.getElementById('lab_montant').addEventListener('click',function(e) {
    document.getElementById('montant_in').focus();
})
document.getElementById('btn_non').addEventListener('click',function(event) {
    document.querySelector("#annulation").style.animation="monte .2s forwards";       
})

document.querySelector('.reset_so_btn').addEventListener('click',function(e){
    document.querySelector('.modif_so').style.animation='monte .5s forwards';
})
console.log(document.querySelectorAll('#modifier_so_btn'));
document.querySelectorAll('#modifier_so_btn').forEach((element,index) => {
    element.addEventListener('click',function(e){
        document.getElementById('modif_id').value=index;
        document.querySelector('.modif_so').style.animation='desc 1s forwards';
    })
});


//Recherche
document.getElementById('btn_search').addEventListener('click',function(e) {
    document.querySelector('.search_box').style.animation="descSearch .7s forwards";    
    document.querySelector("#find_motif").focus()
})
document.querySelector('.quit').addEventListener('click',function(e) {
    document.querySelector("#date_search_in").value=""
    document.querySelector("#find_motif").value=""
    
    if(document.querySelector("#notFoundMessage")!==null){
        document.querySelector("#notFoundMessage").style="display:none"
    }
    
    document.querySelectorAll(".motif").forEach(card=>{
        card.parentElement.style="display:block"
    })
    document.querySelector('.search_box').style.animation="monteSearch .7s forwards"; 
})


document.body.addEventListener('load',transactions());

//Functions

function create(elem){
    return document.createElement(elem)
}

var options=document.querySelector("#options")
var date=document.querySelector("#rec_date")
var motif=document.querySelector("#rec_motif")

options.addEventListener('change',function(){
    if(options.value==="date"){
        date.style="display:block"
        motif.style="display:none"
    }else{
        date.style="display:none"
        motif.style="display:block"
        document.querySelector("#find_motif").focus()
    }
})

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

function transactions(){
    var design=document.querySelector("#nom_eglise");
    console.log(design.textContent)
    const req=new XMLHttpRequest();
    var container=document.querySelector(".list_container")
    var solde=document.querySelector("#solde")

    //This url depends on what server to execute the data/Entrees.php file

    var url="http://localhost/E_glizy/index.php?route=listeSortie&design="+design.textContent;
    req.open("GET", url,true);
    req.send(null);

    req.onreadystatechange = function(){
    
        if(req.status==200 && req.readyState==4){

            data=JSON.parse(req.response)
            console.log(data)
            if(Array.isArray(data)){
                let compteur=0;
                var liste=document.createElement('ul')
                liste.setAttribute("class","entree_list")
            
                container.appendChild(liste)

                                
                data.forEach(element => {
                    solde.innerHTML=formatNumber(element.solde) + " Ar"

                    let elem=document.createElement('li')
                    liste.appendChild(elem)
                    let id=document.createElement('span')
                    id.setAttribute("id","id")
                    id.innerHTML=compteur
                    elem.appendChild(id)

                    let date=document.createElement('p')
                    date.setAttribute("class","date")
                    date.innerHTML="Le "+formatDate(element.dateSortie)
                    elem.appendChild(date)

                    let montant=document.createElement('p')
                    montant.setAttribute("class","montant")
                    montant.innerHTML="Montant: <span id=\"montant\">"+formatNumber(element.montantSortie)+" Ar</span>"
                    elem.appendChild(montant)

                    let motif=document.createElement('p')
                    motif.setAttribute("class","motif")
                    motif.innerHTML=element.motif
                    elem.appendChild(motif)

                    let buttons=document.createElement('div')
                    buttons.className="buttons"
                    elem.appendChild(buttons)

                    let modif=document.createElement('button')
                    modif.setAttribute("id","modifier_en_btn")
                    modif.innerHTML="Modifier"
                    buttons.appendChild(modif)

                    let annuler=document.createElement('button')
                    annuler.setAttribute("id","annuler_en_btn")
                    annuler.innerHTML="Annuler la sortie"
                    buttons.appendChild(annuler)
                    
                    annuler.addEventListener('click',function(event) {
                        document.querySelector("#annulation").style.animation="desc .2s forwards";
                    })

                    console.log(document.querySelectorAll('#modifier_en_btn'));
                    
                    modif.addEventListener('click',function(e){
                        var date_modif=document.getElementById("date_modif")
                        var idSortie=document.getElementById("id_sortie")
                        var montant=document.querySelector('#montant_in')
                        var motif=document.querySelector('#motif')
                        //document.getElementById('modif_id').value=index;
                        document.querySelector('.modif_so').style.animation='desc 1s forwards';

                        date_modif.value=element.dateSortie
                        idSortie.value=element.idSortie
                        montant.value=element.montantSortie
                        motif.value=element.motif
                        
                    })
                    annuler.addEventListener("click",function(e){
                        var annulation=document.querySelector("#annulation");
                        var id=document.getElementById("modif_id")
                        var montant_modif=document.getElementById("modif_montant")
                        var btn_annulation=document.getElementById("confirm_annulation")
                        btn_annulation.setAttribute("href","?route=suppr&idSortie="+element.idSortie+"&idEglise="+element.idEglise)
                        id.innerHTML=element.idSortie
                        montant_modif.innerHTML=formatNumber(element.montantSortie) + " Ar"
                        document.getElementById('btn_conf').appendChild(btn_annulation)
                        
                    })
                    compteur++;
                });

            }else{
                console.log(data.solde)
                solde.innerHTML=formatNumber(data.solde)+" Ar"
                container.innerHTML="Aucune sortie enregistrée"
            }        
        }
    }
}


//Next task : find bar 
function trouver(str){
    var motifs=document.querySelectorAll('.motif');
    var racine=document.querySelector(".list_container")
    var compteur=0
    if(str.length!==0){
        motifs.forEach(element => {
    
            if(element.textContent.toLocaleLowerCase().includes(str.toLocaleLowerCase())){
                if(document.querySelector("#notFoundMessage")!==null){
                    document.querySelector("#notFoundMessage").style="display:none"
                }
                element.parentElement.style="display:block";
                compteur++
            }else{
                element.parentElement.style="display:none"
            }
        });
        if(compteur===0){
            racine.style="display:block"
            if(document.querySelector("#notFoundMessage")!==null){
                document.querySelector("#notFoundMessage").style="display:block;margin-top:60px;margin-left:25%"
            }else{
                var error=create("h3")
                error.setAttribute("id","notFoundMessage")
                error.setAttribute("style","margin-top:60px;margin-left:25%")
                error.innerHTML="Aucune entrée ne correspond à votre recherche"
                racine.prepend(error)
            }
        }
    }else{
        motifs.forEach(l=>{
            l.parentElement.style="display:block"
        })
        if(document.querySelector("#notFoundMessage")!==null){
            document.querySelector("#notFoundMessage").style="display:none"
        }
    }
}
document.getElementById('date_search_in').addEventListener('change',function(event){

    var date=event.target.value
    var dates=document.querySelectorAll('.date')
    var racine=document.querySelector(".list_container")
    var compteur=0

    console.log(date.length)

    if(date.length!==0){
        dates.forEach(element=>{
            let formatedDate=element.textContent.slice(3)
            if(formatDate(date)===formatedDate){
                if(document.querySelector("#notFoundMessage")!==null){
                    document.querySelector("#notFoundMessage").style="display:none"
                }
                element.parentElement.style="display:block"
                compteur++
            }else{
                element.parentElement.style="display:none"
            }
        })
        if(compteur===0){
            racine.style="display:block"
            if(document.querySelector("#notFoundMessage")!==null){
                document.querySelector("#notFoundMessage").style="display:block;margin-top:60px;margin-left:25%"
            }else{
                var error=create("h3")
                error.setAttribute("id","notFoundMessage")
                error.setAttribute("style","margin-top:60px;margin-left:25%")
                error.innerHTML="Aucune entrée ne correspond à votre recherche"
                racine.prepend(error)
            }
        }
    }else{
        dates.forEach(l=>{
            l.parentElement.style="display:block"
        })
        if(document.querySelector("#notFoundMessage")!==null){
            document.querySelector("#notFoundMessage").style="display:none"
        }
    }
 
})