<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$page_title = "Reviews";
$style = "styles/all.css";
const MAX_FILE_SIZE = 1000000;
$db = open_or_init_sqlite_db("secure/bakery_db.sqlite", "secure/init.sql");

const SEARCHES = [
	"product" => "By Product",
	"rating" => "By Rating",
	"review" => "By Review"
];

$errors = array();

//(Kyle Harms) I used Lab 8 from Info 2300 as a reference. This Lab was created by Professor Kyle Harms.

$track_sticky = true;

if (isset($_POST["submit_upload"])) {

	$upload_bool = true;
	$hid1 = "name_error hidden";
	$hid2 = "review_error hidden";
	$hid3 = "cite_error hidden";
	$hid4 = "upload_error hidden";



	$uploaded_info = $_FILES["uploaded_image"];

	$uploaded_review = filter_input(INPUT_POST, 'uploaded_review', FILTER_SANITIZE_STRING);

	$uploaded_name = filter_input(INPUT_POST, 'uploaded_name', FILTER_SANITIZE_STRING);

	$uploaded_product = $_POST['uploaded_product'];

	$uploaded_cite = filter_input(INPUT_POST, 'citation', FILTER_SANITIZE_STRING);

	$uploaded_rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

	if(trim($uploaded_name) == ''){
		$upload_bool = false;
		$hid1 = "name_error";
	}

	if(trim($uploaded_review) == ''){
		$upload_bool = false;
		$hid2 = "review_error";
	}

	if(trim($uploaded_cite) == ''){
		$upload_bool = false;
		$hid3 = "cite_error";
	}

	if ($_FILES['uploaded_image']['error'] == UPLOAD_ERR_NO_FILE){
		$upload_bool = false;
		$hid4 = "upload_error";
	}

	if ( $uploaded_rating < 1 or $uploaded_rating > 5 ) {
		$upload_bool = FALSE;
	}


	//This portion checks if the file uploaded correctly
	if ($uploaded_info['error'] == UPLOAD_ERR_OK and ($upload_bool == TRUE)){

		$hid1 = "name_error hidden";
		$hid2 = "review_error hidden";
		$hid3 = "cite_error hidden";
		$hid4 = "upload_error hidden";
		$add_class = "add_form hidden";


		$up_name = basename($uploaded_info["name"]);
		$up_ext = strtolower(pathinfo($up_name, PATHINFO_EXTENSION));

		$query2 = "INSERT INTO reviews(name, review, product, rating) VALUES (:name, :review, :product,  :rating);";

		$params2 = array(':name' => $uploaded_name, ':review' => $uploaded_review, ":product" => $uploaded_product, ":rating" => $uploaded_rating);

		$res2 = exec_sql_query($db, $query2, $params2);
		$review_id = $db->lastInsertId("id");

		$query_add = "INSERT INTO customer_images(file_name, file_ext, citation, review_id) VALUES (:file_name, :file_ext, :citation, :review_id);";


		$params_add = array(':file_name' => $up_name, ':file_ext' => $up_ext, ':citation' => $uploaded_cite, ':review_id' => $review_id);

		$res1 = exec_sql_query($db, $query_add, $params_add);
		$img_id = $db->lastInsertId("id");


		if ($res1) {

			$store_img = 'uploads/user_images/' . $img_id . '.' . $up_ext;
			if (move_uploaded_file($uploaded_info["tmp_name"], $store_img)) {
				array_push($errors, "Thank you for your review!");

				$add_class = "add_form";
				$track_sticky = false;

			} else {
				array_push($errors, "Failed to upload image. Check submission.");
			}
		} else {
			array_push($errors, "Failed to upload image.");
		}

  } else{
    $upload_bool = false;
    array_push($errors, "Failed to submit review. Please scroll down to check your add form.");
}
}


//DELETING IMAGES FROM REVIEWS

if (isset($_POST["delete_image"])) {

	$id_remove = $_POST["delete_image"];


	$img_delquery = "SELECT file_ext FROM customer_images WHERE id = :del_img;";
	$qparams = array(":del_img" => $id_remove);
	$result = exec_sql_query($db, $img_delquery, $qparams)->fetchAll(PDO::FETCH_COLUMN);

	$file_ext = $result[0];

	//Remove from the images table.
	$query_remove = "DELETE FROM customer_images WHERE id = :del_img;";
	$remove_params = array(":del_img" => $id_remove);
	$result_remove = exec_sql_query($db, $query_remove, $remove_params);

	$file_del = $id_remove;
	$unlink_file = "uploads/user_images/$file_del.$file_ext";
	unlink($unlink_file);
}

