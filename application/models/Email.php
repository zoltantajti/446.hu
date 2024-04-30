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
}