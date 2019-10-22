<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "Terms";
$style = "styles/all.css";

// Check if shipping/returns text is edited
if (isset($_POST["edit-ship"])) {
	$edit_ship = true;
} else {
	$edit_ship = false;
}

// Check if payment text is edited
if (isset($_POST["edit-pay"])) {
	$edit_pay = true;
} else {
	$edit_pay = false;
}

// SAVE shipping/returns text
if (isset($_POST["save-ship"])) {
	// Update shipping terms table with transactions
	$db->beginTransaction();

	// Do transactions here vv

	$save_ship = true;
	$new_ship = trim(filter_input(INPUT_POST, "new-ship", FILTER_SANITIZE_STRING));

	$sql_new_ship = "UPDATE terms_ship SET shipping_text = :new_ship;";
	$params_new_ship = array(
		":new_ship" => $new_ship,
	);
	exec_sql_query($db, $sql_new_ship, $params_new_ship);

	// Do transactions here ^^

	$db->commit();
} else {
	$save_ship = false;
}

// SAVE payment text
if (isset($_POST["save-pay"])) {
	// Update payment terms table with transactions
	$db->beginTransaction();

	// Do transactions here vv

	$save_pay = true;
	$new_pay = trim(filter_input(INPUT_POST, "new-pay", FILTER_SANITIZE_STRING));

	$sql_new_pay = "UPDATE terms_pay SET payment_text = :new_pay;";
	$params_new_pay = array(
		":new_pay" => $new_pay,
	);
	exec_sql_query($db, $sql_new_pay, $params_new_pay);

	// Do transactions here ^^

	$db->commit();
} else {
	$save_pay = false;
}

?>


<!DOCTYPE html>
<html lang="en">
<?php include "includes/head.php";?>
<!-- <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" /> -->

<body>

  <?php include "includes/header.php";?>


  <div class="terms">

  <h2>Terms & Policies</h2>

  <div class="div-content">
	<!-- SHIPPING AND RETURNS -->
	<h3 class="left-align">Shipping & Returns</h3>

	<?php
// Get shipping content from table
$newest_ship_id = 1; // always gonna be 1 since table is just updated
$sql_ship = "SELECT * FROM terms_ship WHERE terms_ship.id = :newest_ship_id;";
$params_ship = array(
	":newest_ship_id" => $newest_ship_id,
);
$results_ship = exec_sql_query($db, $sql_ship, $params_ship)->fetchAll();
?>

	<?php
if ($current_user["username"] == "admin" && $edit_ship) {?>
	  <!-- show editable description box if admin clicks 'edit' -->
	  <form method="post" action="terms.php">
		<textarea name="new-ship" rows="15" cols="100">
		  <?php
echo $results_ship[0]["shipping_text"];
	?>
		</textarea>
		<button class="edit-button" type="submit" name="save-ship">Save Changes</button>
	  </form>
	<?php
} else {?>
	  <p class="about-description">
		<!-- otherwise, just display the text -->
		<!-- echo bio table entry -->
		<?php
echo $results_ship[0]["shipping_text"];
	?>
	  </p>
	<?php
}
?>

	<?php
if ($save_ship) {?>
	  <p class="messages-left">Changes saved successfully.</p>
	<?php
}
if ($current_user["username"] == "admin" && !$edit_ship) {?>
	  <!-- show edit button if admin is logged in -->
	  <form method="post" action="terms.php">
		<button class="edit-button" type="submit" name="edit-ship">Edit Contents</button>
	  </form>
	<?php
}
?>
  </div>
  <div class="div-content">
	<!-- PAYMENT -->
	<h3 class="left-align">Payment Methods</h3>

	<?php
// Get payment content from table
$newest_pay_id = 1;
$sql_pay = "SELECT * FROM terms_pay WHERE terms_pay.id = :newest_pay_id;";
$params_pay = array(
	":newest_pay_id" => $newest_pay_id,
);
$results_pay = exec_sql_query($db, $sql_pay, $params_pay)->fetchAll();
?>

	<?php
if ($current_user["username"] == "admin" && $edit_pay) {?>
	  <!-- show editable description box if admin clicks 'edit' -->
	  <form method="post" action="terms.php">
		<textarea name="new-pay" rows="15" cols="100">
		  <?php
echo $results_pay[0]["payment_text"];
	?>
		</textarea>
		<button class="edit-button" type="submit" name="save-pay">Save Changes</button>
	  </form>
	<?php
} else {?>
	  <p class="about-description">
		<!-- otherwise, just display the text -->
		<!-- echo bio table entry -->
		<?php
echo $results_pay[0]["payment_text"];
	?>
	  </p>
	<?php
}
?>
	<!-- <p>We accept all major credit cards and Paypal.</p> -->

	<?php
if ($save_pay) {?>
	  <p class="messages-left">Changes saved successfully.</p>
	<?php
}
if ($current_user["username"] == "admin" && !$edit_pay) {?>
	  <!-- show edit button if admin is logged in -->
	  <form method="post" action="terms.php">
		<button class="edit-button" type="submit" name="edit-pay">Edit Contents</button>
	  </form>
	<?php
}
?>

  </div>
  </div>

  <?php include "includes/footer.php";?>

</body>
</html>
