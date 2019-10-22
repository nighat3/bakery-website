<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "Contact";
$style = "styles/all.css";

$name_form = "";
$email_form = "";
$inquiry_form = "";

if (isset($_POST['submit'])) {

		$submit_success = true;

		$name_form = $_POST['name_form'];
		$email_form = $_POST['email_form'];
		$inquiry_form = $_POST['inquiry_form'];
		$radio_form = $_POST['radio_form'];

		$valid_name_form = true;
		if ($name_form == '') {
				$valid_name_form = false;
				$submit_success = false;
		}

		$valid_email_form = true;
		if ($email_form == '') {
				$valid_email_form = false;
				$submit_success = false;
		}

		$valid_inquiry_form = true;
		if ($inquiry_form == '') {
				$valid_inquiry_form = false;
				$submit_success = false;
		}

} else {
		$valid_name_form = true;
		$valid_email_form = true;
		$valid_inquiry_form = true;

}
?>


<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php";?>
<body>

	<?php include "includes/header.php";?>

	<h2>Contact Us</h2>



	<?php
if (isset($submit_success) && $submit_success) {?>
			<!--//Confirmation page-->
			<h3 class="content">Congrats! Your message has been submitted.</h3>

			<div class="content">
				<h4>Your Information:</h4>
				<ul class="contact_form_menu" id="info">
					<li>Name: <?php echo htmlspecialchars($name_form); ?></li>
					<li>Email: <?php echo htmlspecialchars($email_form); ?></li>
					<li>Inquiry: <?php echo htmlspecialchars($inquiry_form); ?></li>
					<li>Have you ordered from us before? <?php echo htmlspecialchars($radio_form); ?></li>
				</ul>
			</div>
	<?php } else {?>

			<!--//Contact form-->
	<div class="content">
		<div id="subscribe_box">
			<p class = "text">If you have any inquries about any of our products, prices, and/or policies, feel free to contact us.</p>
		</div>

		<form id="contact_form" method="post" action="contact.php">
			<fieldset>
				<!-- <legend>Contact Form</legend> -->

				<ul class="contact_form_menu">
					<li>
						<p class="error_form <?php if ($valid_name_form == true) {echo ('hidden');}?>">Please enter your name.</p>
						<label class = 'contact_label' for="name_box">Name:*</label><br/>
						<input id="name_box" type="text" name="name_form" value="<?php echo htmlspecialchars($name_form); ?>"/>
					</li>

					<li>
						<p class="error_form <?php if ($valid_email_form == true) {echo ('hidden');}?>">Please enter your email.</p>
						<label class = 'contact_label' for="email_box">Email:*</label><br/>
						<input id="email_box" type="text" name="email_form" value="<?php echo htmlspecialchars($email_form); ?>"/>

					</li>

					<li>
						<p class="error_form <?php if ($valid_inquiry_form == true) {echo ('hidden');}?>">Please enter your message.</p>
						<label class = 'contact_label' for="inquiry_box">Inquiry:*</label><br/>
						<textarea id="inquiry_box" name="inquiry_form" rows="15"><?php echo htmlspecialchars($inquiry_form); ?></textarea>
					</li>

					<li>
						<p>Have you ordered from us before?</p><br/>
						<div>
							<input type="radio" id="yes" name="radio_form" value="yes"/>
							<label for="yes">Yes</label>
						</div>
						<div>
							<input type="radio" id="no" name="radio_form" value="no"/>
							<label for="no">No</label>
						</div>
					</li>
					<li>
						<input class="submit_button" type="submit" name="submit" value="Submit"/>
					</li>
				</ul>
			</fieldset>
		</form>
	</div>
	<?php }?>


	<?php include "includes/footer.php";?>

</body>
</html>
