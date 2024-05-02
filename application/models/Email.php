<?php
class Email extends CI_Model {
    protected $fa = "noreply@tajtizoltan.hu";
    protected $faName = "446PMR | Amatőr Rádió";
    protected $user = "teszt@tajtizoltan.hu";
    protected $pass = "Kyocera1995#";
    protected $host = "mail.tajtizoltan.hu";
    protected $port = 587;
    protected $cfg = array();

    public function __construct(){
        parent::__construct();
        $this->cfg = array(
            "protocol" => "smtp",
            "charset" => "UTF-8",
            "smtp_host" => $this->host,
            "smtp_user" => $this->user,
            "smtp_pass" => $this->pass,
            "smtp_port" => $this->port,
            "mailtype" => "html"
        );
    }

    public function sendOne($to, $dbID, $values = array()){
        $this->email->initialize($this->cfg);
        $this->email->from($this->fa, $this->faName);
        $this->email->to($to);
        $item = $this->db->select('subject,content')->from('emails')->where('alias',$dbID)->get()->result_array()[0];
        $this->email->subject($item['subject']);
        $this->email->message($this->formatLetter($item['content'], $values));
        if(!$this->email->send()){
            die($this->email->print_debugger());
        };
    }
    public function formatLetter($msg, $values)
    {
        foreach($values as $key => $value){
            $msg = str_replace("{$key}", $value, $msg);
        };
        return $msg;
    }

    /*Admin segment*/
    public function getList(){ return $this->db->select('id,name')->from('emails')->get()->result_array(); }
    public function add()
    {
        $p = $this->input->post();
        $data = array(
            "name" => $p['name'],
            "alias" => $p['alias'],
            "subject" => $p['subject'],
            "content" => $p['content']
        );
        $this->db->insert("emails", $data);
        $this->Msg->set("Sikeres hozzáadás!");
        $this->Logs->make("EMAIL::Create", $this->User->getName() . " létrehozott egy E-mail szöveget!");
        redirect("admin/emails/list");
    }
    public function update($id){
        $p = $this->input->post();
        $data = array(
            "name" => $p['name'],
            "alias" => $p['alias'],
            "subject" => $p['subject'],
            "content" => $p['content']
        );
        $this->db->set($data)->where('id',$id)->update('emails');
        $this->Msg->set("Sikeres módosítás!");
        $this->Logs->make("EMAIL::Modify", $this->User->getName() . " módosította a ".$data['name']." E-mail szöveget!");
        redirect("admin/emails/list");
    }
    public function delete($id){
        $sor = $this->db->select('*')->where('id',$id)->from('emails')->get()->result_array();
        $this->db->where('id',$id)->delete('emails');
        $this->Logs->make("DB::Delete", $this->User->getName() . " törölte az alábbi sort: Tábla: E-mails, ID:" . $id . ", Tartalom: " . json_encode($sor[0]));
        $this->Msg->set("Sikeres törlés!");
        redirect("admin/emails/list");
    }
}