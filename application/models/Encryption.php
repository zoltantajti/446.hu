<?php
class Encryption extends CI_Model {
    private $preSalt = "www.446.hu/";
    private $salt = "#aA123456#%*";

    public function __construct(){ parent::__construct(); }
    public function hash($str, $algo = "SHA512/256"){
        switch($algo){
            case "SHA512/256": return hash("SHA512/256", $this->preSalt . $str . $this->salt, false, array()); break;
            default: return base64_encode($str); break;
        }
    }
    public function b64($str, $type = "encode"){
        switch($type){
            case "encode": return base64_encode($str); break;
            case "decode": return base64_decode($str, true); break;
        };
    }
}