<?php
$this->load->view('_global/head');
$this->load->view('_global/navbar');

if(uri_string() != "" || uri_string() != "index"){
    $this->load->view('_global/hero');
};

$this->load->view($thm . "p_" . $p);

$this->load->view('_global/footer');