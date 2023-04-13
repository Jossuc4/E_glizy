<?php 

    if(isset($_GET['design'])){
        $design=$_GET['design'];
        $query=CONNECT->prepare("SELECT * FROM entree JOIN eglise USING(idEglise) WHERE design=? ORDER BY dateEntree DESC");
        $exec=$query->execute([$design]);
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }else{
        $query=CONNECT->query("SELECT * FROM entree JOIN eglise USING(idEglise) ORDER BY dateEntree");
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }
    ?>