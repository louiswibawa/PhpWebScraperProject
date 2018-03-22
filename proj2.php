<?php

  $weatherInfo = "";
  $error = "";

  if (array_key_exists('city', $_GET)){

    // strip out all spaces when searching for cities
    $city = str_replace(' ', '', $_GET['city']);

    // check if url exists
    $file_headers = @get_headers("https://www.weather-forecast.com/locations/".$city."/forecasts/latest");
    
    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {

        $error = "That city could not be found.";

    } else {

        // get contents from a site
        $forecastPage = file_get_contents("https://www.weather-forecast.com/locations/".$city."/forecasts/latest");

        // extract the part of the string
        $pageArray = explode('</h2>(1&ndash;3 days)</span><p class="b-forecast__table-description-content"><span class="phrase">', $forecastPage);

        if (sizeof($pageArray) > 1){

            // delete all trailing extras of the string
            $secondPageArray = explode('</span></p></td><td colspan="9"><span class="b-forecast__table-description-title">', $pageArray[1]);

            if(sizeof($secondPageArray) > 1){

                // store weather info in a string to be used later
                $weatherInfo = $secondPageArray[0];

            } else {

                $error = "That city could not be found.";

            }

        } else {

            $error = "That city could not be found.";

        }

    }

  }

?>



<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Weather Scraper</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style type="text/css">
        html { 
          background: url(background.jpg) no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
        }

        body {
          background-color: transparent;
        }

        .container{
          text-align: center;
          margin-top: 200px;
          width: 650px;
        }

        input{

          margin: 20px 0;

        }

        #weather{

          margin-top: 50px;

        }

    </style>

  </head>
  <body>
    <div class="container">
      
      <h1>What's The Weather</h1>
      <form>
        <div class="form-group">
          <label for="city">Enter the name of a city</label>
          <input type="text" class="form-control" id="city" name="city" placeholder="Eg. London, Tokyo" value=" <?php 

            if (array_key_exists('city', $_GET)){
              echo $_GET['city']; 
            }

          ?>" >
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      <div id="weather"><?php 

          if ($weatherInfo){

            echo '<div class="alert alert-success" role="alert">'.$weatherInfo.'</div>';

          } else if($error){

            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';

          }

        ?>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>