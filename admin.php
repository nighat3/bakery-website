<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include "includes/init.php";
$page_title = "Admin";
$messages = array();
$style = "styles/all.css";

// make sure only the admin can access the edit privileges
if (!is_user_logged_in() || !$current_user["username"] == "admin") {
	$not_admin = TRUE;
}
else {
	$not_admin = FALSE;
}

// Manage Orders - display orders table
if (isset($_POST["admin-orders"])) {
	$show_orders = TRUE;

	// queries to display orders table, fix this to display product name rather than id
	$sql_orders = "SELECT * FROM orders;";
	$results_orders = exec_sql_query($db, $sql_orders) -> fetchAll();
}
else {
	$show_orders = FALSE;
}

// Manage Products - display products table
if (isset($_POST["admin-products"])) {
	$show_products = TRUE;
	$sql_products = " SELECT products.id, products.product_name, product_types.type, products.price FROM products INNER JOIN product_types WHERE products.product_type_id = product_types.id;";
	$results_products = exec_sql_query($db, $sql_products) -> fetchAll();

}
else {
	$show_products = FALSE;
}

// Manage Reviews - Display reviews table
if (isset($_POST["admin-reviews"])) {
	$show_reviews = TRUE;
	$sql_reviews = "SELECT * FROM reviews;";
	$results_reviews = exec_sql_query($db, $sql_reviews) -> fetchAll();
}
else {
	$show_reviews = FALSE;
}

// Delete full reviews (review and any images)
if (isset($_POST["remove_review"]) && is_user_logged_in()) {
	$rev_id = filter_input(INPUT_POST, "remove_review", FILTER_VALIDATE_INT);

	// Get image id of image to remove from review
	$sql_img = "SELECT customer_images.id FROM reviews INNER JOIN customer_images ON customer_images.review_id = reviews.id where reviews.id = :rev_id;";
	$params_img = array (
		":rev_id" => $rev_id
	);
	$results_img = exec_sql_query($db, $sql_img, $params_img) -> fetchAll();

	// Delete from reviews table
	$sql_delete_rev = "DELETE FROM reviews WHERE reviews.id = :rev_id";
	$params_delete_rev = array (
		":rev_id" => $rev_id
	);
	$result_delete_rev = exec_sql_query($db, $sql_delete_rev, $params_delete_rev);

	// Delete corresponding image from customer_images table and remove from disk
	if ($result_delete_rev) {
		$db->beginTransaction();
		foreach ($results_img as $img) {
			$sql_custom_img = "SELECT file_ext FROM customer_images WHERE id = :img_id;";
			$params_custom_img = array (
				":img_id" => $img["id"]
			);
			$results_custom_img = exec_sql_query($db, $sql_custom_img, $params_custom_img) -> fetchAll(PDO::FETCH_COLUMN);

			$file_ext = $results_custom_img[0];

			// Delete query
			$sql_delete_img = "DELETE FROM customer_images WHERE id = :img_id;";
			$params_delete_img = array (
				":img_id" => $img["id"]
			);
			$results_delete_img = exec_sql_query($db, $sql_delete_img, $params_delete_img);


			// Remove from disk
			$file_del = $img["id"];
			$unlink_url = "uploads/user_images/$file_del.$file_ext";
			unlink($unlink_url);

			$show_reviews = TRUE;
			$sql_reviews = "SELECT * FROM reviews;";
			$results_reviews = exec_sql_query($db, $sql_reviews) -> fetchAll();

		}
		$db->commit();
		array_push($messages, "Review deleted successfully.");
	}
	else {
		array_push($messages, "Could not delete review successfully. Please try again.");
	}

}

// Complete order (delete order)
if (isset($_POST["complete_order"]) && is_user_logged_in()) {
	$db->beginTransaction();
	$order_id = filter_input(INPUT_POST, "complete_order", FILTER_VALIDATE_INT);
	$order_id = intval(filter_var($order_id, FILTER_SANITIZE_SPECIAL_CHARS));

	$sql = "DELETE FROM orders WHERE orders.id LIKE :order_id;";
	$params = array(
	  ':order_id' =>
	  $order_id
	);
	$result = exec_sql_query($db, $sql, $params);

	$db->commit();

	$show_orders = TRUE;
	$sql_orders = "SELECT * FROM orders;";
	$results_orders = exec_sql_query($db, $sql_orders) -> fetchAll();
	array_push($messages, "Order Completed Successfully.");
}

