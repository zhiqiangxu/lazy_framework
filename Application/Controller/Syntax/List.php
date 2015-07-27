<?php
use Lazy\Controller;

class Controller_Syntax_List extends Scaffold
{
    function generate_filter()
    {
        list($where, $bindings) = array('', array());
        if(!empty($_GET['keyword']))
        {
            $where .= 'AND (body LIKE ? or comment LIKE ?)';
            $bindings[] = '%' . $_GET['keyword'] . '%';
            $bindings[] = '%' . $_GET['keyword'] . '%';
        }

        if($where)
            $where = 'WHERE ' . substr($where, 4);

        return array('where' => $where, 'bindings' => $bindings, 'sort' => 'id DESC');
    }

    function display()
    {
        $this->set_title("All syntax");
        $db = get_default_db();
        //$this->do_table_select($db, 'syntax', array(), $_GET, FALSE);
        //customize start
        $result = $this->do_table_select($db, 'syntax', $this->generate_filter(), $_GET);
        $result['page_html'] = $this->default_paginator_html(
                $result['page_structure'], 
                empty($_GET['page']) ? 1 : $_GET['page']
        );
        $this->set_main('syntax/list.tmpl');
        $this->output(array_merge($result, $_GET));
    }
}
