<?php
class Downloads extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function listCategory($parent = 0){
        return $this->db->select('id,parent,name,url,image')->from('dl_categories')->where('parent',$parent)->get()->result_array();
    }
    public function checkCategoryIfExists($url){
        $num = $this->db->select('id')->from('dl_categories')->where('url',$url)->count_all_results();
        return $num == 1 ? true : false;
    }
    public function getCurrentCategory($url){
        return $this->db->select('id,parent,name,url,image')->from('dl_categories')->where('url',$url)->get()->result_array()[0];
    }
    public function listFiles($id){
        return $this->db->select('id,name,url,image')->from('dl_files')->where('category',$id)->get()->result_array();
    }
    public function getFile($url){
        return $this->db->select('id,name,url,image,description,dlURL,dl_counter,createdAt,modifiedAt,createdUser')->from('dl_files')->where('url',$url)->get()->result_array()[0];        
    }

    public function search($cond){
        return $this->db->select('category,name,url,image,dl_counter,createdAt,createdUser')->from('dl_files')->like('name',$cond)->or_like('description',$cond)->get()->result_array();
    }

    public function breadcrumb(){
        $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        if(!isset($_GET)){
            $params = $this->uri->segment_array();
            foreach($params as $k=>$item){
                $max = count($params);
                $link = base_url();
                if($k == 1){ $link .= $item; };
                if($k == 2){ $link .= $params[1] . "/" . $params[2]; };
                if($k == 3){ $link .= $params[1] . "/" . $params[2] . "/" . $params[3]; };
                if($k == 4){ $link .= $params[1] . "/" . $params[2] . "/" . $params[3] . "/" . $params[4]; };            
                if($k == 5){ $link .= $params[1] . "/" . $params[2] . "/" . $params[3] . "/" . $params[4] . "/" . $params[5]; }; 
                if($k < $max){
                    $html .= '<li class="breadcrumb-item"><a href="'.$link.'">'.$this->getNamedElement($item).'</a></li>';
                }else{
                    $html .= '<li class="breadcrumb-item">'.$this->getNamedElement($item).'</li>';
                }
            }
        }else{
            $html .= '<li class="breadcrumb-item"><a href="'.base_url() . 'internal"><i class="fa fa-fw fa-home"></i></a></li>';
            $html .= '<li class="breadcrumb-item"><a href="'.base_url() . 'internal/downloads"><i class="fa fa-fw fa-download"></i> Letöltések</a></li>';
            $html .= '<li class="breadcrumb-item">Keresés eredménye</li>';
        }
        $html.= '</ol></nav>';
        return $html;
    }
        private function getNamedElement($element){
            if($element == "internal"){ return '<i class="fa fa-fw fa-home"></i>'; }
            elseif($element == "downloads"){ return '<i class="fa fa-fw fa-download"></i> Letöltések'; }
            else{
                if($this->checkCategoryIfExists($element)){
                    return $this->getCurrentCategory($element)['name'];
                }else{
                    return $this->getFile($element)['name'];
                }
            }
        }
    
        public function fetchCategoryTree($category)
        {
            $cat = $this->db->select('id,parent,name,url')->from('dl_categories')->where('id',$category)->get()->result_array()[0];
            if($cat['parent'] == 0){
                return '<a href="internal/downloads/' . $cat['url'] . '">' . $cat['name'] . '</a>';
            }else{
                $scat = $this->db->select('id,parent,name,url')->from('dl_categories')->where('id',$cat['parent'])->get()->result_array()[0];
                return '<a href="internal/downloads/' . $scat['url'] . '">' . $scat['name'] . '</a> / <a href="internal/downloads/' . $cat['url'] . '">' . $cat['name'] . '</a>';
            }
        }
        public function fetchLinkTree($url,$category)
        {
            $html = 'internal/downloads/';
            $cat = $this->db->select('id,parent,name,url')->from('dl_categories')->where('id',$category)->get()->result_array()[0];
            if($cat['parent'] == 0){
                $html .= $cat['url'] . '/';
            }else{
                $scat = $this->db->select('id,parent,name,url')->from('dl_categories')->where('id',$cat['parent'])->get()->result_array()[0];
                $html .= $scat['url'] . '/' . $cat['url'] . '/';
            }
            $html .= $url;
            return $html;
        }
}