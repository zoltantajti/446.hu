<?php
class Internal extends CI_Controller {
    private $thm = "internal/";
    private $data = array();

    public function __construct(){
        parent::__construct();
    }

    public function index() {
        $this->User->checkLogin();

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
<script src="https://cdn.tiny.cloud/1/ihuzhtbtyuzbwks4d8nuyl9bmu1uq25scfmxd8gjgmmqc5qz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script type="module" src="./assets/js/map/index.js?ref=internal"></script>';
        $this->load->view($this->thm . 'frame', $this->data);
    }
}