<?php
$tyyppi = "video";
$kayttajahaku = $con->prepare("SELECT * FROM penkkarit2019_kuvat WHERE tyyppi = ?");
if( $kayttajahaku &&
    $kayttajahaku->bind_param("s", $tyyppi) &&
    $kayttajahaku->execute() &&
    $kayttajahakutulos = $kayttajahaku->get_result()
  ) {
    foreach ($kayttajahakutulos as $row) {
        $kayttajavuosikerta = $row['lisaaja'];
        $kayttajavuosikerta = mysqli_real_escape_string($con,$kayttajavuosikerta);
    }

} else {
    echo "All rights reserved";
}
$kayttajahaku->close();
$con->close();
?>