<?php
require_once "FormCreationBase.php";

class FormRadioButton extends FormCreationBase
{

    public function __construct($name = "", $isRequired = false)
    {
        parent::__construct($name, null);
        $this->setIsRequired($isRequired);
    }

    public function setRadio($options, $checkValue = false, $required = false, $disabled = false)
    {
        return parent::setRadio($options, $checkValue, $required, $disabled);
    }
}