<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['(:any)'] = 'Pub/index';
$route['default_controller'] = 'Pub';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
