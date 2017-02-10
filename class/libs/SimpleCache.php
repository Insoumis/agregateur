<?php

/*
 * SimpleCache v1.4.1
 *
 * By Gilbert Pellegrom
 * http://dev7studios.com
 *
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

class SimpleCache
{
    // Path to cache folder (with trailing /)
    public $cache_path = 'cache/';
    // Length of time to cache a file (in seconds) -- 1 year
    public $cache_time = SIMPLE_CACHE_TIME;
    // Cache file extension
    public $cache_extension = '.cache';
    
    /**
     * This is just a functionality wrapper function
     *
     * @param      $label
     * @param      $url
     * @param null $post_fields
     * @param null $contentType
     * @param bool $cached
     * @return array|bool|mixed|string
     */
    public function get_data($label, $url, $post_fields = null, $contentType = null, $cached = true) {
        $post_fields_to_json = $post_fields;
        
        if (!empty($post_fields_to_json)) {
            foreach ($post_fields_to_json as $field => $value) {
                if ($field == 'password') {
                    unset($post_fields_to_json[$field]);
                }
            }
        }
        
        $json_fields = json_encode($post_fields_to_json);
        if ($cached && $data = $this->get_cache($label . $json_fields)) {
            return $data;
        } else {
            $data = $this->do_curl($url, $post_fields, $contentType);
            
            // handling error
            if (!is_array($data)) {
                if ($cached) {
                    $this->set_cache($label . $json_fields, $data);
                }
                
                return $data;
            } else {
                return $data;
            }
        }
    }
    
    public function set_cache($label, $data) {
        if (!is_dir($this->cache_path)) {
            mkdir($this->cache_path);
        }
        file_put_contents($this->cache_path . $this->safe_filename($label) . $this->cache_extension, $data);
    }
    
    public function get_cache($label) {
        if ($this->is_cached($label)) {
            $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;
            
            return file_get_contents($filename);
        }
        
        return false;
    }
    
    public function is_cached($label) {
        $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;
        
        if (file_exists($filename) && (filemtime($filename) + $this->cache_time >= time())) {
            return true;
        }
        
        return false;
    }
    
    //Helper function for retrieving data from url
    public function do_curl($url, $fields, $contentType = 'application/json') {
        if (function_exists("curl_init")) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            
            if ($fields) {
                $headers = array();
                
                if ($contentType) {
                    $headers[] = "Content-type: " . $contentType;
                }
                
                if ($contentType == 'application/json') {
                    $fields = json_encode($fields);
                }
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            
            $content = curl_exec($ch);
            
            if (curl_error($ch)) {
                return array('error' => curl_error($ch), 'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),);
            }
            
            curl_close($ch);
            
            return $content;
        } else {
            return file_get_contents($url);
        }
    }
    
    //Helper function to validate filenames
    private function safe_filename($filename) {
        return preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($filename));
    }
}