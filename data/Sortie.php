<?php 

    if(isset($_GET['design'])){
        $design=$_GET['design'];
        $query=CONNECT->prepare("SELECT * FROM sortie JOIN eglise USING(idEglise) WHERE design=? ORDER BY dateSortie DESC");
        $exec=$query->execute([$design]);
        $data=$query->fetchAll(PDO::FETCH_ASSOC);

        if(count($data)==0){
            $query=CONNECT->prepare("SELECT solde FROM  eglise  WHERE design=?");
            $exec=$query->execute([$design]);
            $data=$query->fetch(PDO::FETCH_ASSOC);
        }

      echo json_encode($data);
    }else{
        $query=CONNECT->query("SELECT * FROM sortie JOIN eglise USING(idEglise) ORDER BY dateSortie");
        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }
?>