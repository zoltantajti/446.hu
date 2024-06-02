<?php
class Markers extends CI_Model
{
    public function __construct(){ parent::__construct(); }

    public function getNew(){
        $result = $this->db->select('id,type,title')->from('markers')->where('authorized',0);
        return $result->get()->result_array();
    }
    public function countNew(){
        return $this->db->select('id')->from('markers')->where('authorized',0)->count_all_results();
    }
    public function countAll(){
        return $this->db->select('id')->from('markers')->count_all_results();
    }

    public function getList($filter = null){
        $result = $this->db->select('id,type,title')->from('markers')->where('authorized',1);
        if($filter != null){
            if($filter == "papagáj" || $filter == "papi" || $filter == "parrot"){
                $result->where('type', 'parrot');
            }elseif($filter == "mobil" || $filter == "kézi"){
                $result->where('type', 'mobile_radio');
            }elseif($filter == "desktop" || $filter == "asztali" || $filter == "fix"){
                $result->where('type', 'desktop_radio');
            }else{
                $result->like('title', $filter);
                $result->or_like('description', $filter);
                $result->or_like('place', $filter);
            };
        };
        return $result->get()->result_array();
    }

    public function add($p){
        $p['created_at'] = date("Y-m-d H:i:s");
        $p['created_user'] = $this->Sess->getChain('id','user');
        unset($p['submit']);
        $this->Db->insert("markers", $p);
        $this->Logs->make("MARKER::Create", $this->Sess->getChain('name','user') . " létrehozta " . $p['title'] . " markert.");
        $this->Msg->set("Sikeres létrehozás!");
        redirect('admin/markers');
    }
    public function edit($p){
        $old = $this->db->select('*')->from('markers')->where('id', $p['id'])->get()->result_array()[0];
        $p['modified_at'] = date("Y-m-d H:i:s");
        $p['modified_user'] = $this->Sess->getChain('id', 'user');
        unset($p['submit']);
        $this->Db->update("markers", $p, array("id" => $p['id']));
        $this->Logs->make("MARKER::Update", $this->Sess->getChain('name','user') . " módosította a " . $p['title'] . " markert.<br/>Régi adatok: " . json_encode($old));
        $this->Msg->set("Sikeres módosítás!");
        redirect('admin/markers');
    }
    public function delete($id){
        $old = $this->db->select('*')->from('markers')->where('id', $id)->get()->result_array()[0];
        $this->Db->delete("markers", array("id" => $id));
        $this->Logs->make("MARKER::Delete", $this->Sess->getChain('name','user') . " törölte a " . $old['title'] . " markert.<br/>Adatok: " . json_encode($old));
        $this->Msg->set("Sikeres törlés!");
        redirect('admin/markers');
    }
    public function allow($id){
        $old = $this->db->select('*')->from('markers')->where('id', $id)->get()->result_array()[0];
        $this->Db->update("markers", array("authorized" => 1, "active" => 1), array("id" => $id));
        $this->Logs->make("MARKER::Allow", $this->Sess->getChain('name','user') . " jóváhagyta a " . $old['title'] . " markert.<br/>Adatok: " . json_encode($old));
        $this->Msg->set("Sikeres jóváhagyás!");
        redirect('admin/markers');
    }

    public function getType($type){
        return Types::get($type);
    }

    /*Internal*/
    public function isOnMap($callsign){
        return ($this->db->select('id')->from('markers')->where('title',$callsign)->count_all_results() == 1) ? '<i class="fa-solid fa-check green"></i>' : '<i class="fa-solid fa-xmark red"></i>';
    }
    public function addedParrots($id){
        return $this->db->select('id')->from('markers')->where('type','parrot')->where('active',1)->where('created_user',$id)->count_all_results();
    }
    public function addedRadios($id){
        $radio = $this->db->select('id')->from('markers')->where('type','mobile_radio')->where('active',1)->where('created_user',$id)->count_all_results();
        $desktop = $this->db->select('id')->from('markers')->where('type','desktop_radio')->where('active',1)->where('created_user',$id)->count_all_results();
        return ($radio + $desktop);
    }
    public function addedStations($id){
        return $this->db->select('id')->from('markers')->where('type','station')->where('active',1)->where('created_user',$id)->count_all_results();
    }
    public function getActiveExpatriations(){
        return $this->db->select('lat,lon,title,createdAt,from,to,content,freq,ctcss,dcs')->from('markers_temp')->where('from <= ', date("Y-m-d H:i:s"))->where('to >= ', date("Y-m-d H:i:s"))->get()->result_array();
    }

    /*Admin*/
    public function getErrorList()
    {
        return $this->db->select('*')->from('markers_errors')->order_by('resolved','ASC')->order_by('createdAt','DESC')->get()->result_array();
    }
}

class Types {
    const parrot = 'Papagáj';
    const mobile_radio = 'Mobil rádió';
    const desktop_radio = 'Fix állomás';
    const station = 'Amatőr átjátszó';
    const unkown = 'Ismeretlen';

    public static function get($type){
        return constant('self::' . $type);
    }
}