<!DOCTYPE html>
<?php
    $conn = new mysqli('localhost', 'app', 'appuser', 'app_DB');
    $_SESSION['username']='';
    $_SESSION['pwd']='';
    $user_name='';
    $password='';

    //$conn = new mysqli('localhost', 'app2', 'appuser', 'app_DB');
    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['submit_button']))
    {
        $user_name = $_REQUEST['user_name']; 
        $email = $_REQUEST['email'];
        if ( isset( $_REQUEST['gender'] ) ){
            $gender = $_REQUEST['gender'];     
        } else {
            $gender='';
        }

        if ( isset( $_REQUEST['race'] ) ){
            $race = $_REQUEST['race'];     
        } else {
            $race='';
        }

        if ( isset( $_REQUEST['birth_year'] ) ){
            $birth_year = $_REQUEST['birth_year'];     
        } else {
            $birth_year='';
        }
        
        $zip = $_REQUEST['zip'];
        $password = $_REQUEST['password'];

        $sql = "INSERT INTO app_DB.table_user (birth_year, email, gender, name, password, race, zip) VALUES ('$birth_year','$email','$gender','$user_name','$password','$race','$zip')";

        if ($conn->query($sql) === TRUE) {
            echo "User '$user_name' has been created";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the statement and connection
        $conn->close();

        $_SESSION['username']=$user_name;
        $_SESSION['pwd']=$password;

    }

    $_SESSION['home_url'] = "http://localhost:8000/app/home.php?user=".$user_name."&pwd=".$password;
    $_SESSION['record_url'] = "http://localhost:8000/app/record.php?user=".$user_name."&pwd=".$password;
    $_SESSION['data_url'] = "http://localhost:8000/app/data.php?user=".$user_name."&pwd=".$password;
    $_SESSION['resources_url'] = "http://localhost:8000/app/resources.php?user=".$user_name."&pwd=".$password;
    $_SESSION['account_url'] = "http://localhost:8000/app/account.php?user=".$user_name."&pwd=".$password;
    $_SESSION['login_url'] = "http://localhost:8000/app/login.php?user=&pwd=";
    $_SESSION['logout_url'] = "http://localhost:8000/app/home.php?user=&pwd=";

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
            font-size: 24px;
        }

        /* Navigation Menu */
        nav {
            background-color: #0077B6;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Main Content Styles */
        .container {
            margin: 20px auto;
            max-width: 645px;
            background-color: #7dd3db;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Section */
        .form-section label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .form-section input[type="text"],
        .form-section input[type="email"],
        .form-section input[type="password"] {
            width: 95%;
            padding: 8px;
            margin-bottom: 14px;
            border-radius: 5px;
            border: 2px solid #0077B6;
            font-size: 12px;
        }

        .form-section .checkbox-group {
            margin-bottom: 20px;
        }

        .form-section .checkbox-group label {
            font-size: 16px;
            margin-right: 20px;
        }

        /* Buttons */
        .buttons {
            text-align: right;
        }

        .buttons button {
            background-color: #0077B6;
            color: white;
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .buttons button.cancel {
            background-color: #ccc;
            color: #333;
        }

        .buttons button:hover {
            opacity: 0.8;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 3px;
            
        }  
    </style>
</head>
<body>
        <img src="http://localhost:8000/app/koala.jpg" alt="Description of Image" width="150" height="150" class="center">
        </img>
    <!-- Navigation Menu -->
    <nav>
        <a href=<?php echo $_SESSION['home_url'];?>>Home</a>
        <a href=<?php echo $_SESSION['record_url'];?>>Record</a>
        <a href=<?php echo $_SESSION['data_url'];?>>Data</a>
        <a href=<?php echo $_SESSION['resources_url'];?>>Resources</a>
        <a href=<?php echo $_SESSION['account_url'];?>>Account</a>
        <a href=<?php echo $_SESSION['login_url'];?>>Login</a>
        <a href=<?php echo $_SESSION['logout_url'];?>>Logout</a>
    </nav>
    <!-- Main Content Container -->
    <div class="container">
        <form action="account.php" method="post">
        <!-- Form Section -->
        <div class="form-section">
            <label for="name">User Name</label>
            <input type="text" id="user_name" name="user_name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password">

            <label>Gender (Optional)</label>
            <div class="checkbox-group">
                <label><input type="radio" name="gender" value="Woman"> Woman</label>
                <label><input type="radio" name="gender" value="Man"> Man</label>
                <label><input type="radio" name="gender" value="Transgender"> Transgender</label>
                <label><input type="radio" name="gender" value="Non-binary/non-conforming"> Non-binary/non-conforming</label>
                <label><input type="radio" name="gender" value="Prefer not to respond"> Prefer not to respond</label>
            </div>

            <label for="zip">Zip Code</label>
            <input type="text" id="zip" name="zip">

            <label>Year of Birth (Optional)</label>
            <input type="text" id="birth_year" name="birth_year">

            <label>Race (Optional)</label>
            <div class="checkbox-group">
                <label><input type="radio" name="race" value="white"> White</label>
                <label><input type="radio" name="race" value="Black or African American,"> Black or African American,</label>
                <label><input type="radio" name="race" value="American Indian or Alaska Native"> American Indian or Alaska Native</label>
                <label><input type="radio" name="race" value="Asian"> Asian</label>
                <label><input type="radio" name="race" value="Black or African American,"> Black or African American,</label>

                <label><input type="radio" name="race" value="Native Hawaiian or Other Pacific Islander"> Native Hawaiian or Other Pacific Islander</label>

                <label><input type="radio" name="race" value="Other"> Other</label>
                
            </div>
            
        </div>

        <!-- Buttons -->
        <div class="buttons">
            <button class="cancel">Cancel</button>
            <button type="submit" name="submit_button">Submit</button>

        </div>
        </form>
    </div>
        
        

</body>
</html>
