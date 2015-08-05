<?php
namespace Application\Controller;
use Lazy\Controller\Scaffold;
use Application\Model\ExampleSentence;
use Application\Model\ExampleSentence_DAO;

class ExampleSentence_Add extends Scaffold
{
    function display()
    {
        $this->set_title("Add new example sentence");
        $struct = new ExampleSentence;
        ExampleSentence_DAO::insert($struct);
        $this->set_main('ExampleSentence/Add.tmpl');
        $this->output($_GET);
    }
}
