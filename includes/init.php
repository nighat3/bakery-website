<?php
// vvv DO NOT MODIFY/REMOVE vvv

// check current php version to ensure it meets 2300's requirements
function check_php_version()
{
	if (version_compare(phpversion(), '7.0', '<')) {
		define(VERSION_MESSAGE, "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
		echo VERSION_MESSAGE;
		throw VERSION_MESSAGE;
	}
}
check_php_version();

function config_php_errors()
{
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 0);
	error_reporting(E_ALL);
}
config_php_errors();

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename)
{
	if (!file_exists($db_filename)) {
		$db = new PDO('sqlite:' . $db_filename);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if (file_exists($init_sql_filename)) {
			$db_init_sql = file_get_contents($init_sql_filename);
			try {
				$result = $db->exec($db_init_sql);
				if ($result) {
					return $db;
				}
			} catch (PDOException $exception) {
				// If we had an error, then the DB did not initialize properly,
				// so let's delete it!
				unlink($db_filename);
				throw $exception;
			}
		} else {
			unlink($db_filename);
		}
	} else {
		$db = new PDO('sqlite:' . $db_filename);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	}
	return null;
}

function exec_sql_query($db, $sql, $params = array())
{
	$query = $db->prepare($sql);
	if ($query and $query->execute($params)) {
		return $query;
	}
	return null;
}

// ^^^ DO NOT MODIFY/REMOVE ^^^

$db = open_or_init_sqlite_db('secure/bakery_db.sqlite', 'secure/init.sql');

//SOURCE: KYLE HARMS' LAB 8 - LOGIN CODE vv
define('SESSION_COOKIE_DURATION', 60 * 60 * 1);
// log in function, takes username & password from login.php
function log_in($username, $password)
{
	global $db;
	global $current_user;
	//global variables for the database and to keep track of current user
	if (isset($username) && isset($password)) {
		//check the username and password entered
		$sql = "SELECT * FROM users WHERE username = :username;";
		$params = array(
			':username' => $username,
		);
		//look in the users table we made in init.sql to check if the username
		//entered exists
		$records = exec_sql_query($db, $sql, $params)->fetchAll();
		if ($records) {
			//if records exists/is true
			$account = $records[0];
			//create a user record
			if (password_verify($password, $account['password'])) {
				//php function creates unique id
				$session = session_create_id();
				//check table to verify password
				$sql = "INSERT INTO sessions (user_id, session) VALUES (:user_id, :session);";
				//insert into session table just created
				$params = array(
					':user_id' => $account['id'],
					':session' => $session,
				);
				//execute sql
				$result = exec_sql_query($db, $sql, $params);
				if ($result) {
					setcookie("session", $session, time() + SESSION_COOKIE_DURATION);
					//create session
					$current_user = $account;
					return $current_user;
				} else {
					echo "<p id = 'login_feed'>log in failed</p>";
				}
			} else {
				echo "<p id = 'login_feed'>Invalid username or password</p>";
			}
		} else {
			echo "<p id = 'login_feed'>Invalid username or password</p>";
		}
	} else {
		echo "<p id = 'login_feed'>Missing usernmae or password</p>";
	}
	$current_user = null;
	return null;
}
//helper function to see if user is in our users table
function find_user($user_id)
{
	global $db;

	$sql = "SELECT * FROM users WHERE id = :user_id;";
	$params = array(
		':user_id' => $user_id,
	);
	$records = exec_sql_query($db, $sql, $params)->fetchAll();
	if ($records) {
		return $records[0];
	}
	return null;
}
//helper function to create a session for the user
function find_session($session)
{
	global $db;

	if (isset($session)) {
		$sql = "SELECT * FROM sessions WHERE session = :session;";
		$params = array(
			':session' => $session,
		);
		$records = exec_sql_query($db, $sql, $params)->fetchAll();
		if ($records) {
			return $records[0];
		}
	}
	return null;
}
//helper login function
function session_login()
{
	global $db;
	global $current_user;
	//check if session exists
	if (isset($_COOKIE["session"])) {
		$session = $_COOKIE["session"];
		$session_record = find_session($session);
		//give current user a session, duration 1 hour
		if (isset($session_record)) {
			$current_user = find_user($session_record['user_id']);
			setcookie("session", $session, time() + SESSION_COOKIE_DURATION);
			return $current_user;
		}
	}
	$current_user = null;
	return null;
}
//check if logged in with global current_user var
function is_user_logged_in()
{
	global $current_user;
	return ($current_user != null);
}
//log out function used back in login.php
function log_out()
{
	global $current_user;

	setcookie('session', '', time() - SESSION_COOKIE_DURATION);
	$current_user = null;
}
//on submit of login button from the inputs on login.php
if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	log_in($username, $password);
} else {
	session_login();
}
//logout button or time out
if (isset($current_user) && (isset($_GET['logout']) || isset($_POST['logout']))) {
	log_out();
}

//SOURCE: KYLE HARMS' LAB 8 - LOGIN CODE ^^
