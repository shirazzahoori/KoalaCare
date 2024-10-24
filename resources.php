<!DOCTYPE html>
<?php

    $url= $_SERVER['REQUEST_URI'];   

    $user_name='';
    $password='';
    // Use parse_str() function to parse the
    // string passed via URL
    if(strpos($url,'?') != false){
        $url = substr($url, strpos($url,'?')+1);

        parse_str($url, $params);
        $user_name=$params['user'];

        $password=$params['pwd'];
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
    <title>Resource Page</title>
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

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            color: #0064b6;
        }

        .header p {
            font-size: 18px;
            color: #333;
        }

        /* Section Styles */
        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            margin: 0;
            font-size: 24px;
            color: #0077B6;
            
        }

        .section p {
            margin: 10px
            font-size: 16px;
            color: #333;
            margin-bottom: 30px;

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

        <!-- Header Section -->
        <div class="header">
            <h1> Activities to lift mood </h1>
                
        </div>


            
      



        <!-- Sections -->
        <div class="section">
             

            <h2> <a href = "https://advancedpsychiatryassociates.com/resources/blog/solo-activities-for-depression"> Solo Activities</a href></h2>
        </div>

        <div class="section">
            <h2> <a href = "https://www.mayoclinic.org/diseases-conditions/depression/in-depth/depression-and-exercise/art-20046495">Depression and exercise </a href> </h2>
        </div>
        <div class="section">
            <h2> <a href = "https://health.usnews.com/health-care/patient-advice/articles/activities-to-help-fight-depression">  Activities to help fight depression</a href> </h2>
        </div>
        <br> <br> <br>
        <div class="header">
            <h1> Resources </h1>
                
        </div>

        <div class="section">
            <h2> <a href = "https://www.mentalhealth.ca.gov/"> State of California Mental Health for all </a href> </h2>
        </div>
        <div class="section">
            <h2> <a href = "https://www.nimh.nih.gov"> National Institute of Mental Health (NIMH) </a href> </h2>

        </div>
        <div class="section">
            <h2> <a href = "https://mhanational.org"> Mental Health America (MHA) </a href> </h2>
        </div>
        <div class="section">
            <h2> <a href = "https://www.mentalhealthfirstaid.org"> Mental Health First Aid </a href> </h2>

        </div>



    </div>

</body>
</html>
