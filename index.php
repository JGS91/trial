<?php
require __DIR__ . '/vendor/autoload.php';
require 'propertiesController.php';

$propertyController = new propertiesController;
//Get all properties
$properties = $propertyController->indexAction();

// how many records should be displayed on a page?
$records_per_page = 10;

// instantiate the pagination object
$pagination = new Zebra_Pagination();

// the number of total records is the number of records in the array
$pagination->records(count($properties));

// records per page
$pagination->records_per_page($records_per_page);

// here's the magic: we need to display *only* the records for the current page
$properties = array_slice(
    $properties,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <a><button id="addButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add</button></a>

    <table>
        <thead>
            <tr>
                <th>Properties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($properties as $index => $property) : ?>
                <tr<?php echo $index % 2 ? ' class="even"' : ''; ?>>
                    <td>
                        <?php echo ($property['displayable_address']); ?>
                        <a><button data-uuid="<?php echo $property['uuid'] ?>" id="deleteButton" type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button></a>
                    </td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Property</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="county">County</label>
                            <input type="text" class="form-control" id="county" name="county">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country">
                        </div>
                        <div class="form-group">
                            <label for="town">Town</label>
                            <input type="text" class="form-control" id="town" name="town">
                        </div>
                        <div class="form-group">
                            <label for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="displayableAddress">Displayable Address</label>
                            <input type="text" class="form-control" id="displayableAddress" name="displayableAddress">
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bedrooms">Number of bedrooms</label>
                            <select id="bedrooms" class="custom-select" name="bedrooms">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bathrooms">Number of bathrooms</label>
                            <select id="bathrooms" class="custom-select" name="bathrooms">
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="text" class="form-control" id="price" name="price">
                        </div>
                        <div class="form-group">
                            <label for="propertyType">Property Type</label>
                            <select id="propertyType" class="custom-select" name="propertyType">
                                <option value="Cottage">Cottage</option>
                                <option value="Flat">Flat</option>
                                <option value="House">House</option>
                            </select>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="typeSale" value="sale">
                            <label class="form-check-label" for="typeSale">Sale</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="typeRent" value="rent">
                            <label class="form-check-label" for="typeRent">Rent</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="add" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button id="yes" type="button" class="btn btn-danger">Yes</button>
                    <button type="button" class="btn btn-primary">No</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#deleteButton").click(function() {
                $("#yes").val($(this).data('uuid'));
            });

            $("#yes").click(function() {
                $.post({
                    url: 'propertiesController.php',
                    data: {
                        "action": "delete",
                        "property_uuid": $("#yes").val()
                    },
                    success: function(result) {
                        location.reload();
                    }
                });
            });

            $("#add").click(function() {
                $.post({
                    url: 'propertiesController.php',
                    data: {
                        "action": "add",
                        "property_details": $('form').serialize()
                    },
                    success: function(result) {
                        location.reload();
                    }
                });
            });
        });
    </script>

</body>

</html>

<?php

// render the pagination links
$pagination->render();

//Needs a much better UI, atm only way to know a record is added successfully is to query the db.
//Some client side validation in terms of required and datatypes could be added.