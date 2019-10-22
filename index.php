<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "Home";
$style = "styles/all.css";

?>


<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php";?>

<body>

	<?php include "includes/header.php";?>
	<!-- Source: used slider library from ken wheeler https://kenwheeler.github.io/slick/ -->
	<div class="home_container">
			<div class="icon">
			<a href="shop.php?sort=Cakes"><img class="dessert_icons_home" src="images/cake_icon.png" alt="cake icon" /></a>
			<a class = 'img_src' href = 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg'>Image Source</a>
			</div>
			<!-- Image Source: https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg -->

			<div class="icon">
			<a href="shop.php?sort=Cookies"><img class="dessert_icons_home" src="images/cookies_icon.png" alt="cookie icon" /></a>
			<a class = 'img_src' href = 'https://www.landolakes.com/RecipeManagementSystem/media/Recipe-Media-Files/Recipes/Retail/x17/2018_Peanut-Blossom-Cookies_5287_600x600.jpg?ext=.jpg'>Image Source</a>
			</div>
			<!-- Image Source: https://www.landolakes.com/RecipeManagementSystem/media/Recipe-Media-Files/Recipes/Retail/x17/2018_Peanut-Blossom-Cookies_5287_600x600.jpg?ext=.jpg -->

			<div class="icon">
			<a href="shop.php?sort=Pies"><img class="dessert_icons_home" src="images/pie_icon.png" alt="pie icon" /></a>
			<a class = 'img_src' href = 'https://www.tasteofhome.com/wp-content/uploads/0001/01/Gingerbread-Spiced-Pumpkin-Pie_EXPS_SDDJ19_123630_C07_18_5b-696x696.jpg'>Image Source</a>
			</div>
			<!-- Image Source: https://www.tasteofhome.com/wp-content/uploads/0001/01/Gingerbread-Spiced-Pumpkin-Pie_EXPS_SDDJ19_123630_C07_18_5b-696x696.jpg -->

			<div class = "icon">
			<a href="shop.php?sort=Cupcakes"><img class="dessert_icons_home" src="images/others_icon.png" alt="cupcake icon" /></a>
			<a class = 'img_src' href = 'https://s3-eu-west-1.amazonaws.com/s3.mediafileserver.co.uk/carnation/WebFiles/RecipeImages/pinatacupcakes_lg.jpg'>Image Source</a>
			</div>
			<!-- Image Source: https://s3-eu-west-1.amazonaws.com/s3.mediafileserver.co.uk/carnation/WebFiles/RecipeImages/pinatacupcakes_lg.jpg -->

	</div>

	<h2>Desserts at Your Doorstep!</h2>

	<div class = "text">
		<p>Tired of going out to order your desserts all the time? </p>
		<p>Our online bakery allows you to order sweets just with a click. </p>
		<p>We will deliver your order within 3-5 business days straight to your doorstep, rain or shine!</p>
	</div>

	<?php include "includes/footer.php";?>

</body>
</html>
