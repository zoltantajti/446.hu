<?php
class Internal extends CI_Controller {
    private $thm = "internal/";
    private $data = array();

    public function __construct(){
        parent::__construct();
    }

    public function index() {
        $this->User->checkLogin();
        $this->data['page'] = $this->thm . "pages/main";
        $this->load->view($this->thm . 'frame', $this->data);
    }

    public function login() {
        $this->User->isLoggedIn();
        if($this->Cookie->has('remember_user') && $this->Cookie->has('remember_id')){ $this->User->autoLogin(); };        
        $this->form_validation->set_rules('username', 'Felhasználónév vagy hívójel', 'required|trim');
        $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]');
        if($this->form_validation->run() == FALSE){
            $this->load->view($this->thm . "login/login", $this->data);
        }else{
            $this->User->doLogin($this->input->post());
        }
    }
    public function logout(){
        $this->User->checkLogin();
        $this->User->doLogout();
    }
    public function lostpassword($method = "clear", $hash = null){
        $this->User->isLoggedIn();
        if($method == "clear"){
            $this->form_validation->set_rules('email', 'E-mail cím', 'required|trim|valid_email');
            if($this->form_validation->run() == FALSE){
                $this->load->view($this->thm . "login/clearPassword", $this->data);
            }else{
                $this->User->doPasswordReset($this->input->post());
            }
        }elseif($method == "reset" && $hash != null){
            if($this->db->select('id')->from('users')->where('hash', $hash)->count_all_results() == 1){
                $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]');
                $this->form_validation->set_rules('password_rep', 'Jelszó megerősítése', 'required|trim|min_length[8]|max_length[32]|matches[password]');
                if($this->form_validation->run() == FALSE){
                    $this->data['form'] = true;
                    $this->load->view($this->thm . "login/resetPassword", $this->data);
                }else{
                    $this->User->doPasswordModify($this->input->post(), $hash);
                }
            }else{
                $this->data['form'] = false;
                $this->data['msg'] = "Nem létezik a hash!";
                $this->load->view($this->thm . "login/resetPassword", $this->data);
            }
        }
    }
    public function register($step = 1) {
        $this->User->isLoggedIn();
        if($step == 1){
            $this->form_validation->set_rules('callsign', 'Hívójel', 'required|trim|is_unique[users.callsign]|callback_nmhh_check');
            $this->form_validation->set_rules('opname', 'Operátor név', 'required|trim|is_unique[users.opname]');
            $this->form_validation->set_rules('email', 'E-mail cím', 'required|trim|is_unique[users.email]|valid_email');
            $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]');
            if($this->form_validation->run() == FALSE){
                $this->data['step'] = "step1";
                $this->load->view($this->thm . "register/frame", $this->data);
            }else{
                $_SESSION['registration']['callsign'] = $this->input->post('callsign');
                $_SESSION['registration']['password'] = $this->Encryption->hash($this->input->post('password'));
                $_SESSION['registration']['email'] = $this->input->post('email');
                $_SESSION['registration']['opname'] = $this->input->post('opname');
                redirect("internal/register/2");
            }
        }elseif($step == 2){
            if(!isset($_SESSION['registration'])) { redirect('register/1'); };
            $this->form_validation->set_rules('name', 'Teljes név', 'required|trim');
            $this->form_validation->set_rules('country', 'Ország', 'required|trim');
            $this->form_validation->set_rules('county', 'Vármegye', 'required|trim');
            $this->form_validation->set_rules('city', 'Város', 'required|trim');
            $this->form_validation->set_rules('address', 'Cím', 'trim');

            if($this->form_validation->run() == FALSE){
                $this->data['step'] = "step2";
                $this->load->view($this->thm . "register/frame", $this->data);
            }else{
                $_SESSION['registration']['name'] = $this->input->post('name');
                $_SESSION['registration']['country'] = $this->input->post('country');
                $_SESSION['registration']['county'] = $this->input->post('county');
                $_SESSION['registration']['city'] = $this->input->post('city');
                $_SESSION['registration']['address'] = $this->input->post('address');
                redirect("internal/register/3");
            }
        }elseif($step == 3){
            if(!isset($_SESSION['registration'])) { redirect('register/1'); };
            
            $this->form_validation->set_rules('radios', 'Rádiók', 'trim');
            $this->form_validation->set_rules('antennas', 'Antennák', 'trim');
            $this->form_validation->set_rules('freqs', 'Frekvenciák', 'trim');

            if($this->form_validation->run() == FALSE){
                $this->data['step'] = "step3";
                $this->load->view($this->thm . "register/frame", $this->data);
            }else{
                $_SESSION['registration']['radios'] = $this->input->post("radios");
                $_SESSION['registration']['antennas'] = $this->input->post("antennas");
                $_SESSION['registration']['freqs'] = $this->input->post("freqs");
                redirect("internal/register/4");
            }
        }elseif($step == 4){
            if(!isset($_SESSION['registration'])) { redirect('register/1'); };
            
            $this->form_validation->set_rules('aboutME', 'Bemutatkozás', 'trim');

            if($this->form_validation->run() == FALSE){
                $this->data['step'] = "step4";
                $this->load->view($this->thm . "register/frame", $this->data);
            }else{
                $_SESSION['registration']['aboutME'] = $this->input->post("aboutME");
                $this->Registration->doReg();
            }
        }elseif($step == 5){
            $this->data['step'] = "success";
            $this->load->view($this->thm . "register/frame", $this->data);
        }
    }
    public function activate($hash) {
        $this->User->isLoggedIn();
        if($this->db->select('id')->from('users')->where('hash', $hash)->count_all_results() == 1){
            if($this->db->select('id')->from('users')->where('hash', $hash)->where('expired >=', date("Y-m-d H:i:s"))->count_all_results() == 1){
                if($this->db->select('id')->from('users')->where('hash', $hash)->where('active',0)->where('expired >=', date("Y-m-d H:i:s"))->count_all_results() == 1){
                    $this->Db->update("users", array("active"=>1, "hash"=>null, "expired" => null), array("hash" => $hash));                    
                    $this->data['type'] = "success";
                    $this->data["message"] = "Az aktiválás sikeres! Most már <a href='internal/login'>be tudsz lépni</a> a fiókodba!";
                }else{
                    $this->data['type'] = "danger";
                    $this->data["message"] = "Az aktiválás sikertelen volt az alábbi ok miatt: <br/><br/> <b>A fiókod már aktív!</b>";    
                }
            }else{
                $this->data['type'] = "danger";
                $this->data["message"] = "Az aktiválás sikertelen volt az alábbi ok miatt: <br/><br/> <b>A Hash érvényességi ideje lejárt!</b>";
            }
        }else{
            $this->data['type'] = "danger";
            $this->data["message"] = "Az aktiválás sikertelen volt az alábbi ok miatt: <br/><br/> <b>Nem létező hash-t használsz!</b>";
        };
        $this->load->view($this->thm . "register/activate", $this->data);
    }
    public function nmhh_check($str){
        if($this->db->select('callsign')->from('callsignbook')->where('callsign', $str)->count_all_results() == 1){
            $_SESSION['registration']['nmhh'] = $this->db->select('name,country,city,address')->from('callsignbook')->get()->result_array()[0];
            return true;
        }else{            
            unset($_SESSION['registration']['nmhh']);
            return true;
        }
    }


    public function terkep(){
        $this->User->checkLogin();
        $this->data['page'] = $this->thm . 'pages/map';
        $this->data['css'] = '
<link rel="stylesheet" media="screen" href="./assets/js/leaflet/leaflet.css" />
<link rel="stylesheet" media="screen" href="./assets/js/leaflet/extra-markers/css/leaflet.extra-markers.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
        ';
        $this->data['js'] = '
<script src="./assets/js/leaflet/leaflet.js"></script>
<script src="./assets/js/leaflet_providers/leaflet-providers.js"></script>
<script src="./assets/js/map/Maidenhead.js"></script>
<script src="./assets/js/leaflet/extra-markers/js/leaflet.extra-markers.js"></script>
<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
<script src="./assets/js/tinymce/tinymce.min.js"></script>
<script type="module" src="./assets/js/map/index.js?ref=internal"></script>';
        $this->load->view($this->thm . 'frame', $this->data);
    }

    public function events($page = 0)
    {
        $this->User->checkLogin();
        $this->load->library('pagination');
        $config['use_page_numbers'] = true;
        $config['base_url'] = base_url() . 'internal/events';
        $config['total_rows'] = $this->db->select('id')->from('events')->where('eventStart >=', date("Y-m-d H:i:s"))->count_all_results();
        $config['per_page'] = 10;
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
		$rows = $this->db->select('*')->from('events')->where('eventStart >=', date("Y-m-d H:i:s"))->order_by('eventStart','asc')->limit($config['per_page'],$offset)->get()->result_array();
		$this->data['events'] = $rows;
        $this->data['page'] = $this->thm . "pages/events";
		$this->load->view($this->thm . 'frame', $this->data);
    }
    public function event($alias){
        $this->User->checkLogin();
        $rows = $this->db->select('*')->from('events')->where('seoLink',$alias)->get()->result_array();
		$this->data['event'] = $rows[0];
		$this->data['page'] = $this->thm . "pages/event";
        $this->load->view($this->thm . 'frame', $this->data);
    }

    public function news($page = 0)
    {
        $this->User->checkLogin();
        $this->load->library('pagination');
        $config['use_page_numbers'] = true;
        $config['base_url'] = base_url() . 'internal/news';
        $config['total_rows'] = $this->db->select('id')->from('news')->count_all_results();
        $config['per_page'] = 10;
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
		$rows = $this->db->select('*')->from('news')->order_by('createdAt','desc')->limit($config['per_page'],$offset)->get()->result_array();
		$this->data['events'] = $rows;
        $this->data['page'] = $this->thm . "pages/news";
		$this->load->view($this->thm . 'frame', $this->data);
    }
    
	public function newDetail($alias){
        $this->User->checkLogin();
        $rows = $this->db->select('*')->from('news')->where('alias',$alias)->get()->result_array();
		$this->data['event'] = $rows[0];
		$this->data['page'] = $this->thm . "pages/new";
        $this->load->view($this->thm . 'frame', $this->data);
    }

    public function page($alias){
        if($this->db->select('id,title,content,module,meta_key,meta_desc')->from('pages')->where('alias', $alias)->count_all_results() == 1){
            $page = $this->db->select('id,title,content,module,meta_key,meta_desc')->from('pages')->where('alias', $alias)->get()->result_array()[0];
            if($page['module'] == null){
                $this->data['page'] = $this->thm . "pages/content";
                $this->data['ctx'] = $page;
                $this->load->view($this->thm . "frame", $this->data);
            };
        }else{
            $this->data['page'] = $this->thm . "errors/404";
            $this->load->view($this->thm . "frame", $this->data);
        }
    }
    
    /*QSO page*/
    public function qso($f = "add", $id = -1){
        if($f == "add" && $id == -1){
            $this->data['page'] = $this->thm . "pages/qso_add";
            $this->form_validation->set_rules('date', 'Dátum', 'trim|required');
            $this->form_validation->set_rules('time', 'Idő', 'trim|required');
            $this->form_validation->set_rules('freq', 'Frekvencia', 'trim|required');
            $this->form_validation->set_rules('ctcs', 'CTCS', 'trim|required');
            $this->form_validation->set_rules('dcs', 'DCS', 'trim|required');
            $this->form_validation->set_rules('my_callsign', 'Hívójelem', 'trim|required');
            $this->form_validation->set_rules('my_qth', 'QTH lokátor kódom', 'trim|required');
            $this->form_validation->set_rules('suffix', 'Típus', 'trim|required');
            $this->form_validation->set_rules('rem_callsign', 'Ellenállomás hívójele', 'trim|required');
            $this->form_validation->set_rules('rem_qth', 'Ellenállomás QTH kódja', 'trim|required');
            $this->form_validation->set_rules('rem_opname', 'Ellenállomás Operátora', 'trim|required');
            $this->form_validation->set_rules('mode', 'Hívás módja', 'trim|required');
            $this->form_validation->set_rules('parrot_name', 'Papagáj neve', 'trim');
            $this->form_validation->set_rules('comment', 'Megjegyzés', 'trim');
            $this->form_validation->set_rules('distance', 'Távolság', 'trim|required');
            $this->form_validation->set_rules('myPos', 'GPS koordinátáim', 'trim|required');
            $this->form_validation->set_rules('remPos', 'Ellenállomás GPS korrdinátái', 'trim|required');

            if(!$this->form_validation->run()){
                $this->load->view($this->thm . "frame", $this->data);
            }else{
                $this->Qso->add();
            }
        }elseif($f == "list" && $id == -1){
            $this->data['page'] = $this->thm . "pages/qso_list";
            $this->data['qso'] = $this->Qso->getList();
            $this->load->view($this->thm . "frame", $this->data);
        }elseif($f == "allow" && $id != -1){
            $this->Db->update("qso", array("verified"=>1, "verifiedAt"=>date("Y-m-d H:i:s"), "status" => "approved"), array("id" => $id));
            $this->Msg->set("Sikeresen elfogadtad a QSO-t!", "success");
            redirect("internal/qso/list");
        }elseif($f == "deny" && $id != -1){
            $this->Db->update("qso", array("verified"=>1, "verifiedAt"=>date("Y-m-d H:i:s"), "status" => "denied"), array("id" => $id));
            $this->Msg->set("Sikeresen elutasítottad a QSO-t!", "success");
            redirect("internal/qso/list");
        }elseif($f == "map" && $id == -1){
            $this->data['page'] = $this->thm . "pages/qso_map";
            $this->data['css'] = '<link rel="stylesheet" media="screen" href="./assets/js/leaflet/leaflet.css" />
        <link rel="stylesheet" media="screen" href="./assets/js/leaflet/extra-markers/css/leaflet.extra-markers.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
        ';
        $this->data['js'] = '<script src="./assets/js/leaflet/leaflet.js"></script>
		<script src="./assets/js/leaflet_providers/leaflet-providers.js"></script>
		<script src="./assets/js/map/Maidenhead.js"></script>
		<script src="./assets/js/leaflet/extra-markers/js/leaflet.extra-markers.js"></script>
		<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
		<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
		<script src="./assets/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
		<script type="module" src="./assets/js/map/qso.js"></script>';
            $this->load->view($this->thm . "frame", $this->data);
        }        
    }

    public function profile($id = null){
        if($id == null || $id == "login" || $id == "password" || $id == "personal" || $id == "radio" || $id == "about"){
            if($id == null){ $id = "login"; };
            $this->data['segment'] = $id;
            $this->data['user'] = $this->db->select('callsign,opName,country,county,city,address,radios,antennas,freqs,aboutME,regDate,loginDate,perm')->from('users')->where('id',$this->Sess->getChain('id','user'))->get()->result_array()[0];
            $this->data['page'] = $this->thm . "pages/profile";
        }else{
            if($this->db->select('id')->from('users')->where('id',$id)->count_all_results() == 0){
                $this->data['page'] = $this->thm . "errors/404";
            }else{
                $this->data['user'] = $this->db->select('id,callsign,opName,country,county,city,address,radios,antennas,freqs,aboutME,regDate,loginDate,perm')->from('users')->where('id',$id)->get()->result_array()[0];
                $this->data['page'] = $this->thm . "pages/profile_pub";
            };
        }
        $this->load->view($this->thm . "frame", $this->data);
    }
        public function changePassword(){
            $this->form_validation->set_rules('oldPW', 'Jelenlegi jelszó', 'trim|required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('newPW', 'Új jelszó', 'trim|required|min_length[8]|max_length[32]');
            $this->form_validation->set_rules('newPWRep', 'Új jelszó megerősítése', 'trim|required|min_length[8]|max_length[32]|matches[newPW]');
            if($this->form_validation->run()){
                $this->User->changePassword();
            }else{
                $this->Msg->set(validation_errors(), "danger");
                redirect('internal/profile/password');
            }
        }
        public function updatePersonal(){
            $this->form_validation->set_rules('country', 'Ország', 'trim|required');
            $this->form_validation->set_rules('county', 'Megye', 'trim|required');
            $this->form_validation->set_rules('city', 'Város', 'trim|required');
            $this->form_validation->set_rules('address', 'Cím', 'trim');
            if($this->form_validation->run()){
                $this->User->updatePersonal();
            }else{
                $this->Msg->set(validation_errors(), "danger");
                redirect('internal/profile/personal');
            }
        }
        public function updateRadios(){ $this->User->updateRadios(); }
        public function updateAbout(){ $this->User->updateAbout(); }

}