<?php

class Location
{
  private static ?Location $instance = null;

  private ?float $lat = null;
  private ?float $lon = null;

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
      'SELECT latitude, longitude FROM users WHERE id = ?',
      $userId
    );
    if (!$row) return;
    $this->lat = $row['latitude'] !== null ? (float)$row['latitude'] : null;
    $this->lon = $row['longitude'] !== null ? (float)$row['longitude'] : null;
  }

  public function setPosition(float $lat, float $lon): void
  {
    $this->lat = $lat;
    $this->lon = $lon;

    Db::update(
      'users',
      [
        'latitude'  => $lat,
        'longitude' => $lon
      ],
      'WHERE id = ?',
      Auth::userId()
    );
  }

  public function getLat(): ?float
  {
    return $this->lat;
  }

  public function getLon(): ?float
  {
    return $this->lon;
  }

  public function hasCoords(): bool
  {
    return $this->lat !== null && $this->lon !== null;
  }
}
