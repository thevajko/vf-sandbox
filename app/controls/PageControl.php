<?php
/**
 * Created by IntelliJ IDEA.
 * User: matko
 * Date: 18.1.2016
 * Time: 15:22
 */

namespace app\controls;

use app\models\DummyModel;

class PageControl extends ControlBase
{
    /**
     * @return NULL
     */
    public function defaultAction()
    {
        //do commont logic -> run parent
        parent::defaultAction();


        $this->templateData->pageTitle = "Vajko framework is rolling!";
        $this->templateData->content = new \stdClass();
        $this->templateData->content->date = date("t. N. o");

    }

    public function homeAction(){
        $this->defaultAction();
    }

    public function modelAction(){
        $this->defaultAction();


        $content = new \stdClass();

        $content->title = "Super-duper user list";
        $content->type = "users";
        $content->users  = new DummyModel();

        $this->templateData->pageTitle = "Vajko framework is rolling on page two too!!!";
        $this->templateData->content = $content;

    }

}