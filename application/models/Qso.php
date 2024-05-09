<?php
class Qso extends CI_Model 
{
    public function __construct(){ parent::__construct(); }

    public function getQTHByUserID($id){
        $qth = $this->db->select('country,county,city,address')->from('users')->where('id', $id)->get()->result_array()[0];
        return $qth;
    }
    public function getList()
    {
        $cs = $this->Sess->getChain("callsign","user");
        $qso = $this->db->select('id,date,time,my_callsign,rem_callsign,distance,status,suffix,mode,parrot_name')
            ->from('qso')
            ->where("(my_callsign = '".$cs."' AND status != 'denied') OR (rem_callsign = '".$cs."' AND status != 'denied')")
            ->order_by('verified', 'ASC')
            ->order_by('date','DESC')
            ->order_by('time','DESC')
            ->get()
            ->result_array();
        return $qso;
    }

    public function countAllQso($callsign){
        $in = $this->db->select('id')->from('qso')->where('rem_callsign',$callsign)->count_all_results();
        $out = $this->db->select('id')->from('qso')->where('my_callsign',$callsign)->count_all_results();
        return "(" . $in . " / " . $out . ")";
    }
    public function getQSOByUser($callsign, $limit = 5){
        return $this->db
            ->select('my_callsign,rem_callsign,distance,status,suffix,mode')
            ->from('qso')
            ->where('my_callsign', $callsign)
            ->or_where('rem_callsign', $callsign)
            ->limit(5, 0)
            ->get()
            ->result_array();
    }

    public function add() {
        $p = $this->input->post();
        unset($p['submit'], $p['my_opName']);
        $p['createdAt'] = date("Y-m-d H:i:s");
        $p['status'] = 'pending';
        $p['verified'] = 0;
        $p['verifiedAt'] = null;
        
        if($this->db->select('id')->from('users')->where('callsign', $p['rem_callsign'])->count_all_results() == 1){
            $user = $this->db->select('email,opName,callsign')->from('users')->where('callsign', $p['rem_callsign'])->get()->result_array()[0];
            $name = $user['opName'] . '<i>(' . $user['callsign'] . ')</i>';
            $callsign = $p['my_callsign'];
            $this->Email->sendOne($user['email'], 'qso_request', array('name' => $name, 'callsign' => $callsign));
        };

        $this->Db->insert("qso", $p);
        $this->Msg->set("Log sikeresen leadva!", "success");
        redirect('internal/qso/add');
    }

    public function formatStatus($status){
        switch($status){
            case "pending": return '<div class="badge text-bg-warning">Függőben</div>'; break;
            case "approved": return '<div class="badge text-bg-success">Elfogadva</div>'; break;
            case "denied": return '<div class="badge text-bg-danger">Elutasítva</div>'; break;
        }
    }
    public function formatSuffix($item){
        switch($item){
            case "/": return 'Stabil'; break;
            case "/P": return 'Kitelepült'; break;
            case "/M": return 'Mobil'; break;
            case "/SM": return "Statikus mobil"; break;
        }
    }
    public function formatMode($mode){
        switch($mode){
            case "/D": return "Közvetlen"; break;
            case "/P": return "Papagájon át"; break;
            case "/A": return "Amatőr állomáson át"; break;
        }
    }
}