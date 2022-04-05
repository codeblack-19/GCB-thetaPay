<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thetaPay - Reset Password</title>
    <link rel="stylesheet" href="../public/styles/globals.css">
    <link rel="stylesheet" href="../public/styles/auth.css">
    <link rel="shortcut icon" href="../public/asserts/favicon.ico" type="image/x-icon">
    <?php require_once './public/templates/inheader.php' ?>
</head>
<body>
    <?php require_once './public/templates/header1.php' ?>
    <main class="content">
        <?php 
            if(isset($_GET["message"])){
                echo    '<div class="change_password">
                            <h4>Change Password</h4>
                            <p id="_message"></p>
                            <form id="chPass" method="post" action="changePassword.ejs">
                                <input type="password" autocomplete="off" name="password" placeholder="Password"/>
                                <input type="password" autocomplete="off" name="verifyPassword" placeholder="Confirm Password"/>
                                <button type="submit" id="submitBtn">Submit</button>
                            </form>
                        </div>';
            }else{
                echo '<div class="_error">
                        <p class="error_mgs">'.$_GET["error"].'</p>
                      </div>';
            }
            
        ?>
    </main>
</body>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/changePassword.js"></script>
</html>