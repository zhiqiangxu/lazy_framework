<?php
namespace Application\Controller;
use Lazy\Controller\Scaffold;

class Syntax_Add extends Scaffold
{
    function display()
    {
        $this->set_title("Add new syntax");
        $db = get_default_db();
        if(empty($_POST))
        {
            $this->_do_table_before_insert($db, 'syntax', $_POST);
        }
        else
        {
            if(empty($_SESSION['accountId']) || $_SESSION['accountId'] != 1)
            {
                echo "VIP only!";
                exit;
            }
            $_SERVER['HTTP_REFERER'] = 'list_syntax.php';
            $_POST['created'] = date('Y-m-d H:i:s', time());
            $this->do_table_insert_row($db, 'syntax', $_POST, array('id', 'updated'), FALSE, FALSE);
        }
    }
}
