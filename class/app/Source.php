<?php

class Source extends Base
{
    protected $_id;
    protected $_title;
    protected $_url;
    protected $_type;
    protected $_img_url;
    /**
     * @var DateTime
     */
    protected $_created_at;
    /**
     * @var DateTime
     */
    protected $_updated_at;
    protected $_online;
    protected $_source_reserved_1;
    protected $_source_reserved_2;
    /**
     * La table par défaut utilisée par la classe.
     *
     * @var string
     */
    protected static $_tableSql = TABLE_SOURCES;
    
    public function getTitle() {
        return $this->_title;
    }
    
    public function getType() {
        return $this->_type;
    }
    
    public function getUrl() {
        return $this->_url;
    }
    
    public function getImg_url() {
        return $this->_img_url;
    }
    
    public function getCreated_at() {
        return $this->_created_at;
    }
    
    public function getUpdated_at() {
        return $this->_updated_at;
    }
    
    public function getOnline() {
        return $this->_online;
    }
    
    public function getSource_reserved_1() {
        return $this->_source_reserved_1;
    }
    
    public function getSource_reserved_2() {
        return $this->_source_reserved_2;
    }
    
    public function setTitle($title) {
        $this->_title = $title;
    }
    
    public function setType($type) {
        $this->_type = $type;
    }
    
    public function setUrl($url) {
        $this->_url = $url;
    }
    
    public function setImg_url($img_url) {
        $this->_img_url = $img_url;
    }
    
    public function setCreated_at($created_at) {
        $this->_created_at = $created_at;
    }
    
    public function setUpdated_at($updated_at) {
        $this->_updated_at = $updated_at;
    }
    
    public function setOnline($online) {
        $this->_online = $online;
    }
    
    public function setSource_reserved_1($source_reserved_1) {
        $this->_source_reserved_1 = $source_reserved_1;
    }
    
    public function setSource_reserved_2($source_reserved_2) {
        $this->_source_reserved_2 = $source_reserved_2;
    }
    
    /*
     * Charge les valeurs de l'objet
     * @param array $param Le tableau avec les valeurs nécessaire à l'instanciation de l'objet
     */
    protected function _load($param) {
        if ($param) {
            $this->_id = $param['id'];
            $this->_title = $param['title'];
            $this->_type = $param['type'];
            $this->_url = $param['url'];
            $this->_img_url = $param['img_url'];
            $this->_created_at = new DateTime($param['created_at']);
            $this->_updated_at = new DateTime($param['updated_at']);
            $this->_online = $param['online'];
            $this->_source_reserved_1 = $param['source_reserved_1'];
            $this->_source_reserved_2 = $param['source_reserved_2'];
            $this->_sql = true;
        }
    }
    
