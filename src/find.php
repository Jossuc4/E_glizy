<?php

    if(isset($_GET['motif'])&& !empty($_GET['motif']) && isset($_GET['design'])){
        $motif=$_GET['motif'];
        $design=$_GET['design'];

        $q=CONNECT->query("SELECT idEglise FROM eglise WHERE design='$design'");
        $id=$q->fetch(PDO::FETCH_ASSOC)['idEglise'];

        $q=CONNECT->query("SELECT * FROM sortie WHERE motif LIKE '%$motif%' AND idEglise='$id'");
        $data=$q->fetchAll(PDO::FETCH_ASSOC);
        
    }else if(isset($_GET['date'])){
        $date=$_GET['date'];

        $q=CONNECT->prepare("SELECT * FROM sortie WHERE dateSortie=?");
        $exec=$q->execute([$date]);

        $data=$q->fetchAll(PDO::FETCH_ASSOC);
    }

    if(isset($data)) echo json_encode($data);
    