<?php
require dirname(__DIR__)."/src/dompdf_2-0-3/dompdf/autoload.inc.php";

if(isset($_GET['id'])){

    $id=$_GET['id'];
    $q=CONNECT->query("SELECT design FROM eglise WHERE idEglise='$id'");
    $design=$q->fetch(PDO::FETCH_ASSOC)['design'];

    ob_start();
    require "rapport.php";
    $content=ob_get_contents();
    ob_end_clean();

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($content);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $dompdf->stream($design."_".date("d_m_Y"));

    header("location:?route=details&design=".$design);

}

