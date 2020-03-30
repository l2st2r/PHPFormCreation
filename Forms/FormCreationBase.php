<?php


class FormCreationBase extends mHTTPElement
{

    private $labelHtml = "　", $formRowSm = 12, $lbClass = "", $lbId = "";
    private $rowNumber = 12;

    public function __construct($name = "", $eleType = "input")
    {
        require_once "FormElement.php";
        parent::__construct($name, $eleType);
        $this->setFormControl();
    }

    public function setLabel($html, $class = "", $id = "") {
        $this->labelHtml = $html;
        $this->lbClass = $class;
        $this->lbId = $id;
        return $this;
    }

    public function formHorizontalAlign($rowNumber, $forSmDevice) {
        $this->rowNumber = $rowNumber;
        $this->formRowSm = $forSmDevice;
        return $this;
    }

    public function build()
    {
        return (new FormElement($this, $this->labelHtml, $this->formRowSm, $this->lbClass, $this->lbId))
            ->setRow($this->rowNumber);
    }
}