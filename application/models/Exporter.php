<?php
class Exporter extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function exportCottonEarToCSV(){
        $rows = array();
        array_push($rows, "Location,Name,Frequency,Duplex,Offset,Tone,rToneFreq,cToneFreq,DtcsCode,DtcsPolarity,RxDtcsCode,CrossMode,Mode,TStep,Skip,Power,Comment,URCALL,RPT1CALL,RPT2CALL,DVCODE\r\n");
        $now = date("Y-m-d_H-i_s");
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="gumiful_'.$now.'.csv"');
        $index = 0;
        $sep = ",";
        foreach($this->db->select('*')->from('freq_cottonear')->get()->result_array() as $item){
            $duplex = ($item['duplex'] == "off") ? "" : $item['duplex'];
            $offset = ($item['offset'] == null) ? "0.000000" : $item['offset'];
            $tone = ($item['ctcs'] == null) ? "" : "Tone";
            $ctcs = ($item['ctcs'] == null) ? "88.5" : $item['ctcs'];
            $dcs = ($item['dcs'] == null) ? "023" : $item['dcs'];
            $row = $index . $sep . $item['name'] . $sep . $item['freq'] . $sep . $duplex . $sep . $offset . $sep . $tone . $sep;
            $row .= $ctcs . $sep .  $ctcs . $sep . $dcs . $sep . "NN" . $sep . $dcs . $sep . "Tone->Tone,FM,5,,4.0W,,,,,\r\n";
            array_push($rows, $row);
            $index++;
        };        
        foreach($rows as $row){
            echo($row);
        };
    }
    public function exportPMRToCSV(){
        $rows = array();
        array_push($rows, "Location,Name,Frequency,Duplex,Offset,Tone,rToneFreq,cToneFreq,DtcsCode,DtcsPolarity,RxDtcsCode,CrossMode,Mode,TStep,Skip,Power,Comment,URCALL,RPT1CALL,RPT2CALL,DVCODE\r\n");
        $now = date("Y-m-d_H-i_s");
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="pmr_'.$now.'.csv"');
        $index = 0;
        $sep = ",";
        foreach($this->db->select('*')->from('freq_pmr')->get()->result_array() as $item){
            $duplex = ($item['duplex'] == "off") ? "" : $item['duplex'];
            $offset = ($item['offset'] == null) ? "0.000000" : $item['offset'];
            $tone = ($item['ctcs'] == null) ? "" : "Tone";
            $ctcs = ($item['ctcs'] == null) ? "88.5" : $item['ctcs'];
            $dcs = ($item['dcs'] == null) ? "023" : $item['dcs'];
            $row = $index . $sep . $item['name'] . $sep . $item['freq'] . $sep . $duplex . $sep . $offset . $sep . $tone . $sep;
            $row .= $ctcs . $sep .  $ctcs . $sep . $dcs . $sep . "NN" . $sep . $dcs . $sep . "Tone->Tone,FM,5,,4.0W,,,,,\r\n";
            array_push($rows, $row);
            $index++;
        };        
        foreach($rows as $row){
            echo($row);
        };
    }
}