//DELETING REVIEWS

if (isset($_POST["delete_review"])) {

	$delete_review = $_POST["delete_review"];

	$find_imgquery = "SELECT customer_images.id FROM reviews INNER JOIN customer_images ON customer_images.review_id = reviews.id where reviews.id = :delete_review;";

	$find_imgparams = array(":delete_review" => $delete_review);

	//Gets the image id of the image we want to remove from the images table.
	$image_todelete = exec_sql_query($db, $find_imgquery, $find_imgparams)->fetchAll();


	$query_delete = "DELETE FROM reviews WHERE reviews.id = :delete_review;";
	$delete_params = array(":delete_review" => $delete_review);

	//Removes from the reviews table.
	$result_delete = exec_sql_query($db, $query_delete, $delete_params);


	if ($result_delete) {
		//Also must delete from the images table and remove from uploads folder.
		foreach ($image_todelete as $im) {

			$img_delquery = "SELECT file_ext FROM customer_images WHERE id = :del_img;";
			$qparams = array(":del_img" => $im["id"]);
			$result = exec_sql_query($db, $img_delquery, $qparams)->fetchAll(PDO::FETCH_COLUMN);

			$file_ext = $result[0];

			//Remove from the images table.
			$query_remove = "DELETE FROM customer_images WHERE id = :del_img;";
			$remove_params = array(":del_img" => $im["id"]);
			$result_remove = exec_sql_query($db, $query_remove, $remove_params);

      $file_del = $im['id'];
      $unlink_file = "uploads/user_images/$file_del.$file_ext";
      unlink($unlink_file);
      array_push($errors, "Review removed successfully.");
    }
  } else {
    array_push($errors, "Could not delete.");
  }


}


?>

<!DOCTYPE html>
<html lang="en">

<?php include("includes/head.php");?>
<!-- <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" /> -->
<?php
$top_class = "top_class";
$text = "text";
$text2 = "text2 hidden";




if (isset($_GET['review_clicked'])) {
	$top_class = "top_class hidden";

}

if (isset($_GET['review_clicked'])) {
	$top_class = "top_class hidden";

}


if (is_user_logged_in() and $current_user["username"] == "admin") {
	$add_class = "add_form hidden";
	$hide_button = "add_rev hidden";
	$text = "text hidden";
	$text2 = "text2";
	$add_button = "add_rev3 hidden";
}
else{
	$add_button = "add_rev3";

}


?>

<body>

<?php include("includes/header.php");


foreach ($errors as $er) {
  echo "<p class  = 'messages'>" . htmlspecialchars($er) . "</p>\n";
}



?>
<div class="<?php echo $top_class; ?>">

<h2 class="headings3"> Reviews </h2>

<p class="<?php echo $text; ?>"> Add reviews, share photos, and discover more of our products!</p>

<p class="<?php echo $text2; ?>"> As admin, you can delete reviews and remove images from reviews. Click 'View Review' to delete them or modify them.</p>


<a class = "<?php echo $add_button; ?>" href = "reviews.php#add">Add Review</a>

<form action="reviews.php" method="get">

<select name="search_category">
<option value="" selected disabled>Search By</option>
<?php
foreach (SEARCHES as $search => $s) {
	?>
	<option value="<?php echo $search; ?>"><?php echo $s; ?></option>
	<?php
}
?>
</select>

<input id="search_box" type="text" name="search" />
<button class="search_bar" id="sub-button" type="submit">SEARCH</button>
</form>

</div>



<?php
$review_clicked = $_GET['review_clicked'];

