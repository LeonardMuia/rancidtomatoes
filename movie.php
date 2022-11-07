<!DOCTYPE html>
<html lang="en">

    <?php 

    if(isset($_GET["film"])) {

        $movie = preg_replace("/&#?[a-z0-9]+;/i","",htmlspecialchars($_GET["film"])); 

        $title = strtoupper($movie);
        $path = getcwd().'/'.$movie;
        $overview_image = $movie."/overview.png";

        $movie_details = array();
        $movie_overview = array();
        $review_content = array();
        $number_of_reviews = 0;
        $review_number = 0;
        $pagination;

        if(is_dir($movie)) {
            
            /* 
            * Open the current movie directory
            * locate the info.txt file and read contents of the file
            * Pass the contents of the txt file into a movie details array
            */

            $files = opendir($movie);

            while (($file = readdir($files)) !== false) {

                if(substr($file, 0, 6) === "review") {
                    
                    ++$number_of_reviews;

                    $filePathReview = $path."/".$file;
                    $review = fopen($filePathReview, "r") or die("Permission denied");

                    while(! feof($review)) {    
                        $line = fread($review, filesize($filePathReview));
                        array_push($review_content,$line);
                    }
                    fclose($review);
                }

                /* 
                * locate the info.txt file and read contents of the file
                * Pass the contents of the txt file into a movie details array
                */

                if($file === "info.txt") {
                    $filePathInfo = $path."/". $file;
                    $info = fopen($filePathInfo, "r") or die("Permission denied");
                    while(! feof($info)) {
                        $line = fgets($info);
                        array_push($movie_details,$line);
                    }

                    fclose($info);
                }

                /* 
                * locate the review.txt file and read contents of the file
                * Pass the contents of the txt file into a movie overview array
                */

                if($file === "overview.txt") {
                    $filePathOverview = $path."/".$file;
                    $overview = fopen($filePathOverview, "r") or die("Permission denied");
                    while(! feof($overview)) {
                        $line = fgets($overview);
                        array_push($movie_overview,$line);
                    }

                    fclose($overview);
                }
            }
            
            closedir();
    ?>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title." - "; ?>Rancid Tomatoes</title>
        
        <?php 

            /* 
            *Although not necessary perform type conversion through casting 
            * Use it in the conditional statement for the icon 
            */

            $overall_rating = (int) $movie_details[2];

        ?>

        <link rel="icon" 
            <?php 
            /* 
            * Show rotten or fresh favicon based on the overall rating.
            */
            
            if($overall_rating > 50){ ?> href="images/fresh.gif" <?php } else { ?> href="images/rotten.gif" <?php } ?> 
        >
    
        <link rel="stylesheet" type="text/css" href="movies.css">

    </head>

        <body>
            <div class="banner">
                <img src="images/banner.png" alt="Rancid Tomatoes" />
            </div>

                <h1 class="heading"><?php echo $movie_details[0]. "(".$movie_details[1].")"?></h1>

                <main>
                    <div class="overall-area">
                        <div class="overall-top-section">
                            <img 
                                <?php 
                                /* 
                                * Show rotten or fresh image based on the overall rating.
                                */
                                
                                if($overall_rating > 50){ ?> 
                                    src="images/freshbig.png" alt="Fresh" 

                                <?php } else { ?> 
                                    src="images/rottenbig.png" alt="Rotten" 
                                <?php } ?> 
                            />
                            <span class="rating"><?php echo $overall_rating. "%"; ?></span>
                        </div>
                        <div class="review-wrapper">
                            <?php

                                for($i=0; $i<$number_of_reviews; $i++){
                        
                            ?>
                                <div class="review-container">
                                    <p class="review">
                                        <img src="images/rotten.gif" alt="Rotten" />
                                        <q>Ditching the cheeky, self-aware wink that helped to excuse the concept's inherent corniness, the movie attempts to look polished and 'cool,' but the been-there animation can't compete with the then-cutting-edge puppetry of the 1990 live-action movie.</q>
                                    </p>
                                    <p class="reviewer">
                                        <img src="images/critic.gif" alt="Critic" />
                                        <span>
                                            Peter Debruge <br />
                                            Variety
                                        </span>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="pagination">
                            <?php 
                                // Computing pagination based on the number of reviews.txt files
                                echo "<p>(1-".$number_of_reviews.") of ".$number_of_reviews."</p>";
                            ?>
                        </div>
                    </div>

                    <div class="general-overview">
                        <div>
                            <img src=<?php echo $overview_image; ?> alt="general overview" />
                        </div>
                    
                        <dl>
                            <?php
                                /*
                                * Perform a foreach method to iterate over each overview detail
                                * Use explode method to create an array from the string returned from the foreach
                                * Display the title of the item and the description
                                */

                                foreach($movie_overview as $item) {
                                    $strArray = explode(":",$item);
                                    echo "<dt>".$strArray[0]."</dt>";
                                    echo "<dd>".$strArray[1]."</dd>";
                                }
                            ?>
                        </dl>
                    </div>
                </main>
        </body>

    <?php
            }
        }
    ?>
</html>