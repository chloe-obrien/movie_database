<?php
  $host = "";
  $user = "";
	$password = "";
  $database = "";
  $link = mysqli_connect($host, $user, $password, $database);

	if(! $link ){
		die('Could not connect, init: ' . mysqli_error($link));
	}

$firstname = $_GET['firstname'];
$lastname = $_GET['lastname'];

$actor_query ="
	SELECT
		*
	FROM
		actor
	WHERE
    actor.firstname
      LIKE
        '%".$firstname."%'
    AND
    actor.lastname
      LIKE
        '%".$lastname."%'
";

$movie_query = "
	SELECT
		moviebasic.title AS title
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
		actor.firstname
			LIKE
				'%".$firstname."%'
		AND
		actor.lastname
			LIKE
				'%".$lastname."%'
";


$actor_results = mysqli_query($link, $actor_query);
$movie_results = mysqli_query($link, $movie_query);

$actor_array = mysqli_fetch_array($actor_results);

print "
<div class='actor-main'>
	<div class='actor-name'>$actor_array[firstname] $actor_array[lastname]</div>
";
print "
	<div class='actor-movies'>
  	<table>
			<th>Movies</th>
";

while ($row = mysqli_fetch_array($movie_results)) {
  print "
    	<tr>
        <td>
          <div class='movie-button'>
          <form action='movie.php' method='GET'>
          <input type='hidden' name='title' value='$row[title]'>
          <button type='submit'>
          <a href='movie.php?title=$row[title]'>
            $row[title]
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
";

mysqli_close($link);
?>
