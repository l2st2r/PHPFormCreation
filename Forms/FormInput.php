<?php
require_once "FormCreationBase.php";

class FormInput extends FormCreationBase {

    public function __construct($name = "", $isRequired = false)
    {
        parent::__construct($name, "input");
        $this->setIsRequired($isRequired);
    }

    public function setType($type)
    {
        return parent::setType($type);
    }

}