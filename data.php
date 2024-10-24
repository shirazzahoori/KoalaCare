<!DOCTYPE html>
<html lang="en">
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

 




    $conn = new mysqli('localhost', 'app', 'appuser', 'app_DB');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    $tabledata = [];
    
	$chartData2 =[['Date','Depressed'],['',0]]; 

    $sql = "SELECT created_dt, depressed_yn, text FROM app_DB.table_log where user_name = '$user_name'";
    $result = mysqli_query($conn, $sql);
    $_SESSION['tablevalue']=$result;

    while ($row = $result->fetch_assoc()) {
        $MyObjects = array();
        $emj="&#128512";
        if ($row["depressed_yn"] ==-1){
            $emj='&#128532';
        }
        #array_push($chartData, array($row["created_dt"], $row["depressed_yn"]));
#        $chartData[]= array($row["created_dt"], $row["depressed_yn"]);
        //array_push($tabledata, "Date: ". $row["created_dt"]. ", Text: ". . ", Depressed: ".$emj);
        //echo ("Date: '". $row["created_dt"]. "', Text: '". $row["text"]. "', Depressed: '".."'");

        $MyObject = [
             'Date' => $row["created_dt"],
             'Text' => $row["text"],
             'Emotion' => $emj
        ];

        $tabledata[] = $MyObject;
        $chartData2[]=array($row["created_dt"], $row["depressed_yn"]);
    }
    $tabledataInJson = json_encode($tabledata);
//    var_dump($tabledata);
        
        // Close the statement and connection
    $conn->close();

    $chartDataInJson2 = json_encode($chartData2);
    #echo ($chartDataInJson);
    #echo ($chartDataInJson2);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
            font-size: 24px;
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

        h2 {
            color: #0077B6;
            text-align: center;
        }

        .header p {
            font-size: 18px;
            color: #333;
        }


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

        .container {
            margin: 20px auto;
            max-width: 645px;
            background-color: #7dd3db;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }



        /* Graph Section */
        .graph-section {
            font-size: 18px;
            color: #333;
            text-align: center;
            margin-bottom: 40px;
        }

        .dropdown {
            margin: 10px;
            text-align: right;
        }

        .dropdown label {
            font-weight: bold;
        }

        .dropdown select {
            padding: 5px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #0077B6;
        }

        /* Table Section */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #023E8A;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #0077B6;
            color: white;
        }

        td {
            background-color: #FFFFFF;
        }

        .emoji {
            font-size: 24px;
            text-align: center;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 3px;
            
        }  
        .row {
            display: flex;
        }

        .column {
            float: left;
        }

        .left {
            width: 25%;
        }

        .right {
            width: 75%;
        }
    </style>
</head>


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
      	var jsonData = [];
        var data = google.visualization.arrayToDataTable( <?php echo $chartDataInJson2; ?>);

        var options = {
          title: '',
          curveType: 'function',
          lineWidth: 5,
          chartArea: {
            top: 10,
            left: 0,
            right: 0,
            bottom: 30
            },
        height: '100%',
        width: '100%',

          vAxis: {

                maxValue: 1,
                minValue: -1.5
		        }

        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }

      $url="https://n1lylprkmnr-496ff2e9c6d22116-5000-colab.googleusercontent.com/";
      $result = file_get_contents($url);
      //echo $result

    </script>

    


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
        <!-- Graph Section -->
            <div class="header">
                <h1>Track mood over time</h1>
            </div>
            <div class="graph-section">
                <div class="dropdown">
                    <label for="view">View:</label>
                    <select id="view" name="view">
                        <option value="daily">Daily</option>
                    </select>
                </div> 
            </div>     
        <div class="row">
            <div class="column" > <img src="http://localhost:8000/app/smily4.jpg"> </img></div>
            <div class="column" id="curve_chart" style="width: 600px; height: 284px"></div>
        </div>


    
        <script>
     //       const data = [
       //         { name: 'Rahul', age: 25, city: 'New Delhi' },
         //       { name: 'Vijay', age: 30, city: 'Muzaffarpur' },
           //     { name: 'Gaurav', age: 22, city: 'Noida' },
            //];
            const data = <?php echo $tabledataInJson; ?>

            function createTableWithInnerHTML() {
                let tableHTML = '<table border="1" style="width:45%" align="center" style="margin: 0px auto;"><tr>';

                Object.keys(data[0]).forEach(key => {
                    tableHTML += `<th>${key}</th>`;
                });

                tableHTML += '</tr>';

                data.forEach(item => {
                    tableHTML += '<tr>';
                    Object.values(item).forEach(value => {
                        tableHTML += `<td>${value}</td>`;
                    });
                    tableHTML += '</tr>';
                });

                tableHTML += '</table>';

                document.body.innerHTML += tableHTML;
            }

            createTableWithInnerHTML();
        </script>

    </div>


</body>
</html>