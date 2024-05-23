<?php
class Internal extends CI_Controller {
    private $thm = "internal/";
    private $data = array();
    
    private $errors = array(
        'required' => 'A %s mező kitöltése kötelező!',
        'min_length' => 'A {field} mezőnek legalább {param} karakterből kell állnia!',
        'max_length' => 'A {field} mezőnek legfeljebb {param} karekterből állhat!',
        'valid_email' => 'Kérlek érvényes e-mail címet adj meg!',
        'matches' => 'A {field} mező nem egyezik meg a {param} mezővel!',
        'is_unique' => 'A %s mező értéke már használatban van!',
    );

    public function __construct(){
        parent::__construct();
        $this->data['thm'] = $this->thm;
    }

    public function index() {
        $this->User->checkLogin();
        $this->data['page'] = $this->thm . "pages/main";

        $this->data['css'] = "
		<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.css'>
		<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.3.0/main.min.css'>
		";
        $this->data['js'] = "
		<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.2.0/main.min.js'></script>
		<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.2.0/main.js'></script>
		<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@4.2.0/main.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
		<script src='https://cdn.jsdelivr.net/npm/uuid@8.3.2/dist/umd/uuidv4.min.js'></script>
		<script src='./assets/js/eventCalendar.js'></script>
		";

        $this->load->view($this->thm . 'frame', $this->data);
    }

