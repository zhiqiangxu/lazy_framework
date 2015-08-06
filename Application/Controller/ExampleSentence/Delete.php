<?php
namespace Application\Controller;
use Lazy\Controller\Base;
use Application\Model\ExampleSentence;
use Application\Model\ExampleSentence_DAO;

class ExampleSentence_Delete extends Base
{
    function display()
    {
        $this->set_title("Delete example sentence");
        $id = $_GET['id'];
        $struct = ExampleSentence_DAO::getRecord($id);
        if ($struct)
            ExampleSentence_DAO::delete($struct);
        $this->set_main('ExampleSentence/Delete.tmpl');
        $this->output($_GET);
    }
}
