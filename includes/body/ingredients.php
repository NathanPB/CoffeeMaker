<?php
$message = '';
$message_class = 'alert-success';

if(isset($_POST['action'])){
    switch ($_POST['action']){
        case 'create': {
            if(isset($_POST['name']) && isset($_POST['type'])) {
                try {
                    $statement = $db->prepare("insert into ingredient (name, type) values (:name, :type)");
                    $statement->execute(array(
                        'name' => $_POST['name'],
                        'type' => $_POST['type']
                    ));
                    $message = "Ingredient {$_POST['name']} added successfully!";
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on adding a new ingredient. Please contact the system administrator';
                }
            }
            break;
        }
        case 'edit': {
            if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['type'])){
                try {
                    $statement = $db->prepare("update ingredient set name = :name, type = :type where id = :id");
                    $statement->execute(array(
                        'id' => $_POST['id'],
                        'name' => $_POST['name'],
                        'type' => $_POST['type'] == '0' ? '0' : '1'
                    ));
                    $message = "Ingredient edited successfully!";
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on updating the ingredient. Please contact the system administrator';
                }
            }
            break;
        }
        case 'delete': {
            if(isset($_POST['id']) && isset($_POST['name'])){
                try {
                    $statement = $db->prepare("delete from ingredient where id = :id");
                    $statement->execute(array(
                            'id' => $_POST['id']
                    ));
                    $message = "Ingredient {$_POST['name']} deleted successfully!";
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on deleting the ingredient. Please contact the system administrator';
                }
            }
            break;
        }

    }
}

$ingredients = $db->query("select * from ingredient")->fetchAll();

if($message){?>
    <div class="alert <?= $message_class ?> alert-dismissable"><?= $message ?></div>
<?php }
?>


<form method="post" class="pl-2 pr-2" style="overflow-x: hidden" action>
    <input name="action" value="create" hidden/>
    <div class="row">
        <div class="col-md-6 pt-1">
            <label for="input-create-name" class="d-sm-none" style="display: block">Ingredient Name:</label>
            <input type="text" name="name" placeholder="Ingredient Name" class="form-control" id="input-create-name" required/>
        </div>
        <div class="col-md-4 pt-1">
            <label for="input-create-type" class="d-sm-none" style="display: block">Ingredient Name:</label>
            <select class="form-control pt-1" id="input-create-type" name="type">
                <option value="0">Solid</option>
                <option value="1">Liquid</option>
            </select>
        </div>
        <div class="col-md-2 pt-1 pb-1">
            <button
                type="submit"
                class="btn btn-success w-100"
                title="Create ingredient"
            >
                <span class="fas fa-plus"></span>
            </button>
        </div>
    </div>
</form>
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($ingredients as $ingredient) {
        ?>
        <tr>
            <td class="text-capitalize"><?= $ingredient['name']?></td>
            <td><?= $ingredient['type'] == '0' ? 'Solid' : 'Liquid'?></td>
            <td>
                <div class="d-flex" style="float: right">
                    <button class="btn btn-primary mr-3" data-toggle="modal" data-target="#modal-edit" onclick="editName('<?= $ingredient['id'] ?>', '<?= $ingredient['name'] ?>', '<?= $ingredient['type'] ?>')" title="Edit Properties">
                        <span class="fas fa-pencil-alt"></span>
                    </button>

                    <form class="form-delete" method="post">
                        <input name="action" value="delete" hidden/>
                        <input name="id" value="<?= $ingredient['id'] ?>" hidden/>
                        <input name="name" value="<?= $ingredient['name'] ?>" hidden/>
                        <button
                                type="submit"
                                class="btn btn-danger"
                                title="Delete Ingredient"
                        >
                            <span class="fas fa-trash-alt"></span>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>

<div class="modal" tabindex="-1" role="dialog" id="modal-edit">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ingredient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit-name" method="post">
                    <input name="action" value="edit" hidden>
                    <input type="text" id="input-id-edit" value="0" name="id" hidden>
                    <div class="form-group">
                        <label for="input-name-edit-name">New Name:</label>
                        <input type="text" id="input-name-edit" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="input-type-edit">New Type:</label>
                        <select id="input-type-edit" name="type" class="form-control" required>
                            <option value="0">Solid</option>
                            <option value="1">Liquid</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="form-edit-name">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    Array.from(document.querySelectorAll('.form-delete')).forEach(element => {
        element.addEventListener('submit', () => {
            return !confirm(`Please note that this action is unrecoverable.\n Confirm?`);
        });
    });

    function editName(id, name, type) {
        document.querySelector('#input-id-edit').value = id;
        document.querySelector('#input-name-edit').value = name;
        document.querySelector('#input-type-edit').children[type === '0' ? 0 : 1].selected = 'on';
    }
</script>
