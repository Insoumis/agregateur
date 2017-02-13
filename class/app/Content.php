<?php

class Content extends Base
{
    protected $_id;
    protected $_title;
    protected $_url;
    protected $_source_id;
    protected $_created_at;
    protected $_updated_at;
    protected $_description;
    protected $_content_reserved_1;
    protected $_content_reserved_2;
    /**
     * La table par défaut utilisée par la classe.
     *
     * @var string
     */
    protected static $_tableSql = TABLE_CONTENTS;
    
    public function getTitle() {
        return $this->_url;
    }
    
    public function getUrl() {
        return $this->_url;
    }
    
    public function getSource_id() {
        return $this->_source_id;
    }
    
    public function getCreated_at() {
        return $this->_created_at;
    }
    
    public function getUpdated_at() {
        return $this->_updated_at;
    }
    
    public function getDescription() {
        return $this->_description;
    }
    
    public function getContent_reserved_1() {
        return $this->_content_reserved_1;
    }
    
    public function getContent_reserved_2() {
        return $this->_content_reserved_2;
    }
    
    public function setTitle($title) {
        $this->_title = $title;
    }
    
    public function setUrl($url) {
        $this->_url = $url;
    }
    
    public function setSource_id($source_id) {
        $this->_source_id = $source_id;
    }
    
    public function setCreated_at($created_at) {
        $this->_created_at = $created_at;
    }
    
    public function setUpdated_at($updated_at) {
        $this->_updated_at = $updated_at;
    }
    
    public function setDescription($description) {
        $this->_description = $description;
    }
    
    public function setContent_reserved_1($content_reserved_1) {
        $this->_content_reserved_1 = $content_reserved_1;
    }
    
    public function setContent_reserved_2($content_reserved_2) {
        $this->_content_reserved_2 = $content_reserved_2;
    }
    
    /*
     * Charge les valeurs de l'objet
     * @param array $param Le tableau avec les valeurs nécessaire à l'instanciation de l'objet
     */
    protected function _load($param) {
        if ($param) {
            $this->_id = $param['id'];
            $this->_url = $param['url'];
            $this->_source_id = $param['source_id'];
            $this->_created_at = new DateTime($param['created_at']);
            $this->_updated_at = new DateTime($param['updated_at']);
            $this->_description = $param['description'];
            $this->_content_reserved_1 = $param['content_reserved_1'];
            $this->_content_reserved_2 = $param['content_reserved_2'];
            $this->_sql = true;
        }
    }
    
    protected function _create() {
        $sql = MyPDO::get();
        $req = $sql->prepare(
            'INSERT INTO `' . static::$_tableSql . '` VALUES (:id, :title, :url, :source_id, :created_at, :updated_at, :description, :content_reserved_1, :content_reserved_2)'
        );
        $args = array(
            ':id'                 => $this->_id,
            ':title'              => $this->_title,
            ':url'                => $this->_url,
            ':source_id'          => $this->_source_id,
            ':created_at'         => $this->_created_at->format('Y-m-d H:i:s'),
            ':updated_at'         => $this->_updated_at->format('Y-m-d H:i:s'),
            ':description'        => $this->_description,
            ':content_reserved_1' => $this->_content_reserved_1,
            ':content_reserved_2' => $this->_content_reserved_2,
        );
        if ($req->execute($args)) {
            return $sql->lastInsertId();
        } else {
            trigger_error($req->errorInfo()[2], E_USER_ERROR);
            exit;
        }
    }
    
    protected function _update() {
        $sql = MyPDO::get();
        $req = $sql->prepare(
            'UPDATE `' . static::$_tableSql . '` SET `title` = :title, `url` = :url, `source_id` = :source_id, `created_at` = :created_at, `updated_at` = :updated_at, `description` = :description, `content_reserved_1` = :content_reserved_1, `content_reserved_2` = :content_reserved_2 WHERE id = :id'
        );
        $args = array(
            ':id'                 => $this->_id,
            ':title'              => $this->_title,
            ':url'                => $this->_url,
            ':source_id'          => $this->_source_id,
            ':created_at'         => $this->_created_at->format('Y-m-d H:i:s'),
            ':updated_at'         => $this->_updated_at->format('Y-m-d H:i:s'),
            ':description'        => $this->_description,
            ':content_reserved_1' => $this->_content_reserved_1,
            ':content_reserved_2' => $this->_content_reserved_2,
        );
        if ($req->execute($args)) {
            return $this->_id;
        } else {
            trigger_error($req->errorInfo()[2], E_USER_ERROR);
            exit;
        }
    }
    
    public static function get($id) {
        $array = static::getAll($id, true);
        
        return array_shift($array);
    }
    
    /**
     * Retourne les éléments correspondants aux paramètres donnés
     *
     * @param int     $id        L'id de l'élément à récupérer
     * @param boolean $to_array  True si on souhaite renvoyer les valeurs sous forme de tableau au lieu d'objets
     * @param int     $start
     * @param string  $limit     Le nombre d'éléments à renvoyer
     * @param int     $source_id L'ID de la source des contenus
     * @return Content[]
     */
    public static function getAll($id = null, $to_array = false, $start = null, $limit = null, $source_id = null) {
        $where = '';
        $args = array();
        
        if ($id) {
            $where = ' WHERE id = :id';
            $args[':id'] = (int)$id;
        }
        
        if ($source_id) {
            if ($where) {
                $where .= ' AND ';
            } else {
                $where = ' WHERE ';
            }
            
            $where .= ' source_id = :source_id ';
            $args[':source_id'] = (int)$source_id;
        }
        
        if ($limit !== null) {
            $limit = 'LIMIT ' . $start . ', ' . $limit;
        }
        
        $array = array();
        $sql = MyPDO::get();
        
        $query = '
                    SELECT `' . static::$_tableSql . '`.*
                    FROM `' . static::$_tableSql . '`   ' . $where . ' ORDER BY `created_at` DESC ' . $limit;
        
        $req = $sql->prepare($query);
        
        if ($req->execute($args)) {
            while ($row = $req->fetch()) {
                $Content = new Content($row);
                $array[$row['id']] = $Content;
            }
            
            return $array;
        } else {
            trigger_error($req->errorInfo()[2], E_USER_ERROR);
            exit;
        }
    }
}