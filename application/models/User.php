<?php
class User extends CI_Model {
    public function __construct(){
        parent::__construct();
    }
    public function countAll(){
        return $this->db->select('id')->from('users')->count_all_results();
    }
    public function checkLogin(){
        if(!$this->Sess->has("user") || $this->Sess->getSub("user","login") != true){
            redirect('internal/login');
        };
    }
    public function hasLoggedIn(){
        return $this->Sess->getSub("login","user");
    }
    public function isLoggedIn(){
        if($this->Sess->has("user") || $this->Sess->getSub("user","login") == true){
            redirect('internal');
        };
    }
    public function hasPerm($need){
        if($this->Sess->getSub('user','perm') >= $need){
            return true;
        }else{
            return false;
        };
    }
    public function hasAccess($need){
        if($this->Sess->has("user") && $this->Sess->getSub("user","login") == true)
        {
            if($this->Sess->getSub('user','perm') >= $need){
                return true;
            }else{
                redirect('internal');
            }
        }else redirect('internal/login');
    }
    public function getName() {
        return $this->Sess->getSub('user','opName');
    }
    public function getFName() {
        return $this->Sess->getSub('user','name');
    }
    public function autoLogin()
    {
        $id = $this->Cookie->get('remember_id');
        $cs = $this->Cookie->get('remember_user');
        $user = $this->db->select('id,callsign,opName,name,perm')->from('users')->where('id', $id)->where('callsign', $cs)->get()->result_array()[0];
        $user['loginIP'] = $_SERVER['REMOTE_ADDR'];
        $user['loginDate'] = date("Y-m-d H:i:s");
        $_SESSION['user'] = $user;
        $_SESSION['user']['login'] = true;
        $this->Db->update("users", array("loginIP"=>$user['loginIP'], "loginDate" => $user['loginDate']), array("id" => $id));
        $this->Logs->make("Login:Success", "Felhasználó belépett: {$user['opName']}, {$user['loginIP']} IP címről {$user['loginDate']}");
        redirect('internal');
    }
    public function doLogin($p){
        $user = $p['username'];
        $password = $this->Encryption->hash($p['password']);
        if($this->userIsExists($user)){
            if($this->passwordCheck($user,$password)){
                if($this->userIsActive($user)){
                    if($this->checkPerm($user)){
                        $user = $this->db->select('id,callsign,opName,name,perm')->from('users')
                            ->where('callsign', $user)->or_where('opName', $user)->or_where('email', $user)
                            ->where('password', $password)->where('active', 1)->where('perm >= ', 1)
                            ->get()->result_array()[0];
                        $user['loginIP'] = $_SERVER['REMOTE_ADDR'];
                        $user['loginDate'] = date("Y-m-d H:i:s");
                        $_SESSION['user'] = $user;
                        $_SESSION['user']['login'] = true;
                        $this->Db->update("users", array("loginIP"=>$user['loginIP'], "loginDate" => $user['loginDate']), array("id" => $user['id']));
                        $this->Logs->make("Login:Success", "Felhasználó belépett: {$user['opName']}, {$user['loginIP']} IP címről {$user['loginDate']}");

                        if(@$p['rememberME'] == "on"){
                            $this->Cookie->set('remember_user', $user['callsign']);
                            $this->Cookie->set('remember_id', $user['id']);
                        };
                        redirect('internal');
                    }else{
                        $this->Logs->make("Login:Fail:Blocked", "Sikertelen belépés: {$user} - {$p['password']} adatokkal {$_SERVER['REMOTE_ADDR']} IP címről! Korlátozott felhasználó!");
                        $this->Msg->set('Sikertelen belépés', 'danger');
                        redirect('internal/login');
                    }
                }else{
                    $this->Logs->make("Login:Fail:Inactive", "Sikertelen belépés: {$user} - {$p['password']} adatokkal {$_SERVER['REMOTE_ADDR']} IP címről! Inaktív felhasználó!");
                    $this->Msg->set('Sikertelen belépés', 'danger');
                    redirect('internal/login');    
                }
            }else{
                $this->Logs->make("Login:Fail:BadPassword", "Sikertelen belépés: {$user} - {$p['password']} adatokkal {$_SERVER['REMOTE_ADDR']} IP címről! Hibás jelszó!");
                $this->Msg->set('Sikertelen belépés', 'danger');
                redirect('internal/login');
            }
        }else{
            $this->Logs->make("Login:Fail:UserNotFound", "Sikertelen belépés: {$user} - {$p['password']} adatokkal {$_SERVER['REMOTE_ADDR']} IP címről! Nincs ilyen felhasználó!");
            $this->Msg->set('Sikertelen belépés', 'danger');
            redirect('internal/login');
        };
    }
    public function doLogout()
    {
        if($this->Cookie->has('remember_id') && $this->Cookie->has('remember_user')){
            $this->Cookie->rem('remember_id');
            $this->Cookie->rem('remember_user');
        }
        unset($_SESSION['user']);
        redirect('internal/login');
    }
    public function doPasswordReset($p){
        if($this->db->select('id')->from('users')->where('email',$p['email'])->count_all_results() == 1){
            if($this->db->select('id')->from('users')->where('email',$p['email'])->where('active',1)->count_all_results() == 1){
                if($this->db->select('id')->from('users')->where('email',$p['email'])->where('active',1)->where('perm >=',1)->count_all_results() == 1){
                    $hash = uniqid();
                    $this->Db->update("users",array("hash" => $hash), array("email" => $p['email']));
                    $this->Logs->make("ResetPW:Success", "Jelszópótlás: Email: {$p['email']}, IP: {$_SERVER['REMOTE_ADDR']}");
                    $link = base_url() . "internal/lostpassword/reset/" . $hash;
                    $user = $this->db->select('name')->from('users')->where('email', $p['email'])->get()->result_array()[0];
                    $this->Email->sendOne($p['email'], "lost_password", array("name" => $user['name'], "link" => $link));
                    $this->Msg->set('Amennyiben van a megadott e-mail címhez hozzárendelt fiók, kiküldjük a jelszó törléséhez szükséges levelet!', 'success');
                    redirect('internal/lostpassword/clear');
                }else{
                    $this->Logs->make("ResetPW:Fail:Blocked", "Sikertelen jelszópótlás. Blokkolt fiók! Email: {$p['email']}, IP: {$_SERVER['REMOTE_ADDR']}");
                    $this->Msg->set('Amennyiben van a megadott e-mail címhez hozzárendelt fiók, kiküldjük a jelszó törléséhez szükséges levelet!', 'success');
                    redirect('internal/lostpassword/clear');
                }                
            }else{
                $this->Logs->make("ResetPW:Fail:InactiveUser", "Sikertelen jelszópótlás.Inaktív fiók! Email: {$p['email']}, IP: {$_SERVER['REMOTE_ADDR']}");
                $this->Msg->set('Amennyiben van a megadott e-mail címhez hozzárendelt fiók, kiküldjük a jelszó törléséhez szükséges levelet!', 'success');
                redirect('internal/lostpassword/clear');
            }
        }else{
            $this->Logs->make("ResetPW:Fail:MailNotFound", "Sikertelen jelszópótlás. Nincs ilyen e-mail cím! Email: {$p['email']}, IP: {$_SERVER['REMOTE_ADDR']}");
            $this->Msg->set('Amennyiben van a megadott e-mail címhez hozzárendelt fiók, kiküldjük a jelszó törléséhez szükséges levelet!', 'success');
            redirect('internal/lostpassword/clear');
        }
    }
    public function doPasswordModify($p, $hash){
        unset($p['password_rep']);
        $password = $this->Encryption->hash($p['password']);
        if($this->db->select('id')->from('users')->where('hash',$hash)->where('password',$password)->count_all_results() == 0){
            $user = $this->db->select('callsign,email')->from('users')->where('hash',$hash)->get()->result_array()[0];
            $this->Db->update("users", array("password" => $password, "hash" => null, "expired" => null), array("hash" => $hash));
            $this->Msg->set("Most már be tudsz lépni az <b>új jelszavad</b> használatával!", "success");
            $this->Logs->make("ResetPW:Modified", "{$user['callsign']} jelszava megváltozott! IP: {$_SERVER['REMOTE_ADDR']}");
            redirect('internal/login');
        }else{
            $this->Msg->set("Az új jelszó nem egyezhet meg a régivel!", "danger");
            redirect('internal/lostpassword/reset/' . $hash);
        };
    }
    private function userIsExists($user)
    {
        return $this->db->select('id')->from('users')->where('callsign',$user)->or_where('email',$user)->count_all_results() == 1 ? true : false;
    }
    private function passwordCheck($user, $password)
    {
        return $this->db->select('id')->from('users')->where('callsign',$user)->or_where('email',$user)
                    ->where('password',$password)->count_all_results() == 1 ? true : false;
    }
    private function userIsActive($user)
    {
        return $this->db->select('id')->from('users')->where('callsign',$user)->or_where('email',$user)
                    ->where('active', 1)->count_all_results() == 1 ? true : false;
    }
    private function checkPerm($user)
    {
        return $this->db->select('id')->from('users')->where('callsign',$user)->or_where('email',$user)
                    ->where('perm >=', 1)->count_all_results() == 1 ? true : false;
    }
    
