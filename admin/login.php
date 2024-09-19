<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <meta http-equiv='X-UA-Compatible' content='IE=edge'>

    <title>Login</title>

    <link rel="website icon" href="../images/makati-logo.png" type="png">

    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
</head>

<body>
    <div class="wrapper">
        <div class="container-body-login">
            <div class="transparent">
                <div class="container-white-login">

                    <div class="row-center">
                        <div class="container-round login">
                            <img src="../images/makati-logo.png" alt="" class="image">
                        </div>
                    </div>

                    <div class="row-center title-26px">
                        MakatiLibros
                    </div>

                    <form action="dashboard.php" method="POST" enctype="multipart/form-data" id="form" onsubmit="return validateForm()">
                        <div class="container-form">
    
                            <div class="container-input-100">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="input-text" autocomplete="off" required>
                            </div>
    
                            <div class="container-input-100">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="input-text" autocomplete="off" required>
                            </div>

                            <div class="row row-right">
                                <button type="submit" name="submit" class="button-submit">Login</button>
                            </div>
                        </div>
                    </form>

                    <div class="row-center">
                        <a href="register.php" class="login-link font-14px">Register</a>
                        <a href="reset.php" class="login-link  font-14px ">Reset Password</a>
                    </div>


                </div>
            </div>
        </div>

    </div>
</body>

</html>