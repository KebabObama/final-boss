<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/Db.php';
require_once __DIR__ . '/../lib/Location.php';
require_once __DIR__ . '/../lib/Weather.php';

if (!Auth::isLoggedIn()) {
  http_response_code(401);
  exit;
}

$location = Location::getInstance();

if (!$location->hasCoords()) {
  http_response_code(400);
  exit;
}

$url = sprintf(
  'https://api.openweathermap.org/data/2.5/weather?lat=%s&lon=%s&units=metric&appid=%s',
  $location->getLat(),
  $location->getLon(),
  getenv("WEATHER_KEY"),
);

$response = file_get_contents($url);
if ($response === false) {
  http_response_code(500);
  exit;
}

$data = json_decode($response, true);

if (!$data || ($data['cod'] ?? 500) != 200) {
  http_response_code(500);
  exit;
}

$temp = $data['main']['temp'] ?? null;
$humidity = $data['main']['humidity'] ?? null;
$pressure = $data['main']['pressure'] ?? null;
$description = $data['weather'][0]['description'] ?? null;

if ($temp === null || $humidity === null || $pressure === null) {
  http_response_code(500);
  exit;
}

Weather::insert($data);

header("Location: /");
