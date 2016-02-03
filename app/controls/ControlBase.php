<?php

namespace app\controls;

use vajko\core\abstracts\aControlBase;

class ControlBase extends aControlBase
{
    /**
     * @return NULL
     */
    public function defaultAction()
    {
        $this->render->data->framework = "Vajko Framework";
    }

}