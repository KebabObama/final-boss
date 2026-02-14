<?php
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/Db.php';
require_once __DIR__ . '/../lib/Location.php';

if (!Auth::isLoggedIn()) {
  header("Location: /?error=Unauthorized");
  exit;
}

$lat = isset($_POST['lat']) ? (float)$_POST['lat'] : null;
$lon = isset($_POST['lon']) ? (float)$_POST['lon'] : null;

if ($lat !== null && $lon !== null) {
  try {
    Location::getInstance()->setPosition($lat, $lon);
    header("Location: /?success=location_updated");
  } catch (Exception $e) {
    header("Location: /?error=" . urlencode($e->getMessage()));
  }
} else {
  header("Location: /?error=Invalid_coordinates");
}
exit;
