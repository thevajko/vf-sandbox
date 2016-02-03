<?php
/**
 * Created by IntelliJ IDEA.
 * User: matko
 * Date: 1/28/16
 * Time: 9:50 AM
 */

namespace app\controls;


use app\models\DummyModel;

class JsonControl extends ControlBase
{

    public function ModelInJsonAction()
    {

        parent::defaultAction();

        $user = new DummyModel();
        $out = [
            'generator' => $this->render->data->framework,
            'userList' => $user->data,
        ];


        $this->render->templateFile = "json.view";
        $this->render->data = $out;
    }
}