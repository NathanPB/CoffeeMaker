<?php
    $message = '';
    $message_class = 'alert-success';

    if(isset($_POST['action'])){
        switch ($_POST['action']){
            case 'create': {
                if(isset($_POST['usr']) && isset($_POST['pwd'])){
                   try {
                       $statement = $db->prepare("insert into user (usr, pwd, createdBy) values (:usr, :pwd, :currentUser)");
                       $statement->execute(array(
                           'usr' => $_POST['usr'],
                           'pwd' => md5($_POST['pwd']),
                           'currentUser' => $_SESSION['usr']
                       ));
                       $message = "User {$_POST['usr']} added successfully!";
                   } catch (Exception $ex) {
                       $message_class = 'alert-danger';
                       $message = 'An error occurred on creating a new user. Please contact the system administrator';
                   }
                }
                break;
            }
            case 'delete': {
                if(isset($_POST['usr'])){
                    try {
                        $statement = $db->prepare("delete from user where usr = :usr");
                        $statement->execute(array('usr' => $_POST['usr']));
                        $message = "User {$_POST['usr']} deleted successfully!";
                    } catch (Exception $ex) {
                        $message_class = 'alert-danger';
                        $message = 'An error occurred on deleting the user. Please contact the system administrator';
                    }
                }
                break;
            }

        }
    }

    $users = $db->query("select * from user order by createdAt")->fetchAll();
?>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">

<?php

        if($message){?>
            <div class="alert <?= $message_class ?> alert-dismissable"><?= $message ?></div>
        <?php } ?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Username</th>
            <th>Created At</th>
            <th>Created By</th>
            <th>
                <button
                    class="btn btn-success w-100"
                    title="Create User"
                    data-toggle="modal"
                    data-target="#modal-create-user"
                >
                    <span class="fas fa-plus"></span>
                </button>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($users as $user) {
            ?>
                <tr>
                    <td><?= $user['usr']?></td>
                    <td><?= $user['createdAt']?></td>
                    <td><?= $user['createdBy']?></td>
                    <td>
                        <form class="form-delete" method="post">
                            <input name="action" value="delete" hidden/>
                            <input name="usr" value="<?= $user['usr'] ?>" hidden/>
                            <button
                                type="submit"
                                class="btn btn-danger w-100"
                                title="Delete User"
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

<div class="modal" tabindex="-1" role="dialog" id="modal-create-user">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-create-user" method="post" action>
                    <input name="action" value="create" hidden/>

                    <div class="form-group">
                        <label for="input-create-usr">Username:</label>
                        <input type="text" name="usr" placeholder="Username" id="input-create-usr" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="input-create-pwd">Username:</label>
                        <input type="password" name="pwd" placeholder="Password" id="input-create-usr" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" form="form-create-user">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
</script>
