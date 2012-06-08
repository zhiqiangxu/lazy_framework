<?php

class Controller_ExampleSentence_Edit extends Controller_Scaffold
{
    function display()
    {
        $this->set_title("Edit example sentence");
        $db = get_default_db();
        if(empty($_POST))
        {
            $this->_do_table_before_update($db, 'example_sentence', $_GET);
        }
        else
        {
            if(empty($_SESSION['accountId']) || $_SESSION['accountId'] != 1)
            {
                echo "VIP only!";
                exit;
            }

            $_SERVER['HTTP_REFERER'] = 'view_syntax.php?id=' . $_GET['sid'];
            $_POST['updated'] = date('Y-m-d H:i:s', time());
            $this->do_table_update_row($db, 'example_sentence', $_POST, array(), null, FALSE, FALSE);
        }
    }
}
