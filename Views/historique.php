<?php
    if(isset($_GET['id'])){
        $idEglise=$_GET['id'];

        $q=CONNECT->query("SELECT design FROM eglise WHERE idEglise='$idEglise'");
        $design=$q->fetch(PDO::FETCH_ASSOC)['design'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="src/chartjs/chart.js"></script>
    <script src="src/chartjs/chartjs-adapter-date.js"></script>
    <title><?="Statistiques ".$design ?></title>
</head>
<body>
    <input type="text" name="hidden" value="<?=$idEglise?>" id="hidden" class="id" style="display:none">

    <h1><?=strtoupper($design)?></h1>
    <h3>Histogramme du mouvement des caisses</h3>
    <div class="chart">
        <canvas id="myChart"></canvas>
    </div>
    
    <div class="buttons">
        <button><a href="?route=details&id=<?=$idEglise?>">Retour</a></button>
    </div>
    
    <script src="Views/js/graphics.js?t=<?=time()?>"></script>
    <style>
        body{
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .chart{
            width: 80%;
            height: 60%;
            top: 5%;
            left: 10%;
            position: relative;
        }
        .chart canvas{
            width: 100%;
            border: 1px solid;
            margin: 10px;
        }
        .buttons button{
            user-select: none;
            border-radius: 10px;
            cursor: pointer;
            margin: 5px 0px;
            outline: none;
            border: none;
            padding: 10px;
            background-color: black;
            width: 100%;
            margin-top:60px;
        }
        a{
            text-decoration: none;
            color: white;
            cursor: pointer;
        }
        h1{
            color: gray;
            text-align: center;
        }
        h3{
            color: gray;
            text-align: center;
            text-decoration: underline;
        }
    </style>
</body>
</html>