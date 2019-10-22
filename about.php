<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "About";
$style = "styles/all.css";

// Check if bio text is edited
if (isset($_POST["edit-bio"])) {
		$edit_bio = true;
} else {
		$edit_bio = false;
}

// Check if services text is edited
if (isset($_POST["edit-services"])) {
		$edit_services = true;
} else {
		$edit_services = false;
}

// SAVE BIO EDITS
if (isset($_POST["save-bio"])) {
		// Update bio table with transactions
		$db->beginTransaction();

		// Do transaction here vv

		$save_bio = true;
		$new_bio = trim(filter_input(INPUT_POST, "new-bio", FILTER_SANITIZE_STRING));

		$sql_update_bio = "UPDATE about_bio SET bio_text = :new_bio;";
		$params_update_bio = array(
				":new_bio" => $new_bio,
		);
		exec_sql_query($db, $sql_update_bio, $params_update_bio);

		// Do transaction here ^^

		$db->commit();
} else {
		$save_bio = false;
}

// SAVE SERVICES EDITS
if (isset($_POST["save-services"])) {
		// Update services table with transactions
		$db->beginTransaction();

		// Do transaction here vv

		$save_services = true;
		$new_services = trim(filter_input(INPUT_POST, "new-services", FILTER_SANITIZE_STRING));

		$sql_update_serv = "UPDATE about_services SET services_text = :new_services;";
		$params_update_serv = array(
				":new_services" => $new_services,
		);
		exec_sql_query($db, $sql_update_serv, $params_update_serv);

		// Do transaction here ^^
		$db->commit();
} else {
		$save_services = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include "includes/head.php";?>

<body>

	<?php include "includes/header.php";?>
	<div class="page-width">
	<!-- OWNER BIO -->
	<h2 class="center-text">About the Owner</h2>

	<?php
	// Get bio content from table
	$newest_bio_id = 1; // always gonna be 1 since table is just updated
	$sql_bio = "SELECT * FROM about_bio WHERE about_bio.id = :newest_bio_id;";
	$params_bio = array(
			":newest_bio_id" => $newest_bio_id,
	);
	$results_bio = exec_sql_query($db, $sql_bio, $params_bio)->fetchAll();
	?>

	<div class="row">
		<div class="left-img">
			<figure>
				<!-- Source: Wendy Jiang (client) -->
				<img class="about-img" src="images/bakery_owner_pic.jpg" alt="owner icon"/>
			</figure>
		</div>
		<div class="right-text">
			<?php
			if ($current_user["username"] == "admin" && $edit_bio) { ?>
				<!-- show editable description box if admin clicks 'edit' -->
				<form method="post" action="about.php">
					<textarea name="new-bio" rows="15" cols="100">
						<?php
						echo $results_bio[0]["bio_text"];
						?>
					</textarea>
					<button class="edit-button" type="submit" name="save-bio">Save Changes</button>
				</form>
			<?php
			}
			else { ?>
				<p class="about-description">
					<!-- otherwise, just display the text -->
					<!-- echo bio table entry -->
					<?php
					echo $results_bio[0]["bio_text"];
					?>
				</p>
			<?php
			}
			?>

			<!-- if current_user['username'] == admin, show edit button -->
			<?php
			if ($save_bio) { ?>
				<p class="messages-left">Changes saved successfully.</p>
			<?php
			}
			if ($current_user["username"] == "admin" && !$edit_bio) { ?>
				<!-- show edit button if admin is logged in -->
				<form method="post" action="about.php">
					<button class="edit-button" type="submit" name="edit-bio">Edit Contents</button>
				</form>
			<?php
			}
			?>
		</div>
	</div>

	<!-- SERVICES DESCRIPTION -->
	<h2 class="center-text">Desserts At Your Doorstep</h2>

	<?php
	// Get services content from table
	$newest_services_id = 1;
	$sql_services = "SELECT * FROM about_services WHERE about_services.id = :newest_services_id;";
	$params_services = array(
			":newest_services_id" => $newest_services_id,
	);
	$results_services = exec_sql_query($db, $sql_services, $params_services)->fetchAll();
	?>

	<div class="row">
		<div class="left-text">
			<?php
			if ($current_user["username"] == "admin" && $edit_services) {?>
				<!-- show editable description box if admin clicks 'edit' -->
				<form method="post" action="about.php">
					<textarea name="new-services" rows="15" cols="100">
						<?php
						echo $results_services[0]["services_text"];
						?>
					</textarea>
					<button class="edit-button" type="submit" name="save-services">Save Changes</button>
				</form>
			<?php
			}
			else { ?>
				<p class="about-description">
					<!-- otherwise, just display the text -->
					<!-- echo bio table entry -->
					<?php
					echo $results_services[0]["services_text"];
					?>
				</p>
			<?php
			}
			?>
			<!-- if current_user == admin, show edit button -->
			<?php
			if ($save_services) {?>
				<p class="messages-left">Changes saved successfully.</p>
			<?php
			}
			if ($current_user["username"] == "admin" && !$edit_services) {?>
				<!-- show edit button -->
				<form method="post" action="about.php">
					<button class="edit-button" type="submit" name="edit-services">Edit Contents</button>
				</form>

			<?php
			}
			?>
		</div>
		<div class="right-img">
			<figure>
				<!-- Source: https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg -->
				<img class="about-img" src="images/cake_icon.png" alt="cake icon">
				<a class="citations" href="https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg">Image Source</a>
			</figure>
		</div>
	</div>


</div>
	<?php include "includes/footer.php";?>

</body>
</html>
