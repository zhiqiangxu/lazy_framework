<?php
require "path.php";
require "../Application/path.php";
require "../conf/routes.php";

$uri = trim($_SERVER['REQUEST_URI'], '/');
if (!$uri)
    $uri = $route['default_route'];
    
if (isset($route[$uri]))
{
    $segments = explode('/', $route[$uri]);
}
else
{
    $segments = explode('/', $uri);
} 

$method = ucfirst(array_pop($segments));
$ucfirst_segments = array();
foreach ($segments as $segment)
{
    $result = '';
    $parts = explode('-', $segment);
    foreach ($parts as $part)
    {
        $result .= ucfirst($part);
    }
    $ucfirst_segments[] = $result;
}
unset($segments);
$class = implode('_', $ucfirst_segments);


$fq_class = 'Application\Controller\\' . $class . '_' . $method;
if (defined('AJAX') && AJAX)
    $fq_class::get_instance()->ajax();
else
    $fq_class::get_instance()->display();