<?php
  $host = "";
  $user = "";
	$password = "";
  $database = "";
  $link = mysqli_connect($host, $user, $password, $database);

	if(! $link ){
		die('Could not connect, init: ' . mysqli_error($link));
	}

	$query = $_GET['query'];

	$search_query_actor = "
    SELECT
			actor.firstname AS act_firstname,
      actor.lastname AS act_lastname
		FROM
			actor
    WHERE
      firstname
        LIKE
          '%".$query."%'
			OR
			lastname
        LIKE
          '%".$query."%'
		ORDER BY lastname ASC
	";

	$search_query_title = "
		SELECT DISTINCT
			*,
      year.year AS year,
      releasedate.date AS date,
      releasedate.month AS month,
			SUBSTRING(plot, 1, 70) AS plot

		FROM
			moviebasic
		LEFT JOIN
			year
				ON
					moviebasic.year_id = year.year_id
    LEFT JOIN
      releasedate
        ON
          viebasic.released_id = releasedate.release_id
		WHERE
			title
				LIKE
					'%".$query."%'
	";


  if(isset($_GET['sort'])) {
    $sort = $_GET['sort'];
  } else {
    $sort = 'title';
  }

	if($sort == 'title'){
		$search_results = mysqli_query($link, $search_query_title);

  	if(! $search_results){
    	die('Could not connect: ' . mysqli_error($link));
  	}

  print "<table>
      <th width='16%'>Title</th>
      <th width='3%'>Year</th>
      <th width='3%'>Released</th>
      <th width='2%'>Runtime</th>
      <th width='33%'>Plot</th>
      <th width='17%'>Awards</th>
      <th width='3%'>Metascore</th>
      <th width='3%'>IMDB Rating</th>
      <th width='4%'>IMDB Votes</th>
      <th width='5%'>IMDB ID</th>
      <th width='8%'>Gross</th>
  ";

  while ($row = mysqli_fetch_array($search_results)) {
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
        <td> $row[year] </td>
        <td> $row[month]-$row[date] </td>
        <td> $row[runtime] </td>
        <td class='plot'> $row[plot]... </td>
        <td> $row[awards] </td>
        <td> $row[metascore] </td>
        <td> $row[imdbrating] </td>
        <td> $row[imdbvotes] </td>
        <td> $row[imdbid] </td>
        <td> $row[boxoffice] </td>
      </tr>
  ";
  }
  print "</table>";

	}

	elseif($sort == 'actor'){
    $search_results = mysqli_query($link, $search_query_actor);
  	if(! $search_results){
    	die('Could not connect: ' . mysqli_error($link));
  	}

		print "
			<div class='actor-movies'>
      <table>
        <th>Actor</th>
    ";

    while ($row = mysqli_fetch_array($search_results)) {
			print "
      	<tr>
        	<td>
          	<div class='movie-button'>
							<form action='actor.php' method='GET'>
							<input type='hidden' name='title' value='$row[title]'>
							<button type='submit'>
          		<a href='actor.php?firstname=$row[act_firstname]&lastname=$row[act_lastname]'>
								$row[act_firstname] $row[act_lastname]
							</a></button></form>
					</td>
        </tr>
    			";
    	}
    print "</table></div>";
  }

	mysqli_close($link);
?>
