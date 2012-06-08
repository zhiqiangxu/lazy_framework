<?php

class Controller_Syntax_Edit extends Controller_Scaffold
{
    function display()
    {
        $this->set_title("Edit syntax");
        $db = get_default_db();
        if(empty($_POST))
        {
            $this->_do_table_before_update($db, 'syntax', $_GET);
        }
        else
        {
            if(empty($_SESSION['accountId']) || $_SESSION['accountId'] != 1)
            {
                echo "VIP only!";
                exit;
            }

            $_SERVER['HTTP_REFERER'] = 'list_syntax.php';
            $_POST['updated'] = date('Y-m-d H:i:s', time());
            $this->do_table_update_row($db, 'syntax', $_POST, array(), null, FALSE, FALSE);
        }
    }
}
