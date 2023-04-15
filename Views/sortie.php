<?php

    if(isset($_GET['id']) && find("eglise",$_GET['id'])>0){

        $idEglise=$_GET['id'];
        $details_eglise=listAll("eglise",$idEglise)[0];
        $query=CONNECT->prepare("SELECT solde FROM eglise WHERE idEglise=?");
        $exec=$query->execute([$idEglise]);
        $solde=$query->fetch(PDO::FETCH_ASSOC)['solde'];

        //Ajout d'une nouvelle sortie

        if(isset($_POST['montantSortie']) && !empty($_POST['montantSortie']) &&isset($_POST['motif']) && !empty($_POST['motif'])){
            //var_dump($_POST);
            $montant=(int)$_POST['montantSortie'];
            $motif=$_POST['motif'];

            //Vérification du solde seuil du solde
            $diff=$solde - $montant;

            if($diff >= 10000){

                //Enregistrement de la sortie 
                $query=CONNECT->prepare("INSERT INTO sortie(motif,montantSortie,idEglise,dateSortie)
                VALUES(?,?,?,CURDATE())");

                $res=$query->execute([$motif,$montant,$idEglise]);

                 //Insertion dans la base de transactions
                /* $id=CONNECT->query("SELECT idSortie FROM entree WHERE dateEntree=(SELECT MAX(dateEntree) from entree)")->fetch(PDO::FETCH_ASSOC)['idEntree'];*/
                $id=CONNECT->query("SELECT MAX(idSortie) idSortie FROM sortie")->fetch(PDO::FETCH_ASSOC)['idSortie']++;
                $q=CONNECT->query("INSERT INTO transactions(idEglise,id,typeT,dateT,montant) VALUES('$idEglise',$id,'OUT',CURDATE(),$montant)");

                //Mise à jour du solde
                $update=CONNECT->prepare("UPDATE eglise SET solde=solde-? WHERE idEglise=?");
                $exec=$update->execute([$montant,$idEglise]);

            }else{
                //Réfuser la sortie
                $alert=true;
            }
            
        }

        //Modifier une sortie
        if(isset($_POST['montant']) && isset($_POST['motif']) && isset($_POST['date_modif'])){
            $idSortie=$_POST['idSortie'];

            //Solde actuelle 
            $query=CONNECT->prepare("SELECT solde FROM eglise WHERE idEglise=?");
            $exec=$query->execute([$idEglise]);
            $solde=$query->fetch(PDO::FETCH_ASSOC)['solde'];

            //Montant de la sortie à modifier
            $query=CONNECT->query("SELECT montantSortie FROM sortie WHERE idSortie='$idSortie'");
            $prev_value=$query->fetch(PDO::FETCH_ASSOC)['montantSortie'];

            $new_value=$_POST['montant'];
            $motif=$_POST['motif'];

            //Vérification du solde seuil du solde
            $diff= $solde + $prev_value - $new_value;

            if($diff >= 10000){
                
                $new_motif=$_POST['motif'];
                $date=$_POST['date_modif'];
                 
                //Modifier l'entrée
                $modif=CONNECT->prepare("UPDATE sortie SET montantSortie=?,motif=?,dateSortie=? WHERE idSortie=?");
                $exec=$modif->execute([$new_value,$new_motif,$date,$idSortie]);

                //Modifier le solde
                $modif=CONNECT->prepare("UPDATE eglise SET solde=solde + ? - ? WHERE idEglise=?");
                $exec=$modif->execute([$prev_value,$new_value,$idEglise]);
            }else{
                //Réfuser la sortie
                $alert=true;
                
            }
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Views/style/sortie.css">
    <title>Sortie</title>
</head>
<?php if(isset($_GET['id'])):?>
    <body>
        <h2 id="titre"><span id="nom_eglise"><?=$details_eglise['design']?></span></h2>
        <div class="entree">
            <div class="modif_so">
                <form action="" method="post">
                    <h2>Modifier une sortie en caisse</h2>
                    <div class="input-box">
                    <input type="text" id="id_sortie" value="" name="idSortie" hidden>
                        <input type="number" name="montant" id="montant_in" min="0" required/>
                        <label for="montant" id="lab_montant" autocomplete=false>Montant de la sortie</label>
                    </div>
                    <textarea name="motif" id="motif"></textarea>
                    <div class="input_box">
                        <label for="date_modif"><span style="color:black">Date d'enregistrement</span></label>
                        <input type="date" id="date_modif" value=""name="date_modif" max=<?=date ( 'Y-m-d ' , mktime (0 , 0, 0 , date ( "m" ) , date ( "d" ) , date ( "Y" ))) ;?>>
                    </div><br>
                    <input type="submit" class="modif_so_btn" value="Modifier une sortie">        
                    <input type="reset" class="reset_so_btn" value="Annuler">
                </form>
            </div>
            <div id="annulation">
                <h3>Voulez-vous annuler la sortie n°<span id="modif_id"></span> de montant <span id="modif_montant"></span> ?</h3>
                <div class="buttons">
                    <button id="btn_conf"><a href="" id="confirm_annulation">Oui</a></button>
                    <button id="btn_non">Non</button>
                </div>
            </div>

            <?php if(isset($alert)):?>
                <div id="seuil">
                    <h3>Le solde restant ne doit pas être inférieure à 10 000 Ar <br> Vous-en êtes à <?=$diff?> Ar</h3>
                    <div class="buttons">
                        <button id="ok">Ok</button>
                    </div>
                </div>
                <script>
                    document.querySelector("#seuil").style.animation="desc .2s forwards";
                        document.getElementById('ok').addEventListener('click',function(event) {
                        document.querySelector("#annulation").style.animation="monte .2s forwards"; 
                        document.querySelector("#seuil").style="top:-100%";     
                    })
                </script>
            <?php endif;?>

            <div class="create_so so">
                <form action="" method="post">
                    <h2>Ajouter une sortie en caisse</h2>
                    <h3>Solde actuel: <span id="solde"></span></h3>
                    <div class="input-box">
                        <input type="number" name="montantSortie" id="montant_in" min="0" required/>
                        <label for="montant" id="lab_montant" autocomplete=false>Montant de la sortie</label>
                    </div>
                    <textarea name="motif" id="motif" cols="20" rows="10"></textarea>
                    <input type="submit" class="create_so_btn" value="Ajouter un sortie"><br>
                    <input type="button" id="btn_search" value="Chercher une sortie"><br>
                    <button class="create_so_btn"><a href=<?="?route=details&id=".$idEglise?> style="text-decoration: none;color:white">Voir le mouvement des caisses</a></button>
                </form>
            </div>

            <div id="recherche" class="search_box">
                <label class="select_lab" for="options">Option de recherche : 
                    <select name="options" id="options" default="default">
                        <option value="motif">Par motif</option>
                        <option value="date">Par date</option>
                    </select>
                </label>
                
                <div class="input-box" id="rec_motif">
                    <input type="text" name="find_motif" id="find_motif" onkeyup="trouver(this.value)" />
                    <label for="find_motif" id="find_titre" autocomplete=false>Motif</label>
                </div>

                <div class="input-date-box"id="rec_date" style="display:none">
                    <label for="date">Date: </label>
                    <input type="date" name="date" id="date_search_in"  max=<?=date ( 'Y-m-d ' , mktime (0 , 0, 0 , date ( "m" ) , date ( "d" ) , date ( "Y" ))) ;?>>
                </div>
                <div id="err"style="display:none">
                    Vous devez choisir entre l'option par date ou par motif
                </div>
                <button class="quit">X</button>
            </div>
            
            <div class="list_so so">
                <h2>liste des sortie en caisse</h2>
                <div class="list_container"> 
                </div>
            </div>
        </div>
        <script src="Views/js/sortie.js?t=<?= time(); ?>"></script>
<?php else:?>
    <p id="titre">Veuillez spécifier l'identifiant de l'Eglise</p>

    <div class="entree">
        <form action="" method="get">
            <input type="text" name="route" value="entree" hidden>
            <div class="input-box">
                <input type="text" name="id"><br>
                <label for="id"autocomplete=false>Identifiant de l'église</label>
            </div>
            
            <input type="submit" value="Envoyer"class="create_en_btn">
        </form>
    </div>
    <style>
        #titre{
            font-weight: bold;
            margin-top: 100px;
            font-size: 20px;
        }
        .entree{
            width: 60%;
            height: 80%;
            background-color: white;
            margin-top: 1%;
            margin-left: 20%;
            border-radius: 5%;
        }
    </style>
<?php endif?>
</body>
</html>