if (isset($review_clicked)) {

	$top_class = "top_class hidden";

	$review_query = "SELECT * FROM reviews WHERE reviews.id = :review_clicked;";

	$review_params = array(":review_clicked" => $review_clicked);

	$review_records = exec_sql_query($db, $review_query, $review_params);
	$review_info = $review_records->fetchAll();

	$imgs_query = "SELECT * FROM customer_images WHERE customer_images.review_id =:review_clicked;";

	$images_gall = exec_sql_query($db, $imgs_query, array(':review_clicked' => $review_clicked))->fetchAll();


	if ($review_info) {

		if (is_user_logged_in() and $current_user["username"] == "admin") {

			$class = "tag_style2";
			$class3 = "tag_style3";
			$class2 = "tag_style";
			$class4 = "style4 hidden";
		} else {
			$class = "tag_style2 hidden";
			$class3 = "tag_style3 hidden";
			$class2 = "tag_style hidden";
			$class4 = "style4";
		}


		?>

		<form method="post">
		<h3 class="headings3"> View Review </h3>

		<div class="back_button">
		<a class="add_rev3" href="reviews.php"> Back to Reviews </a>
		</div>

		<div id="rev_container">
		<p class="review_display"> <?php echo $review_info[0]['review']; ?> </p>
		</div>
		<h3 class="headings2"> Images</h3>
		<?php

		if (($images_gall)) {

			?>
			<img class="gal_images" src="uploads/user_images/<?php echo $images_gall[0]['id']; ?>.<?php echo $images_gall[0]['file_ext']; ?>" alt="<?php echo htmlspecialchars($images_gall[0]['id']); ?>" />

			<div>
			<?php

			// echo '<p class = "citation"> Citation: ' . $images_gall[0]["citation"] . '</p>' . PHP_EOL;
			// Image Sources: cited in seed data
			echo '<p class = "citation"><a href="' . $images_gall[0]["citation"] . '">Image Source' . '</a></p>' . PHP_EOL;

			echo '<p class = "rev_info"> Reviewed By: ' . $review_info[0]["name"] . '</p>' . PHP_EOL;

			echo '<p class = "rev_info"> Product Reviewed: ' . $review_info[0]["product"] . '</p>' . PHP_EOL;

			?>
			<p class = "rev_info"> Overall Rating:
			<?php
			// Adopted from Professor Kyle Harms' Lab 4: Shoe Review
			$rating_stars = intval( $review_info[0]["rating"] );
			for ($i = 1; $i <= 5; $i++) {
				if ($i <= $rating_stars) {
					echo "★";
				} else {
					echo "☆";
				}
			}
			?>
			</p>
			</div>
			</form>

			<?php
		}
		?>
		<form action="reviews.php" method="post">
		<button class="<?php echo $class; ?>" name="delete_review" type="submit" value="<?php echo $review_info[0]["id"] ?>">Remove Review </button>
		</form>

		<?php
	}
}


?>


<?php

//SEARCH REVIEWS

if (isset($_GET['search']) && isset($_GET['search_category'])) {

	$search_var = true;
	$search_category =  filter_input(INPUT_GET, 'search_category', FILTER_SANITIZE_STRING);


	if (in_array($search_category, array_keys(SEARCHES))) {
		$search_by = $search_category;
	} else {
		$search_var = false;
		array_push($errors, "Please use a valid category to search!");
	}

	$input_search =  filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
	$input_search =  trim($input_search);
} else {
	$search_var = false;
	$input_search =  null;
	$search_category = null;
}

if ($search_var and $input_search != '') {

	$query = "SELECT * FROM reviews WHERE $search_by LIKE '%'|| :search || '%'";
	$params = array(
		':search' => $input_search
	);
}

$result = exec_sql_query($db, $query, $params);

if ($result) {

	?>

	<h3 class="headings3"> Search Results for:  <?php echo $input_search; ?></h3>
	<a class = "add_rev2"  href = "reviews.php">Go Back</a>
	<div id="container">

	<form class = "gals" action="reviews.php" method="get">
	<?php
	foreach ($result as $review) {

		?>
		<div class="indiv_reviews">
		<?php
		echo '<div class = "review_text">' . $review["review"] . '</div>' . PHP_EOL;

		echo '<p class = "rev_info"> Reviewed By: ' . $review["name"] . '</p>' . PHP_EOL;

		echo '<p class = "rev_info"> Product Reviewed: ' . $review["product"] . '</p>' . PHP_EOL;

		?>
		<p class = "rev_info"> Overall Rating:
		<?php
		// Adopted from Professor Kyle Harms' Lab 4: Shoe Review
		$rating_stars = intval( $review["rating"] );
		for ($i = 1; $i <= 5; $i++) {
			if ($i <= $rating_stars) {
				echo "★";
			} else {
				echo "☆";
			}
		}
		?>
		</p>
		<button class="tag_style" name="review_clicked" type="submit" value="<?php echo $review['id'] ?>"> View Review</button>

		</div>



		<?php
	}
	?>
	</form>
	</div>
	<?php
}



