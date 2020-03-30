<?php
/**
 * User: Lester
 * Date: 29/4/2019
 * Time: 14:25
 */

require_once __DIR__ . '/FormElement.php';
require_once __DIR__ . '/mHTTPElement.php';

class FormCreate
{

    /**
     * @var array [FormElement]
     */
    private $form = array();
    private $data = null;

    public function __construct()
    {
    }

    /**
     * @param array $group
     */
    public function appendFormGroup(...$group)
    {
        array_push($this->form, ...$group);
    }

    /**
     * @param null $data
     */
    public function setData($data)
    {
        if (!empty($data) && is_array($data)) {
            $this->data = $data;
        }
    }

    public function __toString()
    {

        $head = "";
        foreach ($this->form as $group) {
            $head .= "<div class='form-row'>";
            foreach ($group as $item) {
                if (!empty($this->data)) {
                    $itemName = $item->getName();
                    if (array_key_exists($itemName, $this->data)) {
                        $mValue = $this->data[$itemName];
//                        Utils::consoleLog("Key Exist: $itemName, value: $mValue");
                        $item->setData($mValue);
                    }
                }

                $head .= $item;
            }
            $head .= "</div>";
        }

        return $head;
    }
}


