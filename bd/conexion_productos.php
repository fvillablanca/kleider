<?php
class Producto
{
    private $_id_producto;
    private $_producto;
    private $_talle;
    private $_marca;
    private $_color;
    private $_precio_base;
    private $_precio_cliente;
    private $_precio_vendedor;
    private $_fecha_compra;
    private $_fecha_venta;
    private $_comprado;
    private $_year;
    private $_mes;

    public function set_id_producto($valor)
    {
        $this->_id_producto = $valor;
    }
    public function set_producto($valor)
    {
        $this->_producto = $valor;
    }
    public function set_talle($valor)
    {
        $this->_talle = $valor;
    }
    public function set_marca($valor)
    {
        $this->_marca = $valor;
    }
    public function set_color($valor)
    {
        $this->_color = $valor;
    }
    public function set_precio_base($valor)
    {
        $this->_precio_base = $valor;
    }
    public function set_precio_cliente($valor)
    {
        $this->_precio_cliente = $valor;
    }
    public function set_precio_vendedor($valor)
    {
        $this->_precio_vendedor = $valor;
    }
    public function set_fecha_compra($valor)
    {
        $this->_fecha_compra = $valor;
    }
    public function set_fecha_venta($valor)
    {
        $this->_fecha_venta = $valor;
    }
    public function set_comprado($valor)
    {
        $this->_comprado = $valor;
    }
    public function set_year($valor)
    {
        $this->_year = $valor;
    }
    public function set_mes($valor)
    {
        $this->_mes = $valor;
    }

    public function get_id_producto()
    {
        return $this->_id_producto;
    }
    public function get_producto()
    {
        return $this->_producto;
    }
    public function get_talle()
    {
        return $this->_talle;
    }
    public function get_marca()
    {
        return $this->_marca;
    }
    public function get_color()
    {
        return $this->_color;
    }
    public function get_precio_base()
    {
        return $this->_precio_base;
    }
    public function get_precio_cliente()
    {
        return $this->_precio_cliente;
    }
    public function get_precio_vendedor()
    {
        return $this->_precio_vendedor;
    }
    public function get_fecha_compra()
    {
        return $this->_fecha_compra;
    }
    public function get_fecha_venta()
    {
        return $this->_fecha_venta;
    }
    public function get_comprado()
    {
        return $this->_comprado;
    }
    public function get_year()
    {
        return $this->_year;
    }
    public function get_mes()
    {
        return $this->_mes;
    }
}

require_once("conexion_bd.php");
class Producto_model
{
    private $pdo; //driver de conexion a la base de datos

    public function __construct() //constructor de la clase areas_model
    {
        $con = new conexion(); //instancia de la clase conexion
        $this->pdo = $con->getConexion(); //guardo en pdo la conexion de la instancia conexion
    }

