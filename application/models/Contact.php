<?php
class Contact extends CI_Model {
    /*Public*/
    public function index(){
        $ret = '';
        $this->form_validation->set_rules("name","Név","trim|required");
        $this->form_validation->set_rules("email","E-mail","trim|required|valid_email");
        $this->form_validation->set_rules("subject","Tárgy","trim|required");
        $this->form_validation->set_rules("message","Üzenet","trim|required");
        if($this->form_validation->run()){
            $p = $this->input->post();
            unset($p['submit']);
            $p['msgHash'] = md5($p['email'].":".$p['subject'].':'.date("ymdhis") . ":" . $_SERVER['REMOTE_ADDR']);
            $ids = array(
                'msgHash' => $p['msgHash'],
                'name' => $p['name'],
                'email' => $p['email'],
                'subject' => $p['subject'],
                'new' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'assumed' => 0
            );
            $this->db->insert("conversation_ids", $ids);
            $conv = array(
                'hash' => $p['msgHash'],
                'direction' => 'UserToAdmin',
                'time' => date("Y-m-d H:i:s"),
                'message' => $p['message'],
                'new' => 1
            );
            $this->db->insert("conversations", $conv);
            echo('<script>sendMessage("'.$p['name'].' épp most küldött üzenetet!", "message-from-user-to-admin", "warning");</script>');
            $this->Msg->set("Sikeres üzenetküldés! Hamarosan válaszolni fogunk!");
        };

        $ret = '<div class="row"><div class="col-md-6">' . 
            $this->Msg->print() . 
            form_open('', 'method="POST"') . 
            form_label('Név', 'name') . 
            form_input('name', '', 'class="form-control mb-3"') . 
            form_label('E-mail cím', 'email') . 
            form_input('email', '', 'class="form-control mb-3"') . 
            form_label('Tárgy', 'subject') . 
            form_input('subject', '', 'class="form-control mb-3"') . 
            form_label('Üzenet', 'message') . 
            form_textarea('message', '', 'class="form-control mb-3"') . 
            form_submit('submit', 'Küldés', 'class="btn btn-success"') . 
        '</div><div class="col-md-6 text-center">' . 
            $this->Seo->getSeo('contactText') . 
        '</div></div>';
        return $ret;
    }

    /*ADMN*/
    public function countNew(){
        return $this->db->select('id')->from('conversation_ids')->where('haveUnreaded',1)->count_all_results();
    }
    public function countNewMy() { }
    public function getList(){
        return $this->db->select('*')->from('conversation_ids')->order_by('haveUnreaded', 'DESC')->order_by('lastMessage', 'DESC')->get()->result_array();
    }
    public function createIcon($haveNew){
        return ($haveNew == 1) ? '<i class="fa-solid fa-envelope text-danger"></i>' : '<i class="fa-solid fa-envelope-open"></i>';
    }
    public function getItem($id){
        $ret = array(
            'conv' => $this->db->select('*')->from('conversation_ids')->where('id',$id)->get()->result_array()[0],
            'items' => $this->db->select('*')->from('conversations')->where('convID',$id)->order_by('time', 'ASC')->get()->result_array()
        );
        return $ret;
    }
    public function msgETA($dt){
        $ma = date("Y-m-d H:i:s");
        $ts1 = strtotime($dt);
        $ts2 = strtotime($ma);
        $diff = abs($ts2 - $ts1);
        if($diff < 3600){
            return number_format(abs($diff / 60),0) . " perce";
        }else if($diff >= 3600 && $diff < 86400){
            return number_format(abs($diff / (60 * 60)),0) . " órája";
        }else if($diff >= 86400 && $diff < 172800){
            return "tegnap";
        }else if($diff >= 172800 && $diff < 259200){
            return "tegnapelőtt";
        }else if($diff >= 259200 && $diff < 604800){
            return number_format(abs($diff / (60 * 60 * 24)), 0) . " napja";
        }else{
            return $this->beautyDate($dt);
        };
    }

