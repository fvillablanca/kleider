<?php
require_once("bd/conexion_vendedores.php");
$vendedor = new Vendedor();
$model_vendedor = new Vendedor_model();

if (isset($_POST['operacion'])) {
    switch ($_POST['operacion']) {
        case 'registrar':
            $vendedor->set_nombre($_POST['nombre']);
            $vendedor->set_apellido($_POST['apellido']);
            $vendedor->set_dni($_POST['dni']);
            $vendedor->set_domicilio($_POST['domicilio']);
            $vendedor->set_telefono($_POST['telefono']);
            $vendedor->set_fecha_nacimiento($_POST['fecha_nacimiento']);

            $model_vendedor->Registrar($vendedor);
            $vendedor = new Vendedor();

            header("refresh:0");

            break;
        case 'eliminar':
            $vendedor->set_id_vendedor($_POST['id_vendedor']);
            $model_vendedor->Eliminar($vendedor);
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
                <a class="nav-link" href="billetera.php">Billetera <i class="bi bi-wallet"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="productos.php">Productos <i class="bi bi-shop"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resumen.php">Resumen <i class="bi bi-bar-chart-fill"></i></i></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="">Vendedores <i class="bi bi-person-fill"></i></a>
            </li>
        </ul>
    </nav>

    <div style="margin-top: 70px; text-align: center;" class="container-fluid"><br><br>

        <h1>REGISTRO DE VENDEDORES</h1><br>
        <!-- Botones principales -->
        <div class="row">
            <div class="col-sm-10"></div>
            <div class="col-sm-1">
                <div class="btn-group">
                    <!-- Grupo de botones, modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registrar" data-toggle="tooltip" title="Nuevo"><i class="bi bi-person-plus"></i></button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#info" data-toggle="tooltip" title="Información"><i class="bi bi-info-circle"></i></button>
                </div>
            </div>
        </div>

        <!-- Comienzo de tabla de vendedores -->
        <?php
        $listado = $model_vendedor->Listar();
        if ($listado != false) { //comprueba si hay algo en la bd antes de mostrar
        ?>

            <br><br>

            <table class="table table-striped table-hover border" style="text-align: center; ">
                <thead class="thead-dark">
                    <tr>
                        <th>N°</th>
                        <th>NOMBRE</th>
                        <th>APELLIDO</th>
                        <th>DNI</th>
                        <th>DOMICILIO</th>
                        <th>TELEFONO</th>
                        <th>FECHA NACIMIENTO</th>
                        <th>ADMINISTRAR</th>
                    </tr>
                </thead>
                <tbody id="myTable " class="table-striped">
                    <?php
                    $numero_vendedor = 0; //declara variable de contador
                    $vendedor = new Vendedor();
                    foreach ($model_vendedor->Listar() as $r) :
                    ?>

                        <tr>
                            <?php $idproducto = $r->get_id_vendedor(); ?>
                            <th><?php echo $numero_vendedor = $numero_vendedor + 1; ?></th>
                            <td><?php echo $r->get_nombre(); ?></td>
                            <td><?php echo $r->get_apellido(); ?></td>
                            <td><?php echo $r->get_dni(); ?></td>
                            <td><?php echo $r->get_domicilio(); ?></td>
                            <td><?php echo $r->get_telefono(); ?></td>
                            <td><?php $fecha_desordenada = $r->get_fecha_nacimiento();
                                $fecha_ordenada = date("d-m-Y", strtotime($fecha_desordenada));
                                echo $fecha_ordenada; ?></td>
                            <td>
                                <div class="btn-group">

                                    <button style="color: #FFF;" type="button" data-toggle="modal" data-toggle="tooltip" title="Modificar" class="open-Modificar btn btn-warning" href="#Modificar" data-id_vendedor="<?php echo $r->get_id_vendedor(); ?>" data-nombre="<?php echo $r->get_nombre(); ?>" data-apellido="<?php echo $r->get_apellido(); ?>" data-dni="<?php echo $r->get_dni(); ?>" data-domicilio="<?php echo $r->get_domicilio(); ?>" data-telefono="<?php echo $r->get_telefono(); ?>" data-fecha_nacimiento="<?php echo $r->get_fecha_nacimiento(); ?>"><i class="bi bi-gear"></i></button>

                                    <button type="button" data-toggle="modal" data-target="#Eliminar" data-toggle="tooltip" title="Eliminar" data-id="<?php echo $r->get_id_vendedor(); ?>" class="btn btn-danger open-Eliminar"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                    <?php
                    endforeach; ?>

                </tbody>

            </table>

        <?php } else { ?>
            <br>
            <br>
            <br>
            <h2>. . : : SIN VENDEDORES : : . .</h2>

        <?php } ?>
    </div>

    <!-- ................................ MODALES ................................................ -->
    <!-- Registrar -->
    <form action="" method="POST" class="was-validated">
        <div class="modal fade" id="registrar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">REGISTRAR VENDEDOR</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body"><br>
                        <input type="hidden" name="operacion" value="registrar" />
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR NOMBRE" maxlength="20" name="nombre" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR APELLIDO" maxlength="20" name="apellido" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="number" class="form-control" placeholder="INGRESAR DNI" onKeyPress="if(this.value.length==8) return false;" autocomplete="off" name="dni" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR DOMICILIO" maxlength="30" name="domicilio" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input type="number" class="form-control" placeholder="NUMERO DE TELEFONO" onKeyPress="if(this.value.length==10) return false;" name="telefono" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-6" style="margin-top: 6px; text-align:center;"><label for="fecha_nacimiento">FECHA DE NACIMIENTO:</label></div>
                            <div class="col-sm-6">
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>

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
    <!-- Información -->
    <div class="modal fade" id="info" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg ">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">INFORMACIÓN DE LA PÁGINA</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body"><br>
                    <div class="row" style="text-align: center;">
                        <div class="col-sm-12">
                            <p>Esta página permite administrar de forma completa los vendedores kleider. Los vendedores se pueden manipular gracias a las distintas funciones explicadas a continuación:</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="tooltip" title="Nuevo"><i class="bi bi-person-plus"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: Permite ingresar al formulario para crear un nuevo vendedor</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button style="color: #FFF;" class="btn btn-warning" data-toggle="tooltip" title="Modificar"><i class="bi bi-gear"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: El vendedor seleccionado es modificado</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-danger" data-toggle="tooltip" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: El vendedor seleccionado es eliminado</p><br>
                        </div>
                    </div>






                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modificar -->
    <form action="" method="POST" class="was-validated ">

        <div class="modal fade" id="Modificar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">MODIFICAR PRODUCTO</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body"><br>
                        <!-- <input type="text" class="form-control" placeholder="Ingresar Producto" name="bookId" id="bookId" value="" required> -->

                        <input type="hidden" name="operacion" value="actualizar" />
                        <input type="hidden" name="id_vendedor" id="id_vendedor" />
                        <div class="row">
                            <div class="col-sm-12">
                                <input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR NOMBRE" name="nombre" id="nombre" autocomplete="off" required>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR APELLIDO" name="apellido" id="apellido" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="number" class="form-control" onKeyPress="if(this.value.length==8) return false;" placeholder="INGRESAR DNI" name="dni" id="dni" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR DOMICILIO" name="domicilio" id="domicilio" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input type="number" class="form-control" onKeyPress="if(this.value.length==10) return false;" placeholder="TELEFONO" name="telefono" id="telefono" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-6" style="text-align: center; margin-top: 6px;"><label for="fecha_nacimiento">FECHA DE NACIMIENTO:</label></div>
                            <div class="col-sm-6">
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
                            </div>

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
    <!-- Eliminar -->
    <form action="" method="POST" class="was-validated ">

        <div class="modal fade" id="Eliminar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">ELIMINAR PRODUCTO</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body"><br>
                        <input type="hidden" name="operacion" value="eliminar" />
                        <input type="hidden" name="id_vendedor" id="id" />

                        <h4>¿ELIMINAR ESTE PRODUCTO?</h4><br>

                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">ACEPTAR</button>
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
<!-- ................. JS .................... -->
<script>
    //modificar
    // Este script se encarga de enviar valores desde el boton modificar hasta el modal
    $(document).on("click", ".open-Modificar", function() {
        var id_vendedor = $(this).data('id_vendedor');
        var nombre = $(this).data('nombre');
        var apellido = $(this).data('apellido');
        var dni = $(this).data('dni');
        var domicilio = $(this).data('domicilio');
        var telefono = $(this).data('telefono');
        var fecha_nacimiento = $(this).data('fecha_nacimiento');

        $(".modal-body #id_vendedor").val(id_vendedor);
        $(".modal-body #nombre").val(nombre);
        $(".modal-body #apellido").val(apellido);
        $(".modal-body #dni").val(dni);
        $(".modal-body #domicilio").val(domicilio);
        $(".modal-body #telefono").val(telefono);
        $(".modal-body #fecha_nacimiento").val(fecha_nacimiento);
    });


    // Este script se encarga de enviar valores desde el boton Eliminar hasta el modal
    $(document).on("click", ".open-Eliminar", function() {
        var productoId = $(this).data('id');


        $(".modal-body #id").val(productoId);
    });
</script>