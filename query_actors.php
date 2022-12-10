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
      actor
  ";
  $result = mysqli_query($link,$total_pages_sql);
  $total_rows = mysqli_fetch_array($result)[0];
  $total_pages = ceil($total_rows / $no_of_records_per_page);

	$actor_query = "
    SELECT
    	*
		FROM
			actor
		LIMIT $offset, $no_of_records_per_page
  ";

	$actor_result = mysqli_query($link, $actor_query);

	if(! $actor_result){
		die('Could not connect: ' . mysqli_error($link));
	}


  print "
	<div class='actor-movies'>
		<table>
			<th>Name</th>
	";

	while ($row = mysqli_fetch_array($actor_result)) {
    print "
      <tr>
				<td>
					<div class='movie-button'>
						<form action='actor.php' method='GET'>
						<input type='hidden' name='name' value='$row[firstname]$row[lastname]'>
						<button type='submit'>
						<a href='actor.php?firstname=$row[firstname]&lastname=$row[lastname]'>
							$row[firstname] $row[lastname]
						</a></button></form>
				</div>
      </td>
      </tr>
  ";
  }
  print "
		</table>
	</div>
";

	mysqli_close($link);

?>

<div class="pagination">
    <a href="?page=1"> << </a>
    <a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>"> < </a>
    <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>"> > </a>
    <a href="?page=<?php echo $total_pages; ?>"> >> </a>
</div>
