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
    public function Logs($filter = "all", $needle = "", $page = 0){
        $this->User->checkLogin();
        
        $this->load->library('pagination');
        $config['use_page_numbers'] = true;
        $config['base_url'] = base_url() . 'admin/logs/'.$filter.'/'.$needle.'';
        $config['total_rows'] = $this->db->select('id')->from('logs')->count_all_results();
        $config['per_page'] = 25;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_link'] = 'Első';
        $config['first_tag_close'] = '</li>';		
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_link'] = 'Utolsó';
        $config['last_tag_close'] = '</li>';		
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';		
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';		
        $config['cur_tag_open'] = '<li class="page-item active" aria-current="page">';
        $config['curr_tag_close'] = '</li>';		
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        
        $offset = ($page == 0) ? 0 : (($page - 1) * $config['per_page']);  

        
        if($filter == "all"){
            $config['total_rows'] = $this->db->select('id')->from('logs')->count_all_results();
            $this->data = array_merge($this->data, array(
                'page'=>'logs_list',
                'sidebar'=>true,
                'filtered'=>false,
                'data' => $this->Logs->getList(array("all",""), $config['per_page'], $offset)
            ));
        }elseif($filter == "id"){
            $this->data = array_merge($this->data, array(
                'page'=>'logs_detail',
                'sidebar'=>true,
                'filtered'=>false,
                'data' => $this->Logs->get($needle)
            ));
        }else{
            $config['total_rows'] = $this->db->select('id')->where($filter,$needle)->from('logs')->count_all_results();
            $this->data = array_merge($this->data, array(
                'page'=>'logs_list',
                'sidebar'=>true,
                'filtered'=>true,
                'data' => $this->Logs->getList(array($filter,$needle),$config['per_page'], $offset)
            ));
        };
        $this->pagination->initialize($config);
        $this->data['pagi'] = $this->pagination->create_links();
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
            $this->Logs->make("USER::Inactivate", $this->User->getName() . " inaktiválta #" . $id . " felhasználót");
            $this->Msg->set("Sikeres inaktiválás!");
            redirect('admin/users');
        }elseif($f == "activate" && $id != -1){
            $this->Db->update("users",array("active" => 1),array("id" => $id));
            $this->Logs->make("USER::Activate", $this->User->getName() . " aktiválta #" . $id . " felhasználót");
            $this->Msg->set("Sikeres aktiválás!");
            redirect('admin/users');
        }elseif($f == "delete" && $id != -1){
            $this->User->delete($id);
            redirect('admin/users');
        }elseif($f == "location" && $id != -1){
            $geo = json_decode(file_get_contents('./assets/map/' . $id . '.bin'), true);
            $this->form_validation->set_rules('lat', 'GPS LAT', 'trim|required');
            $this->form_validation->set_rules('lon', 'GPS LON', 'trim|required');
            if(!$this->form_validation->run()){
                $this->data['geo'] = $geo;
                $this->data['page'] = 'user_geo';
                $this->load->view($this->thm . '/index', $this->data);
            }else{
                $p = $this->input->post();
                $p['description'] = $geo['description'];
                $p['active'] = $geo['active'];
                $p['parrotState'] = null;
                $p['parrotRadios'] = null;
                $p['authorized'] = 1;
                $p['hasUser'] = true;
                $p['userID'] = $geo['userID'];
                $p['address'] = array(
                    "country" => $p['country'],
                    "county" => $p['county'],
                    "city" => $p['city'],
                    "addr" => $p['addr']
                );
                unset($p['country'],$p['county'],$p['city'],$p['addr']);
                file_put_contents('./assets/map/' . $id . '.bin', json_encode($p));
                
                $callsign = $this->User->getNameById($p['userID']);
                if($this->db->select('id')->from('markers_errors')->where('callsign', $callsign)->where('resolved',0)->count_all_results() == 1){
                    $this->Db->update("markers_errors", array("resolved"=>1), array("callsign"=>$callsign,"resolved"=>0));
                };

                $this->Logs->make("USER::GEOLOCATING_MANUAL", $this->User->getName() . " módosította #" . $p['userID'] . " felhasználó geolokációját!");
                $this->Msg->set("Sikeres módosítás!");
                redirect('admin/users');
            }
        }elseif($f == "updateGeo" && $id == -1){
            $this->User->updateGeo();
            $this->Logs->make("USER::UPDATE_GEO_DESCRIPTION", $this->User->getName() . " frissítette a térképet!");
            $this->Msg->set("Sikeres frissítés!");
            redirect('admin/users');
        }
    }

    /*Markers*/
    public function markers($f = "list", $id = -1, $filter = null){
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->data = array_merge($this->data, array(
                'page'=>'marker_list',
                'data' => $this->Markers->getList(($filter != null) ? urldecode($filter) : null),
                'filter' => ($filter != null) ? urldecode($filter) : null
            ));
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "new" && $id == -1){
            $this->data = array_merge($this->data, array(
                'page'=>'form',
                'sidebar'=>true,
                'title' => 'Új marker létrehozása',
                'data' => array(
                    'db' => 'markers',
                    'method' => 'POST',
                    'action' => '',
                    'btnText' => 'Mentés'
                )
            ));
            $this->form_validation->set_rules("lat", "Hosszúság", "trim|required|is_unique[markers.lat]");
            $this->form_validation->set_rules("lon", "Szélesség", "trim|required|is_unique[markers.lon]");
            $this->form_validation->set_rules("type", "Típus", "trim|required");
            $this->form_validation->set_rules("title", "Szélesség", "trim|required|is_unique[markers.title]");
            $this->form_validation->set_rules("description", "Szélesség", "trim|required");
            $this->form_validation->set_rules("active", "Szélesség", "trim");
            $this->form_validation->set_rules("parrotState", "Szélesség", "trim");
            $this->form_validation->set_rules("parrotRadius", "Szélesség", "trim");
            $this->form_validation->set_rules("place", "Szélesség", "trim");

            if(!$this->form_validation->run()){
                $this->load->view($this->thm . '/index', $this->data);
            }else{
                $this->Markers->add($this->input->post());
            }
        }elseif($f == "edit" && $id != -1){
            $this->data = array_merge($this->data, array(
                'page'=>'form',
                'sidebar'=>true,
                'title' => 'Marker módosítása',
                'data' => array(
                    'db' => 'markers',
                    'method' => 'POST',
                    'action' => '',
                    'btnText' => 'Mentés'
                )
            ));
            $this->data['values'] = $this->db->select('*')->from('markers')->where('id', $id)->get()->result_array()[0];
            $this->form_validation->set_rules("lat", "Hosszúság", "trim|required");
            $this->form_validation->set_rules("lon", "Szélesség", "trim|required");
            $this->form_validation->set_rules("type", "Típus", "trim|required");
            $this->form_validation->set_rules("title", "Szélesség", "trim|required");
            $this->form_validation->set_rules("description", "Szélesség", "trim|required");
            $this->form_validation->set_rules("active", "Szélesség", "trim");
            $this->form_validation->set_rules("parrotState", "Szélesség", "trim");
            $this->form_validation->set_rules("parrotRadius", "Szélesség", "trim");
            $this->form_validation->set_rules("place", "Szélesség", "trim");
            if(!$this->form_validation->run()){
                $this->load->view($this->thm . '/index', $this->data);
            }else{
                $this->Markers->edit($this->input->post());
            }
        }elseif($f == "allow" && $id != -1){
            $this->Markers->allow($id);   
        }elseif($f == "delete" && $id != -1){
            $this->Markers->delete($id);
        }
    }
    public function markerErrors(){
        $this->User->checkLogin();
        $this->data = array_merge($this->data, array(
            'page'=>'marker_error',
            'data' => $this->Markers->getErrorList()
        ));
        $this->load->view($this->thm . '/index', $this->data);
    }

    /*Events*/
    public function events($f = "list", $id = -1){
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->data['page'] = "event_list";
            $this->data["data"] = $this->Events->getList();
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "new" && $id == -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Új oldal létrehozása";
            $this->data['data'] = array(
                'db' => 'events',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Mentés'
            );
            $this->form_validation->set_rules("title", "Cím", "trim|required");
            $this->form_validation->set_rules("seoLink", "SEO url", "trim|required|is_unique[events.seoLink]");
            $this->form_validation->set_rules("image", "Kép", "trim|required");
            $this->form_validation->set_rules("shortDesc", "Rövid leírás", "trim|required");
            $this->form_validation->set_rules("description", "Leírás", "trim|required");
            $this->form_validation->set_rules("eventStart", "Kezdő dátum", "trim|required");
            $this->form_validation->set_rules("eventEnd", "Vége dátum", "trim");
            $this->form_validation->set_rules("place", "Helyszín", "trim|required");
            if($this->form_validation->run()){
                $this->Events->add();
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            };
        }elseif($f == "edit" && $id != -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Esemény módosítása";
            $this->data['data'] = array(
                'db' => 'events',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Módosítás'
            );
            $this->data['values'] = $this->db->select('*')->from('events')->where('id', $id)->get()->result_array()[0];

            $this->form_validation->set_rules("title", "Cím", "trim|required");
            $this->form_validation->set_rules("seoLink", "SEO url", "trim|required");
            $this->form_validation->set_rules("image", "Kép", "trim|required");
            $this->form_validation->set_rules("shortDesc", "Rövid leírás", "trim|required");
            $this->form_validation->set_rules("description", "Leírás", "trim|required");
            $this->form_validation->set_rules("eventStart", "Kezdő dátum", "trim|required");
            $this->form_validation->set_rules("eventEnd", "Vége dátum", "trim");
            $this->form_validation->set_rules("place", "Helyszín", "trim|required");
            if($this->form_validation->run()){
                $this->Events->update($id);
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            }
        }elseif($f == "delete" && $id != 1){
            $this->Events->delete($id);
        }
    }

    /*News*/
    public function news($f = "list", $id = -1){
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->data['page'] = "news_list";
            $this->data["data"] = $this->News->getList();
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "new" && $id == -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Új hír létrehozása";
            $this->data['data'] = array(
                'db' => 'news',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Mentés'
            );
            $this->form_validation->set_rules("title", "Cím", "trim|required");
            $this->form_validation->set_rules("alias", "SEO url", "trim|required|is_unique[events.seoLink]");
            $this->form_validation->set_rules("image", "Kép", "trim|required");
            $this->form_validation->set_rules("short", "Rövid leírás", "trim|required");
            $this->form_validation->set_rules("content", "Leírás", "trim|required");
            $this->form_validation->set_rules("isPublic", "Kezdő dátum", "trim|required");
            if($this->form_validation->run()){
                $this->News->add();
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            };
        }elseif($f == "edit" && $id != -1){
            $this->data['page'] = "form";
            $this->data['title'] = "Hír módosítása";
            $this->data['data'] = array(
                'db' => 'news',
                'method' => 'POST',
                'action' => '',
                'btnText' => 'Módosítás'
            );
            $this->data['values'] = $this->db->select('*')->from('news')->where('id', $id)->get()->result_array()[0];

            $this->form_validation->set_rules("title", "Cím", "trim|required");
            $this->form_validation->set_rules("alias", "SEO url", "trim|required");
            $this->form_validation->set_rules("image", "Kép", "trim|required");
            $this->form_validation->set_rules("short", "Rövid leírás", "trim|required");
            $this->form_validation->set_rules("content", "Leírás", "trim|required");
            $this->form_validation->set_rules("isPublic", "Kezdő dátum", "trim|required");
            if($this->form_validation->run()){
                $this->News->update($id);
            }else{
                $this->load->view($this->thm . '/index', $this->data);
            }
        }elseif($f == "delete" && $id != 1){
            $this->News->delete($id);
        }
    }

    /*Visitors*/
    public function visitors($f = "list", $id = -1, $page = 0)
    {
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->load->library('pagination');
            $config['use_page_numbers'] = true;
            $config['base_url'] = base_url() . 'admin/visitors/list/-1';
            $config['total_rows'] = $this->db->select('ipaddr')->from('visitors')->count_all_results();
            $config['per_page'] = 25;
            $config['full_tag_open'] = '<nav><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_link'] = 'Első';
            $config['first_tag_close'] = '</li>';		
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_link'] = 'Utolsó';
            $config['last_tag_close'] = '</li>';		
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';		
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';		
            $config['cur_tag_open'] = '<li class="page-item active" aria-current="page">';
            $config['curr_tag_close'] = '</li>';		
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $this->data['pagi'] = $this->pagination->create_links();
            $offset = ($page == 0) ? 0 : (($page - 1) * $config['per_page']);
            $this->data = array_merge($this->data, array(
                'page'=>'visitor_list',
                'sidebar'=>true,
                'data' => $this->Visitor->getList($config, $offset)
            ));
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "open" && $id != -1){
            $this->data = array_merge($this->data, array(
                'page'=>'visitor_details',
                'sidebar'=>true,
                'data' => $this->Visitor->get($id)
            ));
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "bann" && $id != -1){
            $this->Banns->add($id);
        }elseif($f == "unbann" && $id != -1){
            $this->Banns->remove($id);
        }
    }

    /*QSO*/
    public function qso($f = "list", $id =-1, $page = 0){
        $this->User->checkLogin();
        if($f == "list" && $id == -1){
            $this->load->library('pagination');
            $config['use_page_numbers'] = true;
            $config['base_url'] = base_url() . 'admin/qso/list/-1';
            $config['total_rows'] = $this->db->select('ipaddr')->from('qso')->count_all_results();
            $config['per_page'] = 25;
            $config['full_tag_open'] = '<nav><ul class="pagination">';
            $config['full_tag_close'] = '</ul></nav>';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_link'] = 'Első';
            $config['first_tag_close'] = '</li>';		
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_link'] = 'Utolsó';
            $config['last_tag_close'] = '</li>';		
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';		
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';		
            $config['cur_tag_open'] = '<li class="page-item active" aria-current="page">';
            $config['curr_tag_close'] = '</li>';		
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $this->data['pagi'] = $this->pagination->create_links();
            $offset = ($page == 0) ? 0 : (($page - 1) * $config['per_page']);
            $this->data = array_merge($this->data, array(
                'page'=>'qso_list',
                'sidebar'=>true,
                'data' => $this->Qso->aList($config, $offset)
            ));
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "approve" && $id != -1){
            $this->Db->update('qso', array('status'=>'approved', 'verified' => 1, 'verifiedAt' => date("Y-m-d H:i:s")), array('id'=>$id));
            $this->Msg->set('Sikeres művelet', 'success');
            redirect('admin/qso/list');
        }elseif($f == "deny" && $id != -1){
            $this->Db->update('qso', array('status'=>'denied', 'verified' => 1, 'verifiedAt' => date("Y-m-d H:i:s")), array('id'=>$id));
            $this->Msg->set('Sikeres művelet', 'success');
            redirect('admin/qso/list');
        }
    }

    /*Email fiók*/
    public function conversations($f = "list", $id = -1){
        $this->User->checkLogin();
        if(!$this->User->getOwner()){ redirect('admin'); };
        if($f == "list" && $id == -1){
            $this->data = array_merge($this->data, array(
                'page'=>'conv_list',
                'sidebar'=>true,
                'data' => $this->Contact->getList()
            ));
            $this->load->view($this->thm . '/index', $this->data);
        }elseif($f == "read" && $id != -1){
            $this->Db->update("conversation_ids", array("haveUnreaded"=>0), array("id"=>$id));
            $this->data = array_merge($this->data, array(
                'page'=>'conv_item',
                'sidebar'=>true,
                'data' => $this->Contact->getItem($id)
            ));
            $this->load->view($this->thm . '/index', $this->data);
            
        }elseif($f == "reply" && $id != -1){
            $this->form_validation->set_rules('message', 'Üzenet', 'trim');
            if($this->form_validation->run()){
                $this->Contact->reply($id);
            }
        }
    }
}