<?php
class Billetera
{

    private $_saldo_caja;
    private $_movimiento;
    private $_tipo_movimiento;
    private $_fecha;
    private $_saldo_productos;


    public function set_saldo_caja($valor)
    {
        $this->_saldo_caja = $valor;
    }
    public function set_movimiento($valor)
    {
        $this->_movimiento = $valor;
    }
    public function set_tipo_movimiento($valor)
    {
        $this->_tipo_movimiento = $valor;
    }
    public function set_fecha($valor)
    {
        $this->_fecha = $valor;
    }
    public function set_saldo_productos($valor)
    {
        $this->_saldo_productos = $valor;
    }

    public function get_saldo_caja()
    {
        return $this->_saldo_caja;
    }
    public function get_movimiento()
    {
        return $this->_movimiento;
    }
    public function get_tipo_movimiento()
    {
        return $this->_tipo_movimiento;
    }
    public function get_fecha()
    {
        return $this->_fecha;
    }
    public function get_saldo_productos()
    {
        return $this->_saldo_productos;
    }
}

require_once("conexion_bd.php");
class Billetera_model
{
    private $pdo; //driver de conexion a la base de datos

    public function __construct() //constructor de la clase areas_model
    {
        $con = new conexion(); //instancia de la clase conexion
        $this->pdo = $con->getConexion(); //guardo en pdo la conexion de la instancia conexion
    }

    //-------------------------------------- LISTAR SALDO-------------------------------------//

    public function ListarSaldo() //Muestra todos los miembros de la bd
    {
        try {
            $sql = "SELECT * FROM caja";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $result = $stm->fetchColumn(); // guarda el valor de la consulta

            return $result;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function IngresarSaldo(Billetera $data) //elimina un horario de la bd
    {
        try {
            $sql = "SELECT * FROM caja";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $saldo_caja = $stm->fetchColumn(); // guarda el valor de la consulta
            $saldo_nuevo = $data->get_movimiento(); //obtiene el monto desde billetera.php

            //Actualizacion del valor en bd caja
            $suma_total = $saldo_caja + $saldo_nuevo; //suma del valor de la base de datos y billetera.php
            $sql = "UPDATE `caja` SET `saldo_caja`= $suma_total";
            $stm = $this->pdo->prepare($sql);-+
            $stm->execute();

            // Insertar informacion del movimiento en la bd 'movimientos'
            $sql = "INSERT INTO movimientos ( tipo_movimiento, movimiento, fecha) VALUES ( ?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_tipo_movimiento(),
                    $data->get_movimiento(),
                    $data->get_fecha()
                )
            );
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function RetirarSaldo(Billetera $data) //elimina un horario de la bd
    {
        try {
            $sql = "SELECT * FROM caja";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta
            $saldo_caja = $stm->fetchColumn(); // guarda el valor de la consulta
            $saldo_nuevo = $data->get_movimiento(); //obtiene el monto desde billetera.php

            $resta_total = $saldo_caja - $saldo_nuevo; //resta del valor de la base de datos y billetera.php
            //Actualizacion del valor en bd caja
            $sql = "UPDATE `caja` SET `saldo_caja`= $resta_total";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            // Insertar informacion del movimiento en la bd 'movimientos'
            $sql = "INSERT INTO movimientos ( tipo_movimiento, movimiento, fecha) VALUES ( ?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_tipo_movimiento(),
                    $data->get_movimiento(),
                    $data->get_fecha()
                )
            );
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Listar tabla movimientos
    public function Listar() //Muestra todos los miembros de la bd
    {
        try {
            $result = array();
            $sql = "SELECT * FROM movimientos ORDER BY fecha desc";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) //recorre una lista de objetos alumno que lo guarda en la variable r
            {
                $billetera = new Billetera();

                $billetera->set_tipo_movimiento($r->tipo_movimiento);
                $billetera->set_movimiento($r->movimiento);
                $billetera->set_fecha($r->fecha);
                $result[] = $billetera; //guarda cada intancia de alumno en el arreglo result
            }

            return $result; //devuelve un arreglo de objetos materia
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}   //Fin