<!-- Adding new user -->
<?php
    // Global variables.
    $errors = false;
    $class = "";
    $fa = "";
    $message = "";
    if(isset($_POST['adduser'])){
        // Check passed values
        if( (isset($_POST['firstname']) && !empty($_POST['firstname'])) && 
            (isset($_POST['lastname']) && !empty($_POST['lastname'])) &&
            (isset($_POST['age']) && !empty($_POST['age'])) &&
            (isset($_POST['email']) && !empty($_POST['email'])) &&
            (isset($_FILES['avatar']) && !empty($_FILES['avatar'])) &&
            (isset($_POST['city']) && !empty($_POST['city'])) &&
            (isset($_POST['region']) && !empty($_POST['region'])) &&
            (isset($_POST['country']) && !empty($_POST['country'])) &&
            (isset($_POST['password']) && !empty($_POST['password']))){
            
            // is the file image?
            if(getimagesize($_FILES["avatar"]["tmp_name"])){
                $target_dir = "uploads/images/";
                $target_file = $target_dir . basename($_FILES["avatar"]["name"]);

                // Upload a file.
                if(move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)){
                    // Saving user to database.
                    // Include classes
                    require_once 'config/Database.php';
                    require_once 'classes/User.php';

                    $database = new Database();
                    $link = $database->getLink();
                    $userObject = new User($link);

                    $userObject->setFirstname($_POST['firstname']);
                    $userObject->setLastname($_POST['lastname']);
                    $userObject->setAge($_POST['age']);
                    $userObject->setEmail($_POST['email']);
                    $userObject->setPassword(password_hash($_POST['new_password'], PASSWORD_DEFAULT));
                    $userObject->setCity($_POST['city']);
                    $userObject->setRegion($_POST['region']);
                    $userObject->setCountry($_POST['country']);
                    $userObject->setAvatar($_FILES["avatar"]["name"]);
                    $result = $userObject->create();

                    if($result){
                        // User added.
                        $errors = true;
                        $class= "alert-success";
                        $fa = "fa-check";
                        $message = "User added successfully.";
                    }
                    else{
                        // Fail to save user.
                        $errors = true;
                        $class= "alert-danger";
                        $fa = "fa-ban";
                        $message = "Can not add new user. Please try to add a new user again.";
                    }
                }
                else{
                    // Upload image failed.
                    $errors = true;
                    $class= "alert-danger";
                    $fa = "fa-ban";
                    $message = "Can not upload the user image. Please try to add a new user again.";
                }
            }
            else{
                // File is not image.
                $errors = true;
                $class= "alert-warning";
                $fa = "fa-frown";
                $message = "The user image should be in a format of .png, .jpg, .jpeg etc.";
            }
        }
        else{
            // No required values posted.
            $errors = true;
            $class= "alert-warning";
            $fa = "fa-frown";
            $message = "To add new user please fill all required details.";
        }
    }
    else{
        // Do nothing.
    }
?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Simple Dashboard - New User</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" 
            integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato|Roboto" rel="stylesheet">  
        <link rel="stylesheet" href="css/simpledashboard.css">
    </head>
    <body>
        <header>
            <h1><a href="index.php">Simple Dashboard</a></h1>
        </header>

        <div class="users">
            <div style="width:100%; margin-bottom: 50px;">
                <a href="list.php" class="btn-orange">
                    <span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Users
                </a>
            </div>

            <?php
                // Handling errors.
                if($errors){?>
                    <div class="alert <?= $class; ?>">
                        <span class="fa <?= $fa; ?>"></span>
                        <span><?= $message; ?></span>
                    </div>
               <?php }
            ?>

            <h2>Adding new user</h2>
            <hr />
            <br />
            
            <form name="user-form" class="user-form" method="post" 
                enctype="multipart/form-data" action="new.php" onsubmit="return validatePassword();">
                <div class="form-input">
                    <label>Firstname <span style="color:red;">*</span></title>
                    <input type="text" name="firstname" placeholder="User Firstname" value="<?= $_POST['firstname'] ?>" required/>
                </div>

                <div class="form-input">
                    <label>Lastname <span style="color:red;">*</span></title>
                    <input type="text" name="lastname" placeholder="User Lastname" value="<?= $_POST['lastname'] ?>" required/>
                </div>

                <div class="form-input">
                    <label>Age <span style="color:red;">*</span></title>
                    <input type="number" name="age" placeholder="User Age" value="<?= $_POST['age'] ?>" required/>
                </div>

                <div class="form-input">
                    <label>Email <span style="color:red;">*</span></title>
                    <input type="email" name="email" placeholder="User Email" value="<?= $_POST['email'] ?>" required/>
                </div>

                <div class="form-input">
                    <label>Avatar / Image <span style="color:red;">*</span></title>
                    <input type="file" name="avatar" placeholder="User Avatar" accept="image/*" required/>
                </div>

                <div class="form-input">
                    <label>City <span style="color:red;">*</span></title>
                    <input type="text" name="city" placeholder="User City" value="<?= $_POST['city'] ?>" required/>
                </div>

                <div class="form-input">
                    <label>Region <span style="color:red;">*</span></title>
                    <input type="text" name="region" placeholder="User Region" value="<?= $_POST['region'] ?>" required/>
                </div>

                <div class="form-input">
                    <label>Country <span style="color:red;">*</span></title>
                    <input type="text" name="country" placeholder="User Country" value="<?= $_POST['country'] ?>" required/>
                </div>

                <hr />

                <div class="form-input">
                    <label>Password <span style="color:red;">*</span></title>
                    <input type="password" id="password" name="password" placeholder="User Password" required minlength="6"/>
                </div>

                <div class="form-input">
                    <label>Confirm Password <span style="color:red;">*</span></title>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required minlength="6"/>
                </div>

                <div class="form-input">
                    <button type="submit" name="adduser" class="btn-green">Add User</button>
                </div>
            </form>
        </div>

        <footer>
            <span>&copy; 2020 - SimpleDashboard</span>
        </footer>
        <script type="text/javascript">
            function validatePassword() {
                var password = document.getElementById('password').value;
                var confirm_password = document.getElementById('confirm_password').value;
                if( password != confirm_password) {
                    alert('Password do not match.');
                    return false;
                }
                return true;
            }
        </script>
    </body>
</html>