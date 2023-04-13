<?php

if(isset($_GET['id'])){
    $id=$_GET['id'];
}   

$q=CONNECT->query("SELECT design FROM eglise WHERE idEglise='$id'");
$nom_eglise=$q->fetch(PDO::FETCH_ASSOC)['design'];

$q=CONNECT->query("SELECT solde FROM eglise WHERE idEglise='$id'");
$solde=$q->fetch(PDO::FETCH_ASSOC)['solde'];

//------------------Entree

//Liste
$q=CONNECT->query("SELECT * from entree WHERE idEglise='$id'ORDER BY dateEntree");
$entree=$q->fetchAll(PDO::FETCH_ASSOC);

if(count($entree)>0){

    //Date de début
    $q=CONNECT->query("SELECT MIN(dateEntree) debut FROM entree WHERE idEglise='$id'");
    $debutEntree=$q->fetch(PDO::FETCH_ASSOC)['debut'];

    //Date de fin
    $q=CONNECT->query("SELECT MAX(dateEntree) fin FROM entree WHERE idEglise='$id'");
    $finEntree=$q->fetch(PDO::FETCH_ASSOC)['fin'];

    //Montant entrant
    $q=CONNECT->query("SELECT SUM(montantEntree) montantEntrant FROM entree GROUP BY idEglise HAVING idEglise='$id'");
    $montantEntrant=$q->fetch(PDO::FETCH_ASSOC)['montantEntrant'];

}


//------------------Sortie

$q=CONNECT->query("SELECT * from sortie WHERE idEglise='$id' ORDER BY dateSortie");
$sortie=$q->fetchAll(PDO::FETCH_ASSOC);

if(count($sortie)>0){

    //Date de début
    $q=CONNECT->query("SELECT MIN(dateSortie) debut FROM sortie WHERE idEglise='$id'");
    $debutSortie=$q->fetch(PDO::FETCH_ASSOC)['debut'];

    //Date de fin
    $q=CONNECT->query("SELECT MAX(dateSortie) fin FROM sortie WHERE idEglise='$id'");
    $finSortie=$q->fetch(PDO::FETCH_ASSOC)['fin'];

    //Montant entrant
    $q=CONNECT->query("SELECT SUM(montantSortie) montantSortant FROM sortie GROUP BY idEglise HAVING idEglise='$id'");
    $montantSortant=$q->fetch(PDO::FETCH_ASSOC)['montantSortant'];

}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilan</title>
</head>
<body>
    <h1><?=strtoupper($nom_eglise)?></h1>


    <h3 class="solde">Solde: <span><?=Ariary($solde)?></span></h3>

    <?php if(!empty($entree)):?>

        <p class="titre">Entrées:</p>
        <?php if($debutEntree == $finEntree):?>
            <h4 class="date">Le <?= formatDate($debutEntree)?></h4>
        <?php else: ?>
            <h4 class="date">Entre <?= formatDate($debutEntree)?> et <?= formatDate($finEntree)?></h4>
        <?php endif?>
        <table>
            <thead>
                <th>Date d'entree</th>
                <th>Motif</th>
                <th>Montant</th>
            </thead>
            <tbody>
                <?php foreach($entree as $data):?>
                    <tr>
                        <td><?= formatDate($data['dateEntree'])?></td>
                        <td><?=$data['motif']?></td>
                        <td><?=Ariary($data['montantEntree'])?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <p class="montant"><span class="bilan">Total entrant:</span>   <?=Ariary($montantEntrant)?></p>
    <?php endif?>
    <?php if(!empty($sortie)):?>

<p class="titre">Sorties:</p>
<?php if($finSortie == $debutSortie):?>
    <h4 class="date">Le <?= formatDate($debutSortie)?></h4>
<?php else: ?>
    <h4 class="date">Entre <?= formatDate($debutSortie)?> et <?= formatDate($finSortie)?></h4>
<?php endif?>
<table>
    <thead>
        <th>Date de sortie</th>
        <th>Motif</th>
        <th>Montant</th>
    </thead>
    <tbody>
        <?php foreach($sortie as $data):?>
            <tr>
                <td><?= formatDate($data['dateSortie'])?></td>
                <td><?=$data['motif']?></td>
                <td><?=Ariary($data['montantSortie'])?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<p class="montant"><span class="bilan">Total sortant:</span>   <?=Ariary($montantSortant)?></p>
<?php endif?>
<?php if(empty($entree) && empty($sortie)):?>
    <h4 class="vide">Aucune transaction n'est enregistrée</h4>
<?php endif?>
<p class="footer">Aujourd'hui le <?= date("d/m/Y")?></p>
</body>
<style>
        h1{
            text-align: center;
            font-family:Arial, Helvetica, sans-serif;
            font-size: 40px;
            margin-top: 60px;
        }
        table{
            width: 80%;
            border-collapse: collapse;
            margin-left: 10%;
        }
        th,td{
            border: 1px solid;
            padding: 5px;
            text-align:center;
            font-size: 14px;
            font-family: 'Times New Roman', Times, serif;
        }
        .date{
            text-align: center;
            font-style: italic;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
        }
        .titre,.bilan{
            font-style: italic;
            font-family: 'Times New Roman', Times, serif;
            text-decoration: underline;
            margin-left: 10%;
        }
        .titre{
            font-size: 30px;
        }
        .montant,.solde{
            font-size: 18px;
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
        }
        .solde{
            margin-left: 10%;
        }
        .vide{
            color: red;
            font-family:'Times New Roman', Times, serif;
            text-align:center;
            font-size: 20px;
            font-style:italic;
        }
        .footer{
            margin-left: 65%;
            margin-top: 10px;
            font-style: italic;
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</html>