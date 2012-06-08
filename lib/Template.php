<?php
include "Smarty/SmartyBC.class.php";

class Template
{
    var $smarty = NULL;
    function __construct()
    {
        global $DOCUMENT_ROOT;
        $this->smarty = new SmartyBC();
        $this->smarty->template_dir = $DOCUMENT_ROOT . '/templates/';
        $this->smarty->compile_dir  = $DOCUMENT_ROOT . '/templates_c/';
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
