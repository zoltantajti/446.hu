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
    public function getInternal()
    {
        $html = "";
        foreach($this->db->select("id,parent,icon,title,alias,isPublic")->from('navbar')->where('parent',0)->where('isPublic',0)->get()->result_array() as $item){
            $html .= $this->genLink($item);
        };
        return $html;
    }


    private function checkChildren($id, $public = 1)
    {
        return $this->db->select("id")->from('navbar')->where('parent',$id)->where('isPublic',$public)->count_all_results() > 0 ? true : false;
    }
    private function checkActive($link){
        if($link == "base_url()" && uri_string() == ""){ return "active"; };
        if($link == uri_string()){ return "active"; };
        return false;
    }
    private function makeIcon($icon){
        return ($icon != null) ? '<i class="' . $icon . '"></i>' : null;
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
        $link = '';
        if($this->checkChildren($item['id'],$item['isPublic'])){
            $link .= '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
            $link .= $this->makeIcon($item['icon']);
            $link .= " " . $item['title'] . '</a>';
            $link .= '<ul class="dropdown-menu">';
            $link .= $this->genChildren($item);
            $link .= '</ul></li>';
        }else{        
            $link .= '<li class="nav-item"><a class="nav-link ' . $this->checkActive($item['alias']) . '" href="'.$this->prepareUri($item['alias']).'">';
            $link .= $this->makeIcon($item['icon']);
            $link .= " " . $item['title'] . '</a></li>';
        };
        return $link;
    }
    private function genChildren($parent){
        $child = '';
        foreach($this->db->select('id,icon,title,alias')->from('navbar')->where('parent', $parent['id'])->get()->result_array() as $item){
            $child .= '<li><a class="dropdown-item" href="'.$this->prepareUri($item['alias']).'">';
            $child .= $this->makeIcon($item['icon']);
            $child .= " " . $item['title'];
            $child .= '</a></li>';
        };
        return $child;
    }
}