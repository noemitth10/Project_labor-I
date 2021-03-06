<?php require_once "pages/head.php";?> 
<div class="login">
<?php
$errors = [];
$modalvalue=0;

if(is_post())
{
    $sql = new SQL();
    $userName = $_POST['userName'];
    $password = $_POST['password'];

    //VALIDATION

    //user name
    if($userName == null) $errors['userName'][] = 'User name is required!';
    else if(strlen($userName) < 6) $errors['userName'][] = 'User name is least 6 characters long!';
    else if(strlen($userName) > 25) $errors['userName'][] = 'User name is shorter than 25 characters!';
    $userFound = false;
    $emailFound = false;
    $userNames = $sql->execute("SELECT `user_name` FROM users");
    $emails = $sql->execute("SELECT `email` FROM `users`");
    foreach($userNames as $row) 
        if($row['user_name'] == $userName)  
               $userFound = true;
    foreach($emails as $row) 
        if($row['email'] == $userName)  
            $emailFound = true;                      
    if(!$userFound && !$emailFound)
        $errors['userName'][] = 'User name does not exist!';

    //Password
    if(count($errors) == 0)
    {
        $dbpassword = $sql->execute("SELECT `password` FROM `users` WHERE `user_name` = ? OR `email` = ?",$userName,$userName);
        if(password_verify($password,$dbpassword[0]['password'])) //Sikeres bejelentkezés
        {
            require_once "lib/userClass.php";
            $user = $sql->execute("SELECT * FROM `users` WHERE `user_name` = ? OR `email` = ?",$userName,$userName);
            $_SESSION['user_id'] = $user[0]['id'];
            $_SESSION['user_user_name'] = $user[0]['user_name'];
            $_SESSION['user_email'] = $user[0]['email'];
            $_SESSION['user_fullname'] = $user[0]['fullname'];
            $_SESSION['user_birth_date'] = $user[0]['birth_date'];
            $_SESSION['user_age'] = $user[0]['age'];
            $_SESSION['user_role'] = $user[0]['role'];
            $_SESSION['user_registration_date'] = $user[0]['registration_date'];
            $_SESSION['user_shippingID'] = $user[0]['shipping_id'];
            $_SESSION['user_del'] = $user[0]['del'];
            unset($_SESSION['guest_user_id']);
            header("Location: ".url('home'));
           
        }  

        else  
            $errors['password'][] = 'Invalid password!';            
    }
}

?>

<div class="login-div">
    <div class="login-left-side">
        <h1 class="login-h"> Log In </h1><br>
            <form class="login-form" action ="<?php echo url('login'); ?>" method ="POST" autocomplete="off">
                <label class="login-label" for="userName"> User Name </label><br>
                <input type ="text" name ="userName" value = "<?php echo isset($userName) ? $userName : '' ?>"> <br>
                <?php if(isset($errors['userName'])) foreach ($errors['userName'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 

                <label class="login-label" for="password"> Password </label><br>
                <input type ="password" name ="password"> <br>
                <?php if(isset($errors['password'])) foreach ($errors['password'] as $value) echo "<p class ='input-error'> $value </p>"; ?> 
                
                <button class ="login-button" style="vertical-align:middle" type="submit" ><Span>Log In</span></button>
            </form>
        <a href = "<?php echo url('forgottenPassword') ?>"> Forgot your password? </a>
    </div>
    <div class="login-right-side">
            <h1 class="login-h">Not a member?</h1>
            <p class="login-text">Here are some benefits for being a registered user:</p>
            <p>
                <ul class="login-ul">
                    <li>Monthly sales</li>
                    <li>Free shipping </li>
                    <li>Saved shipping information</li>
                    <li>You can leave review of the games and our webshop!</li>
                </ul>
            </p>
            <br>
            <a href="<?php echo url('register')?>"><button class="register-button" type="submit"><span>Register</span></button></a><br>
            <hr/>      
    </div>
</div>

</div>
<?php require_once "pages/footer.php"; ?>