    /*Internal*/
    public function changePassword()
    {
        $p = $this->input->post();
        $p['oldPW'] = $this->Encryption->hash($p['oldPW']);
        $p['newPW'] = $this->Encryption->hash($p['newPW']);
        unset($p['submit'],$p['newPWRep']);
        if($p['oldPW'] == $p['newPW']){
            $this->Msg->set("A jelenlegi jelszavad nem egyezhet meg az új jelszóval!", "danger");
            redirect('internal/profile/password');
        }else{
            if($this->db->select('id')->from('user_passwords')->where('userID', $this->Sess->getChain("id","user"))->count_all_results() == 1){
                $passwords = json_decode($this->db->select('prevPasswords')->from('user_passwords')->where('userID', $this->Sess->getChain("id", "user"))->get()->result_array()[0]['prevPasswords'], true);
                if(in_array($p['newPW'], $passwords, true)){
                    $this->Msg->set("Nem használhatod ezt a jelszót, mert szerepel az általad hassznált legutóbbi 5 használt jelszó között!", "danger");
                    redirect('internal/profile/password');
                }else{
                    $passwords[] = $p['oldPW'];
                    if(count($passwords) == 6){ unset($passwords[0]); }
                    $this->Db->update("user_passwords", array("prevPasswords" => json_encode($passwords)), array("userID" => $this->Sess->getChain("id","user")));
                    $this->Db->update("users", array("password" => $p['newPW']), array("id" => $this->Sess->getChain("id", "user")));
                    $this->Msg->set("Sikeres jelszómódosítás!", "success");
                    redirect('internal/profile/password');
                }
            }else{
                $array = array(
                    "userID" => $this->Sess->getChain("id","user"),
                    "prevPasswords" => json_encode(array($p['oldPW']))
                );
                $this->Db->insert("user_passwords", $array);
                $this->Db->update("users", array("password" => $p['newPW']), array("id" => $this->Sess->getChain("id", "user")));
                $this->Msg->set("Sikeres jelszómódosítás!", "success");
                redirect('internal/profile/password');
            }
        }
        print_r($p);
    }
    public function updatePersonal()
    {
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->update("users", $p, array("id" => $this->Sess->getChain("id","user")));
        $this->Msg->set("Sikeres adatmódosítás!", "success");
        redirect('internal/profile/personal');
    }
    public function updateRadios()
    {
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->update("users", $p, array("id" => $this->Sess->getChain("id","user")));
        $this->Msg->set("Sikeres adatmódosítás!", "success");
        redirect('internal/profile/radio');
    }
    public function updateAbout()
    {
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->update("users", $p, array("id" => $this->Sess->getChain("id","user")));
        $this->Msg->set("Sikeres adatmódosítás!", "success");
        redirect('internal/profile/about');
    }
    public function updateMarker()
    {
        $p = $this->input->post();
        unset($p['submit']);
        $this->Db->update("users", $p, array("id" => $this->Sess->getChain("id","user")));
        $this->Msg->set("Sikeres adatmódosítás!", "success");
        redirect('internal/profile/marker');
    }
    /*Admin*/
    public function getOwner(){
        return ($this->Sess->getChain("callsign", "user") == "92-es Zotya") ? true : false;
    }
    public function getToken(){
        return $this->Sess->get('__id');
    }
    public function getList($filter = null){
        $select = $this->db->select('id,callsign,opName,name,perm,active')->from('users');
        if($filter != null){
            $select->like('callsign',$filter)
                ->or_like('opName', $filter)
                ->or_like('email', $filter)
                ->or_like('name', $filter);
        }
        return $select->get()->result_array();
    }
    public function delete($id){
        $sor = $this->db->select('*')->where('id',$id)->from('users')->get()->result_array();
        $this->db->where('id',$id)->delete('users');
        $this->Logs->make("USER::Delete", $this->User->getName() . " törölte az alábbi felhasználót: ID:" . $id . ", Felhasználói adatok: " . json_encode($sor[0]));
        $this->Msg->set("Sikeres törlés!");
        redirect("admin/users");
    }
    public function getPermName(){
        $return = "";
        switch($this->perm){
            case 1: $return = "Felhasználó"; break;
            case 2: $return = "Tartalomfeltöltő"; break;
            case 3: $return = "Adminisztrátor"; break;
            case 99: $return = "Rendszergazda"; break;
        };
        return $return;
    }
    public function getPermById($id){
        switch($id){
            case "1": return "Felhasználó"; break;
            case "2": return "Tartalomfeltöltő"; break;
            case "3": return "Adminisztrátor"; break;
            case "99": return "Rendszergazda"; break;
        }
    }
    public function getNameById($id){
        return $this->db->select('callsign')->from('users')->where('id',$id)->get()->result_array()[0]['callsign'];
    }
   
}