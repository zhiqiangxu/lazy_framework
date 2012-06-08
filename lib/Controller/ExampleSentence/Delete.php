<?php

class Controller_ExampleSentence_Delete extends Controller_Scaffold
{
    function display()
    {
        $this->set_title("Delete example sentence");
        $db = get_default_db();
        if(empty($_POST))
        {
            $this->_do_table_before_delete($db, 'example_sentence', $_GET);
        }
        else
        {
            if(empty($_SESSION['accountId']) || $_SESSION['accountId'] != 1)
            {
                echo "VIP only!";
                exit;
            }

            $_SERVER['HTTP_REFERER'] = 'view_syntax.php?id=' . $_GET['sid'];
            $this->do_table_delete_row($db, 'example_sentence', $_POST, null, FALSE);
        }
    }
}
