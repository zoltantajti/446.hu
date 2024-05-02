<?php

include(APPPATH . 'third_party/SimpleXLS/SimpleXLSX.php');
use Shuchkin\SimpleXLSX;

class Nmhh extends CI_Model {

    protected $remotePath = "https://nmhh.hu/amator/call_sign_book.xml";
    protected $localPath = "./assets/data/call_sign_book.xls";

    public function __construct(){
        parent::__construct();
    }

    public function updateCallSignBook(){
        $this->downloadBook();
        $xml = simplexml_load_file($this->localPath);
        $i = 0;
        foreach($xml->Worksheet->Table->Row as $row){
            if($i > 0){
                $_dateTime = DateTime::createFromFormat("Y-m-d\TH:i:s.u", (string)$row->Cell[6]->Data);
                $dateTime = $_dateTime->format("Y-m-d H:i:s");

                $item = array(
                    "callsign" => (string)$row->Cell[0]->Data,
                    "name" => (string)$row->Cell[1]->Data,
                    "country" => (string)$row->Cell[2]->Data,
                    "city" => (string)$row->Cell[3]->Data,
                    "address" => (string)$row->Cell[4]->Data,
                    "certNo" => (string)$row->Cell[5]->Data,
                    "validTo" => $dateTime,
                    "category" => (string)$row->Cell[7]->Data,
                    "morse" => ((string)$row->Cell[8]->Data == "Morse") ? 1 : 0,
                    "nmhhMember" => (string)$row->Cell[9]->Data,
                    "status" => (string)$row->Cell[10]->Data
                );
                
                if($this->db->select('callsign')->from('callsignbook')->where('callsign', $item['callsign'])->count_all_results() == 0){                
                    $item['createdAt'] = date("Y-m-d H:i:s");
                    $this->Db->insert("callsignbook", $item);
                }else{
                    $callsign = $item['callsign'];
                    unset($item['callsign']);
                    $item['updatedAt'] = date("Y-m-d H:i:s");
                    $this->Db->update("callsignbook", $item, array("callsign" => $callsign));
                }
            };
            $i++;
        }
    }

    private function downloadBook(){
        file_put_contents($this->localPath, fopen($this->remotePath,"r"));   
    }

    public function validateCallsign($callsign){
        if($this->db->select('callsign')->from('callsignbook')->where('callsign',$callsign)->count_all_results() == 1){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}