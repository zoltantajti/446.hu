<?php
class Registration extends CI_Model {
    
    public function __construct(){ parent::__construct(); }

    public function doReg(){
        $arr = $_SESSION['registration'];
        unset($arr['nmhh']);

        $cDT = new DateTime();
        $cDT->modify('+48 hours');

        $arr['regIP'] = $_SERVER['REMOTE_ADDR'];
        $arr['regDate'] = date("Y-m-d H:i:s");
        $arr['active'] = 0;
        $arr['perm'] = 1;
        $arr['loginIP'] = null;
        $arr['loginDate'] = null;
        $arr['hash'] = uniqid();
        $arr['expired'] = $cDT->format('Y-m-d H:i:s');
        unset($arr['abotme']);
        
        $link = base_url() . "internal/activate/" . $arr["hash"];
        $this->Email->sendOne($arr['email'], "register_letter", array("name" => $arr['opname'], "link" => $link));

        $this->Db->insert("users",$arr);

        redirect("internal/register/5");
    }
}