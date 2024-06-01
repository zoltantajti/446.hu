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
    public function getMapMarkers($state = "public")
    {
        $out = array();
        foreach($this->db->select('*')->from('markers')->where('active',1)->where('type','parrot')->or_where('type','station')->get()->result_array() as $v){
            array_push($out, $v);
        };
        if($state == "public"){
			foreach($this->db->select('id,callsign,country,county,city,address,markerDesc,markerIcon')->from('users')->where('allowOnPublicMap',1)->get()->result_array() as $v){
				if($v['county'] == 'Budapest'){ $city = "Budapest"; }else{ $city = $v['city']; };
                if(file_exists('./assets/map/' . md5($v['callsign']) . '.bin')){
					$marker = json_decode(file_get_contents('./assets/map/' . md5($v['callsign']) . '.bin'),true);
					if($marker['address']['city'] != $v['city'] || $marker['address']['addr'] != $v['address']){
						$referer = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&country='.$this->Misc->getCountryName($v['country']).'&county='.urlencode($this->Misc->nominatim($v['county'])).'&city='.urlencode($city).'&street=' . urlencode($this->Misc->nominatim($v['address']));
						$opts = array('http' => array('header' => array('Referer: ' . $referer . "\r\n")));
						$ctx = stream_context_create($opts);
						$geo = json_decode(file_get_contents($referer, false, $ctx),true);
						$marker['lat'] = $geo[0]['lat'];
						$marker['lon'] = $geo[0]['lon'];
						$marker['address'] = array(
							"country" => $v['country'],
							"county" => $v['county'],
							"city" => $v['city'],
							"addr" => $v['address']
						);
						$f = fopen('./assets/map/' . md5($v['callsign']) . '.bin', "w+");
						fwrite($f, json_encode($marker));
						fclose($f);
					}
					array_push($out, $marker);
				}else{
					$referer = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&country='.$this->Misc->getCountryName($v['country']).'&county='.urlencode($this->Misc->nominatim($v['county'])).'&city='.urlencode($city).'&street=' . urlencode($this->Misc->nominatim($v['address']));
					$opts = array('http' => array('header' => array('Referer: ' . $referer . "\r\n")));
					$ctx = stream_context_create($opts);
					$geo = json_decode(file_get_contents($referer, false, $ctx),true);
					$marker = array(
						"lat" => $geo[0]['lat'],
						"lon" => $geo[0]['lon'],
						"address" => array(
							"country" => $v['country'],
							"county" => $v['county'],
							"city" => $v['city'],
							"addr" => $v['address']
						),
						"type" => ($v['markerIcon'] == null) ? "mobile_radio" : $v['markerIcon'],
						"title" => $v['callsign'],
						"description" => $v['markerDesc'],
						"active" => 1,
						"parrotState" => null,
						"parrotRadios" => null,
						"authorized" => 1,
						"hasUser" => true,
						"userID" => $v['id']
					);
					array_push($out,$marker);
					$f = fopen('./assets/map/' . md5($v['callsign']) . '.bin', "w+");
					fwrite($f, json_encode($marker));
					fclose($f);
				};				
            };
        }elseif($state == "internal"){
            foreach($this->db->select('id,callsign,country,county,city,address,markerDesc,markerIcon')->from('users')->where('allowOnInternalMap',1)->get()->result_array() as $v){
				if($v['county'] == 'Budapest'){ $city = "Budapest"; }else{ $city = $v['city']; };
                if(file_exists('./assets/map/' . md5($v['callsign']) . '.bin')){
					$marker = json_decode(file_get_contents('./assets/map/' . md5($v['callsign']) . '.bin'),true);
					if($marker['address']['city'] != $v['city'] || $marker['address']['addr'] != $v['address']){
						$referer = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&country='.$this->Misc->getCountryName($v['country']).'&county='.urlencode($this->Misc->nominatim($v['county'])).'&city='.urlencode($city).'&street=' . urlencode($this->Misc->nominatim($v['address']));
						$opts = array('http' => array('header' => array('Referer: ' . $referer . "\r\n")));
						$ctx = stream_context_create($opts);
						$geo = json_decode(file_get_contents($referer, false, $ctx),true);
						$marker['lat'] = $geo[0]['lat'];
						$marker['lon'] = $geo[0]['lon'];
						$marker['address'] = array(
							"country" => $v['country'],
							"county" => $v['county'],
							"city" => $v['city'],
							"addr" => $v['address']
						);
						$f = fopen('./assets/map/' . md5($v['callsign']) . '.bin', "w+");
						fwrite($f, json_encode($marker));
						fclose($f);
					}
					array_push($out, $marker);
				}else{
					$referer = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&country='.$this->Misc->getCountryName($v['country']).'&county='.urlencode($this->Misc->nominatim($v['county'])).'&city='.urlencode($city).'&street=' . urlencode($this->Misc->nominatim($v['address']));
					$opts = array('http' => array('header' => array('Referer: ' . $referer . "\r\n")));
					$ctx = stream_context_create($opts);
					$geo = json_decode(file_get_contents($referer, false, $ctx),true);
					$marker = array(
						"lat" => $geo[0]['lat'],
						"lon" => $geo[0]['lon'],
						"address" => array(
							"country" => $v['country'],
							"county" => $v['county'],
							"city" => $v['city'],
							"addr" => $v['address']
						),
						"type" => ($v['markerIcon'] == null) ? "mobile_radio" : $v['markerIcon'],
						"title" => $v['callsign'],
						"description" => $v['markerDesc'],
						"active" => 1,
						"parrotState" => null,
						"parrotRadios" => null,
						"authorized" => 1,
						"hasUser" => true,
						"userID" => $v['id']
					);
					array_push($out,$marker);
					$f = fopen('./assets/map/' . md5($v['callsign']) . '.bin', "w+");
					fwrite($f, json_encode($marker));
					fclose($f);
				};				
            };
        };
        echo(json_encode($out));
    }
    public function getMapTempMarkers()
    {
        $next = 7;
        $date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " + " . $next . " days"));
        $arr = $this->db->select('title,lat,lon,from,to,content,createdAt,freq,ctcss,dcs')->from('markers_temp')->where('from >=', $date)->or_where('to >= ', date("Y-m-d H:i:s"))->get()->result_array();
        echo(json_encode($arr));
    }
    public function getMarkerById($id){
        $marker = $this->db->select('lat,lon,type,title,description')->from('markers')->where('id',$id)->get()->result_array()[0];
        echo json_encode($marker);
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
        $events = $this->db->select('events.id as id,
            event_markers.lat as lat,
            event_markers.lon as lon,
            event_markers.icon as icon,
	        event_markers.iconType as iType,
	        event_markers.color as iColor,
            event_markers.zone as zone,
            event_markers.zoneColor as zoneColor,
            events.title as title,
            events.seoLink as seoLink,
            events.shortDesc as description,
	        events.eventStart as start,
	        events.eventEnd as end')
            ->from('event_markers')
            ->join('events', 'events.id = event_markers.eventID', 'inner')
            ->where('events.eventStart <= ', date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " + 21 day")))
            ->or_where('events.eventEnd >= ', date("Y-m-d H:i:s"))
            ->get()
            ->result_array();
        echo(json_encode($events));
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
    public function addTempMarker()
    {
        $p = $this->input->post();
        $p['createdAt'] = $_SESSION['user']['id'];
        $this->Db->insert("markers_temp", $p);
        echo("200");
    }
    public function updateMarker()
    {
        $p = $this->input->post();
        $id = $p['id'];
        unset($p['id']);
        $this->Db->update("markers", $p, array("id"=>$id));
        echo("200");
    }
    public function getEvents()
    {
        $events = $this->db->select("*")->from('events')->get()->result_array();
        echo(json_encode($events));
    }

    public function addRestZone()
    {
        $p = $this->input->post();
        $this->Db->insert("markers_restareas", $p);
        echo("200");
    }
    public function getRestZones()
    {
        $arr = $this->db->select('*')->from('markers_restareas')->where('active',1)->get()->result_array();
        echo(json_encode($arr));
    }
    
    /*Frekvenciák*/
    public function addFreq()
    {
        $p = $this->input->post();
        $tbl = $p['tbl'];
        $p['ctcs'] = $p['ctcss'];
        unset($p['id'],$p['tbl'],$p['ctcss']);
        $this->db->set('name', $p['name']);
        $this->db->set('freq', $p['freq']);
        $this->db->set('ctcs', ($p['ctcs'] == null) ? null : $p['ctcs']);
        $this->db->set('dcs', ($p['dcs'] == null) ? null : $p['dcs']);
        $this->db->set('duplex', ($p['duplex'] == null) ? "off" : $p['duplex']);
        $this->db->set('offset', ($p['offset'] == null) ? null : $p['offset']);
        $this->db->set('comment', ($p['comment'] == null) ? null : $p['comment']);
        $this->db->set('place', $p['place']);
        $this->db->set('type', $p['type']);
        $this->db->insert($tbl);
        $this->Logs->make("FREQ:update", $this->Sess->getChain("callsign","user") . " létrehozta a " . $p['freq'] . " <i>(" . $p['name'] . ")</i> gumifül frekvenciát!" );
        echo("200");
    }
    public function updateFreq() {
        $p = $this->input->post();
        $id = $p['id'];
        $tbl = $p['tbl'];
        $p['ctcs'] = $p['ctcss'];
        unset($p['tbl'],$p['ctcss']);
        $this->db->set('name', $p['name']);
        $this->db->set('freq', $p['freq']);
        $this->db->set('ctcs', ($p['ctcs'] == null) ? null : $p['ctcs']);
        $this->db->set('dcs', ($p['dcs'] == null) ? null : $p['dcs']);
        $this->db->set('duplex', ($p['duplex'] == null) ? "off" : $p['duplex']);
        $this->db->set('offset', ($p['offset'] == null) ? null : $p['offset']);
        $this->db->set('comment', ($p['comment'] == null) ? null : $p['comment']);
        $this->db->set('place', $p['place']);
        $this->db->set('type', $p['type']);
        $this->db->where('id', $id);
        $this->db->update($tbl);
        $this->Logs->make("FREQ:update", $this->Sess->getChain("callsign","user") . " módosította a " . $id . " <i>(" . $p['name'] . ")</i> gumifül frekvenciát!" );
        echo("200");
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

    /*TinyMCE*/
    public function getTemplates() {
        echo json_encode($this->db->select("*")->from("templates")->get()->result_array());
    }

    /*CRONS*/
    public function updateCSBook()
    {
        $this->load->model('NMHH');
        $this->NMHH->updateCallSignBook();
    }

    /*Imap*/
    public function readImap()
    {
        $this->ImapReader->getInbox();
    }

    /*Profile*/
    public function getUserAddressByID($id){
        echo json_encode($this->db->select('country,county,city,address')->from('users')->where('id',$id)->get()->result_array()[0]);
    }


    /*Temp*/
    /*public function updateGEO(){
        if($_SERVER['SERVER_NAME'] == "local.446.hu" && $_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
            foreach($this->db->select('ipaddr')->from('visitors')->limit(120,822)->get()->result_array() as $row){
                $ip = $row['ipaddr'];
                if($ip != '::1' && $ip != '127.0.0.1'){
                    $geo = $this->Visitor->getGeo($ip);
                    $this->db->set('geo', json_encode($geo));
                    $this->db->where('ipaddr', $ip);
                    $this->db->update('visitors');
                };                
            }
        }else{
            die('remote host not allowed!');
        };
        
    }*/
}