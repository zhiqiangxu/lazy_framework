<?php
namespace Application\Controller;
use Lazy\Controller\Base;
use Application\Model\ExampleSentence;
use Application\Model\ExampleSentence_DAO;

class ExampleSentence_Update extends Base
{
    function display()
    {
        $this->set_title("Update new example sentence");
        $struct = ExampleSentence_DAO::getRecord($_GET['id']);
        ExampleSentence_DAO::update($struct);
        $this->set_main('ExampleSentence/Update.tmpl');
        $this->output($_GET);
    }
}