    protected function _create() {
        $sql = MyPDO::get();
        $req = $sql->prepare(
            'INSERT INTO `' . static::$_tableSql . '` VALUES (:id, :title, :type, :url, :img_url, :created_at, :updated_at, :online, :source_reserved_1, :source_reserved_2)'
        );
        $args = array(
            ':id'                => $this->_id,
            ':type'              => $this->_type,
            ':title'             => $this->_title,
            ':url'               => $this->_url,
            ':img_url'           => $this->_img_url,
            ':created_at'        => $this->_created_at->format('Y-m-d H:i:s'),
            ':updated_at'        => $this->_updated_at->format('Y-m-d H:i:s'),
            ':online'            => $this->_online,
            ':source_reserved_1' => $this->_source_reserved_1,
            ':source_reserved_2' => $this->_source_reserved_2,
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
            'UPDATE `' . static::$_tableSql . '` SET `title` = :title, `type` = :type, `url` = :url, `img_url` = :img_url, `created_at` = :created_at, `updated_at` = :updated_at, `online` = :online, `source_reserved_1` = :source_reserved_1, `source_reserved_2` = :source_reserved_2 WHERE id = :id'
        );
        $args = array(
            ':id'                => $this->_id,
            ':type'              => $this->_type,
            ':title'             => $this->_title,
            ':url'               => $this->_url,
            ':img_url'           => $this->_img_url,
            ':created_at'        => $this->_created_at->format('Y-m-d H:i:s'),
            ':updated_at'        => $this->_updated_at->format('Y-m-d H:i:s'),
            ':online'            => $this->_online,
            ':source_reserved_1' => $this->_source_reserved_1,
            ':source_reserved_2' => $this->_source_reserved_2,
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
     * Retourne les sources correspondants aux paramètres donnés
     *
     * @param int     $id       L'id de l'élément à récupérer
     * @param boolean $to_array True si on souhaite renvoyer les valeurs sous forme de tableau au lieu d'objets
     * @param int     $start
     * @param null    $limit
     * @return Source[]
     */
    public static function getAll($id = null, $to_array = false, $start = 0, $limit = null) {
        $where = '';
        $args = array();
        
        if ($id) {
            $where = ' WHERE id = :id';
            $args[':id'] = (int)$id;
        }
        
        if ($limit) {
            $limit = $start . ', ' . $limit;
        }
        
        $array = array();
        $sql = MyPDO::get();
        
        $req = $sql->prepare(
            '
                    SELECT `' . static::$_tableSql . '`.*
                    FROM `' . static::$_tableSql . '`   ' . $where . ' ORDER BY `created_at` DESC ' . $limit
        );
        
        if ($req->execute($args)) {
            while ($row = $req->fetch()) {
                $Source = new Source($row);
                $array[$row['id']] = $Source;
            }
            
            return $array;
        } else {
            trigger_error($req->errorInfo()[2], E_USER_ERROR);
            exit;
        }
    }
    
    public function getContents() {
        return Content::getAll(null, false, null, null, $this->_id);
    }
    
    public function getContentsUrlIndexed() {
        $array_contents = $this->getContents();
        $array_contents_by_url = array();
        
        foreach ($array_contents as $Content) {
            $array_contents_by_url[$Content->getUrl()] = $Content;
        }
        
        return $array_contents_by_url;
    }
    
    public function fetch() {
        if ($this->_type == SOURCE_TYPE_RSS) {
            $this->fetchRSS();
        } else if ($this->_type == SOURCE_TYPE_YT) {
            $this->fetchYouTube();
        }
    }
    
    private function fetchRSS() {
        $file_or_url = $this->resolveFile($this->_url);
        if (!($x = simplexml_load_file($file_or_url))) {
            return;
        }
        
        $array_contents_by_url = $this->getContentsUrlIndexed();
        
        foreach ($x->channel->item as $item) {
            if (!empty($array_contents_by_url[(string)$item->link])) {
                continue;
            }
            $Content = new Content();
            $Content->setCreated_at(new DateTime((string)$item->pubDate));
            $Content->setUrl((string)$item->link);
            $Content->setTitle((string)$item->title);
            $Content->setSource_id($this->_id);
            $Content->setDescription((string)$item->description);
            $Content->save();
        }
    }
    
    private function fetchYouTube() {
        $playlist_id = $this->getYoutubeListIdFromUrl($this->_url);
        
        if (empty($playlist_id)) {
            trigger_error('Impossible de déterminer le playlist_id de ' . $this->_url);
            
            return false;
        }
        
        $content = file_get_contents(
            'https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails,snippet&maxResults=50&playlistId=' .
            $playlist_id . '&key=' . GOOGLE_API
        );
        
        if ($content) {
            $array_contents_by_url = $this->getContentsUrlIndexed();
            $videos = json_decode($content);
            
            foreach ($videos->items as $video) {
                $url = $this->getYoutubeUrlFromId($video->contentDetails->videoId);
                
                if (!empty($array_contents_by_url[$url])) {
                    continue;
                }
                $Content = new Content();
                $Content->setCreated_at(new DateTime($video->snippet->publishedAt));
                $Content->setUrl($url);
                $Content->setTitle($video->snippet->title);
                $Content->setSource_id($this->_id);
                $Content->setDescription($video->snippet->description);
                $Content->save();
            }
        }
    }
    
    private function getYoutubeUrlFromId($id) {
        return 'https://www.youtube.com/watch?v=' . $id;
    }
    
    private function getYoutubeListIdFromUrl($youtube_url) {
        preg_match('#^(?:https?:\/\/)?(?:www\.)?youtu\.?be(?:\.com)?.*?(?:v|list)=(.*?)(?:&|$)|^(?:https?:\/\/)?(?:www\.)?youtu\.?be(?:\.com)?(?:(?!=).)*\/(.*)$#', $youtube_url, $results);
        if (!empty($results[1])) {
            return $results[1];
        }
    }
    
    private function resolveFile($file_or_url) {
        if (!preg_match('|^https?:|', $file_or_url)) {
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] . '/shared/xml/' . $file_or_url;
        } else {
            $feed_uri = $file_or_url;
        }
        
        return $feed_uri;
    }
}