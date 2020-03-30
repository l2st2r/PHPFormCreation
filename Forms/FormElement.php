<?php
/**
 * Created by PhpStorm.
 * User: Lester
 * Date: 29/4/2019
 * Time: 23:16
 */

class FormElement
{
    private $labelName;
    /**
     * @var mHTTPElement
     */
    private $form;
    private $row = 12, $rowSm;
    private $parentClass = "form-group";
    private $formFirst = false;

    public function __construct($form, $lbHtml, $rowSm = 12, $lbClass = "", $lbId = "")
    {
        require_once 'mHTTPElement.php';

        $this->form = $form;
        $this->rowSm = $rowSm;

        if ($lbHtml != null) {
            $this->labelName = (new mHTTPElement())
                ->setEleType("label")
                ->setId($lbId)
                ->setClass($lbClass)
                ->appendInnerElement($lbHtml);
            if ($form != null) {
                $this->labelName->setFor($this->form->getId());
            }
        }



    }

    /**
     * @param mixed $parentClass
     * @return self
     */
    public function setParentClass($parentClass)
    {
        $this->parentClass = $parentClass;
        return $this;
    }

    /**
     * @return mHTTPElement
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return string
     */
    public function getParentClass()
    {
        return $this->parentClass;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->form->setPreset($data);
    }

    public function getName()
    {
        return $this->form->getName();
    }

    /**
     * @param int $row
     * @return self
     */
    public function setRow($row)
    {
        $this->row = $row;
        return $this;
    }

    /**
     * @param bool $formFirst
     * @return self
     */
    public function setFormFirst($formFirst = true)
    {
        $this->formFirst = $formFirst;
        return $this;
    }

    public function __toString()
    {
        $mCol = $this->row == null ? "" : "col-md-$this->row";
        $mColSm = $this->rowSm == null ? "" : "col-$this->rowSm";
        $string = "<div class='$this->parentClass $mColSm $mCol'>";

        if ($this->labelName != null) {
            $mLabel = $this->labelName;
        }
        else {
            $mLabel = "";
        }
        if ($this->form != null) {
            $mForm = $this->form;
        }
        else {
            $mForm = "";
        }

        if ($this->formFirst) {
            $string .= $mForm . $mLabel;
        }
        else {
            $string .= $mLabel . $mForm;
        }

        $string .= "</div>";
        return $string;
    }
}