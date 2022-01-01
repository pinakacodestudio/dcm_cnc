<?php
session_start();
include("inc/connection.php");
require("inc/funcstuffs.php");

$tabname = "joblist";
$tab2 = "production_3";

$query = "select * from $tab2 where id!=0 order by id ";
$rs = mysqli_query($db, $query) or die('Unknown error ' . mysqli_error($db));

$i = 0;
while ($row = $rs->fetch_assoc()) {
    $i++;

    $total_q_after_rejection = $row["total_q_after_rejection"];
    $expected_q = $row["expected_q"];
    $production_loss_increase_q = $total_q_after_rejection - $expected_q;

    $production_per = 0;
    if ($production_loss_increase_q != 0) {
        $production_per = ($total_q_after_rejection * 100) / $expected_q;
    }
    echo "<br>" . $total_q_after_rejection . " | " . $expected_q . " | " . $production_loss_increase_q . " | " . $production_per;

    $form_data = array(
        'production_loss_increase_q' => $production_loss_increase_q,
        'production_per' => $production_per
    );
    // Insertion of Data
    dbRowUpdate($db, $tab2, $form_data, "where id=" . $row["id"]);
}

// $query = "select $tab2.id,$tabname.id as jobid from $tab2 LEFT JOIN $tabname ON $tab2.job_no = $tabname.jobno where $tab2.job_no != '0' ";
// $rs = mysqli_query($db, $query) or die('Unknown error ' . mysqli_error($db));

// while ($row = $rs->fetch_assoc()) {
//     echo $jobid = $row["jobid"];
//     echo "<br>";
//     $form_data = array(
//         'job_no' => $jobid
//     );
//         // Insertion of Data
//     dbRowUpdate($db, $tab2, $form_data, "where id=" . $row["id"]);

// }



?>
