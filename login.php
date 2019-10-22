<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "Login";
$style = "styles/all.css";

?>


<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php";?>

<body>

  <?php include "includes/header.php";?>
  <div class="login">
<?php
if (is_user_logged_in()) {
	//from Kyle Harms - lab 8
	//function form init.php checks if user is logged in
	//make log out button allows user to log out
	$logout_url = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query(array('logout' => ''));
	$name = htmlspecialchars($current_user['username']);
	echo '<br/><h3 class = "inline"> Hi, ' . $name . ' You are now logged in</h3>';
	echo '<p>Continue to <a href="admin.php">Admin Controls</a></p>';
	echo '<p id = "admin_intro">As an admin, you can view and modify orders, products, and reviews. Navigate to the Admin Controls to view your dashboard and manage orders. You can also delete reviews through the Reviews page directly as well as update the content on the About and Terms pages.</p>';
	echo '<p class = ""><a class="a_logout" href="' . $logout_url . '">Logout</a></p>';
} else {?>

	<h1>Login</h1>
	<form class = 'input_style' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id = 'login' method = 'post'>
		<ul>
			<li>
				<label class = 'label'>Username</label>
				<input type="text" name = 'username'>
			</li>
			<li>
				<label class = 'label'>Password</label>
				<input type="password" name = 'password'>
			</li>
		</ul>
		<button class='log_but' type='submit' name='login'>Login</button>
	</form>
<?php }?>
</div>
  <?php include "includes/footer.php";?>

</body>
</html>