    //-------------------------------------- REGISTRAR -------------------------------------//
    public function Registrar(Producto $data)  //Registra un nuevo producto en la bd
    {
        try {
            $sql = "SELECT * FROM caja";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $saldo_caja = $stm->fetchColumn(); // guarda el valor de la consulta
            $saldo_descontar_caja = $data->get_precio_base();
            $resto_caja = $saldo_caja - $saldo_descontar_caja;
            if ($saldo_descontar_caja <= $saldo_caja) {
                $sql = "INSERT INTO productos ( producto, talle, marca, color, precio_base, precio_cliente, precio_vendedor, fecha_compra) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                $stm = $this->pdo->prepare($sql);
                $stm->execute(
                    array(
                        $data->get_producto(),
                        $data->get_talle(),
                        $data->get_marca(),
                        $data->get_color(),
                        $data->get_precio_base(),
                        $data->get_precio_cliente(),
                        $data->get_precio_vendedor(),
                        $data->get_fecha_compra(),
                    ) //termina arreglo
                ); //termina execute

                //Actualizacion del valor en bd caja
                $sql = "UPDATE `caja` SET `saldo_caja`= $resto_caja";
                $stm = $this->pdo->prepare($sql);
                $stm->execute();
            }; //termina if

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }



    //-------------------------------------- LISTAR NO VENDIDOS -------------------------------------//

    public function NoVendido() //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT * FROM productos WHERE comprado = ''";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            $cantreg = $stm->rowcount();

            if ($cantreg == 0) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //-------------------------- VENTA ----------------------------------//

    public function Vendido($data) //elimina un horario de la bd
    {
        try {
            $sql = "UPDATE `productos` SET `fecha_venta`= ? , `comprado` = ? WHERE id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_fecha_venta(),
                    $data->get_comprado(),
                    $data->get_id_producto(),
                )
            );

            // Toma lo que hay en la caja
            $sql = "SELECT * FROM caja";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $saldo_caja = $stm->fetchColumn(); // guarda el valor de la consulta

            $quien_vendio = $data->get_comprado();

            if ($quien_vendio == 'CLIENTE') {
                // Toma el saldo de clientes que se sumara a la caja
                $id_producto = $data->get_id_producto();
                $sql = "SELECT precio_cliente FROM productos WHERE id_producto = $id_producto ";
                $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
                $stm->execute(); //ejecuta la consulta
                $saldo = $stm->fetchColumn(); // guarda el valor de la consulta
            } else {
                // Toma el saldo de vendedores que se sumara a la caja
                $id_producto = $data->get_id_producto();
                $sql = "SELECT precio_vendedor FROM productos WHERE id_producto = $id_producto ";
                $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
                $stm->execute(); //ejecuta la consulta
                $saldo = $stm->fetchColumn(); // guarda el valor de la consulta
            };

            // Suma el saldo a la caja
            $sumaTotal = $saldo_caja + $saldo;

            //Actualizacion del valor en bd caja
            $sql = "UPDATE `caja` SET `saldo_caja`= $sumaTotal";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //-------------------------- Eliminar ----------------------------------//
    public function Eliminar($data) //elimina un horario de la bd
    {
        try {
            // Modifica valor en caja
            $sql = "SELECT * FROM caja";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $saldo_caja = $stm->fetchColumn(); // guarda el valor de la consulta

            $precio_base = $data->get_precio_base();

            $sumaTotal = $saldo_caja + $precio_base;

            //Actualizacion del valor en bd caja
            $sql = "UPDATE `caja` SET `saldo_caja`= $sumaTotal";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            // Elimina
            $sql = "DELETE FROM productos WHERE id_producto = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_id_producto(),
                )
            );
        } catch (Exception $e) {
        }
    }

    //-------------------------- ACTUALIZAR ----------------------------------//

    public function Actualizar(Producto $data) //actualiza un registro de la tabla con un dato de tipo clase Miembro
    {
        try {

            $sql = "UPDATE productos SET producto = ?, talle = ?, marca = ?, color = ? , precio_base = ?, precio_cliente = ?, precio_vendedor = ?, fecha_compra = ?  WHERE id_producto = ?"; // crea la consulta
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_producto(),
                    $data->get_talle(),
                    $data->get_marca(),
                    $data->get_color(),
                    $data->get_precio_base(),
                    $data->get_precio_cliente(),
                    $data->get_precio_vendedor(),
                    $data->get_fecha_compra(),
                    $data->get_id_producto(),
                )
            ); //ejecutla la consulta

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //-------------------------------------- LISTAR -------------------------------------//
    public function Listar() //Muestra todos los miembros de la bd
    {
        try {
            $result = array();
            $sql = "SELECT * FROM productos ORDER BY producto, color, talle";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            $cantreg = $stm->rowcount();

            if ($cantreg == 0) {
                return false;
            } else {

                foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) //recorre una lista de objetos alumno que lo guarda en la variable r
                {
                    $producto = new Producto();

                    $producto->set_id_producto($r->id_producto);
                    $producto->set_producto($r->producto);
                    $producto->set_talle($r->talle);
                    $producto->set_marca($r->marca);
                    $producto->set_color($r->color);
                    $producto->set_precio_base($r->precio_base);
                    $producto->set_precio_cliente($r->precio_cliente);
                    $producto->set_precio_vendedor($r->precio_vendedor);
                    $producto->set_fecha_compra($r->fecha_compra);
                    $producto->set_fecha_venta($r->fecha_venta);
                    $producto->set_comprado($r->comprado);
                    $result[] = $producto; //guarda cada intancia de alumno en el arreglo result
                }

                return $result; //devuelve un arreglo de objetos materia
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    //-------------------------------------- TODO DE RESUMEN.PHP -------------------------------------//
    //------------------------- Listar Ultima Venta --------------------------//

    public function ListarUltimaVenta() //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT fecha_venta FROM `productos` ORDER BY year(fecha_venta) desc";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            $ventaModerna = $stm->fetchColumn(); //toma la venta mas moderna

            return $ventaModerna;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    //------------------------- Listar Ventas Totales --------------------------//

    public function VentasTotales() //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT COUNT(fecha_venta) FROM `productos` WHERE year(fecha_venta)";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            $Ventas = $stm->fetchColumn(); //toma la venta mas moderna

            return $Ventas;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //------------------------- Listar Inversion Total --------------------------//

    public function InversionTotal() //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT SUM(precio_base) FROM `productos` WHERE year(fecha_venta)";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            $Ventas = $stm->fetchColumn(); //toma la venta mas moderna

            return $Ventas;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //------------------------- Listar Ganancia Total --------------------------//

    public function GananciaTotal() //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT SUM(precio_base) FROM `productos` WHERE year(fecha_venta)";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $precio_base = $stm->fetchColumn(); //toma la venta mas moderna

            $sql = "SELECT SUM(precio_cliente) FROM `productos` WHERE year(fecha_venta) AND comprado = 'CLIENTE'";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $precio_cliente = $stm->fetchColumn(); //toma la venta mas moderna

            $sql = "SELECT SUM(precio_vendedor) FROM `productos` WHERE year(fecha_venta) AND comprado != 'CLIENTE'";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $precio_vendedor = $stm->fetchColumn(); //toma la venta mas moderna

            $precio_venta = $precio_cliente + $precio_vendedor;
            $ganancia_total = $precio_venta - $precio_base;

            return $ganancia_total;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //-------------------------------------- Listar Resumen -------------------------------------//

    public function ListarResumen(Producto $data) //Muestra todos los miembros de la bd
    {
        try {
            $result = array();
            $sql = "SELECT * FROM `productos` WHERE year(fecha_venta) = ? AND month(fecha_venta) = ?";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(
                array(
                    $data->get_year(),
                    $data->get_mes(),
                )
            ); //ejecutla la consulta
            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) //recorre una lista de objetos alumno que lo guarda en la variable r
            {
                $producto = new Producto();

                $producto->set_id_producto($r->id_producto);
                $producto->set_producto($r->producto);
                $producto->set_talle($r->talle);
                $producto->set_marca($r->marca);
                $producto->set_color($r->color);
                $producto->set_precio_base($r->precio_base);
                $producto->set_precio_cliente($r->precio_cliente);
                $producto->set_precio_vendedor($r->precio_vendedor);
                $producto->set_fecha_compra($r->fecha_compra);
                $producto->set_fecha_venta($r->fecha_venta);
                $producto->set_comprado($r->comprado);
                $result[] = $producto; //guarda cada intancia de alumno en el arreglo result
            }

            return $result; //devuelve un arreglo de objetos materia

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }




    //-------------------------------------- Comprobar Resumen -------------------------------------//

    public function Comprobar(Producto $data) //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT * FROM `productos` WHERE year(fecha_venta) = ? AND month(fecha_venta) = ?";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(
                array(
                    $data->get_year(),
                    $data->get_mes()
                )
            ); //ejecutla la consulta
            $cantreg = $stm->rowcount();
            return $cantreg; //devuelve un arreglo de objetos materia

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}   //Fin
