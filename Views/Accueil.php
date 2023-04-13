<?php

    $connection=new PDO("mysql:dbname=eglise;port=3308","root","");

    if(!empty($_POST['add'])){
        $design=$_POST['add'];

        if(verifyName($design)){
            //Ajout d'une nouvelle eglise dans la table Eglise
            $query=$connection->prepare("INSERT INTO eglise(idEglise,design) VALUES(?,?)");
            $res=$query->execute([createID(count(listAll("eglise"))),$design]);

            if($res){
                $couleur="green";
                $message="Eglise enregistrée";
            }else{
                $couleur="red";
                $message="Enregistrement non réussie :(";
            }
        }else{
            $couleur="red";
            $message="Cette église est déjà parmi la liste";
        }
        

        
    }
    //echo json_encode($listeEglise);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Views/style/eglise.css">
    <title>Document</title>
</head>
<body>
    <div class="create_eg eg">
        <form action="" method="post">
            <h2> Ajouter une eglise </h2>
            <div class="input-box">
                <input type="text" name="add" id="design_in" required>
                <label for="design" autocomplete=false>Designation de l'eglise</label>
            </div>
            <input type="submit" class="create_eg_btn" value="Ajouter un eglise">
        </form>
        <div id="message"><span style="color:<?php if(isset($couleur)) echo $couleur;?>"><?php if(isset($message)) echo $message; ?></span></div>
    </div>
    
    <div class="list_eg eg">
        <h2>liste des eglises</h2>
        <div class="list_container">
            <ul id="eglise_list">
            </ul>
        </div>
    </div>
    
    <script src="Views/js/eglise.js?t=<?= time(); ?>"></script>
</body>
</html>