    public function beautyDate($date){
        $d = explode(" ", $date);
        $date = explode("-", $d[0]);
        $time = $d[1];

        return $date[0] . ". " . $this->honapok[$date[1]] . " " . $date[2] . " " . $time;
    }

    
    public $honapok = array(
        "01" => "január",
        "02" => "február",
        "03" => "március",
        "04" => "április",
        "05" => "május",
        "06" => "június",
        "07" => "július",
        "08" => "augusztus",
        "09" => "szeptember",
        "10" => "október",
        "11" => "november",
        "12" => "december",
    );
    
    
    public function reply($id){
        $conv = $this->db->select('email,subject')->from('conversation_ids')->where('id',$id)->get()->result_array()[0];
        $p = $this->input->post();
        $data = array(
            'convID' => $id,
            'message' => $p['message'],
            'attachments' => null,
            'direction' => 'adminToUser',
            'time' => date("Y-m-d H:i:s")
        );
        $this->Db->insert('conversations', $data);
        $this->Email->sendBlank($conv['email'],$conv['subject'],$p['message']);
        redirect('admin/conversations/read/' . $id);
    }
    
    
    
    
    
    
    
    /*public function countNewMy(){
        $r = 0;
        foreach($this->db->select('msgHash')->from('conversation_ids')->where('assumed',1)->where('assumed_userID',$this->User->getID())->get()->result_array() as $k=>$v){
            $r = $r + $this->db->select('msgHash')->from('conversations')->where('hash',$v['msgHash'])->where('new',1)->count_all_results();
        };
        return $r;
    }
    public function hasUnreaded($hash){
        $count = $this->db->select('msgHash')->from('conversations')->where('hash',$hash)->where('new',1)->count_all_results();
        return ($count == 0) ? false : true;
    }
    public function list(){
        return $this->db->select('msgHash,name,subject,created_at')->from('conversation_ids')->where('new',1)->order_by('created_at','desc')->get()->result_array();
    }
    public function listMy(){
        return $this->db->select('msgHash,name,subject,created_at')->from('conversation_ids')->where('new',0)->where('assumed',1)->where('assumed_userID',$this->User->getID())->order_by('created_at','desc')->get()->result_array();
    }
    public function assume($hash){
        $data = array(
            'new' => 0,
            'assumed' => 1,
            'assumed_userID' => $this->User->getID(),
            'assumed_date' => date('Y-m-d H:i:s')
        );
        $this->db->set($data)->where('msgHash',$hash)->update('conversation_ids');
        return true;
    }
    public function getConv($hash){
        return $this->db->select('*')->from('conversations')->where('hash',$hash)->get()->result_array();
    }
    public function getHead($hash){
        return $this->db->select('*')->from('conversation_ids')->where('msgHash',$hash)->get()->result_array()[0];
    }
    public function read($id){
        $data = array("new"=>0);
        $this->db->set($data)->where('hash',$id)->update('conversations');
    }
    public function reply($hash){
        $p = $this->input->post();
        $data = array(
            "hash" => $hash,
            "direction" => "AdminToUser",
            "time" => date("Y-m-d H:i:s"),
            "message" => $p['message'],
            "new" => 0
        );
        $this->db->insert("conversations", $data);

        $addr = $this->db->select("email")->from("conversation_ids")->where("msgHash",$hash)->get()->result_array()[0]["email"];
        $subj = "#".$hash." azonosítójú üzenetváltás";
        $msg = $p['message'] . "<hr/><b>Amennyiben válaszolni szeretnél az üzenetünkre, erre az üzenetre válaszolva tedd meg, anélkül, hogy módosítanád az üzenet tárgyát! Köszönjük!</b>";
        $this->Mail->send($addr, $subj, $msg);

        $this->Msg->set("Üzenet elküldve!");
        return true;
    }*/
}