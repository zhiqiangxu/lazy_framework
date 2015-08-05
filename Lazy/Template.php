<?php
namespace Lazy;
require "Library/Smarty/SmartyBC.class.php";

class Template
{
    var $smarty = NULL;
    function __construct()
    {
        $this->smarty = new \SmartyBC();
        $this->smarty->template_dir = APPLI_ROOT. '/Application/Views/';
        $this->smarty->compile_dir  = APPLI_ROOT . '/Application/Views_c/';
        /*
        $this->smarty->config_dir   = './config/';
        $this->smarty->cache_dir    = './cache/';
        $this->smarty->caching      = true;
        $this->smarty->cache_lifetime= 60 * 60 * 24;
        */
        $this->smarty->left_delimiter = "{";
        $this->smarty->right_delimiter = "}";
        //$this->smarty->force_compile = TRUE;
    }

    function params($kv)
    {
        foreach ($kv as $k => $v)
        {
            $this->smarty->assign($k, $v);
        }
    }

    function display($template)
    {
        $this->smarty->display($template);
    }
}
