<html>

<link rel="stylesheet" href="http://hornet.ischool.utexas.edu/earth/static/css/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<head>
<title>
	Earth Movie Database
</title>
</head>

<div class="navbar">
  <div class="nav-header">
    <div class="nav-title">
      <a href="index.php">earth movie db</a>
    </div>
		<div class="nav-search">	
			<form action="search.php" method="GET">
				<input placeholder=" search" type="search" name="query">
				<input type="submit" class="material-icons" value="search">
				<br>
				<input type="radio" name="sort" value="title">title &nbsp; &nbsp;
				<input type="radio" name="sort"value="actor">actor
			</form>
		</div>
    <div class="nav-links">
      <a href="index.php">about</a> &nbsp;
    	<a href="actors.php">actors</a>&nbsp;
      <a href="movies.php">movies</a>
		</div>
  </div>
</div>

<body>
