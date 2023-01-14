<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','kleider');
define('DB_CHARSET','utf-8');

class conexion extends PDO
{
  protected $pdo;
  public function getConexion()
  {
    return $this->pdo;
  }
  public function __construct()
  {
    try {
      $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }
}

// INVESTIGAR pul de conexiones y singleton (patron de diseño)
// es para no saturar de conexiones el boton de base de datos....
