
<?php
if (is_user_logged_in()) {

$logout_url = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query(array('logout' => ''));
?>
	<div id = "logged_in">Logged in as <?php echo $current_user["username"] . " | " ?>
		<a class="a_logout" href = <?php echo ('"' . $logout_url . '"') ?>>Logout</a>
		<div>
		<!-- <a href = <?php //echo ('"' . $logout_url . '"') ?>> <button class = 'log_but' >Logout</button></a> -->
		<div><a href="admin.php">Admin Controls</a></div>

		</div>
	</div>

  <?php } ?>

<div id = "logo_title">
	<!-- Source: Original Design by Team blue-deer -->
	<img src = "images/logo_new4.png" alt = "logo">
</div>

<header>


	  <nav id = "menu">
		  <ul>
		  <?php
$navbar = [['index.php', 'home'], ['shop.php', 'shop'], ['reviews.php', 'reviews'], ['about.php', 'about'], ['contact.php', 'contact'], ['terms.php', 'terms']];

foreach ($navbar as $element) {
	if (basename($_SERVER['PHP_SELF']) == $element[0]) {
		echo "<li><a id = 'current-page' class = 'navlines' href='$element[0]'>$element[1]</a></li>";
	} else {
		echo "<li><a class = 'navlines' href='$element[0]'>$element[1]</a></li>";
	}
}
?>

		  </ul>
	  </nav>

  </header>
