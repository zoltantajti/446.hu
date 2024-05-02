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
    
    public function getMapMarkers()
    {
        $markers = $this->db->select('*')->from('markers')->where('active',1)->get()->result_array();
        echo(json_encode($markers));
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