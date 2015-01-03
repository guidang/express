<?php
/**
 * company data from sqlite to array
 * @created:  Skiychan.
 * @date:  1/3/15
 * @modified:
 */

$dbh = new PDO("sqlite:data.dat");
$results = $dbh->query('SELECT * FROM kuaidi')->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);