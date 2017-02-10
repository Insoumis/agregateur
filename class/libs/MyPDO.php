<?php

class MyPDO
{
    private static $_instance;

    //Construction de MyPDO
    private function __construct() {
        $this->_PDOInstance = new PDO(
            SQL_DSN . ';charset=UTF8', SQL_USER, SQL_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    }

    //Appel de l'instance de PDO
    public static function get() {
        if (is_null(self::$_instance)) {
            self::$_instance = new MyPDO();
        }

        return self::$_instance;
    }

    public function query($statement) {
        return $this->_PDOInstance->query($statement);
    }

    public function setFetchMode() {
        return $this->_PDOInstance->setFetchMode();
    }

    public function prepare($query) {
        $prepare = $this->_PDOInstance->prepare($query);
        // Définition du mode fetch par défaut
        $prepare->setFetchMode(PDO::FETCH_ASSOC);

        return $prepare;
    }

    public function lastInsertId() {
        return $this->_PDOInstance->lastInsertId();
    }
}
