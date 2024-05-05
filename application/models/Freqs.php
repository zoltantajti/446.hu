<?php
class Freqs extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function getList($mit){
        $table = "freq_" . $mit;
        $rows = $this->db->select('id,name,freq,ctcs,dcs,duplex,offset,comment')->from($table)->get()->result_array();
        $html = '<table class="table mb-3"><thead><tr>';
            $html.='<th>Név</th><th>Frekvencia</th><th>CTCS</th><th>DCS</th><th>Eltolás</th>';
        $html.= '</tr><thead><tbody>';
        foreach($rows as $row){
            $html.='<tr class="' . (($row['id'] % 2) ? "freq-table-even" : "freq-table-odd") . '">';
                $html.='<td>'.$row['name'].'</td>';
                $html.='<td>'.$row['freq'].' MHz</td>';
                $html.='<td>'.(($row['ctcs'] != null) ? $row['ctcs'] . ' Hz' : '-' ) . '</td>';
                $html.='<td>'.(($row['dcs'] != null) ? $row['dcs'] : '-') .'</td>';
                $html.='<td>'.(($row['duplex'] != "off") ? $row['duplex'] . $row['offset'] : '-' ) .'</td>';
            $html.= '</tr>';
        };
        $html.= '</tbody></table>';
        $html.= 'Exportálás: <a href="export/csv/cottonEar" target="_blank"><i class="fa-solid fa-file-csv"></i></a>';
        return $html;
    }
}