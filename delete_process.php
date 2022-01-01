<?php
session_start();
include("inc/connection.php");
include("inc/funcstuffs.php");

/// Check Login Session
if ($_SESSION["sadmin_username"] != "") {
    if ($_POST["deleteKey"] != "") {
        $delkey = preg_split("/\//", base64_decode($_POST["deleteKey"]));

        if ($delkey[1] == "deleteCustomerUser") {
            $tabname = "customer";
            $url = "managecustomer.php";

            if ($_POST["delid"] != "") {
                $sql = "delete from " . $tabname . " where id=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);
            }

            $_SESSION["slogin_Error"] = "Customer(s) Deleted Successfully.";
            print "<META http-equiv='refresh' content=0;URL=" . $url . ">";
            exit;
        }
        if ($delkey[1] == "deleteMachine") {
            $tabname = "machine";
            $url = "managemachine.php";

            if ($_POST["delid"] != "") {
                $sql = "delete from " . $tabname . " where id=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);
            }

            $_SESSION["slogin_Error"] = "Machine(s) Deleted Successfully.";
            print "<META http-equiv='refresh' content=0;URL=" . $url . ">";
            exit;
        }

        if ($delkey[1] == "deleteOperator") {
            $tabname = "operator";
            $url = "manageoperator.php";

            if ($_POST["delid"] != "") {
                $sql = "delete from " . $tabname . " where id=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);
            }

            $_SESSION["slogin_Error"] = "Operator(s) Deleted Successfully.";
            print "<META http-equiv='refresh' content=0;URL=" . $url . ">";
            exit;
        }

        if ($delkey[1] == "deleteJobNo") {
            $tabname = "joblist";
            $url = "managejob.php";

            if ($_POST["delid"] != "") {
                $sql = "delete from " . $tabname . " where id=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);
            }

            $_SESSION["slogin_Error"] = "Job No.(s) Deleted Successfully.";
            print "<META http-equiv='refresh' content=0;URL=" . $url . ">";
            exit;
        }


        if ($delkey[1] == "deleteProduction") {
            $tabname = "production_1";
            $tab1 = "production_2";
            $tab2 = "production_3";
            $tab3 = "production_machine_breakdown";
            $url = "manageproduction.php";

            if ($_POST["delid"] != "") {
                $sql = "delete from " . $tabname . " where id=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);

                $sql = "delete from " . $tab1 . " where production_1=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);

                $sql = "delete from " . $tab2 . " where production_1=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);

                $sql = "delete from " . $tab3 . " where production_1=" . $_POST["delid"];
                mysqli_query($db, $sql) or die(mysqli_error($db) . "<br>" . $sql);
            }

            $_SESSION["slogin_Error"] = "Operator(s) Deleted Successfully.";
            print "<META http-equiv='refresh' content=0;URL=" . $url . ">";
            exit;
        }
    } else {
        print "<META http-equiv='refresh' content=0;URL=" . $sitepath . "home/>";
        exit;
    }
} else {
    print "<META http-equiv='refresh' content=0;URL=" . $sitepath . ">";
}