-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;


-- TODO: create tables

CREATE TABLE users (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`username` TEXT NOT NULL UNIQUE,
	`password` TEXT NOT NULL,
	`customer_name` TEXT NOT NULL
);

--TO DO: HASH PASSWORDS - done
--Used password hasher from lab 8
--user table seed data

INSERT INTO users (username, password, customer_name) VALUES ('admin', '$2y$10$mJkC//cJ/RfQHUPwE5k65eBrQ0PFU1k3s616ltpgTqE5s2hi3K3nG', 'Wendy Jiang'); -- password: bakery123

-- Products Table
CREATE TABLE `products` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`product_name` TEXT NOT NULL UNIQUE,
	`product_type_id` INTEGER NOT NULL,
	`price` INTEGER NOT NULL
);
-- cakes
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Mint Cookie Cake', 1, 32);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Valentines Day Cake', 1, 32);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Easter Cake', 1, 32);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Chocolate Velvet Cake', 1, 32);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Fruit Cake', 1, 32);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Chocolate Cherry Cake', 1, 32);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Birthday Surprise Cake', 1, 32);

-- cookies
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Peanut Butter Cookies', 2, 12);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Homemade Oreos', 2, 12);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Almond Cookies', 2, 12);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Hershey Kiss Cookies', 2, 12);

-- pies
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Pumpkin Pie', 3, 24);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Key Lime Pie', 3, 24);

-- cupcakes
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Sprinkle Cupcakes', 4, 18);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Birthday Cupcakes', 4, 18);
INSERT INTO `products` (product_name, product_type_id, price) VALUES ('Chocolate Raspberry Cupcakes', 4, 18);



-- Product Types
CREATE TABLE `product_types` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`type` TEXT NOT NULL UNIQUE
);

INSERT INTO `product_types` (id, type) VALUES (1, "Cakes");
INSERT INTO `product_types` (id, type) VALUES (2, "Cookies");
INSERT INTO `product_types` (id, type) VALUES (3, "Pies");
INSERT INTO `product_types` (id, type) VALUES (4, "Cupcakes");


--Product Images Table
CREATE TABLE `product_images` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`file_ext` TEXT NOT NULL,
	`file_name` TEXT NOT NULL,
	`product_id` INTEGER NOT NULL
);

-- cakes
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (1, 'png', 'mintcookie_cake.png', 1);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (2, 'png', 'valentines_cake.png', 2);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (3, 'png', 'easter_cake.png', 3);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (4, 'png', 'chocolatevelvet_cake.png', 4);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (5, 'png', 'fruit_cake.png', 5);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (6, 'png', 'chocolatecherry_cake.png', 6);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (7, 'png', 'icecream_cake.png', 7);

-- cookies
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (8, 'png', 'peanutbutter_cookies.png', 8);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (9, 'png', 'oreo_cookies.png', 9);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (10, 'png', 'almond_cookies.png', 10);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (11, 'png', 'hersheykiss_cookies.png', 11);

-- pies
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (12, 'png', 'pumpkin_pie.png', 12);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (13, 'png', 'keylime_pie.png', 13);

--cupcakes
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (14, 'png', 'sprinkle_cupcakes.png', 14);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (15, 'png', 'birthday_cupcakes.png', 15);
INSERT INTO `product_images` (id, file_ext, file_name, product_id) VALUES (16, 'png', 'chocolate_cupcakes.png', 16);



