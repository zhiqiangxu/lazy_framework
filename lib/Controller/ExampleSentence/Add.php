<?php

class Controller_ExampleSentence_Add extends Controller_Scaffold
{
    function display()
    {
        $this->set_title("Add new example sentence");
        $db = get_default_db();
        if(empty($_POST))
        {
            $this->_do_table_before_insert($db, 'example_sentence', $_GET);
        }
        else
        {
            if(empty($_SESSION['accountId']) || $_SESSION['accountId'] != 1)
            {
                echo "VIP only!";
                exit;
            }

            $_SERVER['HTTP_REFERER'] = 'view_syntax.php?id=' . $_GET['sid'];
            $_POST['created'] = date('Y-m-d H:i:s', time());
            $this->do_table_insert_row($db, 'example_sentence', $_POST, array('id', 'updated'), FALSE, FALSE);
        }
    }
}
