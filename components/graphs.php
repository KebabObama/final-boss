<?php
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/Weather.php';

$records = Weather::fetchAllByUser();
$latest = $records[0] ?? null;
$records = array_reverse($records);

$feels = [];
$tempMin = [];
$tempMax = [];
$clouds = [];
$windSpeed = [];
$windGust = [];
$visibility = [];

foreach ($records as $row) {
  $labels[]      = $row['created_at'];
  $temps[]       = $row['temp'];
  $feels[]       = $row['feels_like'];
  $tempMin[]     = $row['temp_min'];
  $tempMax[]     = $row['temp_max'];
  $humidity[]    = $row['humidity'];
  $pressure[]    = $row['pressure'];
  $clouds[]      = $row['clouds'];
  $windSpeed[]   = $row['wind_speed'];
  $windGust[]    = $row['wind_gust'];
  $visibility[]  = $row['visibility'];
}

if ($latest):
  $metrics = [
    ['label' => 'Temperature', 'key' => 'temp',       'unit' => '°C'],
    ['label' => 'Humidity',    'key' => 'humidity',   'unit' => '%'],
    ['label' => 'Pressure',    'key' => 'pressure',   'unit' => 'hPa'],
    ['label' => 'Cloudiness',  'key' => 'clouds',     'unit' => '%'],
    ['label' => 'Wind Speed',  'key' => 'wind_speed', 'unit' => 'm/s'],
    ['label' => 'Last Update', 'key' => 'created_at', 'unit' => '']
  ];
?>
  <section class="grid w-full grid-cols-2 md:grid-cols-3 gap-6 p-6 border border-slate-700 rounded-2xl shadow-xl bg-slate-800">

    <?php foreach ($metrics as $metric): ?>
      <div class="flex flex-col gap-1">
        <span class="text-slate-400 text-xs uppercase tracking-wider font-semibold">
          <?= $metric['label'] ?>
        </span>
        <div class="flex items-baseline gap-1">
          <span class="text-2xl font-bold text-white">
            <?= htmlspecialchars($latest[$metric['key']]) ?>
          </span>
          <span class="text-slate-400"><?= $metric['unit'] ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </section>
<?php endif; ?>

<canvas id="weatherChart"></canvas>

<script>
  const chart = new Chart(document.getElementById('weatherChart'), {
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [

        {
          type: 'line',
          label: 'average temperature (°C)',
          data: <?= json_encode($temps) ?>,
          yAxisID: 'yTemp',
          tension: 0.3,
          borderWidth: 2,
        },
        {
          type: 'line',
          label: 'Feel temperature (°C)',
          data: <?= json_encode($feels) ?>,
          yAxisID: 'yTemp',
          tension: 0.3,
          borderDash: [5, 5],
        },
        {
          type: 'line',
          label: 'Min temperature (°C)',
          data: <?= json_encode($tempMin) ?>,
          yAxisID: 'yTemp',
          tension: 0.3,
          borderDash: [2, 2],
        },
        {
          type: 'line',
          label: 'Max temperature (°C)',
          data: <?= json_encode($tempMax) ?>,
          yAxisID: 'yTemp',
          tension: 0.3,
        },

        {
          type: 'bar',
          label: 'Humidity (%)',
          data: <?= json_encode($humidity) ?>,
          yAxisID: 'yPercent',
        },
        {
          type: 'bar',
          label: 'Clouds (%)',
          data: <?= json_encode($clouds) ?>,
          yAxisID: 'yPercent',
        },

        {
          type: 'line',
          label: 'Pressure (hPa)',
          data: <?= json_encode($pressure) ?>,
          yAxisID: 'yPressure',
          tension: 0.3,
        },

        {
          type: 'line',
          label: 'Wind speed (m/s)',
          data: <?= json_encode($windSpeed) ?>,
          yAxisID: 'yWind',
          tension: 0.3,
        }

      ]
    },
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false
      },
      stacked: true,
      scales: {
        yTemp: {
          type: 'linear',
          position: 'left',
          title: {
            display: true,
            text: 'Temperature (°C)',
          }
        },

        yPercent: {
          type: 'linear',
          position: 'right',
          title: {
            display: true,
            text: '%',
          },
        },

        yPressure: {
          type: 'linear',
          position: 'right',
          title: {
            display: true,
            text: 'hPa',
          },
        },

        yWind: {
          type: 'linear',
          position: 'right',
          title: {
            display: true,
            text: 'm/s',
          },
        },
      }
    }
  });
</script>