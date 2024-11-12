
<?php
include "dbconnect.php";

session_start();

$client_id = '628077607742-ukk45tqm3ueq8uskoksmpj1tt2qk34lb.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/htdocs/practice/login.php';
$scope = 'email profile';

if (isset($_GET['code'])) {
    // Exchange the authorization code for an access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $params = [
        'code' => $_GET['code'],  // the code is provided by the google
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
    ];

    $curl = curl_init($token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $token_data = json_decode($response, true);
    $_SESSION['access_token'] = $token_data['access_token'];

    // Get user info
    $user_info_url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token_data['access_token'];
    $user_info = json_decode(file_get_contents($user_info_url), true);

    // Save user info in session or database
    $_SESSION['user'] = $user_info;

    header('Location: dashboard.php');
    exit();
}

// Generate the Google OAuth 2.0 URL
$auth_url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => $scope,
    'access_type' => 'online',
]);


if($_SERVER ["REQUEST_METHOD"] == "POST"){
  
  $email =$_POST['email'];  
  $password = $_POST['password'];
  
  
  $stmt = $conn->prepare("SELECT * FROM login_table WHERE email = ? and password= ?");
  $stmt->bind_param("ss",$email,$password);
  $stmt->execute();
  $result=$stmt->get_result();
    

  if($result->num_rows > 0){
    echo "<script> alert('login sucessfuly ')
    window.location.href= 'forgetpass.php';
    </script>";
  }
  else {
    echo "login failed";
  }
  $result->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Put icon inside an input element</title>
    <!-- <meta name="google-signin-client_id" content="628077607742-ukk45tqm3ueq8uskoksmpj1tt2qk34lb.apps.googleusercontent.com">
    <link rel="stylesheet" href=
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://apis.google.com/js/platform.js" async defer></script> --> 
    <link rel="stylesheet" href=
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
     <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container-1">
    <form action="index.php" method="POST">
        <h2>Login</h2>
        <span class="material-symbols-outlined" style="font-size: 100px; ">
account_circle
</span>

        <div class="input-icons">
            <i class="fa fa-envelope icon"></i>
            <input class="input-field" type="text" name="email" placeholder="Email">
        </div>

        <div class="input-icons">
            <i class="fa fa-key icon"></i>
            <input class="input-field" type="password" name="password" placeholder="Password">
        </div>
      
        <button value="submit" type="submit">Login</button>
        <br><br><hr>
       
        <div class="para">
            <strong><p class="">Forget the password.<a href="forgetpass.php">Click here</a> </p></strong>

            <strong><p class=""> Create Your Own Account. <a href="login.php">Click here.</a></p></strong>
           
            <a href="<?php echo htmlspecialchars($auth_url); ?>" class="google-btn">
                <img src="google.png" alt="google" width="20" height="20"> Log in with Google
            </a>
            
        </div>
        </div>
      
    </form>
    </div>
</body>

</html>