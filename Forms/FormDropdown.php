<?php
require_once "FormCreationBase.php";


class FormDropdown extends FormCreationBase
{
    public function __construct($name = "", $isRequired = false)
    {
        parent::__construct($name, "select");
        $this->setIsRequired($isRequired);
    }

    public function setOptions($options, $hasDefaultWithValue = false, $multipleSelect = false, $hasHierarchy = false)
    {
        return parent::setOptions($options, $hasDefaultWithValue, $multipleSelect, $hasHierarchy);
    }
}