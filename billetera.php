<?php
require_once("bd/conexion_billetera.php");
$billetera = new Billetera();
$model_billetera = new Billetera_model();

if (isset($_POST['operacion'])) {
    switch ($_POST['operacion']) {
        case 'ingresar':
            $billetera->set_tipo_movimiento($_POST['movimiento']);
            $billetera->set_movimiento($_POST['ingreso_saldo']);
            $billetera->set_fecha($_POST['fecha_ingreso']);
            $model_billetera->IngresarSaldo($billetera);

            $billetera = new billetera();
            header("refresh:0");
            break;
        case 'retirar':
            $billetera->set_tipo_movimiento($_POST['movimiento']);
            $billetera->set_movimiento($_POST['retiro_saldo']);
            $billetera->set_fecha($_POST['fecha_ingreso']);
            $model_billetera->RetirarSaldo($billetera);

            $billetera = new billetera();
            header("refresh:0");
            break;
        case 'actualizar':
            $vendedor->set_id_vendedor($_POST['id_vendedor']);
            $vendedor->set_nombre($_POST['nombre']);
            $vendedor->set_apellido($_POST['apellido']);
            $vendedor->set_dni($_POST['dni']);
            $vendedor->set_domicilio($_POST['domicilio']);
            $vendedor->set_telefono($_POST['telefono']);
            $vendedor->set_fecha_nacimiento($_POST['fecha_nacimiento']);

            $model_vendedor->Actualizar($vendedor);
            $vendedor = new Vendedor();
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
                <a class="nav-link  active" href="">Billetera <i class="bi bi-wallet"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="productos.php">Productos <i class="bi bi-shop"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resumen.php">Resumen <i class="bi bi-bar-chart-fill"></i></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vendedores.php">Vendedores <i class="bi bi-person-fill"></i></a>
            </li>
        </ul>
    </nav>

    <div style="margin-top: 70px; text-align: center;" class="container-fluid"><br><br>

        <div class="row">
            <div class="col-sm-12">
                <h1>CONTADURIA</h1><br>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
                <h3>
                    SALDO ACTUAL . . .
                    <?php

                    $variable = $model_billetera->ListarSaldo();
                    echo '$' . $variable;

                    ?>
                </h3><br>
            </div>
            <div class="col-sm-4">
            </div>
        </div>

        <hr>
        <br>
        <!-- Comienzo de resumenes de cada mes -->

        <div class="row">
            <div class="col-sm-4">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-success" data-toggle="modal" data-toggle="tooltip" data-target="#registrar_saldo">INGRESO SALDO</button>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-toggle="tooltip" data-target="#descontar_saldo">RETIRO SALDO</button>
            </div>
            <div class="col-sm-4">
            </div>
        </div>
        <br>
        <hr>


        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">


                <table class="table table-striped table-hover border" style="text-align: center; ">
                    <thead class="thead-dark">
                        <tr>
                            <th>MOVIMIENTO</th>
                            <th>MONTO</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody id="myTable " class="table-striped">
                        <?php
                        $billetera = new Billetera();
                        foreach ($model_billetera->Listar() as $r) :
                        ?>
                            <tr>
                                <td><?php echo $r->get_tipo_movimiento(); ?></td>
                                <td><?php echo $r->get_movimiento(); ?></td>
                                <td><?php $fecha_desordenada = $r->get_fecha();
                                    $fecha_ordenada = date("d-m-Y", strtotime($fecha_desordenada));
                                    echo $fecha_ordenada; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </div>
            <div class="col-sm-2">
            </div>
        </div>





        <!-- MODALES -->
        <!-- INGRESAR SALDO MODEL -->
        <form action="" method="POST" class="was-validated">
            <div class="modal fade" id="registrar_saldo" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">INGRESAR SALDO</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body"><br>
                            <input type="hidden" name="operacion" value="ingresar" />
                            <input type="hidden" name="movimiento" value="INGRESO" />
                            <?php $fcha = date("Y-m-d"); ?>
                            <input type="hidden" name="fecha_ingreso" value="<?php echo $fcha; ?>" required>


                            <div class="row">
                                <div class="col-sm-12"><input type="number" class="form-control" placeholder="CANTIDAD DE SALDO A INGRESAR" name="ingreso_saldo" autocomplete="off" required></div>
                            </div><br>

                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">GUARDAR</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- RETIRAR SALDO MODEL -->
        <form action="" method="POST" class="was-validated">
            <div class="modal fade" id="descontar_saldo" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">RETIRAR SALDO</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body"><br>
                            <input type="hidden" name="operacion" value="retirar" />
                            <input type="hidden" name="movimiento" value="RETIRO" />
                            <?php $fcha = date("Y-m-d"); ?>
                            <input type="hidden" name="fecha_ingreso" value="<?php echo $fcha; ?>" required>


                            <div class="row">
                                <div class="col-sm-12"><input type="number" class="form-control" placeholder="CANTIDAD DE SALDO A RETIRAR" name="retiro_saldo" autocomplete="off" required></div>
                            </div><br>

                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">GUARDAR</button>
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