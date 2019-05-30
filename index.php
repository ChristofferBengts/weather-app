<?php

    // Context with SSL disabled
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );  

    $context = stream_context_create($arrContextOptions);

    $weather = "";
    $error = "";

    if (array_key_exists('city', $_GET)) {

        $city = str_replace(' ', '', $_GET['city']);

        //$file_headers = @get_headers("https://www.weather-forecast.com/locations/".$city."/forecasts/latest/");

        //print_r($file_headers);

        /*if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
            
            $error = "That city could not be found.";

        } else {*/

        $forecastPage = file_get_contents("https://www.weather-forecast.com/locations/".$city."/forecasts/latest/", false, $context);

        if ($forecastPage) {

            // split string twice to get correct textcontent
            $pageArray = explode('<p class="b-forecast__table-description-content"><span class="phrase">', $forecastPage);

            if (sizeof($pageArray) > 1) {

                $secondPageArray = explode('</span></p></td>', $pageArray[1]);

                if (sizeof($secondPageArray) > 1) {

                    $weather = $secondPageArray[0];

                } else {
                    
                    $error = "That city could not be found.";

                }
                                               
            } else {
                
                $error = "That city could not be found.";

            }
            

            

        } else {

            $error = "That city could not be found.";

        }
        
    }



?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <style type="text/css">
    
        html {

            background: url(background.jpg) no-repeat center center fixed; 
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;

        }

        body {

            background: none;

        }

        .container {

            text-align: center;
            margin-top: 100px;
            width: 450px;

        }

        input {

            margin: 20px 0;
        }

        #weather {

            margin-top: 15px;

        }


    </style>
    <title>Weather Scraper</title>

  </head>

  <body>

    <div class="container">
        
        <h1>What's the weather?</h1>

        <form>

            <div class="form-group">

                <label for="city">Enter the name of a city.</label>
                <input type="text" class="form-control" name="city" id="city" placeholder="eg. London, Tokyo" value="<?php 
                if (array_key_exists('city', $_GET)){echo $_GET['city'];}?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Submit</button>

            <div id="weather">
                <?php 

                if ($weather) {

                    echo '<div class="alert alert-primary" role="alert">'.$weather.'</div>';

                } else if ($error) {

                    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

                }

                ?>
            </div>

        </form>

    </div>





    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>

</html>