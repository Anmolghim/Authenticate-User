<?php
include 'dbconnect.php';
session_start();

$client_id = '628077607742-ukk45tqm3ueq8uskoksmpj1tt2qk34lb.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/htdocs/practice/login.php';
$scope = 'email profile';

if (isset($_GET['code'])) {
    // Exchange the authorization code for an access token
    $token_url = 'https://oauth2.googleapis.com/token';
    $params = [
        'code' => $_GET['code'],
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

// Database connection
$servername = "localhost";
$username= "root";
$password = "";
$dbname= "database1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmPassword'];

    if ($password != $confirmpassword) {
        echo "<script>
                alert('Passwords do not match');
                window.location.href = 'login.php';
              </script>";
    } else {
        $hashed_password = password_hash($password ,PASSWORD_DEFAULT);
        $sql = "INSERT INTO `login_table` (`username`, `email`, `password`) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        $result=$stmt->execute();

        if ($result) {
            echo "<script>
                    alert('Data inserted successfully');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "Insertion failed: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css 
">
  <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-2">
        <form method="post" action="" >
            <h2>Create Your Account</h2>
            <div class="input-icons">
            <i class="fa fa-user icon"></i>
                <input class="input-field" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-icons">
                <i class="fa fa-envelope icon"></i>
                <input class="input-field" type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-icons">
                <i class="fa fa-key icon"></i>
                <input class="input-field" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-icons">
                <i class="fa fa-key icon"></i>
                <input class="input-field" type="password" name="confirmPassword" placeholder="Confirm Password" required>
            </div>
            <button type="submit">Create an Account</button>
            <br><br><hr>
            <strong><p class="para">Signup with these services</p></strong>
            <div class="image">
                <img src="facebook.png" alt="facebook">
            </div>
            <a href="<?php echo htmlspecialchars($auth_url); ?>" class="google-btn">
                <img src="google.png" alt="google" width="20" height="20"> Log in with Google
            </a>
            <div class="para">
                <strong><p>I am already a member. <a href="index.php">click here</a></p>
