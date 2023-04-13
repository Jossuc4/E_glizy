<?php

if(isset($_GET['id'])){
    $id=$_GET['id'];
}   

$q=CONNECT->prepare("SELECT design FROM eglise WHERE idEglise=?");
$exec=$q->execute([$id]);
$nom_eglise=$q->fetch(PDO::FETCH_ASSOC)['design'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Views/style/Detail.css">
    <title>Detail</title>
</head>
<body>
    <div class="eglise">
        <div class="img">
            <img src="Views/Images/logo1.jpeg" alt="logo_eglise">
        </div>
            <div class="nom">
                <input type="text" id="idEglise" value=<?=$id?> hidden>
                <input type="text" id="nom" value="<?=$nom_eglise?>" readonly>
                <button id="renommer">Renommer</button>
            </div>
            <!-- <input type="submit" value="Confirmer" id="confirmer"> -->
        <div class="buttons">
            <button><a href=<?="?route=entree&id=".$id?>>Gérer les entrées</a></button>
            <button><a href=<?="?route=sortie&id=".$id?>>Gérer les sorties</a></button>
            <button><a href=<?="?route=histo&id=".$id?>>Visionner l'histogramme</a></button>
            <button><a href=<?="?route=rapport&id=".$id?>>Génerer un fichier PDF des mouvements de caisse</a></button>
            <button><a href=<?="?route=eglise"?>>Revenir en arrière</a></button>
        </div>
    </div>
    <div class="section">
        <h2>Mouvements de caisses</h2>
        <div class="mv_entree">
        </div>
        <div class="mv_sortie">
        </div>
        <div id="loading">En attente...</div>
    </div>

    <script src="Views/js/Detail.js?t=<?= time(); ?>"></script>
</body>
</html>