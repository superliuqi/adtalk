<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function redirect_ssl() {
   $CI =& get_instance();
   $class = $CI->router->fetch_class();
   $exclude =  array();  // add more controller name to exclude ssl.
   if(!in_array($class,$exclude)){
       // redirecting to ssl.
       if ($_SERVER['SERVER_PORT'] != 443)
       {
           redirect($CI->config->config['base_url'].$_SERVER['REQUEST_URI']);}
   }
   else
   {
       // redirecting with no ssl.
       if ($_SERVER['SERVER_PORT'] == 443)
       {
           redirect($CI->config->config['base_url'].$_SERVER['REQUEST_URI']);
       }
   }
}
?>