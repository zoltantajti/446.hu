<?php
class Map extends CI_Controller {
    protected $thm = "public/";
    private $data = array();

    public function __construct(){
        parent::__construct();
    }

    public function publicMap()
    {
        $this->data['css'] = '<link rel="stylesheet" media="screen" href="./assets/js/leaflet/leaflet.css" />
        <link rel="stylesheet" media="screen" href="./assets/js/leaflet/extra-markers/css/leaflet.extra-markers.min.css" />
		<link rel="stylesheet" media="screen" href="./assets/js/leaflet/weather/Leaflet.Weather.css" />
        ';
        $this->data['js'] = '<script src="./assets/js/leaflet/leaflet.js"></script>
		<script src="./assets/js/leaflet_providers/leaflet-providers.js"></script>
		<script src="./assets/js/map/Maidenhead.js"></script>
		<script src="./assets/js/leaflet/extra-markers/js/leaflet.extra-markers.js"></script>
		<script src="./assets/js/leaflet/weather/Leaflet.Weather.js"></script>
		<script src="./assets/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
		<script type="module" src="./assets/js/map/index.js?ref=public"></script>';
        $this->load->view($this->thm . 'map/index', $this->data);
    }
}