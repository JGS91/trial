<?php
require 'propertiesTable.php';

class propertiesController
{
    private $propertyTable;

    function __construct()
    {
        $this->propertyTable = new propertiesTable;
    }

    function indexAction()
    {
        return $this->propertyTable->getProperties();
    }

    function addAction($propertyDetails)
    {
        $property = array();
        parse_str($propertyDetails, $property);

        // Code needs to be reworked to store image into images folder and do resizing.
        // $info = pathinfo($_FILES['image']['name']);
        // $ext = $info['extension']; // get the extension of the file
        // $newname = "newname." . $ext;
        // $target = 'images/' . $newname;
        // move_uploaded_file($_FILES['images']['tmp_name'], $target);

        //Validation as well as as sanitization

        $this->propertyTable->addProperty($property);
    }

    function deleteAction($uuid)
    {
        $this->propertyTable->deleteProperty($uuid);
    }

    function editAction()
    { }
}

$propertyController = new propertiesController;
switch ($_POST['action']) {
    case 'delete':
        $propertyController->deleteAction($_POST['property_uuid']);
        break;
    case 'add':
        $propertyController->addAction($_POST['property_details']);
        break;
}
