<?php

class Location
{
  private static ?Location $instance = null;

  private ?float $lat = null;
  private ?float $lon = null;
  private ?string $city = null;
  private ?string $postalCode = null;
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
    $this->city = $row['city'];
    $this->postalCode = $row['postal_code'];
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
    $this->city = $geocodeData['city'];
    $this->postalCode = $geocodeData['postal_code'];
    $this->address = $geocodeData['display_name'];

    Db::update(
      'users',
      [
        'latitude'   => $this->lat,
        'longitude'  => $this->lon,
        'city'       => $this->city,
        'postal_code' => $this->postalCode,
        'address'    => $this->address
      ],
      'WHERE id = ?',
      Auth::userId()
    );
  }

  public function setPosition(float $lat, float $lon): void
  {
    $this->lat = $lat;
    $this->lon = $lon;
    $this->city = null;
    $this->postalCode = null;
    $this->address = null;

    Db::update(
      'users',
      [
        'latitude'  => $lat,
        'longitude' => $lon,
        'city'      => null,
        'postal_code' => null,
        'address'   => null
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
      'postal_code' => isset($result['address']['postcode']) ? str_replace(' ', '', $result['address']['postcode']) : null
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

  public function getCity(): ?string
  {
    return $this->city;
  }

  public function getPostalCode(): ?string
  {
    return $this->postalCode;
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
