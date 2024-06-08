<?php
require_once('controlador.php');
$controlador = new controlador();

if (isset($_POST["temp"]) && isset($_POST["agua"]) && isset($_POST["consumo"]) && isset($_POST["ppm"]) && isset($_POST["pPb"]) && isset($_POST["pCr"]) && isset($_POST["pZn"]) && isset($_POST["pCd"])) {

    $temp = floatval($_POST["temp"]);
    $agua = $_POST["agua"];
    $consumo = floatval($_POST["consumo"]);
    $ppm = floatval($_POST["ppm"]);
    $pCd = $_POST["pCd"];
    $pZn = $_POST["pZn"];
    $pCr = $_POST["pCr"];
    $pPb = $_POST["pPb"];
    
    $consumoAnt = $controlador->getConsumo();
    $consumo = $consumo + $consumoAnt;
    echo $consumoAnt; 

    
    $controlador->saveTemperatura($temp, $agua, $consumo, $ppm, $pCd, $pZn, $pCr, $pPb);
} else {

    if ( isset($_POST['accion'])) {
        
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        
        switch ($accion) {
        case 'getlecturas':
            $lecturas = $controlador->getlecturas();
            echo $lecturas;
            break;
        
        case 'getFlujo':
            $flujo = $controlador->getFlujo();
            echo $flujo;
            break;
            
        case 'getPredeterminado':
            $pre = $controlador->getPredeterminado(531378373);
            echo $pre;
            break;
    
        default:
            echo "Acción no válida";
            break;
        }
    }
}
?>
