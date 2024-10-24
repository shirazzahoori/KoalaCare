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

		// This arrangement can be altered based on how we want the date's format to appear.
	$today = date("m/d/y");
	$today_date = (string) $today;
		//console.log(currentDate); // "17-6-2022"
    $conn = new mysqli('localhost', 'app', 'appuser', 'app_DB');
    $_SESSION['thoughts'] = 'Enter text here...';
    $_SESSION['output_image'] = "";
    $_SESSION['user_name'] = $user_name;
    //$conn = new mysqli('localhost', 'app2', 'appuser', 'app_DB');
    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['submit_button']))
    {	

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



    	$text = $_REQUEST['thoughts']; 

        $sql = "INSERT INTO app_DB.table_log (created_dt, depressed_yn, text, user_name) VALUES 
        ('$today_date',1,'$text','$user_name')";

        if ($conn->query($sql) === TRUE) {
            //echo "Text has been recorded";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the statement and connection
        
        $_SESSION['thoughts'] = $text;
        $apiask='http://127.0.0.1:6000/getPredictionOutput?user='.$user_name.'&date='.$today_date; 

        $response = file_get_contents($apiask);
        
        //echo ":".$response.":";
        if (stripos($response, 'sad_nobackground.jpeg') !== FALSE ){
            $sql = "UPDATE app_DB.table_log SET depressed_yn=-1 WHERE created_dt= '$today_date' and user_name = '$user_name'";
            //echo $sql;
            $conn->query($sql);
        }

        $conn->close();
        $_SESSION['output_image']='width="125" height="125" src='.$response;
        //header("Location: http://localhost:8000/app/record.php?user=".$user_name."&pwd=".$password);
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Page</title>
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

        /* Camera Toggle Section */
        .camera-toggle {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .camera-toggle label {
            margin-right: 10px;
            font-size: 18px;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #0077B6;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* Camera Area */
        .camarea {
            width: 99.12%;
            height: 480px;
            background-color: white;
            border: 2px dashed #0077B6;
            margin-bottom: 20px;

        }
        .video {
            width: 80%;
            height: 200px;
            background-color: white;
            border: 2px dashed #0077B6;
            margin-bottom: 20px;
            border-radius: 10px;
            object-fit: cover;

        }

        /* Record Thoughts Section */
        .thoughts-section {
            margin-bottom: 20px;
        }

        .thoughts-section label {
            display: block;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .thoughts-section textarea {
            width: 97%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #0077B6;
            font-size: 16px;
            color:#808080;
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
        </img>    <!-- Navigation Menu -->
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
		<form action=<?php echo $_SESSION['record_url'];?> method="post">
        <!-- Camera Toggle -->
	        <div class="camera-toggle">
	            <label for="cameraToggle">Record a video</label>
	            <label class="toggle-switch">
	                <input type="checkbox" id="cameraToggle">
	                <span class="slider" id="slider"></span>
	            </label>
	        </div>

	        <!-- Camera Area -->
	        <div class = "camarea">
	            <video id="vid"></video>
	        </div>
	        
	        
		        <!-- Record Thoughts Section -->
		        <div class="thoughts-section">
		            <label for="thoughts">Record Thoughts</label>
		            <textarea type="text" id="thoughts" name="thoughts" 
		            ><?php echo $_SESSION['thoughts'];?></textarea>
		            
		        </div>

		        <!-- Buttons -->
		        <div class="buttons">
		            <button class="cancel">Cancel</button>
		            
		            <button type="submit" name = "submit_button" id = "submit_button" > 

                        Submit</button>
		            
				
		        </div>
	        

			<image id="output_image" name="output_image"  <?php echo $_SESSION['output_image'];?>
			></image>
		</form>
    </div>

</body>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        let but = document.getElementById("but");
        let video = document.getElementById("vid");
        let mediaDevices = navigator.mediaDevices;
        vid.muted = true;

        cameraToggle.addEventListener("click", () => {

            // Accessing the user camera and video.
            mediaDevices
                .getUserMedia({
                    video: true,
                    audio: true,
                })
                .then((stream) => {
                    // Changing the source of video to current stream.
                    video.srcObject = stream;

                    video.addEventListener("loadedmetadata", () => {
                        video.play();
                    });

                })
                .catch(alert);

        });
    });







    	// event : new recorded video blob available 
  	media_recorder.addEventListener('dataavailable', function(e) {
		blobs_recorded.push(e.data);
	});
  	let video_local = URL.createObjectURL(new Blob(blobs_recorded, { type: 'video/webm' }));
	download_link.href = video_local;

</script>


</html>
