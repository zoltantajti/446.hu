<?php
class Navbar extends CI_Model {
    public function __construct(){
        parent::__construct();
    }

    public function getPublicIndex()
    {
        $html = "";
        foreach($this->db->select("id,parent,icon,title,alias")->from('navbar')->where('parent',0)->where('isPublic',1)->get()->result_array() as $item){
            $html .= $this->genIndexLink($item); 
        };
        return $html;
    }
    public function getPublic()
    {
        $html = "";
        foreach($this->db->select("id,parent,icon,title,alias")->from('navbar')->where('parent',0)->where('isPublic',1)->get()->result_array() as $item){
            $html .= $this->genLink($item); 
        };
        return $html;
    }


    private function checkChildren($id, $public = 1)
    {
        return $this->db->get("id")->from('navbar')->where('parent',$id)->where('isPublic',$public)->count_all_results() > 0 ? true : false;
    }
    private function checkActive($link){
        if($link == "base_url()" && uri_string() == ""){ return "active"; };
        if($link == uri_string()){ return "active"; };
        return false;
    }
    private function makeIcon($icon){
        return '<i class="' . $icon . '"></i>';
    }
    private function prepareUri($alias){
        if($alias == "base_url()"){ return base_url(); }
        else{ return $alias; }
    }
    private function genIndexLink($item){
        $link = '<a class="nav-link fw-bold py-1 px-0 ' . $this->checkActive($item['alias']) . '" href="'.$this->prepareUri($item['alias']).'">';
        $link .= $this->makeIcon($item['icon']);
        $link .= $item['title'] . '</a>';
        return $link;
    }
    private function genLink($item)
    {
        $link = '<li class="nav-item"><a class="nav-link ' . $this->checkActive($item['alias']) . '" href="'.$this->prepareUri($item['alias']).'">';
        $link .= $this->makeIcon($item['icon']);
        $link .= $item['title'] . '</a></li>';
        return $link;
    }
}