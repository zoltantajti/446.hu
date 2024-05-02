<?php
class Visitor extends CI_Model {

    protected $api = "https://api.ipgeolocation.io/ipgeo?apiKey={apikey}&ip={ip}";
    protected $key= "7a265e074848497eac843fbd2abe5b44";

    public function __construct(){
        parent::__construct();

        if(!in_array("admin", $this->uri->segment_array())){
            $ip  = $_SERVER['REMOTE_ADDR'];
            $sID = $this->Sess->get('__id');
            if($this->db->select('ipaddr')->from('visitors')->where('ipaddr', $ip)->count_all_results() == 0){
                $this->newVisitor($ip, $sID);
            }else{
                if($this->db->select('ipaddr')->from('visitors')->where('ipaddr', $ip)->where('lastSessionID', $sID)->count_all_results() == 0){
                    $this->updateVisitor($ip, $sID);
                };
            }
        };
    }
    
    public function countUnique(){
        return $this->db->select('ipaddr')->from('visitors')->count_all_results();
    }
    public function countAll(){
        $data = $this->db->select('SUM(visits) as visits')->get('visitors')->result();
        return $data[0]->visits;
    }

    private function newVisitor($ip, $sID){
        $now = date("Y-m-d H:i:s");
        $data = array(
            'ipaddr' => $ip,
            'visits' => 1,
            'lastSessionID' => $sID,
            'lastVisit' => $now,
            'visitsDate' => json_encode(array($now))
        );
        $this->db->insert('visitors', $data);
    }
    private function updateVisitor($ip, $sID){
        $visitor = $this->db->select('visits,visitsDate')->where('ipaddr', $ip)->get('visitors')->result()[0];
        $visitor->visitsDate = json_decode($visitor->visitsDate, true);
        $now = date("Y-m-d H:i:s");
        array_push($visitor->visitsDate, $now);
        $visitor->visitsDate = array_reverse($visitor->visitsDate);
        $data = array(
            'visits' => $visitor->visits + 1,
            'lastSessionID' => $sID,
            'lastVisit' => $now,
            'visitsDate' => json_encode($visitor->visitsDate)
        );
        $this->db->where('ipaddr', $ip);
        $this->db->update('visitors', $data);
    }

    /*ADMIN*/
    public function list(){
        return $this->db->select('ipaddr,visits,lastVisit')->from('visitors')->order_by('lastVisit','DESC')->get()->result_array();
    }
    public function get($ip){
        return $this->db->select('*')->from('visitors')->where('ipaddr',$ip)->get()->result_array()[0];
    }
    public function getLocation($ip){
        $url = str_replace(array("{apikey}","{ip}"),array($this->key, $ip), $this->api);
        @$string = file_get_contents($url);
        @$geo = json_decode($string,true);
        if(isset($geo) && !isset($geo['message'])){
            return $geo;
        }else{
            return array("message" => "Hiba történt az API hívása során! Kérlek tájékoztasd a rendszergazdát!");
        }
    }
    public function getFlag($ip){
        try{
            $url = str_replace(array("{apikey}","{ip}"),array($this->key, $ip), $this->api);
            @$string = file_get_contents($url);
            @$geo = json_decode($string,true);
            if(isset($geo) && !isset($geo['message'])){
                return '<img src="'.$geo["country_flag"].'" width="32" data-bs-toggle="tooltip" data-bs-title="'.$geo['city'].', '.$geo['country_name_official'] . '"/>';
            }else{
                return '';
            }
        }catch(Exception $e){ return ''; };
    }
}