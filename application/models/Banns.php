<?php
class Banns extends CI_Model {
    public function __construct(){
        parent::__construct();
    }

    public function check($ip){
        if($this->db->select("ip")->from('banns')->where('ip', $ip)->count_all_results() == 1){
            return true;
        }else{
            return false;
        }
    }
    public function add($ip){
        $this->db->insert("banns", array("ip"=>$ip));
        $this->Logs->make("BANN::Create", $this->User->getName() ." kitiltotta az " . $ip . " IP címet az oldalról!");
        $this->Msg->set("Sikeres kitiltás");
        redirect('admin/visitors');
    }
    public function remove($ip){
        $this->db->where('ip', $ip)->delete('banns');
        $this->Logs->make("BANN::Remove", $this->User->getName() ." feloldotta az " . $ip . " IP cím kitiltását!");
        $this->Msg->set("Sikeres feloldás");
        redirect('admin/visitors');
    }
    public function makeTrace($ip){
        $id = $_SESSION['__id'];
        $dateTime = date("Y-m-d H:i:s");
        $this->Logs->make("BANN::Visited", $ip . " meg szerette volna tekinteni az oldalt az alábbi SessionID használatával: " . $id);
        if($this->db->select("id")->from("banns_trace")->where("id",$id)->count_all_results() == 0){
            $this->Db->Insert("banns_trace", array("id" => $id, "ip" => $ip, "datetime" => $dateTime));
        }else{
            $this->Db->update("banns_trace", array("datetime" => $dateTime), array("id" => $id));
        }
        return $id;
    }
}