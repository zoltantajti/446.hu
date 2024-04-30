<?php
class Sess extends CI_Model{
    public function __construct(){
        parent::__construct();
        if(!$this->has("__id")){ $this->set("__id", $this->get('__ci_last_regenerate')); };
    }
    public function has($key){
        return (isset($_SESSION[$key]) ? true : false);
    }
    public function get($key){
        return ($this->has($key) ? $_SESSION[$key] : false);
    }
    public function getSub($key,$sub){
        return ($this->has($key) ? @$_SESSION[$key][$sub] : false);
    }
    public function getChain($key = "", $chain = ""){
        $chain = explode("/", $chain);
        $value = $_SESSION;
        foreach($chain as $segment){
            if(isset($value[$segment])){
                $value = $value[$segment];
            } else {
                return "";
            }
        };
        return isset($value[$key]) ? $value[$key] : "";
    }
    public function set($key,$val){
        $_SESSION[$key] = $val;
        return true;
    }
    public function rem($key){
        unset($_SESSION[$key]);
        return true;
    }
    public function checkID(){
        if($this->get('__id') !== $this->get('__ci_last_regenerate')){
            $this->set('__id', $this->get('__ci_last_regenerate'));
            return true;
        };
    }
    public function destroy(){
        sess_destroy();
    }
    
}