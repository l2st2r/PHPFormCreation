# PHPFormCreation

### Quick start

`require_once __DIR__ . '/Forms/formAutoloader.php';`

### Usage

Create a form with input type
```
$form = [
  (new FormInput("LastName", true))
    ->setTitle("Please input your Surname")
    ->setLabel("Surname")
    ->formHorizontalAlign(6, 12)
    ->build()
];

$create = new FormCreate();
$create->appendFormGroup($form);
echo $formMember1;
```

will generate 

```
<div class="form-row">
  <div class="form-group col-12 col-md-6">
    <label for="form-LastName">Surname</label>
    <input class="form-control " id="form-LastName" name="LastName" title="Please input your Surname" required="">
  </div>
</div>
```

If you want to set dynamic value get from API, use `$create->setData($data)` with `$data` an associative array.

Key of the `$data` will be mapped to the form and value will be displayed.