// Delete products - delete from products table
if(isset($_POST["remove_submit"]) && is_user_logged_in()){

	$db->beginTransaction();
	$id = filter_input(INPUT_POST, 'remove_submit', FILTER_VALIDATE_INT);
	$id = intval(filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS));

	$sql = "DELETE FROM products WHERE products.id LIKE :id;";
	$params = array(
	  ':id' =>
	  $id
	);
	$result = exec_sql_query($db, $sql, $params);

	$sql_prd_img = "SELECT file_ext FROM product_images WHERE id = :img_id;";
	$params_prd_img = array (
		":img_id" => $id
	);
	$results_prd_img = exec_sql_query($db, $sql_prd_img, $params_prd_img) -> fetchAll(PDO::FETCH_COLUMN);

	$file_ext = $results_prd_img[0];


	$sql = "DELETE FROM product_images WHERE product_images.product_id LIKE :delete_img;";
	$params = array(
		':delete_img' =>
		$id
	);
	$result = exec_sql_query($db, $sql, $params);

	$file_del = $id;
	$unlink_url = "uploads/product_images/$file_del.$file_ext";
	fclose($fdelete);
	unlink($unlink_url);

	$db->commit();

	$show_products = TRUE;
	$sql_products = " SELECT products.id, products.product_name, product_types.type, products.price FROM products INNER JOIN product_types WHERE products.product_type_id = product_types.id;";
	$results_products = exec_sql_query($db, $sql_products) -> fetchAll();
	array_push($messages, "Product deleted successfully.");
}

// Add new product image - insert into product_images table
if (isset($_POST['upload_submit']) && is_user_logged_in()) {

$db->beginTransaction();
	$valid_image_upload = TRUE;
	$upload_info = $_FILES["upload"];

	$p_id = filter_input(INPUT_POST, 'p_no_image', FILTER_SANITIZE_STRING);
	$p_id = filter_var($p_id, FILTER_SANITIZE_SPECIAL_CHARS);

	if($p_id){
		$sql = "SELECT products.id FROM products WHERE products.product_name LIKE :p_id;";
		$params = array(':p_id' => $p_id);
		$results_p_image_id = exec_sql_query($db, $sql, $params) -> fetchAll();
		$p_id = $results_p_image_id[0]["id"];
	}

	if($p_id == "" || ($_FILES['upload']['error'] != 0)){
		$valid_image_upload = FALSE;
	}

	if($p_id && $_FILES['upload']['error'] == 0){
	  $file_name = $upload_info["name"];
	  $upload_ext = strtolower( pathinfo($file_name, PATHINFO_EXTENSION) );
	  $file_name = basename($file_name); // cleanses

	  exec_sql_query(
		$db,
		"INSERT INTO product_images (file_ext, file_name, product_id) VALUES (:upload_ext, :file_name, :product_id);",
		array(':upload_ext' => $upload_ext,
		':file_name' => $file_name,
		'product_id' => $p_id
	  )
	  )->fetchAll();

	  $last_insert = $db->lastInsertId("id");

	  $new_path = "uploads/product_images/" . $last_insert . "." . $upload_ext;
	  move_uploaded_file( $_FILES["upload"]["tmp_name"], $new_path );
	}

	$db->commit();

	$show_products = TRUE;
	$sql_products = " SELECT products.id, products.product_name, product_types.type, products.price FROM products INNER JOIN product_types WHERE products.product_type_id = product_types.id;";
	$results_products = exec_sql_query($db, $sql_products) -> fetchAll();
	array_push($messages, "New product image added successfully.");
  }

