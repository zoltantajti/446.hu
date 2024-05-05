<?php
class CExporter extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->Model('Exporter');
    }

    public function csv($mit){
        if($mit == "cottonEar"){
            $this->Exporter->exportCottonEarToCSV();
        }
    }
}