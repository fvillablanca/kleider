<?php
// $pdf->Cell(40,7,utf8_decode('Plantilla de casa nación de milagros')); //celda o linea
// $pdf->SetFont('Arial','B',16); //fuente,tipo,tamaño
// $pdf->Cell(70,7, $mensaje, 1,1,'C'); //celda (ancho,altura,mensaje,borde,salto,centrado)
require('material/fpdf.php');

require_once("bd/conexion_productos.php");
$producto = new Producto();
$model_producto = new Producto_model();

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetMargins(5, 5, 5);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);


$fecha_actual = date("d/m/Y");
$pdf->Cell(287, 20, 'KLEIDER INDUMENTARIA                                     FECHA ' . $fecha_actual . ' ', 1, 1, 'R');

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(12, 10, utf8_decode('N°'), 1, 0, 'C');
$pdf->Cell(65, 10, 'PRODUCTO', 1, 0, 'C');
$pdf->Cell(31, 10, 'TALLE', 1, 0, 'C');
$pdf->Cell(40, 10, 'MARCA', 1, 0, 'C');
$pdf->Cell(40, 10, 'COLOR', 1, 0, 'C');
$pdf->Cell(21, 10, 'P.B', 1, 0, 'C');
$pdf->Cell(21, 10, 'P.C', 1, 0, 'C');
$pdf->Cell(21, 10, 'P.V', 1, 0, 'C');
$pdf->Cell(36, 10, 'F.C', 1, 1, 'C');

$contador = 0;

$producto = new Producto();
foreach ($model_producto->Listar() as $r) :
    $comprado = $r->get_comprado();
    if ($comprado == '') {

        $contador = $contador + 1;
        $producto = $r->get_producto();
        $talle = $r->get_talle();
        $marca = $r->get_marca();
        $color = $r->get_color();
        $precio_base = $r->get_precio_base();
        $precio_cliente = $r->get_precio_cliente();
        $precio_vendedor = $r->get_precio_vendedor();
        $fechaCompraDesordenada = $r->get_fecha_compra();
        $fecha_compra = date("d-m-Y", strtotime($fechaCompraDesordenada));

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(12, 7.6, $contador, 1, 0, 'C');  //Contador
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(65, 7.6, utf8_decode($producto), 1, 0, 'C'); //Producto
        $pdf->Cell(31, 7.6, $talle, 1, 0, 'C');  //Talle
        $pdf->Cell(40, 7.6, utf8_decode($marca), 1, 0, 'C'); //Marca
        $pdf->Cell(40, 7.6, utf8_decode($color), 1, 0, 'C'); //Color
        $pdf->Cell(21, 7.6, $precio_base, 1, 0, 'C');  //precio base
        $pdf->Cell(21, 7.6, $precio_cliente, 1, 0, 'C');  //precio cliente
        $pdf->Cell(21, 7.6, $precio_vendedor, 1, 0, 'C');  //precio vendedor
        $pdf->Cell(36, 7.6, $fecha_compra, 1, 1, 'C');  //fecha compra

    };
endforeach;

$pdf->Output();
