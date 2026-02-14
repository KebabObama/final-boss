<?php

Db::connect(
  getenv('DB_HOST') ?: 'db',
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS')
);

class Weather
{
  public static function findById(int $id): ?array
  {
    $result = Db::queryOne(
      "SELECT *
        FROM weather
        WHERE id = ?",
      $id
    );

    return $result ?: null;
  }

  public static function insert(array $data): int
  {
    $userId = Auth::userId();
    if (empty($userId)) return -1;

    Db::insert('weather', [
      'user_id' => $userId,

      'latitude'  => $data['coord']['lat'] ?? null,
      'longitude' => $data['coord']['lon'] ?? null,

      'base' => $data['base'] ?? null,
      'clouds' => $data['clouds']['all'] ?? null,
      'visibility' => $data['visibility'] ?? null,
      'sunrise' => $data['sys']['sunrise'] ?? null,
      'sunset'  => $data['sys']['sunset'] ?? null,
      'temp'         => $data['main']['temp'] ?? null,
      'feels_like'   => $data['main']['feels_like'] ?? null,
      'temp_min'     => $data['main']['temp_min'] ?? null,
      'temp_max'     => $data['main']['temp_max'] ?? null,
      'pressure'     => $data['main']['pressure'] ?? null,
      'humidity'     => $data['main']['humidity'] ?? null,
      'sea_level'    => $data['main']['sea_level'] ?? null,
      'ground_level' => $data['main']['grnd_level'] ?? null,

      'wind_speed' => $data['wind']['speed'] ?? null,
      'wind_deg'   => $data['wind']['deg'] ?? null,
      'wind_gust'  => $data['wind']['gust'] ?? null,
    ]);

    return (int) Db::getLastId();
  }

  public static function fetchAllByUser(): array
  {
    $userId = Auth::userId();
    if (empty($userId)) return [];

    return Db::queryAll(
      "SELECT *
        FROM weather
        WHERE user_id = ?
        ORDER BY created_at DESC",
      $userId
    ) ?: [];
  }

  public static function getLatest(): ?array
  {
    $userId = Auth::userId();
    if (empty($userId)) return null;

    $result = Db::queryOne(
      "SELECT *
        FROM weather
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 1",
      $userId
    );

    return $result ?: null;
  }
}
