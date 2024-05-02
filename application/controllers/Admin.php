<?php
class Admin extends CI_Controller {
    protected $thm = "admin/";
    private $data = array();

    public function __construct(){ 
        parent::__construct(); 
        if($this->Banns->check($_SERVER['REMOTE_ADDR'])){ die("A biztonsági házirend tiltja, hogy megtekintsd az oldalt!"); };
        $this->User->checkLogin();
        $this->User->hasAccess(2);
        $this->data['thm'] = $this->thm;
    }

    public function index()
    {
        $this->data['page'] = "index";
        $this->load->view($this->thm . 'index', $this->data);
    }

    /*Pages*/
    public function pages($f = "list", $id = -1)
    {
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->data['page'] = "pages_list";
            $this->data["data"] = $this->Page->getList();
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "new" && $id == -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Új oldal létrehozása";
            $this->data['data'] = array(
                'db' => 'pages',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Mentés'
            );
            $this->form_validation->set_rules("name", "Név", "trim|required|is_unique[pages.name]");
            $this->form_validation->set_rules("title", "Cím", "trim|required|is_unique[pages.title]");
            $this->form_validation->set_rules("alias", "SEO url", "trim|required|is_unique[pages.alias]");
            $this->form_validation->set_rules("content", "Tartalom", "trim|required");
            $this->form_validation->set_rules("meta_key", "SEO kulcsszavak", "trim");
            $this->form_validation->set_rules("meta_desc", "SEO leírás", "trim");
            if($this->form_validation->run()){
                $this->Page->add();
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            };
        }elseif($f == "edit" && $id != -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Oldal módosítása";
            $this->data['data'] = array(
                'db' => 'pages',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Módosítás'
            );
            $this->data['values'] = $this->db->select('*')->from('pages')->where('id', $id)->get()->result_array()[0];

            $this->form_validation->set_rules("name", "Név", "trim|required");
            $this->form_validation->set_rules("title", "Cím", "trim|required");
            $this->form_validation->set_rules("alias", "SEO url", "trim|required");
            $this->form_validation->set_rules("content", "Tartalom", "trim|required");
            $this->form_validation->set_rules("meta_key", "SEO kulcsszavak", "trim");
            $this->form_validation->set_rules("meta_desc", "SEO leírás", "trim");
            if($this->form_validation->run()){
                $this->Page->update($id);
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            }
        }elseif($f == "delete" && $id != 1){
            $this->Page->delete($id);
        }
    }

    /*Emails*/
    public function emails($f = "list", $id = -1)
    {
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->data['page'] = "emails_list";
            $this->data["data"] = $this->Email->getList();
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "new" && $id == -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Új E-mail létrehozása";
            $this->data['data'] = array(
                'db' => 'emails',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Mentés'
            );
            $this->form_validation->set_rules("name", "Név", "trim|required|is_unique[emails.name]");
            $this->form_validation->set_rules("alias", "SEO url", "trim|required|is_unique[emails.alias]");
            $this->form_validation->set_rules("subject", "Tárgy", "trim|required|is_unique[emails.subject]");
            $this->form_validation->set_rules("content", "Tartalom", "trim|required");
            
            if($this->form_validation->run()){
                $this->Email->add();
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            };
        }elseif($f == "edit" && $id != -1){
            $this->data['page'] = "form";
            $this->data['title'] = "E-mail módosítása";
            $this->data['data'] = array(
                'db' => 'emails',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Módosítás'
            );
            $this->data['values'] = $this->db->select('*')->from('emails')->where('id', $id)->get()->result_array()[0];

            $this->form_validation->set_rules("name", "Név", "trim|required");
            $this->form_validation->set_rules("subject", "Tárgy", "trim|required");
            $this->form_validation->set_rules("alias", "SEO url", "trim|required");
            $this->form_validation->set_rules("content", "Tartalom", "trim|required");
            if($this->form_validation->run()){
                $this->Email->update($id);
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            }
        }elseif($f == "delete" && $id != 1){
            $this->Email->delete($id);
        }
    }

    /*Logs*/
    public function Logs($filter = "all", $needle = ""){
        $this->User->checkLogin();
        if($filter == "list"){
            $this->data = array_merge($this->data, array(
                'page'=>'logs_list',
                'sidebar'=>true,
                'filtered'=>false,
                'data' => $this->Logs->list(array("all",""))
            ));
        }elseif($filter == "id"){
            $this->data = array_merge($this->data, array(
                'page'=>'logs_detail',
                'sidebar'=>true,
                'filtered'=>false,
                'data' => $this->Logs->get($needle)
            ));
        }else{
            $this->data = array_merge($this->data, array(
                'page'=>'logs_list',
                'sidebar'=>true,
                'filtered'=>true,
                'data' => $this->Logs->list(array($filter,$needle))
            ));
        }
        $this->load->view($this->thm . '/index', $this->data);
    }

    /*Users*/
    public function users($f = "list", $id = -1, $filter = null){
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->data = array_merge($this->data, array(
                'page'=>'user_list',
                'sidebar'=>true,
                'data' => $this->User->getList(($filter != null) ? urldecode($filter) : null),
                'filter' => ($filter != null) ? urldecode($filter) : null
            ));
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "new" && $id == -1){
            $this->data = array_merge($this->data, array(
                'page'=>'form',
                'sidebar'=>true,
                'title' => 'Új felhasználó létrehozása',
                'data' => array(
                    'db' => 'users',
                    'method' => 'POST',
                    'action' => '',
                    'btnText' => 'Mentés'
                )
            ));
            $this->form_validation->set_rules("username", "Felhasználónév", "trim|required|is_unique[users.username]");
            $this->form_validation->set_rules("password", "Jelszó", "trim|required|min_length[6]");
            $this->form_validation->set_rules("email", "E-mail cím", "trim|required|is_unique[users.email]|valid_email");
            $this->form_validation->set_rules("fullname", "Teljes név", "trim|required");
            if($this->form_validation->run()){
                $this->User->add();
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            };
        }elseif($f == "inactivate" && $id != -1){
            $this->Db->update("users",array("active" => 0),array("id" => $id));
            $this->Logs->make("USER::Inactivate",$this->Sess->getChain('user','name') . " inaktiválta #" . $id . " felhasználót");
            $this->Msg->set("Sikeres létrehozás!");
            redirect('admin/users');
        }elseif($f == "activate" && $id != -1){
            $this->Db->update("users",array("active" => 1),array("id" => $id));
            $this->Logs->make("USER::Activate",$this->Sess->getChain('user','name') . " aktiválta #" . $id . " felhasználót");
            $this->Msg->set("Sikeres létrehozás!");
            redirect('admin/users');
        }
    }
}