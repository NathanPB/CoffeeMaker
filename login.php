<?php
    session_start();
    include_once 'includes/database.php';

    function is_authentication_valid(){
        global $db;
        if(!isset($_SESSION['usr']) || !isset($_SESSION['pwd'])){
            return false;
        } else {
            $sth = $db->prepare("select count(*) from user where usr=:usr and pwd=:pwd");
            $sth->execute($_SESSION);
            return $sth->fetchColumn();
        }
    }

    $tryingToLogin = isset($_POST['usr']) && isset($_POST['pwd']);
    if($tryingToLogin){
        $_SESSION['usr'] = $_POST['usr'];
        $_SESSION['pwd'] = md5($_POST['pwd']);
    }

    $credentialsAccepted = is_authentication_valid();

    if($credentialsAccepted) {
        header("Location: index.php");
    } else {
        ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Login</title>
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                    <link rel="stylesheet" href="assets/css/common.css">
                    <style>
                        .forgot-pwd {
                            font-size: 16px;
                        }

                        <?php
                            if($tryingToLogin && !$credentialsAccepted){
                                ?>

                        .forgot-pwd {
                            animation: blink-red 1s;
                            animation-delay: 0.5s;
                            animation-iteration-count: 3;
                        }

                        @keyframes blink-red {
                            0% {
                                color: red;
                            }
                            50% {
                                color: red;
                            }
                            51% {
                                color: #007bff;
                            }
                        }
                                <?php
                            }
                         ?>

                        .card-wrapper {
                            flex-grow: 1;
                        }

                        #pwd .input-group-addon-btn {
                            padding: 0;

                            button {
                                border: none;
                                background: transparent;
                                cursor: pointer;
                                height: 100%;
                            }
                        }
                    </style>
                </head>

                <body>
                    <div class="container-fluid h-100 d-flex" style="align-items: center;">
                        <div class="card-wrapper">
                            <div class="card p-3">
                                <div class="row">
                                    <div class="col-lg-6 text-center">
                                        <img src="assets/coffee.png" class="coffee-img w-100 img-fluid"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card mt-3 p-3">
                                            <div class="alert alert-danger hidden" id="alert-forgot-pwd">I'm really sorry for you :c</div>
                                            <?php
                                                if($tryingToLogin && !$credentialsAccepted) {
                                                    ?>
                                                    <div class="alert alert-danger">Hey! It looks like your credentials are wrong or your account is disabled. Maybe you misspelled something. Please re-check and try again!</div>
                                                    <?php
                                                }
                                            ?>
                                            <h3 class="text-center">Wait! Who are you?</h3>
                                            <form action="login.php" method="post">
                                                <div class="form-group">
                                                    <label for="usr" class="label">User:</label>
                                                    <input type="text" id="usr" name="usr" class="form-control" placeholder="Your username" required autofocus>
                                                </div>

                                                <div class="form-group">
                                                    <label for="pwd" class="label">Password:</label>
                                                    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Your password" required>
                                                    <a href="#" class="forgot-pwd">Heyy! I forgot my password!</a>
                                                </div>
                                                <div class="form-grup">
                                                    <input type="submit" class="btn btn-primary btn-block" value="Login"/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
                    <script>
                        $('#alert-forgot-pwd').hide();
                        $('.forgot-pwd').on('click', () => {
                            let node = $('#alert-forgot-pwd');
                            node.show();
                            $(node).fadeTo(2000, 500).slideUp(500, function(){
                                $(node).slideUp(500, function () {
                                    node.hide();
                                });
                            });
                        });

                        <?php
                            if($tryingToLogin && !$credentialsAccepted){
                                ?>
                                    $('.forgot-pwd');
                                <?php
                            }
                        ?>
                    </script>
                </body>
            </html>
        <?php
    }