// Add new product
if(isset($_POST["add_submit"]) && is_user_logged_in()){

	$db->beginTransaction();

	$valid_add = TRUE;

	$product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);
	$product_type = filter_var($product_name, FILTER_SANITIZE_SPECIAL_CHARS);

	if(!isset($product_name) || $product_name == ""){
		$valid_add = FALSE;
	}

	$product_type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
	$product_type = filter_var($product_type, FILTER_SANITIZE_SPECIAL_CHARS);

	// echo($product_type);
	$sql_pid = "SELECT product_types.id FROM product_types WHERE product_types.type LIKE :product_type;";
	$params = array(':product_type' => $product_type);
	$results_pid = exec_sql_query($db, $sql_pid, $params) -> fetchAll();

	if(!isset($product_type) || $results_pid[0]["id"] == ""){
		$valid_add = FALSE;
	}

	$product_price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
	$product_price =  intval(filter_var($product_price, FILTER_SANITIZE_SPECIAL_CHARS));

	if(!isset($product_price) || $product_price == ""){
		$valid_add = FALSE;
	}

	if($valid_add) {
		$sql = "INSERT INTO products(product_name, product_type_id, price) VALUES (:name, :type_id, :price);";
		$params = array(
		':name' =>
		$product_name,
		':type_id' => $results_pid[0]["id"],
		':price' => $product_price
		);
		$result = exec_sql_query($db, $sql, $params);
	}
	$db->commit();

	$show_products = TRUE;
	$sql_products = " SELECT products.id, products.product_name, product_types.type, products.price FROM products INNER JOIN product_types WHERE products.product_type_id = product_types.id;";
	$results_products = exec_sql_query($db, $sql_products) -> fetchAll();
	array_push($messages, "New product added successfully.");
}


?>

<?php
function print_table($rec) { ?>
	<tr>
		<td><?php echo htmlspecialchars($rec["customer_name"]);?></td>
		<td><?php echo htmlspecialchars($rec["customer_email"]);?></td>
		<td><?php echo htmlspecialchars($rec["product_id"]);?></td>
		<td><?php echo htmlspecialchars($rec["quantity"]);?></td>
		<td><?php echo htmlspecialchars($rec["delivery_date"]);?></td>
		<td><?php echo htmlspecialchars($rec["customer_address"]);?></td>
		<td><?php echo htmlspecialchars($rec["customization"]);?></td>
		<td>
			<form method='post' action ='admin.php' novalidate>
				<button class = "back_button" name = 'complete_order' type = 'submit' value = <?php echo $rec["id"];?>> Complete Order </button>
			</form>
		</td>
	</tr>
<?php
}

