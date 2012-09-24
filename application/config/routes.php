<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
 */
/*
$route['oauth/apply'] = '/oauth/apply'; 
$route['oauth/register'] = '/oauth/register'; 
$route['oauth/request_token'] = '/oauth/request_token'; 
$route['oauth/authorize'] = '/oauth/authorize'; 
$route['signin'] = '/login';
*/
$route['contact'] = 'contact'; 
$route['check'] = 'transactionopt/search'; 
$route['browserwarn'] = 'IE';
$route['about'] = 'about';

$route['no/war/we/we'] = 'admin/login';

$route['admin/dologin'] = 'admin/dologin';
$route['admin/dologout'] = 'admin/dologout';
$route['admin/user_admin_search'] = 'admin/user_admin_search'; 
$route['admin/user_admin_search_result'] = 'admin/user_admin_search_result';
$route['admin/add_new_user'] = 'admin/add_new_user';
$route['admin/solution_admin'] = 'admin/solution_admin';

//默认路径
$route['default_controller'] = "home";

//忧先路由以排除特殊的路径
$route['/'] = 'home';
$route['home'] = 'home';
 
$route['message'] = 'message'; 

$route['buy'] = 'buy';
$route['sale'] = 'sale';
$route['reg'] = 'reg'; 

$route['login'] = '/auth/login';
$route['do_login'] = '/auth/do_login';
$route['logout'] = '/auth/do_logout';
$route['lostpwd'] = '/auth/lostpwd'; 
$route['resetpassword'] = '/auth/resetpassword';
$route['do_resetpassword'] = '/auth/resetpassword'; 

$route['settings'] = '/settings';
$route['settings/myface'] = '/settings/myface';
$route['settings/snstools'] = '/settings/snstools';
$route['settings/account'] = '/settings/account';

$route['([a-zA-Z0-9]+)/following'] = "/followopt/contacts/$1";
$route['([a-zA-Z0-9]+)/followers'] = "/followopt/rev_contacts/$1";

//浏览用户方案, (用户domain)/(solution_id)
//$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)'] = "/solution/view/$2/$1";

//实现个性域名
//$route['([a-zA-Z0-9]+)'] = "/u/index/$1";


$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
