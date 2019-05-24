<?php include_once 'includes/authentication.php'?>
<!DOCTYPe html>
<html>
    <head>
        <title>CoffeeMaker</title>
        <link rel="shortcut icon" href="https://image.flaticon.com/icons/png/512/130/130484.png" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/css/common.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
    <body>
        <div class="content h-100 d-flex">
            <nav class="navbar fixed-top navbar-expand-sm navbar-light bg-light">

                <a class="navbar-brand" href="index.php">CoffeeMaker</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarToggle">

                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="?page=ingredients">
                                <span class="fas fa-utensil-spoon"></span> Ingredients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=recipes">
                                <span class="fas fa-mug-hot"></span> Recipes
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fas fa-cog"></span> Administrative
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="?page=users">
                                    <span class="fas fa-user"></span> Users
                                </a>
                                <a class="dropdown-item" href="export.php" target="_blank">
                                    <span class="fas fa-file-pdf"></span> Export to PDF
                                </a>
                            </div>
                        </li>
                        <li class="nav-item d-md-inline" style="display: none">
                            <span class="nav-link">|</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <span class="fas fa-sign-out-alt"></span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="content-wrapper p-3">
                <div class="card h-100 w-100">
                    <?php
                        switch ($_GET['page']) {
                            case 'ingredients': include 'includes/body/ingredients.php'; break;
                            case 'recipes': include 'includes/body/recipes.php'; break;
                            case 'users': include 'includes/body/users.php'; break;
                            default: include 'includes/body/overview.php'; break;
                        }
                    ?>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            Array.from(document.querySelectorAll('.alert-dismissable')).forEach((element, index) => {
                $(element).fadeTo(2000 * (index + 1), 500).slideUp(500, function(){
                    $(element).slideUp(500, function () {
                        element.hide();
                    });
                });
            });
        </script>
    </body>
</html>