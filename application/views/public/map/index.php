<?php
$this->load->view('_global/head');

if(uri_string() != "" || uri_string() != "index"){
    $this->load->view('_global/navbar');
}
?>
<div id="windy"></div>
<?php
$this->load->view('_global/footer');
?>