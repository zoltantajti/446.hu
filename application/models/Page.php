<?php 
class Page extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getList()
    {
        return $this->db->select('id,name')->from('pages')->get()->result_array();
    }
    public function getParentNameById($id){
        return ($id == 0) ? "/" : $this->db->select('name')->from('pages')->where('id',$id)->get()->result_array()[0]['name'] . "/";
    }
    public function add()
    {
        $p = $this->input->post();
        $data = array(
            "icon" => $p['icon'],
            "name" => $p['name'],
            "title" => $p['title'],
            "alias" => $p['alias'],
            "content" => $p['content'],
            "module" => $p['module'],
            "meta_key" => $p['meta_key'],
            "meta_desc" => $p['meta_desc']
        );
        $this->db->insert("pages", $data);
        $this->Msg->set("Sikeres hozzáadás!");
        $this->Logs->make("PAGE::Create", $this->User->getName() . " létrehozott egy oldalt!");
        redirect("admin/pages/list");
    }
    public function update($id){
        $p = $this->input->post();
        $data = array(
            "icon" => $p['icon'],
            "name" => $p['name'],
            "title" => $p['title'],
            "alias" => $p['alias'],
            "content" => $p['content'],
            "module" => $p['module'],
            "meta_key" => $p['meta_key'],
            "meta_desc" => $p['meta_desc']
        );
        $this->db->set($data)->where('id',$id)->update('pages');
        $this->Msg->set("Sikeres módosítás!");
        $this->Logs->make("PAGE::Modify", $this->User->getName() . " módosította a ".$data['name']." oldalt!");
        redirect("admin/pages/list");
    }
    public function delete($id){
        $sor = $this->db->select('*')->where('id',$id)->from('pages')->get()->result_array();
        $this->db->where('id',$id)->delete('pages');
        $this->Logs->make("DB::Delete", $this->User->getName() . " törölte az alábbi sort: Tábla: Pages, ID:" . $id . ", Tartalom: " . json_encode($sor[0]));
        $this->Msg->set("Sikeres törlés!");
        redirect("admin/pages/list");
    }
}