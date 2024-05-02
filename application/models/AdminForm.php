<?php
class AdminForm extends CI_Model {
    public function build($tabla,$meta,$values = array()){
        $html = form_open($meta['action'], array('method'=>$meta['method']));
        $html .= $this->fields($this->db->field_data($tabla), $values);
        $html .= form_submit('submit', $meta['btnText'], 'class="btn btn-success"');
        $html .= form_close();
        return $html;
    }
    private function fields($dataSet, $values){
        $html = '';
        foreach($dataSet as $k=>$v){
            $param = $this->db->select('*')->from('options')->where('_key', $v->name)->get()->result()[0];
            $html .= '<div class="mb-3">';
            $html .= $this->label($v->name,$param->_value,$param->_hint);
            switch($param->_fieldParam){
                case "text": $html .= $this->text($v->name, @$values[$v->name], $param->_args); break;
                case "password": $html .= $this->password($v->name, @$values[$v->name], $param->_args); break;
                case "select": $html .= $this->select($v->name,$param->_fieldSource,$param->_args,@$values[$v->name]); break;
                case "imagemanager": $html .= $this->imageManager($v->name, @$values[$v->name]); break;
                case "textarea": $html .= $this->textarea($v->name, @$values[$v->name]); break;
            }
            $html .= '</div>';
        }
        return $html;
    }
    /*FieldTypes*/
    private function label($id,$text,$hint = null){ 
        if($hint != null){
            $title = '<i class="fa-regular fa-circle-question" data-bs-toggle="tooltip2" data-bs-placement="top" onClick="openHint(\''.$hint.'\');"></i>'; }else{ $title = ""; };
        return '<label for="'.$id.'" class="form-label">'.$text . ' ' . $title . '</label>'; 
    }
    private function text($id,$value = "",$args = ""){ 
        return '<input '.$args.' type="text" class="form-control" id="'.$id.'" name="'.$id.'" value="'.$value.'">';
    }
    private function password($id,$value = "",$args = ""){ return '<input type="password" class="form-control" id="'.$id.'" name="'.$id.'">'; }
    private function select($id,$source,$args,$value){
        $html = '<select id="'.$id.'" name="'.$id.'" class="form-select">';
        $source = explode(":", $source);
        if($source[0] == "args"){
            foreach(explode("|", $args) as $v){
                $_v = explode(":",$v);
                $key = $_v[0];
                $val = $_v[1];
                $selected = ($value == $key) ? "selected" : "";
                $html .= '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
            };
        }elseif($source[0] == "db"){
            $_args = explode("|", $args);
            $selected = ($value == 0) ? "selected" : "";
            $html .= '<option value="0" '.$selected.'>Nincs</option>';
            foreach($this->db->select(implode(",", $_args))->from($source[1])->get()->result() as $item){
                $selected = ($value == $item->{$_args[0]}) ? "selected" : "";
                $html .= '<option value="'.$item->{$_args[0]}.'" '.$selected.'>'.$item->{$_args[1]}.'</option>';
            };
        };
        $html.= '</select>';
        return $html;
    }
    private function textarea($id, $value = "", $args = ""){
        $html = '<textarea class="form-control" '.$args.' id="'.$id.'" name="'.$id.'">'.$value.'</textarea>';
        $html.= '<script src="./assets/js/tinymce/tinymce.min.js"></script>';
        $html.= '<script src="./assets/js/tinymce/index.js"></script>';
        return $html;
    }
    /*Image manager*/
    private function imageManager($id,$value = "")
    {
        $html = '<div class="input-group">';
        $html .= '<input type="text" id="'.$id.'" name="'.$id.'" value="'.$value.'" class="form-control image-manager-target" aria-describedby="button-addon2">';
        $html .= '<button class="btn btn-outline-secondary" title="Kép tallózása" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#image-manager-modal"><i class="fa-regular fa-image"></i></button>';
        $html .= $this->imageManagerModal($id);
        $html .= '<script src="https://code.germanov.dev/context_menu/context_menu.umd.cjs"></script>';
        $html .= '<script src="./assets/js/imagemanager/index.js"></script>';
        $html .= '</div>';
        return $html;
    }
    private function imageManagerModal($id){
        $html = '<div class="modal fade" id="image-manager-modal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen ">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Fénykép tallózása</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="modal-close-button"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10" id="image-manager-controller">
                        <div class="row"id="image-manager-frame"></div>
                    </div>
                    <div class="col-md-2" id="image-manager-info-frame">
                        <b id="image-name">%ImageName%</b><br/>
                        Fájlméret: <b id="image-size"></b>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="selected-image-path" />
                <button type="button" class="btn btn-primary" id="image-upload-segment-button">Új kép feltöltése</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                <button type="button" class="btn btn-success" id="pick-image">Mentés</button>
            </div>
          </div>
        </div>
      </div>';
        return $html;
    }

}