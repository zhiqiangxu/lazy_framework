<?php
namespace Application\Controller;
use Lazy\Controller\Base;
use Application\Model\ExampleSentence;
use Application\Model\ExampleSentence_DAO;

class ExampleSentence_List extends Base
{
    function display()
    {
        $this->set_title("List example sentence");
        $list = ExampleSentence_DAO::getAll();
        $this->set_main('ExampleSentence/List.tmpl');
        $data = array('list' => $list);
        $this->output($data);
    }
}
