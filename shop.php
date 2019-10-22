<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "Shop";
$style = "styles/shop.css";

if (isset($_GET['id'])) {
	$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	$id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);
	$sql = "SELECT * FROM product_images WHERE product_images.id = :id;";
	$params = array(
		':id' =>
		$id,
	);
	$result = exec_sql_query($db, $sql, $params);
	if ($result) {
		$order_product = $result->fetchAll();
		$order_product = $order_product[0];
	}
	;

}

if (isset($_POST['submit_order'])) {

	$valid_order = true;
	$name_error = false;
	$email_error = false;
	$delivery_date_error = false;
	$quantity_error = false;
	$address_error = false;

//name

	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
	$name = trim($name);

	if ($name == "") {
		$valid_order = false;
		$name_error = true;
	}

//product_id
	$product_id_order = filter_input(INPUT_POST, 'product_id_order', FILTER_VALIDATE_INT);
	$product_id_order = filter_var($product_id_order, FILTER_SANITIZE_SPECIAL_CHARS);
	$product_id_order = trim($product_id_order);

	//name
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
	$email = trim($email);
	if ($email == "") {
		$valid_order = false;
		$email_error = true;
	}

	//address
	$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
	$address = filter_var($address, FILTER_SANITIZE_SPECIAL_CHARS);
	$address = trim($address);

	if ($address == "") {
		$valid_order = false;
		$address_error = true;
	}

	//delivery date
	$delivery_date = filter_input(INPUT_POST, 'delivery_date', FILTER_SANITIZE_STRING);
	$delivery_date = filter_var($delivery_date, FILTER_SANITIZE_SPECIAL_CHARS);
	$delivery_date = trim($delivery_date);
	// ensures desired delivery date is after today
	$valid_date = $delivery_date > date("Y-m-d");

	if ($delivery_date == "" || !($valid_date == 1)) {
		$valid_order = false;
		$delivery_date_error = true;
	}

	//quantity
	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
	$quantity = filter_var($quantity, FILTER_SANITIZE_SPECIAL_CHARS);
	$quantity = (int) trim($quantity);

	if ($quantity == "" || $quantity < 1 || $quantity > 100 || !is_integer($quantity)) {
		$valid_order = false;
		$quantity_error = true;
	}

	//special_instructions
	$special_instructions = filter_input(INPUT_POST, 'special_instructions', FILTER_SANITIZE_STRING);
	$special_instructions = filter_var($special_instructions, FILTER_SANITIZE_SPECIAL_CHARS);

	//TODO: SUBMIT ORDER BY INSERTING INTO ORDER TABLE
	if ($valid_order) {
		$db->beginTransaction();

		// Did the user request a withdraw?
		if (!isset($special_instructions) || $special_instructions == null) {
			$special_instructions = "";
		}

		exec_sql_query($db,
			"INSERT INTO `orders` (customer_name, customer_email, product_id, quantity, delivery_date, customer_address, customization) VALUES (:name, :email, :product_id_order, :quantity, :delivery_date, :address, :special_instructions);",
			array(
				':email' => $email,
				':product_id_order' => $product_id_order,
				':quantity' => $quantity,
				':delivery_date' => $delivery_date,
				':address' => $address,
				':special_instructions' => $special_instructions,
				':name' => $name)
		);

		$db->commit();
	}
}

if (isset($_GET['sort'])) {
	$sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);
	// $sort = filter_var($sort, FILTER_SANITIZE_SPECIAL_CHARS);
	$sql = "SELECT product_types.id FROM product_types WHERE product_types.type LIKE :sort;";
	$params = array(
		':sort' =>
		$sort,
	);
	$sort_result = exec_sql_query($db, $sql, $params);
	if ($sort_result) {
		$sort_products = $sort_result->fetchAll();
		$sort_products = $sort_products[0];
	}
	;

}

?>



<!DOCTYPE html>

<html lang="en">
<?php include "includes/head.php";?>
<!-- <link rel="stylesheet" type="text/css" href="styles/shop.css" media="all" /> -->


<body>
  <?php include "includes/header.php";

?>

  <div id = "products_content">
	<?php

