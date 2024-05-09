<?php 
class ImapReader extends CI_Model{
    public $conn;
    private $inbox;
    private $msg_cnt;
    private $server = 'mail.446.hu';
    private $user = 'info@446.hu';
    private $pass = 'Kyocera1995#%';
    private $port = 143;

    function __construct(){
        //$this->connect();
        //$this->inbox();
        //$this->getInbox();
    }
    function close(){
        $this->inbox = array();
        $this->msg_cnt = 0;
        imap_close($this->conn);
    }
    function connect(){
        $this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
    }
    function inbox(){
        $this->msg_cnt = imap_num_msg($this->conn);
        $in = array();
        for($i = 1; $i <= $this->msg_cnt; $i++){
            $in[] = array(
                'index'     => $i,
                'header'    => imap_headerinfo($this->conn, $i),
                'body'      => imap_body($this->conn, $i),
                'structure' => imap_fetchstructure($this->conn, $i)
            );
        }
        $this->inbox = $in;
    }
    private function getMessage($msg)
    {
        $msg = quoted_printable_decode($msg);
        $dom = new DOMDocument();
        @$dom->loadHTML(iconv("UTF-8","ISO-8859-1//TRANSLIT", $msg));
        $div = $dom->getElementsByTagName("div")->item(0);
        $string = $div->nodeValue;
        $msg = $string;
        return $msg;
    }
    private function getAttachment($index, $key){
        $att = quoted_printable_decode(imap_fetchbody($this->conn, $index, $key));
        $att = str_replace(array("\n","\r"), array("",""), $att);
        $fn = "./attachments/" . uniqid("att_", true) . ".bin";
        $f = fopen($fn, "w");
        fwrite($f, $att);
        fclose($f);
        return $fn;
    }
    function getInbox(){
        $this->connect();
        $this->inbox();
        foreach($this->inbox as $index => $inbox){
            if($inbox['header']->Unseen == "U"){
                $index++;
                if(isset($inbox['structure']->parts) && count($inbox['structure']->parts)){
                    $msg = "";
                    $atts = array();
                    foreach($inbox['structure']->parts as $key=>$part){
                        if ($part->ifdparameters && $part->disposition == "attachment") {
                            array_push($atts, $this->getAttachment($index, $key + 1));
                        }else{
                            $msg = $this->getMessage($inbox['body']);
                        };
                    };
                    $sender = $inbox['header']->from[0]->mailbox . "@" . $inbox['header']->from[0]->host;
                    $subject = str_replace(array('_','Re: '),array(' ', ''), mb_decode_mimeheader($inbox["header"]->subject));
                    if($this->db->select('id')->from('conversation_ids')->where('email',$sender)->where('subject',$subject)->count_all_results() == 0){
                        $this->Db->insert('conversation_ids', array('email'=>$sender,'subject'=>$subject,'createdAt'=>date("Y-m-d H:i:s"),'lastMessage'=>date("Y-m-d H:i:s"),"haveUnreaded"=>1));
                    };
                    $id = $this->db->select('id')->from('conversation_ids')->where('email',$sender)->where('subject',$subject)->get()->result_array()[0]['id'];
                    
                    $data = array(
                        'convID' => $id,
                        'message' => $msg,
                        'attachments' => json_encode($atts),
                        'direction' => "UserToAdmin",
                        'time' => date("Y-m-d H:i:s")
                    );
                    $this->Db->insert("conversations", $data);
                };
            };
        };
    }
}