<?php
require_once("bd/conexion_productos.php");
$producto = new Producto();
$model_producto = new Producto_model();

if (isset($_POST['operacion'])) {
    switch ($_POST['operacion']) {
        case 'registrar':
            $producto->set_producto($_POST['producto']);
            $producto->set_talle($_POST['talle']);
            $producto->set_marca($_POST['marca']);
            $producto->set_color($_POST['color']);
            $producto->set_precio_base($_POST['precio_base']);
            $producto->set_precio_cliente($_POST['precio_cliente']);
            $producto->set_precio_vendedor($_POST['precio_vendedor']);
            $producto->set_fecha_compra($_POST['fecha_compra']);

            $model_producto->Registrar($producto);
            $producto = new Producto();

            header("refresh:0");
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="css/crown.png">
    <title>Indumentaria Kleider's</title>
</head>

<body>
    <!-- barra superior -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top justify-content-center">
        <a class="navbar-brand" href="">KLEIDER</a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="billetera.php">Billetera <i class="bi bi-wallet"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="productos.php">Productos <i class="bi bi-shop"></i></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="">Resumen <i class="bi bi-bar-chart-fill"></i></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vendedores.php">Vendedores <i class="bi bi-person-fill"></i></a>
            </li>
        </ul>
    </nav>

    <div style="margin-top: 70px; text-align: center;" class="container-fluid"><br><br>

        <div class="row">
            <div class="col-sm-12">
                <h1>INFORME GENERAL</h1><br>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <button class="btn btn-primary">VENTAS REALIZADAS</button>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-info">INVERSION EN PRODUCTOS</button>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-success">GANANCIA TOTAL</button><br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">

                <h3><?php echo $totalVentas = $model_producto->VentasTotales(); ?></h3><br>
            </div>
            <div class="col-sm-4">
                <h3><?php echo $total_inversion = $model_producto->InversionTotal(); ?></h3><br>
            </div>
            <div class="col-sm-4">
                <h3><?php echo $total_ganancia = $model_producto->GananciaTotal(); ?></h3><br>
            </div>
        </div>
        <br>
        <hr>
        <br>

        <!-- Comienzo de resumenes de cada mes -->

        <?php
        $ultimaVenta = $model_producto->ListarUltimaVenta();
        $yearVenta = date("Y", strtotime($ultimaVenta)); //almacena el año mas moderno
        $mesVenta = date("m", strtotime($ultimaVenta)); //almacena el mes mas moderno
        if ($ultimaVenta != 0) {

            while ($yearVenta >= 2020) {

                while ($mesVenta >= 1) {
                    // if ($comprobador_contenido != false) {
                    switch ($mesVenta) {
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
                    $producto->set_year($yearVenta);
                    $producto->set_mes($mesVenta);
                    $comprobar = $model_producto->Comprobar($producto);
                    if ($comprobar != 0) {


        ?>


                        <h2 style="text-align: center;"><?php echo $mes . '/' . $yearVenta ?></h2>
                        <table class="table table-striped table-hover border" style="text-align: center; ">
                            <thead class="thead-dark">
                                <tr>

                                    <th>PRODUCTO</th>
                                    <th>TALLE</th>
                                    <th>MARCA</th>
                                    <th>COLOR</th>
                                    <th>FECHA COMPRA</th>
                                    <th>FECHA VENTA</th>
                                    <th>COMPRADOR</th>
                                    <th>PRECIO BASE</th>
                                    <th>VENTA</th>
                                    <th>GANANCIA</th>

                                </tr>
                            </thead>
                            <tbody id="myTable " class="table-striped">
                                <?php
                                $producto = new Producto();
                                $precio_base_total = 0;
                                $precio_venta_total = 0;
                                $ganancia_total = 0;

                                $producto->set_year($yearVenta);
                                $producto->set_mes($mesVenta);
                                foreach ($model_producto->ListarResumen($producto) as $r) : ?>
                                    <tr>
                                        <td style="text-align: left;" style="width: 10px ;"><?php echo $r->get_producto(); ?></td>
                                        <td><?php echo $r->get_talle(); ?></td>
                                        <td style="text-align: left;"><?php echo $r->get_marca(); ?></td>
                                        <td style="text-align: left;"><?php echo $r->get_color(); ?></td>
                                        <td><?php $fecha_desordenada = $r->get_fecha_compra();
                                            $fecha_ordenada = date("d-m-Y", strtotime($fecha_desordenada));
                                            echo $fecha_ordenada; ?></td>
                                        <td><?php $fecha_desordenada = $r->get_fecha_venta();
                                            $fecha_ordenada = date("d-m-Y", strtotime($fecha_desordenada));
                                            echo $fecha_ordenada; ?></td>
                                        <td><?php echo $comprado = $r->get_comprado(); ?></td>
                                        <td><?php echo $precio_base = $r->get_precio_base(); ?></td>
                                        <?php if ($comprado == 'CLIENTE') { ?>
                                            <td><?php echo $venta = $r->get_precio_cliente(); ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $venta = $r->get_precio_vendedor(); ?></td>
                                        <?php }  ?>

                                        <td><?php echo $ganancia = $venta - $precio_base ?></td>

                                    </tr>
                            </tbody>
                        <?php
                                    $ganancia_total = $ganancia_total + $ganancia;
                                    $precio_venta_total = $precio_venta_total + $venta;
                                    $precio_base_total = $precio_base_total + $precio_base;

                                endforeach; ?>
                        <tfoot class="thead-light">
                            <tr>
                                <th> <button type="button" data-toggle="modal" data-toggle="tooltip" data-target="#pdf" title="PDF" data-mes="<?php echo $mesVenta; ?>" data-year="<?php echo $yearVenta; ?>" class="open-pdf btn btn-danger">GENERAR PDF <i class="bi bi-file-earmark-pdf"></i></button></th>
                                <th colspan="6" style="text-align: right;">RESULTADOS TOTALES</th>
                                <th><?php echo $precio_base_total; ?></th>
                                <th><?php echo $precio_venta_total; ?></th>
                                <th><?php echo $ganancia_total; ?></th>
                            </tr>
                        </tfoot>
                        </table>
                        <br>
                        <hr>
        <?php
                    }; //if
                    $mesVenta = $mesVenta - 1;
                }; //while year
                $mesVenta = 12;
                $yearVenta = $yearVenta - 1;
            }; //termina if
        };
        ?>
    </div>



    <!-- MODAL -->
    <form action="resumen_pdf.php" target="_blank" method="POST" class="was-validated">
        <div class="modal fade" id="pdf" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Formato de Documento Portátil</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" style="text-align: center;"><br>
                        <h4>¿Generar archivo PDF?</h4>
                        <input type="hidden" name="mes" id="mes">
                        <input type="hidden" name="year" id="year">

                        <br>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">GENERAR</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Barra inferior de información -->
    <hr>
        <nav class="navbar navbar-expand-sm justify-content-center static-bottom" >
            <p style="text-align:center;">Copyright &copy; 2022 - Desarrollado por Neat - Contacto 299-4291023</p>
        </nav>

</body>

</html>

<script>
    //modificar
    $(document).on("click", ".open-pdf", function() {
        var mes = $(this).data('mes');
        var years = $(this).data('year');

        $(".modal-body #mes").val(mes);
        $(".modal-body #year").val(years);

    });
</script>