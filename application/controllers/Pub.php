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
        $this->data['css'] = "";
        $this->data["js"] = "";
    }
    private function render($page, $data){
        $this->load->view($this->thm . $page, $data);
    }
    public function index()
    {
        $this->data['p'] = "index";
        $this->render("index", $this->data);
    }
    public function newsItem($alias){
        if($this->db->select('id')->from('news')->where('alias', $alias)->count_all_results() == 1){
            $this->data['item'] = $this->db->select('*')->from('news')->where('alias',$alias)->get()->result_array()[0];
            $this->data['meta'] = array(
                'description' => $this->data['item']['metadesc'],
                'keywords' => $this->data['item']['metakey'],
                'robots' => 'index, follow',
                'revisit-after' => '1 Hour'
            );
            $this->data['p'] = "newsItem";
        }else{
            $this->data['p'] = "404";
        };
        $this->render("index", $this->data);
    }
    public function eventItem($alias){
        if($this->db->select('id')->from('events')->where('seoLink', $alias)->count_all_results() == 1){
            $this->data['item'] = $this->db->select('*')->from('events')->where('seoLink',$alias)->get()->result_array()[0];
            $this->data['p'] = "eventItem";
        }else{
            $this->data['p'] = "404";
        };
        $this->render("index", $this->data);
    }
}