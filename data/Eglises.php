<?php
    
    //Listage des eglises existantes dans la plateforme
    
    if(isset($_GET['id'])){
        $query=CONNECT->prepare("SELECT * FROM eglise WHERE idEglise=?");
        $exec=$query->execute([$_GET['id']]);

        echo json_encode($query->fetch(PDO::FETCH_ASSOC));
    }else{
        $query=CONNECT->query("SELECT idEglise,design FROM eglise ORDER BY design");
        $listeEglise=$query->fetchAll(PDO::FETCH_ASSOC);


        echo json_encode($listeEglise);
    }
