<?php
class Logs extends CI_Model {
    public function make($type, $msg){
        $data = array(
            'date' => date("Y-m-d H:i:s"),
            'logType' => $type,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'msg' => $msg
        );
        $this->db->insert('logs', $data);
    }

    public function getList($cond, $limit = 25, $offset = 0){
        if($cond[0] == "all"){
            return $this->db->select('*')->from('logs')->order_by('date','DESC')->limit($limit,$offset)->get()->result_array();
        }else{
            return $this->db->select('*')->from('logs')->order_by('date','DESC')->where($cond[0],$cond[1])->limit($limit,$offset)->get()->result_array();
        }
    }
    public function count(){
        //HA meglesz a lastLogin modifikáció, akkor itt számoljuk meg az új naplófájlokat!!!
    }
    public function get($id){
        return $this->db->select('*')->from('logs')->where('id',$id)->get()->result_array()[0];
    }
    public function getTypeName($type){
        $ret = '';
        switch($type){
            case "Login::Success": $ret = 'Sikeres bejelentkezés'; break;
            case "Login::Fail": $ret = 'Sikertelen bejelentkezés'; break;
            case "Logout::Success": $ret = 'Sikeres kijelentkezés'; break;
            case "DB::Delete": $ret = 'Adatbázisból való törlés'; break;
            case "SETTINGS::Update": $ret = 'Beállítás módosítása'; break;
            case "USER::Create": $ret = 'Új felhasználó'; break;
            case "USER::Modify": $ret = 'Felhasználó módosítás'; break;
            default: $ret = $type; break;
        };
        return $ret;
    }
}