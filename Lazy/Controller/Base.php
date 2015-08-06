<?php
namespace Lazy\Controller;
use Lazy\DB\Table\ConnectionManager;
use Lazy\Template;
require APPLI_ROOT . '/Lazy/Library/FormValidator/FormValidator.php';

if(!function_exists('get_called_class'))
{
    function get_called_class()
    {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        preg_match('/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/',
                   $lines[$bt[1]['line']-1],
                   $matches);
        return $matches[1];
    }
}

class Base
{
    var $tmpl_layout = 'layout/default.tmpl';
    var $tmpl_main = NULL;
    var $title = '';
    var $template = NULL;
    var $js_files = array();
    var $css_files = array();

    function __construct()
    {
        global $db_connection_manager;
        global $DB_CONF;
        $db_connection_manager = new ConnectionManager($DB_CONF);
    }

    /* just for chaining */
    static function get_instance()
    {
        $class = get_called_class();
        session_start();
        return new $class();
    }

	// load on demand
	public function __get($key)
	{
		if($key == 'form_validator')
			return $this->form_validator = new FormValidator;
	}

    function generate_uri($params, $path = NULL)
    {
        if(!$path)
        {
            if(($pos = strpos($_SERVER['REQUEST_URI'], '?')) === false)
            {
                $path = $_SERVER['REQUEST_URI'];
            } 
            else
            {
                $path = substr($_SERVER['REQUEST_URI'], 0, $pos);
                parse_str((string) substr($_SERVER['REQUEST_URI'], $pos+1), $tmp);
                foreach ($params as $key => $val) 
                {
                    if(NULL === $val)
                        unset($tmp[$key]);
                    else
                        $tmp[$key] = $val;
                }
                $params = $tmp;
            }
        }
        return $path . (!empty($params) ? '?'.http_build_query($params) : '');
    }

    function paginator($page, $total_pages, $num)
    {
        $page = ($page < 1) ? 1 : ($page > $total_pages ? $total_pages : $page);
        $start = $page - ($num - 1)/2;
        $start = $start < 1 ? 1 : $start;
        $end = $start + $num - 1;
        $end = $end > $total_pages ? $total_pages : $end;
        return array(
            'LEFT' => $start > 1 ? '...' : '',
            'START' => $start,
            'END'  => $end,
            'RIGHT'=> $end < $total_pages ? '...' : '',
        );
    }

    function default_paginator_html($page_structure, $page)
    {
        $html = '';
        if($page_structure['END'] > $page_structure['START'])
        {
            if($page_structure['LEFT'])
                $html .= '<a href="'
                    . $this->generate_uri(
                        array(
                                'page' => $page - 1
                                )
                        )
                    . '">' . $page_structure['LEFT'] . '</a>';
            for($i = $page_structure['START']; $i <= $page_structure['END']; $i++)
            {
                if($page == $i)
                {
                    $html .= '<span>' . $i . '</span>';
                }
                else
                {
                    $html .= '<a href="' . $this->generate_uri(array('page' => $i)) . '">' . $i . '</a>';
                }
            }
            if($page_structure['RIGHT'])
                $html .= '<a href="'
                    . $this->generate_uri(
                        array(
                                'page' => $page + 1
                                )
                        )
                    . '">' . $page_structure['RIGHT'] . '</a>';

        }
        return $html;
    }

    function cookie($k = NULL, $v = NULL, $expire = 86400/* default 1 day */, $path = '/', $domain = NULL)
    {
        if(!$k)
            return $_COOKIE;
        else if($v)
        {
            setcookie($k, $v, time() + $expire, $path, $domain);
            $_COOKIE[$k] = $v;
        }
        else
        {
            return $_COOKIE[$k];
        }
    }

    function redirect($uri)
    {
        header('Location: ' . $uri);
    }

    function log($msg)
    {
        file_put_contents('php://stderr', $msg);
    }
    
    function show_404($params = null)
    {
        $this->set_main('error/404.tmpl');
        $this->output($params);
        exit;
    }
    
    /* template related stuff */
    function set_layout($layout)
    {
        $this->tmpl_layout = $layout;
    }

    function set_main($main)
    {
        $this->tmpl_main = $main;
    }

    function set_title($title)
    {
        $this->title = $title;
    }

    function add_js($js)
    {
        $this->js_files[] = $js;
    }

    function add_css($css)
    {
        $this->css_files[] = $css;
    }

    function output($params = null)
    {
        header('Content-type: text/html; charset=utf-8');
        $template = new Template();
        if($params)
            $template->params($params);
        $template->params(
                array(
                    'title' => $this->title,
                    'tmpl_main' => $this->tmpl_main,
                    'js_files' => $this->js_files,
                    'css_files' => $this->css_files,
                    )
                );
        $template->display($this->tmpl_layout);
    }

    function output_json($data)
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}