// Source: images sources below vv
$image_links = ['https://tmbidigitalassetsazure.blob.core.windows.net/secure/RMS/attachments/37/1200x1200/Mint-Patty-Cake_exps140673_CMT2426390C08_17_2b_RMS.jpg', 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/02/Strawberry-Tuxedo-Cake-4.jpg', 'https://www.bbcgoodfood.com/sites/default/files/styles/recipe/public/recipe/recipe-image/2018/02/easter-nest-cake.jpg?itok=-ZAZCCss', 'https://ichef.bbci.co.uk/food/ic/food_16x9_832/recipes/salted_dark_chocolate_16338_16x9.jpg', 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg', 'https://2.bp.blogspot.com/-tBaVlde8WjQ/WluzDBUtThI/AAAAAAACozs/gfKnTBcslQAiln8NbTrs_wcjr5fqjjW0QCEwYBhgL/s1600/Classic%2BBlack%2BForest%2BCake%2B1.jpg', 'https://blog.williams-sonoma.com/wp-content/uploads/2018/04/apr-26-Neapolitan-Ice-Cream-Cake.jpg', 'https://www.simplyrecipes.com/wp-content/uploads/2015/08/peanut-butter-cookies-horiz-a-1800.jpg', 'https://chocolatecoveredkatie.com/wp-content/uploads/2012/10/healthy-oreos-recipe.jpg', 'https://italianculturalcentre.ca/wp-content/uploads/2017/03/20130125-cookiemonster-almond-blood-orange-cookies-thumb-625xauto-301545.jpg', 'https://www.landolakes.com/RecipeManagementSystem/media/Recipe-Media-Files/Recipes/Retail/x17/2018_Peanut-Blossom-Cookies_5287_600x600.jpg?ext=.jpg', 'https://www.tasteofhome.com/wp-content/uploads/0001/01/Gingerbread-Spiced-Pumpkin-Pie_EXPS_SDDJ19_123630_C07_18_5b-696x696.jpg', 'https://cdn-image.foodandwine.com/sites/default/files/styles/4_3_horizontal_-_1200x900/public/lemon-meringue-pie-xl-recipe0216_0.jpg?itok=QlAfUDLq', 'https://s3-eu-west-1.amazonaws.com/s3.mediafileserver.co.uk/carnation/WebFiles/RecipeImages/pinatacupcakes_lg.jpg', 'https://www.dessertfortwo.com/wp-content/uploads/2018/07/birthday-cupcakes-with-sprinkles-small-batch-cupcake-recipe-720x720.jpg', 'https://www.handletheheat.com/wp-content/uploads/2015/02/Chocolate-Raspberry-Cupcakes-square.jpg'];

?>
<!-- product order form vv -->
	<?php
if (isset($order_product)) {

	$product_name = exec_sql_query($db, "SELECT * FROM products WHERE products.id LIKE :order_product;", array(":order_product" => $order_product['id']))->fetchAll(PDO::FETCH_ASSOC);

	$product_name = $product_name[0];

	?>
	  <?php
if (isset($valid_order) && $valid_order) {?>

		<div id = "order_success">
		<div>
		<a class= "order_button" href = "shop.php"> Back </a>
	</div>
		  <h2>Order Placed!</h2>
		  <p>Order Total: $ <?php echo ($product_name["price"] * $quantity) ?>.00</p>
		  <p>Thank you for purchasing <?php if (iseset($name)) {echo (htmlspecialchars($name));}?>! We will send you an email with more information regarding your order.</p>
		</div>


	  <?php } else {?>
		<div id = "order_form_container">
		<div>
		  <a class = "order_button" href = "shop.php"> Back </a>
		</div>
		  <h2> ORDER FORM </h2>
		  <form id = "order_form" action= "<?php echo('shop.php?' . http_build_query(array('id' => $product_name["id"])) . PHP_EOL);?>" method="post" novalidate>
					<!-- name -->

			<p class="form_error <?php if (!isset($name_error) || $name_error == false) {echo 'hidden';}?>">Please enter a name for the order.
			</p>
			<label>Customer Name:*</label>
			<input type="text" name="name" value="<?php if (isset($name)) {echo htmlspecialchars($name);}?>" required/>

			<p class="form_error <?php if (!isset($email_error) || $email_error == false) {echo 'hidden';}?>">Please provide a valid email address.
			</p>
			<label for="email">Email:*</label>
			<input id="email" type="email" name="email" value="<?php if (isset($email)) {echo htmlspecialchars($email);}?>" required/>

			<!-- address -->
			<p class="form_error <?php if (!isset($address_error) || $address_error == false) {echo 'hidden';}?>">Please provide an address for your delivery.
			</p>
			<label for="address">Address:*</label>
			<input id="address" type="text" name="address" value="<?php if (isset($address)) {echo htmlspecialchars($address);}?>" required />
			<!-- date of delivery -->
			<!-- to do: make it a triple drop down for month day year -->
			<p class="form_error <?php if (!isset($delivery_date_error) || $delivery_date_error == false) {echo 'hidden';}?>">Please provide a valid delivery date.
			</p>
			<label for="delivery_date"> Delivery Date:* </label>
			<input id="delivery_date" type="date" name="delivery_date" value="<?php if (isset($delivery_date)) {echo htmlspecialchars($delivery_date);}?>" required/>
			<!-- ordered product -->
			<label id="productfor_label"> <?php echo ("Product Ordered:  " . $product_name["product_name"]) ?> </label>
			<!-- quantity -->
			<p class="form_error <?php if (!isset($quantity_error) || $quantity_error == false) {echo 'hidden';}?>">Order quantity must be between 1 and 100.
			</p>
			<label for="quantity">Quantity:*</label>
			<input id="quantity" type="number" name="quantity" min="1" max="100" value="<?php if (isset($quantity)) {echo htmlspecialchars($quantity);}?>" required/>

			<!-- special instructions -->
			<label for="special_instructions">Special Instructions:</label>
			<textarea id="special_instructions" name="special_instructions"><?php if (isset($special_instructions)) {echo htmlspecialchars($special_instructions);}?></textarea>

			<!-- hidden -->
			<input type ="hidden" name = "product_id_order" value = '<?php echo $product_name["id"] ?>' />
			<button id = "submit_order" type="submit" name="submit_order"> Place Order </button>

		  </form>

		</div>
	  <?php }
} else {?>

<!-- product order form^^ -->

	<div class = "shop_page_title">
	  <h2><?php echo $page_title ?></h2>
	</div>
	<div id = "product_categories">
	  <ul>
		<?php
$dessert_categories = ["All", "Cakes", "Cookies", "Pies", "Cupcakes"];

	foreach ($dessert_categories as $category) {
		if (isset($sort_products) && $category == $dessert_categories[$sort_products["id"]]) {
			$current_sort_class = "current_sort";} else {
			$current_sort_class = "";
		}
		;

		echo '<li class = "' . $current_sort_class . '"><a href="shop.php?' . http_build_query(array('sort' => $category)) . '">' . $category . '</a>' . PHP_EOL . '</li>';
	}

	?>
  </ul>
</div>

	<?php
if (isset($sort_products) && $sort_products != "") {

		$product_images = exec_sql_query($db, "SELECT product_images.id, product_images.file_ext, product_images.file_name, product_images.product_id, products.product_name, products.price FROM product_images LEFT OUTER JOIN products ON product_images.product_id = products.id INNER JOIN product_types ON products.product_type_id = product_types.id WHERE product_types.id LIKE :sort;", array(":sort" => $sort_products['id']))->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$product_images = exec_sql_query($db, "SELECT product_images.id, product_images.file_ext, product_images.file_name, product_images.product_id, products.product_name, products.price FROM product_images LEFT OUTER JOIN products ON product_images.product_id = products.id;", array())->fetchAll(PDO::FETCH_ASSOC);
	}

	// VV IMAGE CITATIONS from google images: VV 'https://tmbidigitalassetsazure.blob.core.windows.net/secure/RMS/attachments/37/1200x1200/Mint-Patty-Cake_exps140673_CMT2426390C08_17_2b_RMS.jpg', 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/02/Strawberry-Tuxedo-Cake-4.jpg', 'https://www.bbcgoodfood.com/sites/default/files/styles/recipe/public/recipe/recipe-image/2018/02/easter-nest-cake.jpg?itok=-ZAZCCss', 'https://ichef.bbci.co.uk/food/ic/food_16x9_832/recipes/salted_dark_chocolate_16338_16x9.jpg', 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg', 'https://2.bp.blogspot.com/-tBaVlde8WjQ/WluzDBUtThI/AAAAAAACozs/gfKnTBcslQAiln8NbTrs_wcjr5fqjjW0QCEwYBhgL/s1600/Classic%2BBlack%2BForest%2BCake%2B1.jpg', 'https://blog.williams-sonoma.com/wp-content/uploads/2018/04/apr-26-Neapolitan-Ice-Cream-Cake.jpg', 'https://www.simplyrecipes.com/wp-content/uploads/2015/08/peanut-butter-cookies-horiz-a-1800.jpg', 'https://chocolatecoveredkatie.com/wp-content/uploads/2012/10/healthy-oreos-recipe.jpg', 'https://italianculturalcentre.ca/wp-content/uploads/2017/03/20130125-cookiemonster-almond-blood-orange-cookies-thumb-625xauto-301545.jpg', 'https://www.landolakes.com/RecipeManagementSystem/media/Recipe-Media-Files/Recipes/Retail/x17/2018_Peanut-Blossom-Cookies_5287_600x600.jpg?ext=.jpg', 'https://www.tasteofhome.com/wp-content/uploads/0001/01/Gingerbread-Spiced-Pumpkin-Pie_EXPS_SDDJ19_123630_C07_18_5b-696x696.jpg', 'https://cdn-image.foodandwine.com/sites/default/files/styles/4_3_horizontal_-_1200x900/public/lemon-meringue-pie-xl-recipe0216_0.jpg?itok=QlAfUDLq', 'https://s3-eu-west-1.amazonaws.com/s3.mediafileserver.co.uk/carnation/WebFiles/RecipeImages/pinatacupcakes_lg.jpg', 'https://www.dessertfortwo.com/wp-content/uploads/2018/07/birthday-cupcakes-with-sprinkles-small-batch-cupcake-recipe-720x720.jpg', 'https://www.handletheheat.com/wp-content/uploads/2015/02/Chocolate-Raspberry-Cupcakes-square.jpg'


	foreach ($product_images as $product_image) {
		echo ('<div class="product">' .
			'<div class = "product_image">' .
			"<img class = 'shop_img' src='uploads/product_images/" . $product_image["id"] . "." . $product_image["file_ext"] . "'" . " alt = '" . htmlspecialchars($product_image["file_name"]) . "'/>" .
			'<a href="' . $image_links[$product_image["id"] - 1] . '">Image Source</a></div>' .
			'<div class="product_info_shop">' .
			'<span class = "product_title">' . $product_image["product_name"] . '</span>' .
			'<div class = "info_order_section"><span>Price: $' . $product_image["price"] . '</span>' .
			"<a class = 'order_button' href = 'shop.php?" . http_build_query(array('id' => $product_image["product_id"])) . "'>" . "Order" . "</a>" . PHP_EOL .
			'</div></div>' .
			'</div>');

		$link_index = $link_index + 1;
	}
}

// ^^ IMAGE CITATIONS from google images: ^^ 'https://tmbidigitalassetsazure.blob.core.windows.net/secure/RMS/attachments/37/1200x1200/Mint-Patty-Cake_exps140673_CMT2426390C08_17_2b_RMS.jpg', 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/02/Strawberry-Tuxedo-Cake-4.jpg', 'https://www.bbcgoodfood.com/sites/default/files/styles/recipe/public/recipe/recipe-image/2018/02/easter-nest-cake.jpg?itok=-ZAZCCss', 'https://ichef.bbci.co.uk/food/ic/food_16x9_832/recipes/salted_dark_chocolate_16338_16x9.jpg', 'https://tatyanaseverydayfood.com/wp-content/uploads/2018/07/Summer-Sangria-Cake-4.jpg', 'https://2.bp.blogspot.com/-tBaVlde8WjQ/WluzDBUtThI/AAAAAAACozs/gfKnTBcslQAiln8NbTrs_wcjr5fqjjW0QCEwYBhgL/s1600/Classic%2BBlack%2BForest%2BCake%2B1.jpg', 'https://blog.williams-sonoma.com/wp-content/uploads/2018/04/apr-26-Neapolitan-Ice-Cream-Cake.jpg', 'https://www.simplyrecipes.com/wp-content/uploads/2015/08/peanut-butter-cookies-horiz-a-1800.jpg', 'https://chocolatecoveredkatie.com/wp-content/uploads/2012/10/healthy-oreos-recipe.jpg', 'https://italianculturalcentre.ca/wp-content/uploads/2017/03/20130125-cookiemonster-almond-blood-orange-cookies-thumb-625xauto-301545.jpg', 'https://www.landolakes.com/RecipeManagementSystem/media/Recipe-Media-Files/Recipes/Retail/x17/2018_Peanut-Blossom-Cookies_5287_600x600.jpg?ext=.jpg', 'https://www.tasteofhome.com/wp-content/uploads/0001/01/Gingerbread-Spiced-Pumpkin-Pie_EXPS_SDDJ19_123630_C07_18_5b-696x696.jpg', 'https://cdn-image.foodandwine.com/sites/default/files/styles/4_3_horizontal_-_1200x900/public/lemon-meringue-pie-xl-recipe0216_0.jpg?itok=QlAfUDLq', 'https://s3-eu-west-1.amazonaws.com/s3.mediafileserver.co.uk/carnation/WebFiles/RecipeImages/pinatacupcakes_lg.jpg', 'https://www.dessertfortwo.com/wp-content/uploads/2018/07/birthday-cupcakes-with-sprinkles-small-batch-cupcake-recipe-720x720.jpg', 'https://www.handletheheat.com/wp-content/uploads/2015/02/Chocolate-Raspberry-Cupcakes-square.jpg'
?>
  </div>

  <?php include "includes/footer.php";?>

</body>
</html>
