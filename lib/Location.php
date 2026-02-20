<?php

class Location
{
  private static ?Location $instance = null;

  private ?float $lat = null;
  private ?float $lon = null;
  private ?string $address = null;

  private function __construct()
  {
    $this->refresh();
  }

  public static function getInstance(): Location
  {
    if (self::$instance === null)
      self::$instance = new self();
    return self::$instance;
  }

  public function setPosition(float $lat, float $lon): void
  {
    $this->lat = $lat;
    $this->lon = $lon;
    $this->address = null;

    Db::update(
      'users',
      [
        'latitude'  => $lat,
        'longitude' => $lon,
        'address'   => null
      ],
      'WHERE id = ?',
      Auth::userId()
    );
  }

  public function refresh(): void
  {
    $userId = Auth::userId();
    if (!$userId) return;
    $row = Db::queryOne(
      'SELECT latitude, longitude, city, postal_code, address FROM users WHERE id = ?',
      $userId
    );
    if (!$row) return;
    $this->lat = $row['latitude'] !== null ? (float)$row['latitude'] : null;
    $this->lon = $row['longitude'] !== null ? (float)$row['longitude'] : null;
    $this->address = $row['address'];
  }

  public function setByAddress(string $address): void
  {
    $geocodeData = $this->geocode($address);
    if (!$geocodeData) {
      throw new Exception("Unable to geocode address: $address");
    }

    $this->lat = $geocodeData['lat'];
    $this->lon = $geocodeData['lon'];
    $this->address = $geocodeData['display_name'];

    Db::update(
      'users',
      [
        'latitude'   => $this->lat,
        'longitude'  => $this->lon,
        'address'    => $this->address
      ],
      'WHERE id = ?',
      Auth::userId()
    );
  }

  private function geocode(string $query): ?array
  {
    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($query) . '&limit=1';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'FinalBossWeatherApp/1.0');
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (empty($data)) {
      return null;
    }

    $result = $data[0];
    return [
      'lat' => (float)$result['lat'],
      'lon' => (float)$result['lon'],
      'display_name' => $result['display_name'],
      'city' => $result['address']['city'] ?? $result['address']['town'] ?? $result['address']['village'] ?? null,
      'postal_code' => $result['address']['postcode'] ?? null
    ];
  }

  public function getLat(): ?float
  {
    return $this->lat;
  }

  public function getLon(): ?float
  {
    return $this->lon;
  }

  public function getAddress(): ?string
  {
    return $this->address;
  }

  public function hasCoords(): bool
  {
    return $this->lat !== null && $this->lon !== null;
  }
}
