<?php

class Rest extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Banns');
		if($this->Banns->check($_SERVER['REMOTE_ADDR'])){
			die("A biztonsági házirend tiltja, hogy megtekintsd az oldalt!");
		};
    }
    public function getQSOs()
    {
        $qso = $this->db
                    ->select('*')
                    ->from('qso')
                    ->where('my_callsign', $this->Sess->getChain("callsign","user"))
                    ->or_where('rem_callsign', $this->Sess->getChain("callsign","user"))
                    ->where('verified',1)->where('status','approved')->or_where('status','pending')
                    ->get()
                    ->result_array();
        echo(json_encode($qso));
    }
    public function getCitiesByCounty($_county){
        $county = $this->db->select('code')->from('counties')->where('name',$_county)->get()->result_array()[0];
        $cities = $this->db->select('name')->from('cities')->where('county_code', $county['code'])->get()->result_array();
        echo json_encode($cities);
    }
    public function getMapMarkers()
    {
        $markers = $this->db->select('*')->from('markers')->where('active',1)->get()->result_array();
        foreach($markers as $k=>$marker){
            if($marker['type'] == "mobile_radio" || $marker['type'] == "desktop_radio"){
                if($this->db->select('id')->from('users')->where('callsign',$marker['title'])->count_all_results() == 1){
                    $markers[$k]['hasUser'] = true;
                    $markers[$k]['userID'] = $this->db->select('id')->from('users')->where('callsign',$marker['title'])->get()->result_array()[0]['id'];
                }else{
                    $markers[$k]['hasUser'] = false;
                    $markers[$k]['userID'] = -1;
                }
            }
        }
        echo(json_encode($markers));
    }

    public function checkUser($callsign){
        $callsign = base64_decode(str_replace('_','=', $callsign),true);
        if($this->db->select('id')->from('users')->where('callsign',urldecode($callsign))->count_all_results() == 1){
            echo $this->db->select('id')->from('users')->where('callsign',urldecode($callsign))->get()->result_array()[0]['id'];
        }else{
            echo "-1";
        }
    }

    public function getMapEvents()
    {
        /*$events = $this->db->select('events.id as id,
            event_markers.lat as lat,
            event_markers.lon as lon,
            event_markers.icon as icon,
	        event_markers.iconType as iType,
	        event_markers.color as iColor,
            events.title as title,
            events.seoLink as seoLink,
            events.shortDesc as description,
	        events.eventStart as start,
	        events.eventEnd as end')
            ->from('event_markers')
            ->join('events', 'events.id = event_markers.eventID', 'inner')
            ->get()
            ->result_array();
        echo(json_encode($events));*/
    }
    public function setState()
    {
        $p = $this->input->post();
        $this->Db->update("markers", array("parrotState"=>$p['state']), array("id" => $p['id']));
    }
    public function addMarker()
    {
        $p = $this->input->post();
        $p['active'] = 0;
        $p['created_at'] = date("Y-m-d H:i:s");
        $p['created_user'] = $_SESSION['user']['id'];
        $p['authorized'] = 0;
        $p['parrotState'] = 1;
        $p['parrotRadius'] = 10;
        $this->Db->insert("markers", $p);
        echo("200");
    }

    public function getEvents()
    {
        $events = $this->db->select("*")->from('events')->get()->result_array();
        echo(json_encode($events));
    }

    /*Imagemanager*/
    public function getFolder()
    {
        $f = scandir("./assets/uploads");
		$files = array();
		foreach($f as $k=>$v){
			if($v != "." && $v != ".." && $v != ".htaccess"){
				array_push($files, $v);
			};
		};
		echo(json_encode($files));
    }
    public function uploadFile(){
        error_reporting(0);
            $folder = "./assets/uploads/";
            $errors = array();
            $allowed = ["jpg","jpeg","png","gif","webp"];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $tmp = $_FILES['file']['tmp_name'];
            $ext = end(explode(".", $name));
            
            $uPath = $folder . basename($name);
            if(!in_array(strtolower($ext), $allowed)){
                echo(json_encode(array('success' => false, 'msg' => "Az $ext nem engedélyezett!")));
                exit();
            };
            if($size > 1250000){
                echo(json_encode(array('success' => false, 'msg' => "Az engedélyezett fájlméret 12 Mb!")));
                exit();
            };

            $didUpload = move_uploaded_file($tmp, $uPath);
            if($didUpload){
                echo(json_encode(array('success' => true, 'msg' => "A fájl sikeresen feltöltve!", 'data' => $name)));
                exit();
            }else{
                echo(json_encode(array('success' => false, 'msg' => "Valami hiba történt!")));
                exit();
            };
    }
    public function removeFile(){
        error_reporting(0);
        $p = $this->input->post();
        unlink($p['path']);
        echo(json_encode(array("success" => true)));
    }
    /*CRONS*/
    public function updateCSBook()
    {
        $this->load->model('NMHH');
        $this->NMHH->updateCallSignBook();
    }
}