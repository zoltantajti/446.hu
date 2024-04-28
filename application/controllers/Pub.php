<?php 
class Pub extends CI_Controller{
    protected $thm = "public/";
    private $data = array();

    public function __construct()
    {
        parent::__construct();
        if($this->Banns->check($_SERVER['REMOTE_ADDR'])){
            $id = $this->Banns->makeTrace($_SERVER['REMOTE_ADDR']);
            $this->load->view('errors/html/error_403', array(
                'heading' => 'Nem tekintheted meg az oldalt!',
                'message' => 'A biztonsági házirent tiltja, hogy megtekintsd az oldalt!<br/><br/>
                Ha úgy gondolod, hiba történt, kélrek jelezd felénk az alábbi hivatkozási számmal: 
                <center><b>' . $id . '</b></center>',
            ));
        };
        $this->data['thm'] = $this->thm;
    }
    private function render($page, $data){
        $this->load->view($this->thm . $page, $data);
    }
    public function index()
    {
        $this->data['p'] = "index";
        $this->render("index", $this->data);
    }
}