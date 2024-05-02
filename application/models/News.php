<?php
class News extends CI_Model {
    public function __construct() { parent::__construct(); }

    public function getList(){
        return $this->db->select('id,title,createdAt')->from('news')->order_by('createdAt','desc')->get()->result_array();
    }

    public function add(){
        $p = $this->input->post();
        unset($p['submit']);
        $p['createdAt'] = date("Y-m-d H:i:s");
        $this->Db->insert("news", $p);
        $this->Msg->set("Sikeres hozzáadás!");
        $this->Logs->make("NEWS::Create", $this->User->getName() . " létrehozta a " . $p['title'] . " hírt!");
        redirect("admin/news");
    }
    public function update($id)
    {
        $old = $this->db->select('*')->from('news')->where('id',$id)->get()->result_array()[0];
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->update("news", $p, array("id" => $id));
        $this->Msg->set("Sikeres módosítás!");
        $this->Logs->make("NEWS::Modify", $this->User->getName() . " módosította a " . $p['title'] . " hírt!<br/>Régi adatok: " . json_encode($old) . "<br/> Új adatok: " . json_encode($p));
        redirect("admin/news");
    }
    public function delete($id){
        $old = $this->db->select('*')->from('news')->where('id',$id)->get()->result_array()[0];
        $this->Db->delete("news", array("id" => $id));
        $this->Msg->set("Sikeres törlés!");
        $this->Logs->make("NEWS::Delete", $this->User->getName() . " tötölte a " . $old['title'] . " hírt!<br/>Adatok: " . json_encode($old) . "");
        redirect("admin/news");
    }
}