function print_reviews($rec) { ?>
	<tr>
		<td><?php echo htmlspecialchars($rec["name"]);?></td>
		<td><?php echo htmlspecialchars($rec["review"]);?></td>
		<td><?php echo htmlspecialchars($rec["product"]);?></td>
		<td>
			<form method='post' action ='admin.php' novalidate>
				<button class= "back_button" name = 'remove_review' type = 'submit' value = <?php echo $rec["id"];?>> Remove </button>
			</form>
		</td>
	</tr>
<?php
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php";?>
<!-- <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" /> -->

<body>

<?php include "includes/header.php";?>

<h2><a id = "admin_controls" href = "admin.php">Admin Controls</a></h2>

<?php
if ($not_admin) { ?>
	<!-- non-admin attempting to access admin page -->
	<p>Access Denied!</p>
	<p>You must be logged in as "admin" to access this page.</p>
<?php
}
else { ?>

	<div class="admin-container">
		<div class="but_group">
			<form method="post" action="admin.php" novalidate>
				<button class="admin-button" name="admin-orders">Manage Orders</button>
				<button class="admin-button" name="admin-products">Manage Products</button>
				<button class="admin-button" name="admin-reviews">Manage Reviews</button>
			</form>
		</div>
	<?php if (!$show_orders && !$show_products && !$show_reviews){
		echo("<p class = 'main_inst'> On the admin controls page you can view customer orders, add products and product images, or edit customer reviews. Select a tab above to get started!</p>");
	}?>

	</div>

	<div class="admin-container">
		<?php
		// MANAGE ORDERS
		if ($show_orders) {
			?>
			<h3>Order Requests</h3>
			<?php
			foreach ($messages as $m) {
				echo "<p class  = 'messages'>" . htmlspecialchars($m) . "</p>";
			}
			?>
			<table>
			<?php
			$rec_columns = ["Customer Name", "Customer Email", "Product ID", "Quantity", "Delivery Date", "Customer Address", "Customization"];
			echo("<tr>");
			foreach ($rec_columns as $rc) {
				echo("<td class = 'col_head'>".$rc."</td>");
			}
			echo("</tr>");
			foreach ($results_orders as $r) {
				print_table($r);

			}
			?>
			</table>
			<?php
		}
		// MANAGE PRODUCTS
		if ($show_products) {
		?>
		<h3>Products</h3>
		<?php
			foreach ($messages as $m) {
				echo "<p class  = 'messages'>" . htmlspecialchars($m) . "</p>";
			}
		?>
		<table>
			<tr>
				<td class = 'col_head'>Product Name</td>
				<td class = 'col_head'>Type</td>
				<td class = 'col_head'>Price</td>
			</tr>
		<?php
			foreach ($results_products as $p) {
				echo("<tr>");
					echo("<td>".$p["product_name"]."</td>");
					echo("<td>".$p["type"]."</td>");
					echo("<td>"."$".$p["price"]."</td>");
					echo("<td><form method='post' action ='admin.php'><button class= 'back_button' name = 'remove_submit' type = 'submit' value = '".$p["id"]."'> Remove </button></form></td>");
				echo("</tr>");
			}
			$sql_types = "SELECT product_types.type FROM product_types;";
			$results_types = exec_sql_query($db, $sql_types) -> fetchAll();
		?>
		</table>
		<form action = "admin.php" method="post" novalidate>
			<table>
			<tr>
			<td>
			<input class = "admin_input" name = "product_name" type = "text"/>
		</td>
		<td>
			<select class = "admin_input" name = "type">
				<?php
				foreach($results_types as $type){
					echo( "<option>". $type[0]."</option>");
				}
				?>
		</select>
		</td>
		<td>
			<input name = "price" class = "admin_input" type = "number"/>
		</td>
		<td>
		<button class = 'back_button' type="submit" name = "add_submit">Add Product</button>
		</td>
			<!-- <tr> -->
			</table>
		</form>

		<div class = <?php if(isset($valid_add) && !$valid_add){ echo("error_form");}else{echo("hidden");} ?>> Please complete product addition entry with valid inputs. </div>

				  <!-- upload-->
			<h3>Upload Product Image</h3>
			<p class = "instructions">To upload an image for a new product, ensure you have already added the product to the database. Products without images will be displayed below in the selection menu. Once a product has an image, it will be displayed for customers on the 'Shop' page.</p>
			<form id = "upload_form" action="admin.php" method="post" enctype="multipart/form-data">

			  <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			  <label>Image:</label>
			  <input class= "admin_input" id = "upload" type="file" name="upload"/>
			  <label>Image for Product:</label>
			  <select class = "admin_input" name = "p_no_image">
				  <?php
					$sql_p = "SELECT products.product_name FROM products LEFT OUTER JOIN product_images ON products.id = product_images.product_id WHERE product_images.product_id IS NULL;";
					$results_p = exec_sql_query($db, $sql_p) -> fetchAll();

					foreach ($results_p as $p){
						echo("<option value ='".$p["product_name"]."'>". $p["product_name"]."</option>");
					}
				  ?>
			  </select>
			  <button class = "back_button upload" type="submit" name="upload_submit"> Upload </button>

		  </form>

		<div class = <?php if(isset($valid_image_upload) && !$valid_image_upload){ echo("error_form");}else{echo("hidden");} ?>> Please Upload a Valid Image</div>




		<?php
		// MANAGE REVIEWS
		}
		if ($show_reviews) { ?>
			<h3>Reviews</h3>
			<?php
			foreach ($messages as $m) {
				echo "<p class  = 'messages'>" . htmlspecialchars($m) . "</p>";
			}
			?>
			<table>
				<tr>
					<td class="col_head">Name</td>
					<td class="col_head">Review</td>
					<td class="col_head">Product</td>
				</tr>
				<?php
				foreach ($results_reviews as $rev) {
					print_reviews($rev);
				}
				?>
			</table>
		<?php
		}
		?>
	</div>

<?php
}
?>




<?php include "includes/footer.php";?>
<?php include "includes/login.php";?>
</body>
</html>
