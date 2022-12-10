<?php
  $host = "";
  $user = "";
	$password = "";
  $database = "";
  $link = mysqli_connect($host, $user, $password, $database);

	if(! $link ){
		die('Could not connect: ' . mysqli_error($link));
	}

  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  } else {
    $page = 1;
  }

  $no_of_records_per_page = 10;
  $offset = ($page-1) * $no_of_records_per_page;

  $total_pages_sql = "
		SELECT
			COUNT(*)
		FROM
			moviebasic
	";
	$result = mysqli_query($link,$total_pages_sql);
	$total_rows = mysqli_fetch_array($result)[0];
	$total_pages = ceil($total_rows / $no_of_records_per_page);



	$movie_query = "
    SELECT
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
					moviebasic.released_id = releasedate.release_id
		LIMIT $offset, $no_of_records_per_page
  ";

	$movie_result = mysqli_query($link, $movie_query);

	if(! $movie_result){
		die('Could not connect: ' . mysqli_error($link));
	}

  print "
		<table>
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

	while ($row = mysqli_fetch_array($movie_result)) {
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

	mysqli_close($link);
?>
<div class="pagination">
    <a href="?page=1"> << </a>
    <a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>"> < </a>
    <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>"> > </a>
    <a href="?page=<?php echo $total_pages; ?>"> >> </a>
</div>
