<?php

ob_start();
session_start();
require_once 'config.php';

// it will never let you open index(login) page if session is set
if ( isset($_SESSION['user'])!="" ) {
 header("Location: /dashboard.php");
 exit;
}

$error = false;

if( isset($_POST['btn-login']) ) {

 // prevent sql injections/ clear user invalid inputs
 $email = trim($_POST['email']);
 $email = strip_tags($email);
 $email = htmlspecialchars($email);

 $pass = trim($_POST['pass']);
 $pass = strip_tags($pass);
 $pass = htmlspecialchars($pass);
 // prevent sql injections / clear user invalid inputs

 if(empty($email)){
  $error = true;
  $emailError = "Please enter your email address.";
 } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
  $error = true;
  $emailError = "Please enter valid email address.";
 }

 if(empty($pass)){
  $error = true;
  $passError = "Please enter your password.";
 }

 // if there's no error, continue to login
 if (!$error) {

  $password = hash('sha256', $pass); // password hashing using SHA256

  $res=mysql_query("SELECT userId, userName, userPass FROM users WHERE userEmail='$email'");
  $row=mysql_fetch_array($res);
  $count = mysql_num_rows($res); // if uname/pass correct it returns must be 1 row

  if( $count == 1 && $row['userPass']==$password ) {
   $_SESSION['user'] = $row['userId'];
   header("Location: /dashboard.php");
  } else {
   $errMSG = "Incorrect Credentials, Try again...";
  }

 }

}

include '../includes/head.php';
include '../includes/nav.php';
include '../includes/scripts.php';

echo '
<div id="index-banner" class="parallax-container">
  <div class="section no-pad-bot">
    <div class="container">
      <br><br>
      <h1 class="header center teal-text text-lighten-2">Login</h1>
      <div class="row center">
        <h5 class="header col s12 light"> Log in to your Profile to view your results</h5>
      </div>
      <!-- <div class="row center">
        <a href="" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">Register Below</a>
      </div> -->
      <br><br>

    </div>
  </div>
  <div class="parallax"><img src="/images/register.jpeg" alt="Unsplashed background img 1"></div>
</div>

<div class="container">

 <div id="login-form">
    <form method="post" action="'; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '" autocomplete="off">

     <div class="col-md-12">

         <div class="form-group">
             <h2 class="">Sign In</h2>
            </div>

         <div class="form-group">
             <hr />
            </div>';


   if ( isset($errMSG) ) {


    echo '<div class="form-group">
             <div class="alert alert-danger">
    <span class="glyphicon glyphicon-info-sign"></span>'; echo $errMSG;
                echo '</div>
             </div> ';

   }

        echo '    <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input type="email" name="email" class="form-control" placeholder="Your Email" value="'; echo $email; echo '" maxlength="40" />
                </div>
                <span class="text-danger">'; echo $emailError; echo '</span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="pass" class="form-control" placeholder="Your Password" maxlength="15" />
                </div>
                <span class="text-danger">'; echo $passError; echo '</span>
            </div>



            <div class="row center">
              <div class="form-group">
               <button type="submit" class="btn btn-block btn-primary blue accent-2" name="btn-login" style="margin:auto" >Login</button>
              </div>
            </div>



            <div class="form-group" style="text-align:center">
             <p style="text-align:center"> Don\'t have an account? <a href="/server/register.php">Signup Here</a> </p>
            </div>

        </div>

    </form>
    </div>

</div>

';

?>
<?php ob_end_flush(); ?>
