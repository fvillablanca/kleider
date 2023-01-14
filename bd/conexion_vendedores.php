<?php
class Vendedor
{

    private $_id_vendedor;
    private $_nombre;
    private $_apellido;
    private $_dni;
    private $_domicilio;
    private $_telefono;
    private $_fecha_nacimiento;

    public function set_id_vendedor($valor){$this->_id_vendedor = $valor;}
    public function set_nombre($valor){$this->_nombre = $valor;}
    public function set_apellido($valor){$this->_apellido = $valor;}
    public function set_dni($valor){$this->_dni = $valor;}
    public function set_domicilio($valor){$this->_domicilio = $valor;}
    public function set_telefono($valor){$this->_telefono = $valor;}
    public function set_fecha_nacimiento($valor){$this->_fecha_nacimiento = $valor;}

    public function get_id_vendedor(){return $this->_id_vendedor;}
    public function get_nombre(){return $this->_nombre;}
    public function get_apellido(){return $this->_apellido;}
    public function get_dni(){return $this->_dni;}
    public function get_domicilio(){return $this->_domicilio;}
    public function get_telefono(){return $this->_telefono;}
    public function get_fecha_nacimiento(){return $this->_fecha_nacimiento;}
}

require_once("conexion_bd.php");
class Vendedor_model
{
    private $pdo; //driver de conexion a la base de datos

    public function __construct() //constructor de la clase areas_model
    {
        $con = new conexion(); //instancia de la clase conexion
        $this->pdo = $con->getConexion(); //guardo en pdo la conexion de la instancia conexion
    }



    //-------------------------------------- REGISTRAR -------------------------------------//
    public function Registrar(Vendedor $data)  //Registra un nuevo miembro en la bd
    {
        try {
            $sql = "INSERT INTO vendedores ( nombre, apellido, dni, domicilio, telefono, fecha_nacimiento) VALUES ( ?, ?, ?, ?, ?, ?)";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_nombre(),
                    $data->get_apellido(),
                    $data->get_dni(),
                    $data->get_domicilio(),
                    $data->get_telefono(),
                    $data->get_fecha_nacimiento(),
                )
            );
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    //-------------------------------------- LISTAR -------------------------------------//

    public function Listar() //Muestra todos los miembros de la bd
    {
        try {
            $result = array();
            $sql = "SELECT * FROM vendedores";
            $stm = $this->pdo->prepare($sql); //directiva de traer toda la tabla materias
            $stm->execute(); //ejecuta la consulta

            $cantreg = $stm->rowcount();

            if ($cantreg == 0) {
                return false;
            } else {

                foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) //recorre una lista de objetos alumno que lo guarda en la variable r
                {
                    $vendedor = new Vendedor();

                    $vendedor->set_id_vendedor($r->id_vendedor);
                    $vendedor->set_nombre($r->nombre);
                    $vendedor->set_apellido($r->apellido);
                    $vendedor->set_dni($r->dni);
                    $vendedor->set_domicilio($r->domicilio);
                    $vendedor->set_telefono($r->telefono);
                    $vendedor->set_fecha_nacimiento($r->fecha_nacimiento);
                    $result[] = $vendedor; //guarda cada intancia de alumno en el arreglo result
                }

                return $result; //devuelve un arreglo de objetos materia
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    //-------------------------- ELIMINAR ----------------------------------//
    public function Eliminar($data) //elimina un horario de la bd
    {
        try {
            $sql = "DELETE FROM vendedores WHERE id_vendedor = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_id_vendedor(),
                )
            );
        } catch (Exception $e) {
        }
    }

    //-------------------------- ACTUALIZAR ----------------------------------//

    public function Actualizar(Vendedor $data) //actualiza un registro de la tabla con un dato de tipo clase Miembro
    {
        try {

            $sql = "UPDATE vendedores SET nombre = ?, apellido = ?, dni = ?, domicilio = ? , telefono = ?, fecha_nacimiento = ? WHERE id_vendedor = ?"; // crea la consulta
            $stm = $this->pdo->prepare($sql);
            $stm->execute(
                array(
                    $data->get_nombre(),
                    $data->get_apellido(),
                    $data->get_dni(),
                    $data->get_domicilio(),
                    $data->get_telefono(),
                    $data->get_fecha_nacimiento(),
                    $data->get_id_vendedor(),
                )
            ); //ejecutla la consulta

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}   //Fin
