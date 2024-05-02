<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*Internal segment*/
$route['internal'] = 'Internal/index';
$route['internal/register/(:num)'] = 'Internal/register/$1';
$route['internal/events'] = 'Internal/events';
$route['internal/events/(:num)'] = 'Internal/events/$1';
$route['internal/news'] = 'Internal/news';
$route['internal/news/(:num)'] = 'Internal/news/$1';
$route['internal/new/(:any)'] = 'Internal/new/$1';
$route['internal/page/(:any)'] = 'Internal/page/$1';

/*Rest*/
$route['Rest/(:any)'] = 'Rest/$1';

/*Admin*/
$route['admin/users/list'] = 'Admin/users/list/-1';
$route['admin/users/(inactivate|activate)/(:num)'] = 'Admin/users/$1/$2';
$route['admin/users/(:any)'] = 'Admin/users/list/-1/$1';

/*Public segment*/
$route['public/terkep'] = 'Map/publicMap';
$route['default_controller'] = 'Pub';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
