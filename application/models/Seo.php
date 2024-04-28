<?php
class Seo extends CI_Model
{
    public $description = "";
    public $keywords = "";

    public function __construct(){ parent::__construct(); }

    public function get($mit, $overWrite = null){
        if($overWrite == null){
            if($this->db->select("value")->from('seo')->where('_key',$mit)->count_all_results() > 0){
                return $this->db->select("value")->from('seo')->where('_key',$mit)->get()->result_array()[0]['value'];
            }else{
                return null;
            }
        }else{
            return $overWrite;
        }
    }
}