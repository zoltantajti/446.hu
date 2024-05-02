<?php
class Notifications extends CI_Model {
    public function __construct()
    {
        parent::__construct();


    }

    public function collectNotificatable()
    {
        $notifiactions = 0;
        
        $myCS = $this->Sess->getChain("callsign", "user");
        $notifiactions += $this->db->select("id")->from('qso')->where('rem_callsign',$myCS)->where('verified',0)->where('status !=','denied')->count_all_results();

        return $notifiactions;
    }

    public function getAllNotification()
    {
        $notifs = array();

        $myCS = $this->Sess->getChain("callsign", "user");
        $logs = $this->db->select('id,my_callsign,createdAt')->from('qso')->where('rem_callsign',$myCS)->where('verified',0)->where('status !=','denied')->get()->result_array();
        foreach($logs as $log){
            $arr = array(
                "id" => $log['id'],
                "date" => $log['createdAt'],
                "type" => "log",
                "msg" => "<b>" . $log['my_callsign'] . "</b> megjelölt téged egy logban!"
            );
            array_push($notifs, $arr);
        };
        
        return $notifs;
    }

    public function showNotif($item){
        $html = '<li><div class="notifItem row"><div class="col-md-12">';
        $html.= $item['msg'] . "<br/>";
        $html.='<div class="notif-meta"><i class="fa fa-fw fa-calendar-alt"></i> ' . str_replace('-','.',$item['date']) . "</div>";
        if($item['type'] == "log"){
            $html.='<div class="btn-group" role="group" aria-label="Basic example">';
            $html.='<a href="javascript:;" onClick="(window.confirm(\'El akarod fogadni?\') ? document.location.assign(\'internal/qso/allow/'.$item['id'].'/'.str_replace("=","_",base64_encode(uri_string())).'\') : \'\')" class="btn btn-success"><i class="fa fa-fw fa-check"></i> Elfogadom</a>';
            $html.='<a href="javascript:;" onClick="(window.confirm(\'El akarod utasítani?\') ? document.location.assign(\'internal/qso/deny/'.$item['id'].'/'.str_replace("=","_",base64_encode(uri_string())).'\') : \'\')" class="btn btn-danger"><i class="fa fa-fw fa-times"></i> Elutasítom</a>';
            $html.='</div>';
        };
        $html.= '<hr/></div></div></li>';
        return $html;
    }
}