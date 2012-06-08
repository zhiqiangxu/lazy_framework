<?php

class Controller_Syntax_Delete extends Controller_Scaffold
{
    function display()
    {
        $this->set_title("Delete syntax");
        $db = get_default_db();
        if(empty($_POST))
        {
            $this->_do_table_before_delete($db, 'syntax', $_GET);
        }
        else
        {
            if(empty($_SESSION['accountId']) || $_SESSION['accountId'] != 1)
            {
                echo "VIP only!";
                exit;
            }

            $_SERVER['HTTP_REFERER'] = 'list_syntax.php';
            $this->do_table_delete_row($db, 'syntax', $_POST, null, FALSE);
        }
    }
}