-- Reviews Table
CREATE TABLE `reviews` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`name` TEXT NOT NULL,
	`review` TEXT NOT NULL,
	`product` TEXT NOT NULL,
	`rating` INTEGER NOT NULL
);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Pam Beesly', 'Delicious and beautiful! My mom loved this cake, it was perfect for her birthday!', 'Fruit Cake', 5);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Michael Scott', 'This was the best pie I have ever had. It was not too sweet, and had such a crispy crust!', 'Pumpkin Pie', 4);
INSERT INTO `reviews` (name, review, product, rating) VALUES ('Dwight Schrute','The cake looked good, but lacked freshness. The frosting had melted by the time it got to me.', 'Chocolate Velvet Cake', 3);
INSERT INTO `reviews` (name, review, product, rating) VALUES ('Phyllis Vance','The cookies were so crisp and gooey at the same time. Absolute perfection!', 'Chocolate Chip Cookies', 5);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Ryan Howard','These almond cookies are solid and probably one of the better ones I have tasted!', 'Almond Cookies', 4);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Jim Halpert','This birthday cake was exactly what my daughter Cece wanted! The princess theme was perfect and Wendy did it on short notice. Amazing frosting too!', 'Birthday Surprise Cake', 5);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Angela Martin','This key-lime pie looks good, but tastes like lime toothpaste.', 'Key Lime Pie', 2);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Kevin Malone','I have had a lot of peanut butter cookies, but these are my favorite of all time.', 'Peanut Butter Cookies', 5);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Creed Bratton','Sprinkles Cupcakes were OK. Not sure why I even ordered this.The blue frosting was cool though.', 'Sprinkles Cupcakes', 2);

INSERT INTO `reviews` (name, review, product, rating) VALUES ('Kelly Kapoor','LITERALLY AMAZING! Wendy makes better oreos than Nabisco.', 'Homemade Oreos', 5);

-- Customer Images Table
CREATE TABLE `customer_images` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`file_ext` TEXT NOT NULL,
	`file_name` TEXT NOT NULL,
	`citation` TEXT NOT NULL,
	`review_id` INTEGER NOT NULL
);


INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (1, 'fruitCake.png', 'png', 'https://www.yelp.com/biz_photos/two-little-red-hens-new-york-4?select=CbucpcW39hPmP5DDIsDzzQ', 1 );
--(Batool C. on Yelp) Image Source: https://www.yelp.com/biz_photos/two-little-red-hens-new-york-4?select=CbucpcW39hPmP5DDIsDzzQ

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (2, 'pie.png', 'png', 'https://www.yelp.com/biz_photos/petees-pie-company-new-york?select=hrP-SP3dE-RDe4Bk32Uw9g', 2 );
--(Jeffrey D. on Yelp) Image Source: https://www.yelp.com/biz_photos/petees-pie-company-new-york?select=hrP-SP3dE-RDe4Bk32Uw9g

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (3, 'chocCake.png', 'png', 'https://www.yelp.com/biz_photos/cafe-patoro-new-york?select=Nnlsd9Wl7KFVVqZxtDRWpw', 3 );
--(Preeti P. on Yelp) Image Source: https://www.yelp.com/biz_photos/cafe-patoro-new-york?select=Nnlsd9Wl7KFVVqZxtDRWpw

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (4, 'cookies.png', 'png', 'https://www.yelp.com/biz_photos/duchess-cookies-new-york-2?select=4VGfvySsMsAQ-wxX6G0x6w', 4 );
--(Duchess Cookies on Yelp) Image Source: https://www.yelp.com/biz_photos/duchess-cookies-new-york-2?select=4VGfvySsMsAQ-wxX6G0x6w


INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (5, '5.png', 'png', 'https://www.yelp.com/biz_photos/lung-moon-bakery-new-york?select=xO10j-ZQnpiGMPWccM3oZw', 5 );
--(Cecilia T. on Yelp) Image Source: https://www.yelp.com/biz_photos/lung-moon-bakery-new-york?select=xO10j-ZQnpiGMPWccM3oZw


INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (6, '6.png', 'png', 'https://www.yelp.com/biz_photos/du-jour-bakery-brooklyn?select=VRIZ4xIIloLXo-qas_in0Q', 6 );
--(Jen H. on Yelp) Image Source: https://www.yelp.com/biz_photos/du-jour-bakery-brooklyn?select=VRIZ4xIIloLXo-qas_in0Q

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (7, '7.png', 'png', 'https://www.yelp.com/biz_photos/key-west-key-lime-pie-key-west?select=ebk1JAJRYoayMdLWYBlp2A', 7 );
--(Judy L. on Yelp) Image Source: https://www.yelp.com/biz_photos/key-west-key-lime-pie-key-west?select=ebk1JAJRYoayMdLWYBlp2A

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (8, '8.png', 'png', 'https://www.yelp.com/biz_photos/steve-and-rockys-novi?select=5UmAJ3xE1Dd7oiYZTjZiIw', 8 );
--(Mercedes V. on Yelp) Image Source: https://www.yelp.com/biz_photos/steve-and-rockys-novi?select=5UmAJ3xE1Dd7oiYZTjZiIw

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (9, '9.png', 'png', 'https://www.yelp.com/biz_photos/mollys-cupcakes-new-york?select=O27p5ZQBrttSTfcsyraC9g', 9 );
--(Kaye J. on Yelp) Image Source: https://www.yelp.com/biz_photos/mollys-cupcakes-new-york?select=O27p5ZQBrttSTfcsyraC9g

INSERT INTO `customer_images`(id, file_name, file_ext, citation, review_id) VALUES (10, '10.png', 'png', 'https://www.finecooking.com/recipe/homemade-oreos', 10 );
--(Joanne Chang) Image Source: https://www.finecooking.com/recipe/homemade-oreos



-- Orders Table
CREATE TABLE `orders` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`customer_name` TEXT,
	`customer_email` TEXT NOT NULL,
	`product_id` INTEGER NOT NULL,
	`quantity` INTEGER NOT NULL,
	`delivery_date` DATE NOT NULL,
	`customer_address` TEXT NOT NULL,
	`customization` TEXT
);

INSERT INTO `orders` (customer_name, customer_email, product_id, quantity,delivery_date, customer_address, customization) VALUES ('Jessica Lang', 'jessica24@aol.com', 7, 2,'2019-11-11', '277 Alice Lane, Ithaca NY', 'Please write "Happy Birthday Nelly!" on top.');

INSERT INTO `orders` (customer_name, customer_email, product_id, quantity,delivery_date, customer_address, customization) VALUES ('Alice Wu', 'aw1947@gmail.com', 16, 4,'2019-07-12', '404 Nice Ave, Brooklyn NY', 'Our child has a nut allergy! Please be careful.');


-- Sessions Table
CREATE TABLE `sessions` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`user_id` INTEGER NOT NULL,
	`session` TEXT NOT NULL UNIQUE
);


-- About Bakery Owner Table
CREATE TABLE `about_bio` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`bio_text` TEXT NOT NULL
);

INSERT INTO `about_bio` (bio_text) VALUES ("Meet Wendy Jiang, the owner of A Journey to the Sweets. Wendy has loved baking since she was a little girl and has even taken multiple baking classes in China, to learn different techniques. Currently residing in Pennsylvania, Wendy hopes to share her passion for baking with the world and one day open up her very own physical bakery shop.");

-- About Services Table
CREATE TABLE `about_services` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`services_text` TEXT NOT NULL
);

INSERT INTO `about_services` (services_text) VALUES ("Have a special event coming up? A Journey to the Sweets can help provide you the best desserts. Customize cakes, pies, cookies, and cupcakes! And on top of that, we will deliver your sweets straight to your doorsteps so that you can enjoy them in comfort.");

-- Shipping terms table
CREATE TABLE `terms_ship` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`shipping_text` TEXT NOT NULL
);

INSERT INTO `terms_ship` (shipping_text) VALUES ("Orders are usually custom-made a few days before your selected delivery date. All orders will be delivered to your doorstep by your selected delivery date. At this moment, we do not accept returns. Order cancellations will be accepted up until two weeks of your selected delivery date. Please allow for at least one week in advance of delivery date before placing an order.");

-- Payment terms table
CREATE TABLE `terms_pay` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`payment_text` TEXT NOT NULL
);

INSERT INTO `terms_pay` (payment_text) VALUES ("We accept all major credit cards and Paypal.");


COMMIT;