?>
<h3 class="headings3"> All Reviews </h3>
<div class="gallery_div">


<form class="gals" method="get">

<?php
$gall_query = "SELECT * FROM reviews";
$gall_results = exec_sql_query($db, $gall_query)->fetchAll();



foreach ($gall_results as $review) {

	?>
	<div class="indiv_reviews">
	<?php
	echo '<div class = "review_text">' . $review["review"] . '</div>' . PHP_EOL;

	echo '<p class = "rev_info"> Reviewed By: ' . $review["name"] . '</p>' . PHP_EOL;

	echo '<p class = "rev_info"> Product Reviewed: ' . $review["product"] . '</p>' . PHP_EOL;

	?>
	<p class = "rev_info"> Overall Rating
	<?php
	// Adopted from Professor Kyle Harms' Lab 4: Shoe Review
	$rating_stars = intval( $review["rating"] );
	for ($i = 1; $i <= 5; $i++) {
		if ($i <= $rating_stars) {
			echo "★";
		} else {
			echo "☆";
		}
	}
	?>
	</p>

	<button class="tag_style" name="review_clicked" type="submit" value="<?php echo $review['id'] ?>"> View Review</button>
	</div>

	<?php
}

      ?>
      </form>
      </div>

<?php

$prod_query  = "SELECT product_name FROM products;";
$exec_prod = exec_sql_query($db, $prod_query)->fetchAll();


?>
<!-- Add Review -->
<div class="<?php echo $add_class; ?>">
<h3 id = "add" class="headings"> Add a Review! </h3>

<form id="image_upload" action="reviews.php" method="post" enctype="multipart/form-data">
<ul>
<li>
<p class="<?php echo $hid1; ?>">Please provide your name.</p>
<label for="uploaded_name">Your Name:</label>
<?php if ($track_sticky ==  false) {
	?>
	<input id="uploaded_name" type="text" name="uploaded_name">
	<?php
} else{?>
	<input id="uploaded_name" type="text" name="uploaded_name" value = "<?php if( isset($uploaded_name) ) { echo htmlspecialchars($uploaded_name);}?>">
	<?php
} ?>
</li>
<li>
<p class="<?php echo $hid4; ?>">Please upload an image.</p>
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
<label for="uploaded_image">Upload an Image:</label>
<input id="uploaded_image" type="file" name="uploaded_image">
</li>

<li>
<p class="<?php echo $hid2; ?>">Please write a review.</p>
<label for="uploaded_review">Review:</label>
<?php if ($track_sticky ==  false) {
	?>
	<textarea id="uploaded_review" name="uploaded_review"></textarea>
	<?php
} else{?>
	<textarea id="uploaded_review" name="uploaded_review"><?php if( isset($uploaded_review) ) { echo htmlspecialchars($uploaded_review);}?></textarea>
	<?php
} ?>
</li>
<li>
<label for="uploaded_product">Product you're Reviewing:</label>
<select id = "uploaded_product" class="input_style" name="uploaded_product">
<?php
foreach ($exec_prod as $product) {
	?>
	<option><?php echo $product[0]; ?></option>
	<?php
}
?>
</select>
</li>
<li>
<label for="uploaded_review">Overall Rating:</label>
<input type="radio" name="rating" value="5" checked/>5
<input type="radio" name="rating" value="4"/>4
<input type="radio" name="rating" value="3"/>3
<input type="radio" name="rating" value="2"/>2
<input type="radio" name="rating" value="1"/>1
</li>
<li>
<p class="<?php echo $hid3; ?>">Please specify your source.</p>
<label for="citation">Citation (Personal or Link):</label>

<?php if ($track_sticky ==  false) {
	?>
	<input id="citation" type="text" name="citation">
	<?php
} else{?>
	<input id="citation" type="text" name="citation" value = "<?php if( isset($uploaded_cite) ) { echo htmlspecialchars($uploaded_cite);}?>">
	<?php
} ?>
</li>
<li id="submit_center">
<button class="add_rev2" name="submit_upload" type="submit">Add Review</button>
</li>
</ul>
</form>
</div>


<div id="footer">
<?php include("includes/footer.php"); ?>
</div>
</body>

</html>
