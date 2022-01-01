<?php
include_once("connection.php");
// To get the Complete Url
function full_url()
{
	$s = (empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on")) ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}
function StringRepair($temptext)
{
	$temptext = trim($temptext);
	$temptext = str_replace("'", "&#39;", $temptext);
	$temptext = str_replace("\"", "&#34;", $temptext);
	return $temptext;
}
function StringRepair3($temptext)
{
	$temptext = trim($temptext);
	$temptext = str_replace("&#39;", "'", $temptext);
	$temptext = str_replace("&#34;", "\"", $temptext);
	return $temptext;
}
// To get the Alert message for success and failure
function alertBox()
{
	if ($_SESSION["slogin_Error"] != "") {
		echo '  <div class="alert alert-danger">' . $_SESSION["slogin_Error"] . '</div>';
		$_SESSION["slogin_Error"] = "";
	}
	if ($_SESSION["sadmin_changeImage_Delete"] != "") {
		echo '  <div class="alert alert-success">' . $_SESSION["sadmin_changeImage_Delete"] . '</div>';
		$_SESSION["sadmin_changeImage_Delete"] = "";
	}
	if ($_SESSION["sadmin_siteAccessMessage"] != "") {
		echo '  <div class="alert alert-warning">' . $_SESSION["sadmin_siteAccessMessage"] . '</div>';
		$_SESSION["sadmin_siteAccessMessage"] = "";
	}
}
// Check Record existence in saved page
function recordexist($db, $opr, $sname, $tabname, $cname, $id, $addpage, $url, $msgexist, $ctid)
{
	if ($ctid == "") {
		$ctid = "id";
	}
	if ($opr == "Add") {
		$sql = "select " . $sname . " from " . $tabname . " where " . $sname . "='" . $cname . "'";
	} elseif ($opr == "Edit") {
		$sql = "select " . $sname . " from " . $tabname . " where " . $sname . "='" . $cname . "' and " . $ctid . "!=" . $id;
	}

	$rs = $db->query($sql) or die("cannot Select records" . $db->error);
	if ($rs->num_rows > 0) {

		if ($opr == "Add") {
			$_SESSION["sadmin_changeImage_Delete"] = $msgexist;
			print "<META http-equiv='refresh' content=0;URL='" . $addpage . "'>";
			exit;
		} elseif ($opr == "Edit") {
			$_SESSION["sadmin_changeImage_Delete"] = $msgexist;
			print "<META http-equiv='refresh' content=0;URL=" . $addpage . "?id=" . $id . ">";
			exit;
		} else {
			print "<META http-equiv='refresh' content=0;URL='" . $url . "'>";
			exit;
		}
	}
}
function recordexistMulti($db, $opr, $tabname, $conditional, $id, $addpage, $url, $msgexist, $ctid)
{
	if ($ctid == "") {
		$ctid = "id";
	}
	if ($opr == "Add") {
		$sql = "select * from " . $tabname . " where " . $conditional;
	} elseif ($opr == "Edit") {
		$sql = "select * from " . $tabname . " where " . $conditional . " and " . $ctid . "!=" . $id;
	}
	$rs = $db->query($sql) or die("cannot Select records" . $db->error);
	if ($rs->num_rows > 0) {
		if ($opr == "Add") {
			$_SESSION["sadmin_changeImage_Delete"] = $msgexist;
			print "<META http-equiv='refresh' content=0;URL='" . $addpage . "'>";
			exit;
		} elseif ($opr == "Edit") {
			$_SESSION["sadmin_changeImage_Delete"] = $msgexist;
			print "<META http-equiv='refresh' content=0;URL=" . $addpage . "?id=" . $id . ">";
			exit;
		} else {
			print "<META http-equiv='refresh' content=0;URL='" . $url . "'>";
			exit;
		}
	}
}
// for New Record Insert
function dbRowInsert($db, $table_name, $form_data)
{
	// retrieve the keys of the array (column titles)
	$fields = array_keys($form_data);
	// build the query
	$sql = "INSERT INTO " . $table_name . "
    (`" . implode('`,`', $fields) . "`)
    VALUES('" . implode("','", $form_data) . "')";
	// run and return the query result resource
	return $db->query($sql) or die("cannot execute Insert statement" . $db->error);
}
// For Updating the Table
function dbRowUpdate($db, $table_name, $form_data, $where_clause = '')
{
	// check for optional where clause
	$whereSQL = '';
	if (!empty($where_clause)) {
		// check to see if the 'where' keyword exists
		if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
			// not found, add key word
			$whereSQL = " WHERE " . $where_clause;
		} else {
			$whereSQL = " " . trim($where_clause);
		}
	}
	// start the actual SQL statement
	$sql = "UPDATE " . $table_name . " SET ";
	// loop and build the column /
	$sets = array();
	foreach ($form_data as $column => $value) {
		$sets[] = "`" . $column . "` = '" . $value . "'";
	}
	$sql .= implode(', ', $sets);
	// append the where statement
	$sql .= $whereSQL;
	// run and return the query result
	return $db->query($sql) or die("cannot execute Update Query" . $db->error);
}
// For Deleting the Row the where clause is left optional incase the user wants to delete every row!
function dbRowDelete($db, $table_name, $where_clause = '')
{
	// check for optional where clause
	$whereSQL = '';
	if (!empty($where_clause)) {
		// check to see if the 'where' keyword exists
		if (substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
			// not found, add keyword
			$whereSQL = " WHERE " . $where_clause;
		} else {
			$whereSQL = " " . trim($where_clause);
		}
	}
	// build the query
	$sql = "DELETE FROM " . $table_name . $whereSQL;
	// run and return the query result resource
	return $db->query($sql) or die("cannot execute Delete statement" . $db->error);
}
