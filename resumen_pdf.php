<?php
// $pdf->Cell(40,7,utf8_decode('Plantilla de casa nación de milagros')); //celda o linea
// $pdf->SetFont('Arial','B',16); //fuente,tipo,tamaño
// $pdf->Cell(70,7, $mensaje, 1,1,'C'); //celda (ancho,altura,mensaje,borde,salto,centrado)
require('material/fpdf.php');

require_once("bd/conexion_productos.php");
$producto = new Producto();
$model_producto = new Producto_model();

$producto->set_mes($_POST['mes']);
$producto->set_year($_POST['year']);
$mes = ($_POST['mes']);
$year = ($_POST['year']);

switch ($mes) {
    case '1':
        $mes = 'ENERO';
        break;
    case '2':
        $mes = 'FEBRERO';
        break;
    case '3':
        $mes = 'MARZO';
        break;
    case '4':
        $mes = 'ABRIL';
        break;
    case '5':
        $mes = 'MAYO';
        break;
    case '6':
        $mes = 'JUNIO';
        break;
    case '7':
        $mes = 'JULIO';
        break;
    case '8':
        $mes = 'AGOSTO';
        break;
    case '9':
        $mes = 'SEPTIEMBRE';
        break;
    case '10':
        $mes = 'OCTUBRE';
        break;
    case '11':
        $mes = 'NOVIEMBRE';
        break;
    case '12':
        $mes = 'DICIEMBRE';
        break;
}


$ganancia_total = 0;
$precio_venta_total = 0;
$precio_base_total = 0;


$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetMargins(5, 5, 5);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(287, 20, 'RESUMEN - ' . $mes . '/' . $year . '', 1, 1, 'C');

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(10, 10, utf8_decode('N°'), 1, 0, 'C');
$pdf->Cell(50, 10, 'PRODUCTO', 1, 0, 'C');
$pdf->Cell(21, 10, 'TALLE', 1, 0, 'C');
$pdf->Cell(31, 10, 'MARCA', 1, 0, 'C');
$pdf->Cell(26, 10, 'COLOR', 1, 0, 'C');
$pdf->Cell(26, 10, 'F.C', 1, 0, 'C');
$pdf->Cell(26, 10, 'F.V', 1, 0, 'C');
$pdf->Cell(37, 10, 'COMPRADOR', 1, 0, 'C');
$pdf->Cell(20, 10, 'P.B', 1, 0, 'C');
$pdf->Cell(20, 10, 'VEN', 1, 0, 'C');
$pdf->Cell(20, 10, 'GAN', 1, 1, 'C');

$contador = 0;
$mes = $producto->set_mes($_POST['mes']);
$year = $producto->set_year($_POST['year']);
foreach ($model_producto->ListarResumen($producto) as $r) :

    $contador = $contador + 1;
    $producto = $r->get_producto();
    $talle = $r->get_talle();
    $marca = $r->get_marca();
    $color = $r->get_color();
    $fechaCompraDesordenada = $r->get_fecha_compra();
    $fecha_compra = date("d-m-Y", strtotime($fechaCompraDesordenada));
    $fechaventaDesordenada = $r->get_fecha_venta();
    $fecha_venta = date("d-m-Y", strtotime($fechaventaDesordenada));
    $precio_base = $r->get_precio_base();
    $comprado = $r->get_comprado();
    $precio_base = $r->get_precio_base();

    if ($comprado == 'CLIENTE') {
        $venta = $r->get_precio_cliente();
    } else {
        $venta = $r->get_precio_vendedor();
    }

    $ganancia = $venta - $precio_base;

    $ganancia_total = $ganancia_total + $ganancia;
    $precio_venta_total = $precio_venta_total + $venta;
    $precio_base_total = $precio_base_total + $precio_base;

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 7.6, $contador, 1, 0, 'C');  //Contador
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(50, 7.6, utf8_decode($producto), 1, 0, 'C'); //Producto
    $pdf->Cell(21, 7.6, $talle, 1, 0, 'C');  //Talle
    $pdf->Cell(31, 7.6, utf8_decode($marca), 1, 0, 'C'); //Marca
    $pdf->Cell(26, 7.6, utf8_decode($color), 1, 0, 'C'); //Color
    $pdf->Cell(26, 7.6, $fecha_compra, 1, 0, 'C');  //precio base
    $pdf->Cell(26, 7.6, $fecha_venta, 1, 0, 'C');  //precio cliente
    $pdf->Cell(37, 7.6, $comprado, 1, 0, 'C');  //precio vendedor
    $pdf->Cell(20, 7.6, $precio_base, 1, 0, 'C');  //precio vendedor
    $pdf->Cell(20, 7.6, $venta, 1, 0, 'C');  //precio vendedor
    $pdf->Cell(20, 7.6, $ganancia, 1, 1, 'C');  //fecha compra

endforeach;
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(227, 7.6, utf8_decode('RESULTADOS TOTALES'), 1, 0, 'C');
$pdf->Cell(20, 7.6, $precio_base_total, 1, 0, 'C');
$pdf->Cell(20, 7.6, $precio_venta_total, 1, 0, 'C');
$pdf->Cell(20, 7.6, $ganancia_total, 1, 1, 'C');
$pdf->Output();
