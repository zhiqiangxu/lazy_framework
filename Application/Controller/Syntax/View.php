<?php
use Lazy\Controller;

class Controller_Syntax_View extends Scaffold
{
    function display()
    {
        $this->set_title("View syntax");
        $db = get_default_db();
        $record = $this->do_table_select_row($db, 'syntax', $_GET);

        $bindings = array();
        $where = 'WHERE ' . $this->prepare_select_binding_string(array('sid'), array('sid' => $_GET['id']), $bindings);
        $filter = array('where' => $where, 'bindings' => $bindings, 'sort' => 'id DESC');
        // set per large enough so that I don't need to deal with paginator in tmpl..
        $page_info = array('page' => empty($_GET['page']) ? 1 : $_GET['page'], 'per' => 100);
        $example_sentences = $this->do_table_select($db, 'example_sentence', $filter, $page_info);
        $example_sentences['record'] = $record;
        $example_sentences['pks'] = array('id');
        $this->set_main('syntax/view.tmpl');
        $this->output($example_sentences);
    }
}