    public function login() {
        $this->User->isLoggedIn();
        if($this->Cookie->has('remember_user') && $this->Cookie->has('remember_id')){ $this->User->autoLogin(); };        
        $this->form_validation->set_rules('username', 'Felhasználónév vagy hívójel', 'required|trim', $this->errors);
        $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]', $this->errors);
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
            $this->form_validation->set_rules('email', 'E-mail cím', 'required|trim|valid_email', $this->errors);
            if($this->form_validation->run() == FALSE){
                $this->load->view($this->thm . "login/clearPassword", $this->data);
            }else{
                $this->User->doPasswordReset($this->input->post());
            }
        }elseif($method == "reset" && $hash != null){
            if($this->db->select('id')->from('users')->where('hash', $hash)->count_all_results() == 1){
                $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]', $this->errors);
                $this->form_validation->set_rules('password_rep', 'Jelszó megerősítése', 'required|trim|min_length[8]|max_length[32]|matches[password]', $this->errors);
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
            $_SESSION['registration'] = null;
            $this->form_validation->set_rules('callsign', 'Hívójel', 'required|trim|is_unique[users.callsign]|callback_nmhh_check', $this->errors);
            $this->form_validation->set_rules('opname', 'Operátor név', 'required|trim', $this->errors);
            $this->form_validation->set_rules('email', 'E-mail cím', 'required|trim|is_unique[users.email]|valid_email', $this->errors);
            $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]', $this->errors);
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
            $this->form_validation->set_rules('name', 'Teljes név', 'required|trim', $this->errors);
            $this->form_validation->set_rules('country', 'Ország', 'required|trim', $this->errors);
            $this->form_validation->set_rules('county', 'Vármegye', 'required|trim', $this->errors);
            $this->form_validation->set_rules('city', 'Város', 'required|trim', $this->errors);
            $this->form_validation->set_rules('address', 'Cím', 'trim', $this->errors);

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
            
            $this->form_validation->set_rules('radios', 'Rádiók', 'trim', $this->errors);
            $this->form_validation->set_rules('antennas', 'Antennák', 'trim', $this->errors);
            $this->form_validation->set_rules('freqs', 'Frekvenciák', 'trim', $this->errors);

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
            
            $this->form_validation->set_rules('aboutME', 'Bemutatkozás', 'trim', $this->errors);

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
            $_SESSION['registration']['nmhh'] = $this->db->select('name,country,city,address')->from('callsignbook')->where('callsign', $str)->get()->result_array()[0];
            return true;
        }else{            
            unset($_SESSION['registration']['nmhh']);
            return true;
        }
    }
    public function terkep($attrs = null){
        $this->User->checkLogin();
        $this->data['page'] = $this->thm . 'pages/map';
        $this->data['css'] = '
<link rel="stylesheet" media="screen" href="./assets/js/leaflet/leaflet.css" />
<link rel="stylesheet" media="screen" href="./assets/js/leaflet/extra-markers/css/leaflet.extra-markers.min.css" />
<link rel="stylesheet" media="screen" href="./assets/js/leaflet/weather/Leaflet.Weather.css" />
<link rel="stylesheet" media="screen" href="./assets/js/leaflet/draw/leaflet.draw.css" />
        ';
        $this->data['js'] = '
<script src="./assets/js/leaflet/leaflet.js"></script>
<script src="./assets/js/leaflet_providers/leaflet-providers.js"></script>
<script src="./assets/js/map/Maidenhead.js"></script>
<script src="./assets/js/leaflet/extra-markers/js/leaflet.extra-markers.js"></script>
<script src="./assets/js/leaflet/weather/Leaflet.Weather.js"></script>
<script src="./assets/js/tinymce/tinymce.min.js"></script>
<script type="module" src="./assets/js/map/index.js?ref=internal' . (($attrs != null) ? '&center='.$attrs : '' ) . '"></script>';
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
        $this->data['marker'] = ($this->db->select('id')->from('event_markers')->where('eventID', $rows[0]['id'])->count_all_results() == 1) ? $this->db->select('lat,lon')->from('event_markers')->where('eventID', $rows[0]['id'])->get()->result_array()[0] : null;
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
    
	public function newsDetails($alias){
        $this->User->checkLogin();
        $rows = $this->db->select('*')->from('news')->where('alias',$alias)->get()->result_array();
		$this->data['event'] = $rows[0];
		$this->data['page'] = $this->thm . "pages/new";
        $this->load->view($this->thm . 'frame', $this->data);
    }

    public function page($alias){
        $this->User->checkLogin();
        if($this->db->select('id,title,content,module,meta_key,meta_desc')->from('pages')->where('alias', $alias)->count_all_results() == 1){
            $page = $this->db->select('id,title,content,module,meta_key,meta_desc')->from('pages')->where('alias', $alias)->get()->result_array()[0];
            if($page['module'] == null){
                $this->data['page'] = $this->thm . "pages/content";
                $this->data['ctx'] = $page;
                $this->load->view($this->thm . "frame", $this->data);
            }else{
                $this->data['page'] = $this->thm . "pages/content";
                $this->data['ctx'] = $page;
                $module = explode("/", $page['module']);
                $this->load->model($module[0]);
                $this->data['module'] = $this->{$module[0]}->{$module[1]}($module[2]);
                $this->load->view($this->thm . "frame", $this->data);
            }
        }else{
            $this->data['page'] = $this->thm . "errors/404";
            $this->load->view($this->thm . "frame", $this->data);
        }
    }
    
    /*QSO page*/
    public function qso($f = "add", $id = -1){
        $this->User->checkLogin();
        if($f == "add" && $id == -1){
            $this->data['page'] = $this->thm . "pages/qso_add";
            $this->form_validation->set_rules('date', 'Dátum', 'trim|required', $this->errors);
            $this->form_validation->set_rules('time', 'Idő', 'trim|required', $this->errors);
            $this->form_validation->set_rules('freq', 'Frekvencia', 'trim|required', $this->errors);
            $this->form_validation->set_rules('ctcs', 'CTCS', 'trim', $this->errors);
            $this->form_validation->set_rules('dcs', 'DCS', 'trim', $this->errors);
            $this->form_validation->set_rules('my_callsign', 'Hívójelem', 'trim|required', $this->errors);
            $this->form_validation->set_rules('my_country', 'Országom', 'trim', $this->errors);
            $this->form_validation->set_rules('my_county', 'Megyém', 'trim', $this->errors);
            $this->form_validation->set_rules('my_city', 'Városom', 'trim', $this->errors);
            $this->form_validation->set_rules('my_address', 'Címem', 'trim', $this->errors);
            $this->form_validation->set_rules('my_qth', 'QTH lokátor kódom', 'trim|required', $this->errors);
            $this->form_validation->set_rules('suffix', 'Típus', 'trim|required');
            $this->form_validation->set_rules('rem_callsign', 'Ellenállomás hívójele', 'trim|required', $this->errors);
            $this->form_validation->set_rules('rem_country', 'Ellenállomás Országa', 'trim', $this->errors);
            $this->form_validation->set_rules('rem_county', 'Ellenállomás Megyéje', 'trim', $this->errors);
            $this->form_validation->set_rules('rem_city', 'Ellenállomás Városa', 'trim', $this->errors);
            $this->form_validation->set_rules('rem_address', 'Ellenállomás Címe', 'trim', $this->errors);
            $this->form_validation->set_rules('rem_qth', 'Ellenállomás QTH kódja', 'trim|required', $this->errors);
            $this->form_validation->set_rules('rem_opname', 'Ellenállomás Operátora', 'trim|required', $this->errors);
            $this->form_validation->set_rules('mode', 'Hívás módja', 'trim|required', $this->errors);
            $this->form_validation->set_rules('parrot_name', 'Papagáj neve', 'trim', $this->errors);
            $this->form_validation->set_rules('comment', 'Megjegyzés', 'trim', $this->errors);
            $this->form_validation->set_rules('distance', 'Távolság', 'trim|required', $this->errors);
            $this->form_validation->set_rules('myPos', 'GPS koordinátáim', 'trim|required', $this->errors);
            $this->form_validation->set_rules('remPos', 'Ellenállomás GPS korrdinátái', 'trim|required', $this->errors);

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
        }elseif($f == "upload" && $id == -1){
            $this->data['page'] = $this->thm . "pages/qso_upload";
            
            if(empty($_FILES['file']['name'])){
                $this->form_validation->set_rules('file', 'Fájl', 'required', $this->errors);
                $this->load->view($this->thm . 'frame', $this->data);
            }else{
                $uploadPath = './uploads/qso/';
                $allowedTypes = ['jpg','jpeg','png','pdf','xls','xlsx'];
                $maxSize = 10 * 1024 * 1024;
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $fileName = $_FILES['file']['name'];
                $fileSize = $_FILES['file']['size'];
                $fileType = $_FILES['file']['type'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($fileSize > $maxSize) {
                    $this->Msg->set("A fájl mérete túl nagy. A maximális méret 10 MB.",'danger');
                    redirect('internal/qso/upload');
                };
                if (!in_array($fileExtension, $allowedTypes)) {
                    $this->Msg->set("Nem engedélyezett fájlformátum",'danger');
                    redirect('internal/qso/upload');
                };
                $newFileName = $this->Sess->getChain('callsign','user') . '-' . date('Y-m-d') . '.' . $fileExtension;
                $destPath = $uploadPath . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $this->Msg->set("A fájl sikeresen feltöltve",'success');
                    redirect('internal/qso/upload');
                }else{
                    $this->Msg->set("Hiba történt a file feltöltése során",'danger');
                    redirect('internal/qso/upload');
                }
            }
        }    
    }

    public function profile($id = null){
        $this->User->checkLogin();
        if($id == null || $id == "login" || $id == "password" || $id == "personal" || $id == "radio" || $id == "about" || $id == "marker"){
            if($id == null){ $id = "login"; };
            $this->data['segment'] = $id;
            $this->data['user'] = $this->db->select('callsign,opName,country,county,city,address,radios,antennas,freqs,aboutME,regDate,loginDate,perm,allowOnInternalMap,allowOnPublicMap,markerDesc,markerIcon')->from('users')->where('id',$this->Sess->getChain('id','user'))->get()->result_array()[0];
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
            $this->User->checkLogin();
            $this->form_validation->set_rules('oldPW', 'Jelenlegi jelszó', 'trim|required|min_length[8]|max_length[32]', $this->errors);
            $this->form_validation->set_rules('newPW', 'Új jelszó', 'trim|required|min_length[8]|max_length[32]', $this->errors);
            $this->form_validation->set_rules('newPWRep', 'Új jelszó megerősítése', 'trim|required|min_length[8]|max_length[32]|matches[newPW]', $this->errors);
            if($this->form_validation->run()){
                $this->User->changePassword();
            }else{
                $this->Msg->set(validation_errors(), "danger");
                redirect('internal/profile/password');
            }
        }
        public function updatePersonal(){
            $this->User->checkLogin();
            $this->form_validation->set_rules('country', 'Ország', 'trim|required', $this->errors);
            $this->form_validation->set_rules('county', 'Megye', 'trim|required', $this->errors);
            $this->form_validation->set_rules('city', 'Város', 'trim|required', $this->errors);
            $this->form_validation->set_rules('address', 'Cím', 'trim', $this->errors);
            if($this->form_validation->run()){
                $this->User->updatePersonal();
            }else{
                $this->Msg->set(validation_errors(), "danger");
                redirect('internal/profile/personal');
            }
        }
        public function updateMarker(){
            $this->User->checkLogin();
            $this->form_validation->set_rules('allowOnInternalMap', 'Látszik a belső térképen', 'trim|required', $this->errors);
            $this->form_validation->set_rules('allowOnPublicMap', 'Látszik a publikus térképen', 'trim|required', $this->errors);
            $this->form_validation->set_rules('markerDesc', 'Marker leírás', 'trim|required', $this->errors);
            $this->form_validation->set_rules('markerIcon', 'Marker ikon', 'trim|required', $this->errors);
            if($this->form_validation->run()){
                $this->User->updateMarker();
            }else{
                $this->Msg->set(validation_errors(), "danger");
                redirect('internal/profile/marker');
            }
        }
        public function updateRadios(){$this->User->checkLogin(); $this->User->updateRadios(); }
        public function updateAbout(){$this->User->checkLogin(); $this->User->updateAbout(); }

    public function downloads($cat = null, $subcat = null, $file = null){        
        $this->data['page'] = $this->thm . "pages/dl_index";
        if(!isset($_GET['search'])){
            if($cat == null){
                $this->data['segment'] = "catlist";
                $this->data['level'] = 0;
                $this->data['current'] = 'main';
                $this->data['parent'] = null;
                $this->data['childrens'] = $this->Downloads->listCategory(0);
            }elseif($cat != null && $subcat == null && $file == null){
                $this->data['segment'] = "catlist";
                $this->data['level'] = 1;
                $this->data['current'] = $this->Downloads->getCurrentCategory($cat);
                $this->data['childrens'] = $this->Downloads->listCategory($this->data['current']['id']);
                $this->data['files'] = $this->Downloads->listFiles($this->data['current']['id']);
            }elseif($cat != null && $subcat != null && $file == null){
                if($this->Downloads->checkCategoryIfExists($subcat)){
                    $this->data['segment'] = "catlist";
                    $this->data['level'] = 2;
                    $this->data['current'] = $this->Downloads->getCurrentCategory($subcat);
                    $this->data['parent'] = $this->Downloads->getCurrentCategory($cat);
                    $this->data['childrens'] = $this->Downloads->listCategory($this->data['current']['id']);
                    $this->data['files'] = $this->Downloads->listFiles($this->data['current']['id']);
                }else{
                    $this->data['segment'] = "fileDetails";
                    $this->data['cat'] = $this->Downloads->getCurrentCategory($cat);
                    $this->data['file'] = $this->Downloads->getFile($subcat);
                    $_SESSION['file'] = base64_encode($this->uri->uri_string());
                }
            }elseif($cat != null && $subcat != null && $file != null){
                $this->data['segment'] = "fileDetails";
                $this->data['cat'] = $this->Downloads->getCurrentCategory($cat);
                $this->data['subcat'] = $this->Downloads->getCurrentCategory($subcat);
                $this->data['file'] = $this->Downloads->getFile($file);
                $_SESSION['file'] = base64_encode($this->uri->uri_string());
            };
        }else{
            $this->data['segment'] = "search";
            $this->data['files'] = $this->Downloads->search(htmlentities($_GET['search']));
        }
        $this->load->view($this->thm . "frame", $this->data);
    }
    public function getFile($url){
        if(isset($_SESSION['file'])){
            $file = $this->Downloads->getFile($url);
            $dlC = $file['dl_counter'] + 1;
            $this->Db->update("dl_files",array("dl_counter"=>$dlC),array("url"=>$url));
            unset($_SESSION['file']);
            header('Location: ' . base_url() . str_replace('./','',$file['dlURL']));            
        }else{
            redirect('internal/downloads');
        };
    }
}