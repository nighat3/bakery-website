<footer class = 'footer'>
	<p class=footer_txt><a href="index.php"> Home</a> | <a href="shop.php"> Shop</a> | <a href="gallery.php"> Reviews</a> | <a href="about.php"> About</a> | <a href="contact.php"> Contact</a> | <a href="terms.php"> Terms & Policies</a>
	<?php
	if ($current_user["username"] == "admin") { ?>
		| <a href="admin.php"> Admin Controls</a>
	<?php
	} else { ?>
		|  <a href="login.php"> Admin Login</a>
	<?php }
	?>
	</p>
	<p>© Copyright 2019</p>
</footer>
