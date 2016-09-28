<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['api/admin/(:any)/(:any)/(:num)'] = 'api/admin/$1/$2/id/$3';
$route['api/admin/(:any)/(:any)/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/admin/$1/$2/id/$3/format/$5$6';
$route['api/admin/(:any)/(:any)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/admin/$1/$2/format/$4$5';
// $route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
// $route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
