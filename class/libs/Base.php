<?php

abstract class Base
{
    /**
     * L'id de l'objet
     *
     * @var int
     */
    protected $_id;
    /**
     * Indique si l'objet est chargé depuis la base de données ou non
     *
     * @var boolean
     */
    protected $_sql;
    /**
     * @var DateTime
     */
    protected $_created_at;
    /**
     * @var DateTime
     */
    protected $_updated_at;
    /**
     * Définit la table sur laquelle l'objet est stocké
     *
     * @var string
     */
    protected static $_tableSql;
    
    /**
     * Instancie une page à partir de son ID ou d'un tableau
     *
     * @param       mixed :int|array $param
     * @param array $parameters
     */
    public function __construct($param = null, $parameters = array()) {
        // Si on construit l'objet à partir de son ID
        if (is_numeric($param)) {
            // Récupération des valeurs
            $param = static::get($param);
        }
        
        $this->_load($param, $parameters);
    }
    
    /**
     * Renvoi l'id de l'objet.
     *
     * @return number
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * Attribue un ID à l'objet
     *
     * @param int $id
     */
    public function setId($id) {
        $this->_id = $id;
    }
    
    /**
     * Vérifie si l'objet est chargé depuis la base de données
     *
     * @return boolean
     */
    public function isSql() {
        return $this->_sql;
    }
    
    /**
     * Enregistre l'objet dans la base de données
     *
     * @return int L'id de l'élément enregistré
     */
    public function save() {
        if (empty($this->_updated_at)) {
            $this->_updated_at = new DateTime();
        }
        
        // Si l'objet n'est pas encore enregistré dans la base de données, on l'enregistre !
        if (!$this->isSql()) {
            if (empty($this->_created_at)) {
                $this->_created_at = new DateTime();
            }
            
            $id = $this->_create();
        } else {
            $id = $this->_update();
        }
        
        // Vérification de la validité de l'enregistrement / la mise à jour.
        if (empty($id) || !is_numeric($id)) {
            trigger_error(
                '_create et _update (class ' . get_class($this) . ') doivent retourner un ID numérique !',
                E_USER_ERROR
            );
        }
        
        $this->_sql = true;
        $this->_id = $id;
        
        return $id;
    }
    
    /**
     * Supprime l'objet de la base de données
     */
    public function delete() {
        $sql = MyPDO::get();
        $req = $sql->prepare('DELETE FROM ' . static::$_tableSql . ' WHERE id = :id');
        
        if ($req && $req->execute(array(':id' => $this->_id))) {
            return true;
        }
    }
    
    public static function get($id) {
        $sql = MyPDO::get();
        $req = $sql->prepare('SELECT * FROM ' . static::$_tableSql . ' WHERE id = :id');
        if ($req->execute(array(':id' => $id))) {
            while ($row = $req->fetch()) {
                return $row;
            }
        }
    }
}
