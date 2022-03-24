<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thetaPay - Account Verification</title>
    <link rel="stylesheet" href="../public/styles/auth.css">
    <?php require_once './public/templates/inheader.php' ?>
</head>
<body>
    <?php require_once './public/templates/header1.php' ?>
    <main class="content">
        <?php 
            if(isset($_GET["message"])){
                echo '<div class="verify_notice">
                    <h3>Email Verified Succesfully</h3>
                    <p class="check"><span>&#10003;</span></p>
                    <a href="/GCB-thetaPay/gateway">
                        <button>Go To Portal</button>
                    </a>
                </div>';
            }else{
                echo '<div class="_error">
                        <p class="error_mgs">'.$_GET["error"].'</p>
                      </div>';
            }
            
        ?>
    </main>
</body>
</html>