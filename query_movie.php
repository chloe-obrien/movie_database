<?php
  $host = "";
  $user = "";
	$password = "";
  $database = "";
  $link = mysqli_connect($host, $user, $password, $database);

	if(! $link ){
		die('Could not connect, init: ' . mysqli_error($link));
	}

$title = $_GET['title'];

$moviebasic_query = "
	SELECT
		*,
		releasedate.date AS date,
		releasedate.month AS month,
		releasedate.year AS year_rel,
		image.picture AS img_url,
		production.production AS production,
		year.year AS year
	FROM
		moviebasic
	LEFT JOIN
		image
			ON
				moviebasic.movie_id = image.movie_id
	LEFT JOIN
		releasedate
			ON
				moviebasic.released_id = releasedate.release_id
	LEFT JOIN
		year
			ON
				moviebasic.year_id = year.year_id
  LEFT JOIN
	  production
      ON
        moviebasic.production_id = production.production_id
	LEFT JOIN
		movielanguage
			ON
				movielanguage.movie_id = moviebasic.movie_id
	LEFT JOIN
		language
			ON
				movielanguage.language_id = language.language_id
  LEFT JOIN
    moviecountry
      ON
        moviecountry.movie_id = moviebasic.movie_id
  LEFT JOIN
    country
      ON
        moviecountry.country_id = country.country_id
	WHERE
		moviebasic.title
			LIKE
				'%".$title."%'
";

$actor_query = "
			SELECT
				actor.firstname AS act_firstname,
        actor.lastname AS act_lastname
			FROM
				moviebasic
			LEFT JOIN
				movieactor
					ON
						movieactor.movie_id = moviebasic.movie_id
			LEFT JOIN
				actor
					ON
						movieactor.actor_id = actor.actor_id
			WHERE
				moviebasic.title
					LIKE
						'%".$title."%'
";

$director_query = "
	SELECT
		director.firstname AS dir_firstname,
		director.lastname AS dir_lastname
	FROM
		moviebasic
  LEFT JOIN
    moviedirector
	    ON
  	    moviedirector.movie_id = moviebasic.movie_id
	LEFT JOIN
  	director
      ON
    		moviedirector.director_id = director.director_id
	WHERE
		title
			LIKE
				'%".$title."%'
";

$writer_query = "
  SELECT
    writer.firstname AS wri_firstname,
    writer.lastname AS wri_lastname
  FROM
    moviebasic
  LEFT JOIN
    moviewriter
      ON
        moviewriter.movie_id = moviebasic.movie_id
  LEFT JOIN
    writer
      ON
        moviewriter.writer_id = writer.writer_id
  WHERE
    title
      LIKE
        '%".$title."%'
";

$genre_query = "
	SELECT
		genre.genre AS genre
	FROM
		moviebasic
	LEFT JOIN
		moviegenre
			ON
				moviegenre.movie_id = moviebasic.movie_id
	LEFT JOIN
		genre
			ON
				moviegenre.genre_id = genre.genre_id
	WHERE
		title
			LIKE
				'%".$title."%'
";

$moviebasic_results = mysqli_query($link, $moviebasic_query);
$actor_results = mysqli_query($link, $actor_query);
$director_results = mysqli_query($link, $director_query);
$genre_results = mysqli_query($link, $genre_query);
$writer_results = mysqli_query($link, $writer_query);

$moviebasic_array = mysqli_fetch_array($moviebasic_results);

print "
<div class='movie-head'>
	<div class='movie-title'>$moviebasic_array[title]</div>
	<div class='movie-year'>$moviebasic_array[year]</div>
	<div class='movie-ratings'>
  	Metascore: <b>$moviebasic_array[metascore]</b> &nbsp;
  	IMDB Rating: <b>$moviebasic_array[imdbrating]</b>
	</div>
	<div class='movie-genres'>
";
while ($row = mysqli_fetch_array($genre_results)) {
print "
  	$row[genre] &nbsp;
";
	}
print "
	</div>
</div>

<div class='movie-container row'>
	<div class='movie-poster column'>
  	<img src='$moviebasic_array[img_url]'>
	</div>
";



print "
	<div class='movie-crew column'>
";
print "
	<div class='movie-directors'>
  	<table>
			<th>Director(s)</th>
";

while ($row = mysqli_fetch_array($director_results)) {
  print "
    	<tr>
      	<td> $row[dir_firstname] $row[dir_lastname] </td>
    	</tr>
  ";
}
print "
		</table>
	</div>
";

print "
	<div class='movie-writers'>
  	<table>
			<th>Writer(s)</th>
";

while ($row = mysqli_fetch_array($writer_results)) {
  print "
    	<tr>
      	<td> $row[wri_firstname] $row[wri_lastname] </td>
    	</tr>
  ";
}
print "
		</table>
	</div>
";


print "
	<div class='movie-cast'>
		<table>
			<th>Cast</th>
";

while ($row = mysqli_fetch_array($actor_results)) {
	print "
			<tr>
        <td>
          <div class='movie-button'>
          <form action='actor.php' method='GET'>
          <input type='hidden' name='name' value='$row[firstname]$row[lastname]'>
          <button type='submit'>
          <a href='actor.php?firstname=$row[act_firstname]&lastname=$row[act_lastname]'>
            $row[act_firstname] $row[act_lastname]
          </a></button></form>
          </div>

			</td>
			</tr>
	";
}
print "
		</table>
	</div>
</div>
  <div class='movie-info column'>
  <div class='movie-info-text'>
    Runtime: $moviebasic_array[runtime] <br>
    Released: $moviebasic_array[month] $moviebasic_array[date] $moviebasic_array[year_rel] <br>
    Production company: $moviebasic_array[production] <br>
    Box office: $moviebasic_array[boxoffice] <br>
    Language: $moviebasic_array[language] <br>
    Country: $moviebasic_array[country] <br>
  </div>
	</div>
</div>
";

mysqli_close($link);
?>
