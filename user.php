<!-- Updating user password -->
<?php
    // Global variables.
    $errors = false;
    $class = "";
    $fa = "";
    $message = "";
    if(isset($_POST['updatepassword'])){
        // Check passed values
        if( (isset($_POST['userid']) && !empty($_POST['userid'])) &&
            (isset($_POST['new_password']) && !empty($_POST['new_password']))){

            // Update user password.
            // Include classes
            require_once 'config/Database.php';
            require_once 'classes/User.php';

            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $userid = $_POST['userid'];

            $database = new Database();
            $link = $database->getLink();
            $userObject = new User($link);

            $user = $userObject->getUser($userid);

            $result = $user->updatePassword($password);

            if($result){
                // User added.
                $errors = true;
                $class= "alert-success";
                $fa = "fa-check";
                $message = "User password updated successfully.";
            }
            else{
                // Fail to update user password.
                $errors = true;
                $class= "alert-danger";
                $fa = "fa-ban";
                $message = "Can not udpate user password. Please try again.";
            }
        }
        else{
            // No required values posted.
            $errors = true;
            $class= "alert-warning";
            $fa = "fa-frown";
            $message = "To update user password please fill all required fields.";
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
        <title>Simple Dashboard - Listing Users</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" 
            integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" 
            crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato|Roboto" rel="stylesheet">  
        <link rel="stylesheet" href="css/simpledashboard.css">
    </head>
    <body>
        <header>
            <h1><a href="index.php">Simple Dashboard</a></h1>
        </header>

        <div class="users">
            <?php
                // Handling errors.
                if($errors){?>
                    <div class="alert <?= $class; ?>" style="margin-bottom: 3rem;">
                        <span class="fa <?= $fa; ?>"></span>
                        <span><?= $message; ?></span>
                    </div>
               <?php }
            ?>

            <!-- Displaying a user -->
            <?php
                if(isset($_GET['id']) && !empty(trim($_GET['id']))){
                    // Include classes
                    require_once 'config/Database.php';
                    require_once 'classes/User.php';

                    $database = new Database();
                    $link = $database->getLink();
                    $userObject = new User($link);
                    
                    $id = $_GET['id'];
                    $user = $userObject->getUser($id);
                    
                    if ($user) {?>
                        <div style="width:100%; margin-bottom: 50px;">
                            <a href="list.php" class="btn-orange">
                                <span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Users
                            </a>
                        </div>
                        <div class="big-image" 
                            style="background: url('uploads/images/<?= $user->getAvatar(); ?>') 50% 50% no-repeat;">
                            &nbsp;
                        </div>
                        <div class="user">
                            <div class="details">
                                <table>
                                    <tr>
                                        <th>Firstname</th>
                                        <td><?= $user->getFirstname(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Lastname</th>
                                        <td><?= $user->getLastname(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Age</th>
                                        <td><?= $user->getAge(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= $user->getEmail(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td><?= $user->getCity(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Region</th>
                                        <td><?= $user->getRegion(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td><?= $user->getCountry(); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Registered On</th>
                                        <td><?= (new DateTime($user->getCreatedAt()))->format('M d, Y'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- ./user -->
                        <br />
                        <h2>Change Password</h2>
                        <hr />
                        <br />
                        <form name="user-form" class="user-form" method="post" enctype="multipart/form-data" 
                            action="user.php?id=<?= $user->getId(); ?>" onsubmit="return validatePassword();">
                            <div class="form-input">
                                <label>New Password <span style="color:red;">*</span></title>
                                <input type="password" id="password" name="new_password" placeholder="New User Password" required minlength="6" />
                            </div>

                            <div class="form-input">
                                <label>Confirm New Password <span style="color:red;">*</span></title>
                                <input type="password" id="confirm_password" name="confirm_new_password" placeholder="Confirm New Password" required minlength="6" />
                            </div>

                            <div class="form-input">
                                <input type="hidden" name="userid" value="<?= $user->getId(); ?>" />
                                <button type="submit" name="updatepassword" class="btn-green">Update Password</button>
                            </div>
                        </form>
                    <?php } else {
                        // Redirect to user listing.
                        header("location: list.php");
                        exit();
                    }
                }
                else{
                    // Redirect to user listing.
                    header("location: list.php");
                    exit();
                }
            ?>
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