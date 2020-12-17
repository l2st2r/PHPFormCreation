<?php
/**
 * Created by PhpStorm.
 * User: Lester
 * Date: 2/5/2019
 * Time: 20:28
 */

class mHTTPElement
{
    private $eleType = "", $value = "", $class = "", $id = "", $for = "";
    private $type = "", $name = "", $selected = "", $hidden = "", $plainAttr = "";
    private $href = "", $target = "", $pattern = "", $maxlength = "", $title = "";
    private $accept = "", $capture = "";
    private $otherAttr = array();
    private $innerElement = array();
    private $valueAttributeRequired = false;

    private $isChecked = false;
    private $isDisabled = false;
    private $isRequired = false;
    private $readOnly = false;


    public function __construct($name = "", $eleType = "input", $prefix = "form-")
    {
        $this->setEleType($eleType);
        $this->setName($name);
        $this->setId($prefix . $name);
    }

    // Drop-down
    protected function setOptions($options, $hasDefault = false, $multipleSelect = false, $hasHierarchy = false)
    {
        if ($multipleSelect) {
            $this->appendClass("selectpicker");
            $this->setPlainAttr("multiple");
        }
        $currentHierarchy = '';
        foreach ($options as $key => $value) {

            if (is_array($value)) {
                $optionsDisable = $value['disabled'];
                $labelText = $value['name'];
                if ($optionsDisable === true) $currentHierarchy = $labelText;
            } else {
                $optionsDisable = false;
                $labelText = $value;
            }

            if ($hasDefault !== false && $key === $hasDefault) {
                $selected = "selected";
                $hidden = "hidden";
                $mValue = '';
            } else {
                $selected = "";
                $hidden = "";
                $mValue = $key;
            }

            $item = (new mHTTPElement($this->name . $key, "option"))
                ->setSelected($selected)
                ->setHidden($hidden)
                ->setIsDisabled($optionsDisable)
                ->setValue($mValue)
                ->setValueAttributeRequired()
                ->appendInnerElement($labelText);

            if ($hasHierarchy) $item = $item->appendOtherAttr(CATEGORY, $currentHierarchy);

//            $oHtml = "<option value='$mValue' $hidden>$value</option>";
            $this->appendInnerElement($item);
        }

        return $this;
    }

    private function alterOptions()
    {
//        if ($this->name == "MostInterestedShoppingItem") {
//            Utils::consoleLog("Value set $this->name: ". json_encode($this->value));
//            Utils::consoleLog($this->innerElement);
//        }

        foreach ($this->innerElement as $html) {
//            $mClass = get_class($html);
//            Utils::consoleLog("Compare Class: $mClass");

            if ($html instanceof mHTTPElement && $html->eleType == "option") {

                $selected = $html->value == $this->value ? "selected" : "";
                $html->setSelected($selected);
            } // Radio option
            else if ($html instanceof FormElement && $html->getForm()->type == "radio") {

                $form = $html->getForm();

                if ($this->value === 0 || $this->value === "false" || $this->value == false) {
                    $optionValue = "false";
                } else if ($this->value === 1 || $this->value === "true" || $this->value == true) {
                    $optionValue = "true";
                } else {
                    $optionValue = $this->value;
                }
                $selected = $form->value == $optionValue;
//                Utils::consoleLog("Comparing Radio $form->name: $form->value == $optionValue // thisValue: $this->value, selected: $selected");
                $form->setIsChecked($selected);


            }
        }
    }

    protected function setRadio($options, $checkValue = false, $required = false, $disabled = false)
    {
        $foo = 0;
        foreach ($options as $value => $label) {
            $optionsDisable = false;
            if (is_array($label)) {
                $optionsDisable = $label['disabled'];
                $labelText = $label['name'];
            } else {
                $labelText = $label;
            }

            if ($foo == 0) {
                $setRequired = $required;
            } else {
                $setRequired = false;
            }

            $item = (new FormElement(
                (new mHTTPElement($this->name))
                    ->setType("radio")
                    ->setId($this->name . $labelText . $foo)
                    ->setValue($value)
                    ->setIsRequired($setRequired)
                    ->setIsDisabled($disabled || $optionsDisable)
                    ->setClass("form-check-input")
                    ->setIsChecked($checkValue === $value)
                , $labelText, null, "form-check-label"))
                ->setRow(null)
                ->setFormFirst()
                ->setParentClass("form-check form-check-inline");

            $this->appendInnerElement($item);

            $foo++;
        }

        return $this;
    }


