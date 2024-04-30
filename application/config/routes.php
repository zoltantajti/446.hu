<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*Internal segment*/
$route['internal'] = 'Internal/index';
$route['register/(:num)'] = 'Internal/register/$1';

/*Public segment*/
$route['public/terkep'] = 'Map/publicMap';
$route['default_controller'] = 'Pub';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
