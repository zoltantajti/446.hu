<?php
class Events extends CI_Model {
    public function __construct() { parent::__construct(); }

    public function getList(){
        return $this->db->select('id,title,eventStart')->from('events')->order_by('eventStart', 'desc')->get()->result_array();
    }
    public function hasEventOnMap($id){
        return $this->db->select('id')->from('event_markers')->where('eventID',$id)->count_all_results();
    }

    public function add()
    {
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->insert("events",$p);
        $this->Msg->set("Sikeres hozzáadás!");
        $this->Logs->make("EVENT::Create", $this->User->getName() . " létrehozta a " . $p['title'] . " eseményt!");
        redirect("admin/events");
    }
    public function update($id)
    {
        $old = $this->db->select('*')->from('events')->where('id',$id)->get()->result_array()[0];
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->update("events", $p, array("id" => $id));
        $this->Msg->set("Sikeres módosítás!");
        $this->Logs->make("EVENT::Modify", $this->User->getName() . " módosította a " . $p['title'] . " eseményt!<br/>Régi adatok: " . json_encode($old) . "<br/> Új adatok: " . json_encode($p));
        redirect("admin/events");
    }
    public function delete($id){
        $old = $this->db->select('*')->from('events')->where('id',$id)->get()->result_array()[0];
        $this->Db->delete("events", array("id" => $id));
        $this->Msg->set("Sikeres törlés!");
        $this->Logs->make("EVENT::Delete", $this->User->getName() . " tötölte a " . $old['title'] . " eseményt!<br/>Adatok: " . json_encode($old) . "");
        redirect("admin/events");
    }
}