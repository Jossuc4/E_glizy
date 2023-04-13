<?php

    if(isset($_GET['id']) && find("eglise",$_GET['id'])>0){

        $idEglise=$_GET['id'];
        $details_eglise=listAll("eglise",$idEglise)[0];

        if(isset($_POST['montantEntree']) && !empty($_POST['montantEntree']) &&isset($_POST['motif']) && !empty($_POST['motif'])){
            //var_dump($_POST);
            $montant=(int)$_POST['montantEntree'];
            $motif=$_POST['motif'];
            
            //Enregistrement de l'entrée
            $query=CONNECT->prepare("INSERT INTO entree(motif,montantEntree,idEglise,dateEntree)
            VALUES(?,?,?,CURDATE())");

            $res=$query->execute([$motif,$montant,$idEglise]);

            //Mise à jour du solde
            $update=CONNECT->prepare("UPDATE eglise SET solde=solde+? WHERE idEglise=?");
            $exec=$update->execute([$montant,$idEglise]);

        }

        //Modification d'une entrée
        if(isset($_POST['montant_modif']) && isset($_POST['motif_modif']) && isset($_POST['date_modif']) && isset($_POST['date_modif'])){

            $new_value=$_POST['montant_modif'];
            $new_motif=$_POST['motif_modif'];
            $date=$_POST['date_modif'];
            $idEntree=$_POST['idEntree'];

            $query=CONNECT->query("SELECT montantEntree FROM entree WHERE idEntree=".$idEntree);
            $prev_value=$query->fetch(PDO::FETCH_ASSOC)['montantEntree'];
            
            //Modifier l'entrée
            $modif=CONNECT->prepare("UPDATE entree SET montantEntree=?,motif=?,dateEntree=? WHERE idEntree=?");
            $exec=$modif->execute([$new_value,$new_motif,$date,$idEntree]);

            //Modifier le solde
            $modif=CONNECT->prepare("UPDATE eglise SET solde=solde - ? + ?WHERE idEglise=?");
            $exec=$modif->execute([$prev_value,$new_value,$idEglise]);

        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Views/style/entree.css">
    <title>Entree</title>
</head>

<body>
<h2 style="font-family:aharoni;text-align:center" ></h2>
    <h2 id="titre"><span id="nom_eglise" style="font-style:italic"><?=$details_eglise['design']?></span></h2>
    <div class="entree">
        <div class="modif_en">
            <form action="" method="post">
                <h2>Modification</h2>
                <div class="input-box">
                    <input type="text" id="id_entree" value="" name="idEntree" hidden>
                    <input type="number" name="montant_modif" id="montant_in" min="0" required/>
                    <label for="montant" id="lab_montant" autocomplete=false required>Montant de l'entrée</label><br>
                </div>
                <textarea name="motif_modif" id="motif"></textarea>
                <div class="input_box">
                    <label for="date_modif"><span style="color:black">Date d'enregistrement</span></label>
                    <input type="date" id="date_modif" value=""name="date_modif" max=<?=date ( 'Y-m-d ' , mktime (0 , 0, 0 , date ( "m" ) , date ( "d" ) , date ( "Y" ))) ;?>>
                </div><br>
                <input type="submit" class="modif_en_btn" value="Modifier une entrée">
                <input type="reset" class="reset_en_btn" value="Annuler">
            </form>
            
        </div>
            <div id="annulation">
                <h3>Voulez-vous annuler l'entrée n°<span id="modif_id"></span> de montant <span id="modif_montant"></span> ?</h3>
                <div class="buttons">
                    <button id="btn_conf"><a href="" id="confirm_annulation">Oui</a></button>
                    <button id="btn_non">Non</button>
                </div>
            </div> 

        <?php if(isset($_GET['id'])):?>
        
        <div class="create_en en">
            <form action="" method="post">
                <h2>Ajouter une entrée en caisse</h2>
                <h3>Solde actuel: <span id="solde"></span></h3>
                <div class="input-box">
                    <input type="number" name="montantEntree" required><br>
                    <label for="montant" id="lab_montant" autocomplete=false >Montant de l'entrée</label>
                </div>
                
                <textarea name="motif" id="motif" cols="20" rows="10" required></textarea><br>
        
                <input type="submit" value="Ajouter" class="create_en_btn"><br>
                <input type="button" id="btn_search" value="Chercher une entrée"><br>
                <button class="create_en_btn"><a href=<?="?route=details&id=".$idEglise?> style="text-decoration: none;color:white">Voir le mouvement des caisses</a></button><br><br>
                
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
                <input type="text" name="find_motif" id="find_motif" onkeyup="trouver(this.value)"/>
                <label for="find_motif" id="find_titre" autocomplete=false>Motif</label>
            </div>

            <div class="input-date-box"id="rec_date"style="display:none">
                <label for="date">Date: </label>
                <input type="date" name="date" id="date_search_in"  max=<?=date ( 'Y-m-d ' , mktime (0 , 0, 0 , date ( "m" ) , date ( "d" ) , date ( "Y" ))) ;?>>
            </div>
            <div id="err"style="display:none">
                Vous devez choisir entre l'option par date ou par motif
            </div>
            <button class="quit">X</button>
            <div id="txtHint">

            </div>
        </div>
        
        <div class="list_en en">
            <h2>liste des entrées en caisse</h2>
            <div class="list_container"></div>
        </div>
    </div>
    <script src="Views/js/entree.js?t=<?= time(); ?>"></script>
 
    <?php else :?>
    
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
<?php endif; ?>
</body>
</html>