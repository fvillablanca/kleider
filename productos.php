<?php
require_once("bd/conexion_vendedores.php");
$vendedor = new Vendedor();
$model_vendedor = new Vendedor_model();

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
        case 'vendido':
            // se ejecuta el model vendido y vendedor sobre el mismo case

            $producto->set_id_producto($_POST['id_producto']);
            $producto->set_comprado($_POST['comprado']);
            $producto->set_fecha_venta($_POST['fecha_venta']);
            $model_producto->Vendido($producto);
            header("refresh:0");
            break;
        case 'eliminar':
            $producto->set_id_producto($_POST['id_producto']);
            $producto->set_precio_base($_POST['precio_base']);
            $model_producto->Eliminar($producto);
            header("refresh:0");
            break;
        case 'actualizar':
            $producto->set_id_producto($_POST['id_producto']);
            $producto->set_producto($_POST['producto']);
            $producto->set_talle($_POST['talle']);
            $producto->set_marca($_POST['marca']);
            $producto->set_color($_POST['color']);
            $producto->set_precio_base($_POST['precio_base']);
            $producto->set_precio_cliente($_POST['precio_cliente']);
            $producto->set_precio_vendedor($_POST['precio_vendedor']);
            $producto->set_fecha_compra($_POST['fecha_compra']);

            $model_producto->Actualizar($producto);
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
                <a class="nav-link active" href="">Productos <i class="bi bi-shop"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resumen.php">Resumen <i class="bi bi-bar-chart-fill"></i></i></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="vendedores.php">Vendedores <i class="bi bi-person-fill"></i></a>
            </li>
        </ul>
    </nav>
    <div style="margin-top: 70px; text-align: center;" class="container-fluid"><br><br>

        <h1>STOCK DE PRODUCTOS</h1><br>

        <!-- Botones principales -->
        <div class="row">
            <div class="col-sm-10"></div>
            <div class="col-sm-1">
                <div class="btn-group">
                    <!-- Grupo de botones, modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-toggle="tooltip" data-target="#registrar" title="Nuevo"><i class="bi bi-plus-lg"></i></button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-toggle="tooltip" data-target="#info" title="Información"><i class="bi bi-info-circle"></i></button>
                    <?php
                    $noVendido = $model_producto->NoVendido();
                    if ($noVendido == true) { //comprueba si hay algo en la bd antes de mostrar
                    ?>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-toggle="tooltip" data-target="#pdf" title="PDF"><i class="bi bi-file-earmark-pdf"></i></button>
                    <?php }; ?>
                </div>
            </div>
        </div>
        <br><br>

        <!-- Comienzo de lista de productos -->

        <?php
        $noVendido = $model_producto->NoVendido();
        if ($noVendido == true) { //comprueba si hay algo en la bd antes de mostrar
        ?>

            <table class="table table-striped table-hover border" style="text-align: center; ">
                <thead class="thead-dark">
                    <tr>
                        <th>N°</th>
                        <th>PRODUCTO</th>
                        <th>TALLE</th>
                        <th>MARCA</th>
                        <th>COLOR</th>
                        <th>PRECIO BASE</th>
                        <th>PRECIO CLIENTE</th>
                        <th>PRECIO VENDEDOR</th>
                        <th>FECHA COMPRA</th>
                        <th>ADMINISTRAR</th>
                    </tr>
                </thead>
                <tbody id="myTable " class="table-striped">
                    <?php


                    $numero_producto = 0; //declara variable de contador
                    $producto = new Producto();
                    foreach ($model_producto->Listar() as $r) :

                        // el if acontinuacion comprueba que el producto no este vendido
                        $comprado = $r->get_comprado();
                        if ($comprado == '') { ?>

                            <tr>
                                <?php $idproducto = $r->get_id_producto(); ?>
                                <th><?php echo $numero_producto = $numero_producto + 1; ?></th>
                                <td><?php echo $r->get_producto(); ?></td>
                                <td><?php echo $r->get_talle(); ?></td>
                                <td><?php echo $r->get_marca(); ?></td>
                                <td><?php echo $r->get_color(); ?></td>
                                <td><?php echo $r->get_precio_base(); ?></td>
                                <td><?php echo $r->get_precio_cliente(); ?></td>
                                <td><?php echo $r->get_precio_vendedor(); ?></td>
                                <td><?php $fecha_desordenada = $r->get_fecha_compra();
                                    $fecha_ordenada = date("d-m-Y", strtotime($fecha_desordenada));
                                    echo $fecha_ordenada; ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" data-toggle="modal" data-toggle="tooltip" title="Vender" data-id="<?php echo $r->get_id_producto(); ?>" data-target="#Vender" class="btn btn-success open-Vender"><i class="bi bi-currency-dollar"></i></button>
                                        <button type="button" data-toggle="modal" data-toggle="tooltip" title="Modificar" data-id="<?php echo $r->get_id_producto(); ?>" data-producto="<?php echo $r->get_producto(); ?>" data-talle="<?php echo $r->get_talle(); ?>" data-marca="<?php echo $r->get_marca(); ?>" data-color="<?php echo $r->get_color(); ?>" data-precio_base="<?php echo $r->get_precio_base(); ?>" data-precio_cliente="<?php echo $r->get_precio_cliente(); ?>" data-precio_vendedor="<?php echo $r->get_precio_vendedor(); ?>" data-fecha_compra="<?php echo $r->get_fecha_compra(); ?>" class="open-Modificar btn btn-warning" href="#Modificar" style="color: #FFF;"><i class="bi bi-gear"></i></button>

                                        <button type="button" data-toggle="modal" data-toggle="tooltip" title="Borrar" data-id="<?php echo $r->get_id_producto(); ?>" data-precio_base="<?php echo $r->get_precio_base(); ?>" data-target="#Eliminar" class="btn btn-danger open-Eliminar"><i class="bi bi-trash"></i></button>
                                        <button type="button" data-toggle="modal" data-toggle="tooltip" title="Vendedor" data-id="<?php echo $r->get_id_producto(); ?>" data-target="#Vendedor" class="btn btn-info open-Vendedor"><i class="bi bi-person-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                    <?php };
                    endforeach; ?>
                </tbody>
            </table>

        <?php } else { ?>
            <h2>. . : : SIN PRODUCTOS EN VENTA : : . .</h2>
        <?php }; ?>

    </div>

    <!------------------------------------ MODALES ------------------------------------>
    <!-- REGISTRAR -->
    <form action="" method="POST" class="was-validated">
        <div class="modal fade" id="registrar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">REGISTRAR PRODUCTO</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body"><br>
                        <input type="hidden" name="operacion" value="registrar" />
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR PRODUCTO" name="producto" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input type="number" type="number" class="form-control" placeholder="INGRESAR TALLE" name="talle" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR MARCA" name="marca" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR COLOR" name="color" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-6"><input type="number" class="form-control" placeholder="PRECIO BASE" name="precio_base" autocomplete="off" required></div>
                            <div class="col-sm-6"><input type="number" class="form-control" placeholder="PRECIO CLIENTE" name="precio_cliente" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-6"><input type="number" class="form-control" placeholder="PRECIO VENDEDOR" name="precio_vendedor" autocomplete="off" required></div>
                            <div class="col-sm-6">
                                <!-- Carga fecha actual -->
                                <?php $fcha = date("Y-m-d"); ?>
                                <input type="date" name="fecha_compra" class="form-control" value="<?php echo $fcha; ?>" required>
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
    <!-- INFORMACION el producto -->
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
                            <p>Esta página permite administrar de forma completa los productos. Los productos se pueden manipular gracias a las distintas funciones explicadas a continuación:</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-primary" data-toggle="tooltip" title="Nuevo"><i class="bi bi-plus-lg"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: Permite ingresar al formulario para crear un nuevo producto</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-danger" data-toggle="tooltip" title="PDF"><i class="bi bi-file-earmark-pdf"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: Genera una lista del stock completo en formato .PDF </p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-success" data-toggle="tooltip" title="Vendido"><i class="bi bi-currency-dollar"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: El producto seleccionado pasa a ser comprado por un cliente</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button style="color: #FFF;" class="btn btn-warning" data-toggle="tooltip" title="Modificar"><i class="bi bi-gear"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: El producto seleccionado es modificado</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-danger" data-toggle="tooltip" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: El producto seleccionado es eliminado</p><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2" style="text-align: right;">
                            <button class="btn btn-info" data-toggle="tooltip" title="Vendedor"><i class="bi bi-person-fill"></i></button>
                        </div>
                        <div class="col-sm-10">
                            <p>: El producto seleccionado pasa a ser vendido por un vendedor Kleider</p><br>
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
    <!-- generar PDF -->
    <form action="productos_pdf.php" target="_blank" method="POST" class="was-validated">
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
    <!-- MODIFICAR el producto -->
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
                        <input type="hidden" name="id_producto" id="id" />
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR PRODUCTO" name="producto" id="producto" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input type="number" class="form-control" placeholder="INGRESAR TALLE" name="talle" id="talle" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR MARCA" name="marca" id="marca" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-12"><input onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" class="form-control" placeholder="INGRESAR COLOR" name="color" id="color" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-6"><input type="number" class="form-control" placeholder="PRECIO BASE" name="precio_base" id="precio_base" autocomplete="off" required></div>
                            <div class="col-sm-6"><input type="number" class="form-control" placeholder="PRECIO CLIENTE" name="precio_cliente" id="precio_cliente" autocomplete="off" required></div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-6"><input type="number" class="form-control" placeholder="PRECIO VENDEDOR" name="precio_vendedor" id="precio_vendedor" autocomplete="off" required></div>
                            <div class="col-sm-6">
                                <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" required>
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
    <!-- ELIMINAR el producto -->
    <form action="" method="POST" class="was-validated ">

        <div class="modal fade" id="Eliminar" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">BORRAR PRODUCTO</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" style="text-align: center;"><br>
                        <input type="hidden" name="precio_base" id="precio_base" />
                        <input type="hidden" name="operacion" value="eliminar" />
                        <input type="hidden" name="id_producto" id="id" />

                        <h4>¿DESEA BORRAR ESTE PRODUCTO?</h4><br>
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
    <!-- VENDER el producto -->
    <form action="" method="POST" class="was-validated ">

        <div class="modal fade" id="Vender" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">VENDER PRODUCTO</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" style="text-align: center;"><br>
                        <?php $fcha = date("Y-m-d"); ?>
                        <input type="hidden" name="fecha_venta" value="<?php echo $fcha; ?>">
                        <input type="hidden" name="operacion" value="vendido" />
                        <input type="hidden" name="comprado" value="CLIENTE" />
                        <input type="hidden" name="id_producto" id="id" />

                        <h4>¿DESEA VENDER ESTE PRODUCTO?</h4><br>
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
    <!-- elegir que VENDEDOR a vendido el producto -->
    <form action="" method="POST" class="was-validated ">

        <div class="modal fade" id="Vendedor" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">VENDEDOR</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" style="text-align: center;"><br>
                        <?php $fcha = date("Y-m-d"); ?>
                        <input type="hidden" name="fecha_venta" value="<?php echo $fcha; ?>">
                        <input type="hidden" name="operacion" value="vendido" />
                        <input type="hidden" name="id_producto" id="id" />

                        <h4>¿QUIEN VENDIO ESTE PRODUCTO?</h4>
                        <br>
                        <input type="text" list="listabusqueda" name="comprado" id="comprado" class="form-control" required> <!-- Este input es un buscador gracias a datalist -->
                        <datalist id="listabusqueda">
                            <?php
                            $vendedor = new Vendedor();
                            foreach ($model_vendedor->Listar() as $r) :
                                $nombre = $r->get_nombre();
                                $apellido = $r->get_apellido();
                                $informacionCompleta = $nombre . " " . $apellido;
                            ?>
                                <option value="<?php echo $informacionCompleta; ?>"><?php echo $informacionCompleta; ?></option>
                            <?php
                            endforeach; ?>
                        </datalist>
                        <br>

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
    <nav class="navbar navbar-expand-sm justify-content-center">
        <p style="text-align:center;">Copyright &copy; 2022 - Desarrollado por Neat - Contacto 299-4291023</p>
    </nav>
</body>

</html>

<!-- ..................... JS ............................... -->
<script>
    //vender
    $(document).on("click", ".open-Vender", function() {
        var productoId = $(this).data('id');

        $(".modal-body #id").val(productoId);
    });
</script>

<script>
    //vendedor
    $(document).on("click", ".open-Vendedor", function() {
        var productoId = $(this).data('id');

        $(".modal-body #id").val(productoId);
    });
</script>

<script>
    //eliminar
    $(document).on("click", ".open-Eliminar", function() {
        var productoId = $(this).data('id');
        var precio_base = $(this).data('precio_base');

        $(".modal-body #id").val(productoId);
        $(".modal-body #precio_base").val(precio_base);
    });
</script>

<script>
    //modificar
    $(document).on("click", ".open-Modificar", function() {
        var productoId = $(this).data('id');
        var producto = $(this).data('producto');
        var talle = $(this).data('talle');
        var marca = $(this).data('marca');
        var color = $(this).data('color');
        var precio_base = $(this).data('precio_base');
        var precio_cliente = $(this).data('precio_cliente');
        var precio_vendedor = $(this).data('precio_vendedor');
        var fecha_compra = $(this).data('fecha_compra');

        $(".modal-body #id").val(productoId);
        $(".modal-body #producto").val(producto);
        $(".modal-body #talle").val(talle);
        $(".modal-body #marca").val(marca);
        $(".modal-body #color").val(color);
        $(".modal-body #precio_base").val(precio_base);
        $(".modal-body #precio_cliente").val(precio_cliente);
        $(".modal-body #precio_vendedor").val(precio_vendedor);
        $(".modal-body #fecha_compra").val(fecha_compra);
    });
</script>