    public function __toString()
    {
        $inner = "";
        $head = "";
        $footer = "";
        if (!empty($this->eleType)) {
            $head .= "<$this->eleType";
            $footer .= "</$this->eleType>";

            $nonEmpty = array(
                "class" => $this->class,
                "id" => $this->id,
                "for" => $this->for,
                "type" => $this->type,
                "value" => $this->value,
                "name" => $this->name,
                "selected" => $this->selected,
                "hidden" => $this->hidden,
                "href" => $this->href,
                "target" => $this->target,
                "maxlength" => $this->maxlength,
                "pattern" => $this->pattern,
                "accept" => $this->accept,
                "capture" => $this->capture,
                "title" => $this->title);

            $nonEmpty = array_merge($nonEmpty, $this->otherAttr);

            $boolEle = array(
                "checked" => $this->isChecked,
                "disabled" => $this->isDisabled,
                "required" => $this->isRequired,
                "readonly" => $this->readOnly
            );

            foreach ($nonEmpty as $itemHead => $item) {
                if (!empty($item)) {
                    $item = htmlspecialchars($item);
                    $head .= " $itemHead=\"$item\"";
                }
            }

//            if (strcasecmp($this->type, "radio") == 0) {
//                $boolEle["checked"] = ($this->value === "true" || $this->value == true || $this->value == 1);
//            }


//                Utils::consoleLog("<$this->eleType>$this->name: $this->value");
            if ($this->valueAttributeRequired) {
                $head .= " value=\"$this->value\"";
            }

            if (!empty($this->plainAttr)) {
                $head .= " " . $this->plainAttr;
            }

            foreach ($boolEle as $itemHead => $item) {
                if ($item === true) {
                    $head .= " $itemHead";
                }
            }

            $head .= ">";

            if (strcasecmp($this->eleType, "textarea") === 0) {
                $head .= $this->value;
            }
        }


        if (!empty($this->innerElement)) {
            foreach ($this->innerElement as $data) {
                if ($data instanceof mHTTPElement) {
                    $inner .= (string)$data;
                } else {
                    $inner .= (string)$data;
                }
            }
        }

        return $head . $inner . $footer;
    }

    /**
     * @param mixed $eleType
     * @return self
     */
    public function setEleType($eleType)
    {
        $this->eleType = $eleType;
        return $this;
    }

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $class
     * @return self
     */
    public function appendClass($class)
    {
        $this->class .= " $class";
        return $this;
    }


    public function setFormControl($size = "")
    {
        $this->class = "form-control " . $this->class;
        if (!empty($size)) {
            $this->class = "form-control-$size " . $this->class;
        }
        return $this;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $value
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->alterOptions();
        return $this;
    }

    public function setPreset($value)
    {
        if ($this->type == "checkbox") {
            $this->setIsChecked($value == "on" || $value == "true" || $value == true || intval($value) == 1);
            return $this;
        } else if ($this->type == "radio") {
            $this->setIsChecked($value == $this->value);
            return $this;
        } else {
            $this->setValue($value);
            foreach ($this->innerElement as $element) {
                if ($element instanceof FormElement) {
                    $element->setData($value);
                }
            }
        }
    }

    /**
     * @param null $innerElement
     * @return self
     */
    public function appendInnerElement($innerElement)
    {
        array_push($this->innerElement, $innerElement);
        return $this;
    }

    /**
     * @param string $for
     * @return self
     */
    public function setFor($for)
    {
        $this->for = $for;
        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    protected function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param bool $isChecked
     * @return self
     */
    protected function setIsChecked($isChecked = true)
    {
        $this->isChecked = $isChecked;
        return $this;
    }

    /**
     * @param bool $isDisabled
     * @return self
     */
    public function setIsDisabled($isDisabled = true)
    {
        $this->isDisabled = $isDisabled;
        return $this;
    }

    /**
     * @param bool $readOnly
     * @return self
     */
    public function setReadOnly($readOnly = true)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * @param bool $isRequired
     * @return self
     */
    public function setIsRequired($isRequired = true)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $selected
     * @return self
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;
        return $this;
    }

    /**
     * @param boolean $hidden
     * @return self
     */
    public function setHidden($hidden = true)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @param string $href
     * @return self
     */
    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @param string $target
     * @return self
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @param bool $valueAttributeRequired
     * @return self
     */
    public function setValueAttributeRequired($valueAttributeRequired = true)
    {
        $this->valueAttributeRequired = $valueAttributeRequired;
        return $this;
    }

    /**
     * @param string $plainAttr
     * @return self
     */
    public function setPlainAttr($plainAttr)
    {
        $this->plainAttr .= " $plainAttr";
        return $this;
    }

    /**
     * @param string $maxlength
     * @return self
     */
    public function setMaxlength($maxlength)
    {
        $this->maxlength = $maxlength;
        return $this;
    }

    /**
     * @param string $pattern
     * @return self
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return self
     */
    public function appendOtherAttr($name, $value)
    {
        $this->otherAttr[$name] = $value;
        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $accept
     * @return self
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * @param string $capture
     * @return self
     */
    public function setCapture($capture)
    {
        $this->capture = $capture;
        return $this;
    }
}
