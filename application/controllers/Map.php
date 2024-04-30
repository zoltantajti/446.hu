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
        <link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
        ';
        $this->data['js'] = '<script src="./assets/js/leaflet/leaflet.js"></script>
		<script src="./assets/js/leaflet_providers/leaflet-providers.js"></script>
		<script src="./assets/js/map/Maidenhead.js"></script>
		<script src="./assets/js/leaflet/extra-markers/js/leaflet.extra-markers.js"></script>
		<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
		<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
		<script src="https://cdn.tiny.cloud/1/ihuzhtbtyuzbwks4d8nuyl9bmu1uq25scfmxd8gjgmmqc5qz/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
		<script type="module" src="./assets/js/map/index.js?ref=public"></script>';
        $this->load->view($this->thm . 'map/index', $this->data);
    }
}