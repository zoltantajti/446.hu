<?php
class Internal extends CI_Controller {
    private $thm = "internal/";
    private $data = array();

    public function __construct(){
        parent::__construct();
    }

    public function index() {
        $this->User->checkLogin();
    }

    public function login() {
        $this->form_validation->set_rules('username', 'Felhasználónév vagy hívójel', 'required|trim');
        $this->form_validation->set_rules('password', 'Jelszó', 'required|trim|min_length[8]|max_length[32]');
        if($this->form_validation->run() == FALSE){
            $this->load->view($this->thm . "login/login", $this->data);
        }else{
            $this->User->doLogin($this->input->post());
        }
    }
    public function logout(){
        $this->User->doLogout();
    }
    public function lostpassword($method = "clear", $hash = null){
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
}