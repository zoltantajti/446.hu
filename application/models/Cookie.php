<?php
class Cookie extends CI_Model {

    private $domain = "/";
    private $exp = 86400;
    private $path = "/";

    public function __construct(){ parent::__construct(); $this->domain = base_url(); }

    public function set($key,$val)
    {
        setcookie($key,$val,(time() + $this->exp),'/');
        $_COOKIE[$key] = $val;
    }
    public function has($key) { return (isset($_COOKIE[$key]) ? true : false); }
    public function get($key) { return ($this->has($key) ? get_cookie($key) : null); }
    public function rem($key, $prefix = ""){ 
        setcookie($key,false, -1, '/');
        unset($_COOKIE[$key]);
    }

}