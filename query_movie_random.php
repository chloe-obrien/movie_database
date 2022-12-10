<?php
  $host = "";
  $user = "";
	$password = "";
  $database = "";
  $link = mysqli_connect($host, $user, $password, $database);

	if(! $link ){
		die('Could not connect: ' . mysqli_error($link));
	}

	$movie_query = "
    SELECT
    	moviebasic.title AS title,
			moviebasic.plot AS plot,
			year.year AS year,
			image.picture AS img_url
		FROM
			moviebasic
		LEFT JOIN image ON moviebasic.movie_id = image.movie_id
		LEFT JOIN year ON moviebasic.year_id = year.year_id
		ORDER BY
			RAND()
		LIMIT
			1
  ";

	$movie_result = mysqli_query($link, $movie_query);
	if(! $movie_result ){
    die('Could not connect: ' . mysqli_error($link));
  }

	print "
		<div class='random-movie'><div class='random-container'>
	";
	while ($row = mysqli_fetch_array($movie_result)) {
    print "
			<form action='movie.php' method='GET'>
			<input type='hidden' name='title' value='$row[title]'>
			<button type='submit'>

			<a href='movie.php?title=$row[title]'>
      	<div class='random-title'>$row[title]</div>
      	<div class='random-year'>$row[year]</div>
      	<div class='random-tagline'>$row[plot]</div>
				<img src='$row[img_url]'>
			</a>

			</button>
			</form>
	";
  }
	print "
		</div></div>
	";

	mysqli_close($link);

?>
