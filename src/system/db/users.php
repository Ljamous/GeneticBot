<?php
require_once 'mycon.php';  // Ensure this path is correct

function login($em, $pw) {
  global $con;

  // Hash the password for comparison
  $pw_hashed = sha1($pw);

  $st = $con->prepare("SELECT id, name FROM users WHERE email=? AND password=?");

  if ($st === false) {
    error_log("Error preparing login statement: " . $con->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false; // Or handle the error appropriately
  }

  $st->bind_param("ss", $em, $pw_hashed);
  $st->execute();
  $rs = $st->get_result();

  if ($rs === false) {
    error_log("Error executing login statement: " . $st->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false;  //Or handle the error
  }

  if ($rs->num_rows > 0) {
    return $rs; //Return the entire resultset if login is successful
  } else {
    return false; // Indicate login failure
  }
}

function getName($userid) {
  global $con;

  $st = $con->prepare("SELECT name FROM users WHERE id=?");

  if ($st === false) {
    error_log("Error preparing getName statement: " . $con->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false; // Or handle the error
  }

  $st->bind_param("i", $userid);
  $st->execute();
  $rs = $st->get_result();

  if ($rs === false) {
    error_log("Error executing getName statement: " . $st->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return "Add"; // Or some default name, or handle the error.  Returning "Add" when an error occurs is a bad idea.
  }

  if ($rs->num_rows == 0) {
    return "Add"; // Or some default name, or handle the absence of a name.
  } else {
    $row = $rs->fetch_assoc();
    return $row["name"];
  }
}

function changePassword($userid, $oldpw, $newpw) {
  global $con;

  // Hash the old password for comparison
  $oldpwenc = sha1($oldpw);

  $st = $con->prepare("SELECT id FROM users WHERE id=? AND password=?");

  if ($st === false) {
    error_log("Error preparing changePassword select statement: " . $con->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false;
  }

  $st->bind_param("is", $userid, $oldpwenc);
  $st->execute();
  $rs = $st->get_result();

  if ($rs === false) {
    error_log("Error executing changePassword select statement: " . $st->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false;
  }

  if ($rs->num_rows == 0) { //Corrected the logic here
    return false; // Old password doesn't match
  } else {
    // Hash the new password before updating
    $newpwenc = sha1($newpw);

    $st = $con->prepare("UPDATE users SET password=? WHERE id=?");

    if ($st === false) {
      error_log("Error preparing changePassword update statement: " . $con->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
      return false;
    }

    $st->bind_param("si", $newpwenc, $userid);
    if (!$st->execute()) {
      error_log("Error executing changePassword update statement: " . $st->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
      return false;
    }
    return true;
  }
}


function signup($em, $pw, $nm) {
  global $con;

  // Hash the password before storing it
  $pw_hashed = sha1($pw);

  // Prepare the SQL statement
  $roleid = 3; // Default role: Customer
  $st = $con->prepare("INSERT INTO users(email, password, name, roleid) VALUES(?,?,?,?)");

  if ($st === false) {
    error_log("Error preparing signup statement: " . $con->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false;
  }

  // Bind parameters
  $st->bind_param("sssi", $em, $pw_hashed, $nm, $roleid);

  // Execute the statement
  if (!$st->execute()) {
    error_log("Error executing signup statement: " . $st->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false;
  }

  // Log successful signup
  error_log("User signed up successfully: Email: $em, Name: $nm", 3, "/opt/lampp/htdocs/lama2/db_connection.log");

  // Get the user ID
  $userId = getUserId($em);

  if ($userId === null) {
    error_log("Error retrieving user ID for email: $em", 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return false;
  }

  return $userId;
}

function getUserId($email) {
  global $con;

  $st = $con->prepare("SELECT id FROM users WHERE email=?");

  if ($st === false) {
    error_log("Error preparing getUserId statement: " . $con->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return null;  //Return null rather than false
  }

  $st->bind_param("s", $email);
  $st->execute();

  $rs = $st->get_result();

  if ($rs === false) {
    error_log("Error executing getUserId statement: " . $st->error, 3, "/opt/lampp/htdocs/lama2/db_connection.log");
    return null;   //Return null rather than false
  }


  if ($rs->num_rows > 0) {
    $row = $rs->fetch_assoc();
    return $row["id"];
  } else {
    return null;  // Or false, depending on your preference
  }
}
?>
