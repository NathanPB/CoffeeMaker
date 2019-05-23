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
                <form class="form-delete" method="post">
                    <input name="action" value="delete" hidden/>
                    <input name="id" value="<?= $ingredient['id'] ?>" hidden/>
                    <input name="name" value="<?= $ingredient['name'] ?>" hidden/>
                    <button
                        type="submit"
                        class="btn btn-danger w-100"
                        title="Delete Ingredient"
                    >
                        <span class="fas fa-trash-alt"></span>
                    </button>
                </form>
            </td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>

<script>
    Array.from(document.querySelectorAll('.form-delete')).forEach(element => {
        element.addEventListener('submit', () => {
            return !confirm(`Please note that this action is unrecoverable.\n Confirm?`);
        });
    });
</script>
