<?php
$message = '';
$message_class = 'alert-success';

if(isset($_POST['action'])){
    switch ($_POST['action']){
        case 'create': {
            if(isset($_POST['name'])) {
                try {
                    $statement = $db->prepare("insert into recipe (name) values (:name)");
                    $statement->execute(array(
                        'name' => $_POST['name']
                    ));
                    $message = "recipe {$_POST['name']} added successfully!";
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on adding a new recipe. Please contact the system administrator';
                }
            }
            break;
        }
        case 'delete': {
            if(isset($_POST['id']) && isset($_POST['name'])){
                try {
                    $statement = $db->prepare("delete from recipe where id = :id");
                    $statement->execute(array(
                        'id' => $_POST['id']
                    ));
                    $message = "Recipe {$_POST['name']} deleted successfully!";
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on deleting the recipe. Please contact the system administrator';
                }
            }
            break;
        }
        case 'addingredient': {
            if(isset($_POST['recipe']) && isset($_POST['ingredient']) && isset($_POST['amount'])){
                try {
                    $statement = $db->prepare("insert into ingredientOnRecipe (recipe, ingredient, amount) value (:recipe, :ingredient, :amount)");
                    $statement->execute(array(
                        'recipe'     => $_POST['recipe'],
                        'ingredient' => $_POST['ingredient'],
                        'amount'     => $_POST['amount']
                    ));
                    $message = 'Ingredient added successfully!';
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on adding the ingredient. Please contact the system administrator';
                }
            }
            break;
        }
        case 'remingredient': {
            if(isset($_POST['id'])) {
                try {
                    $statement = $db->prepare("delete from ingredientOnRecipe where id = :id");
                    $statement->execute(array(
                        'id' => $_POST['id']
                    ));
                    $message = 'Ingredient removed successfully!';
                } catch (Exception $ex) {
                    $message_class = 'alert-danger';
                    $message = 'An error occurred on removing the ingredient. Please contact the system administrator';
                }
            }
            break;
        }
    }
}

$recipes = $db->query("select * from recipe")->fetchAll();

if($message){?>
    <div class="alert <?= $message_class ?> alert-dismissable"><?= $message ?></div>
<?php }
?>

<style>
    .pointer {
        cursor: pointer;
    }
</style>

<form method="post" class="m-1" action>
    <input name="action" value="create" hidden/>
    <div class="row">
        <div class="col-sm-10">
            <label for="input-create-name" class="d-sm-none" style="display: block">Ingredient Name:</label>
            <input type="text" name="name" placeholder="Recipe Name" class="form-control" id="input-create-name" required/>
        </div>
        <div class="col-sm-2">
            <button
                type="submit"
                class="btn btn-success w-100"
                title="Create Recipe"
            >
                <span class="fas fa-plus"></span>
            </button>
        </div>
    </div>
</form>

<ul class="list-group p-1">
<?php
    foreach ($recipes as $recipe) {
        ?>
            <li class="list-group-item mb-1">
                <div class="row">
                    <div class="col-sm-11">
                        <span class="text-capitalize"><?= $recipe['name'] ?></span>
                    </div>
                    <div class="col-sm-1">
                        <a class="pointer" data-toggle="collapse" href="#collapse-<?= $recipe['id'] ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="fas fa-sort-down"></span>
                                </div>
                                <div class="col-md-8">
                                    <form class="form-delete" method="post">
                                        <input name="action" value="delete" hidden/>
                                        <input name="id" value="<?= $recipe['id'] ?>" hidden/>
                                        <input name="name" value="<?= $recipe['name'] ?>" hidden/>
                                        <button
                                                type="submit"
                                                class="btn btn-danger w-100"
                                                title="Delete Recipe"
                                        >
                                            <span class="fas fa-trash-alt"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="collapse" id="collapse-<?= $recipe['id'] ?>">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ingredient</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ingredients = $db->query('select i.id as id, i.amount as amount, ingredient.name as name, ingredient.type as type from ingredientOnRecipe i inner join ingredient on ingredient.id = i.ingredient where i.recipe = '.$recipe['id'])->fetchAll();
                        foreach ($ingredients as $ingredient) { ?>
                            <tr>
                                <td class="text-capitalize"><?= $ingredient['name']?></td>
                                <td><?= $ingredient['amount'].' '.($ingredient['type'] == 'mg' ? '' : 'mL') ?> </td>
                                <td>
                                    <form method="post" action>
                                        <input name="recipe" value="<?= $recipe['id'] ?>" hidden/>
                                        <input name="ingredient" value="<?= $ingredient['id'] ?>" hidden/>
                                        <input name="action" value="remingredient" hidden/>
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
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </li>
        <?php
    }
?>
</ul>

<script>
    Array.from(document.querySelectorAll('.form-delete')).forEach(element => {
        element.addEventListener('submit', () => {
            return !confirm(`Please note that this action is unrecoverable.\n Confirm?`);
        });
    });
</script>
