<?php
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/Weather.php';

$records = Weather::fetchAllByUserAndCurrentPosition();

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

if (isset($records[0])):
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
            <?= htmlspecialchars($records[0][$metric['key']]) ?>
          </span>
          <span class="text-slate-400"><?= $metric['unit'] ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </section>
<?php endif; ?>


<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
  <div>
    <canvas class="w-full" id="tempChart"></canvas>
  </div>
  <div>
    <canvas class="w-full" id="humidityChart"></canvas>
  </div>
  <div>
    <canvas class="w-full" id="windChart"></canvas>
  </div>
</div>

<script>
  new Chart(document.getElementById('tempChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
          label: 'Average (°C)',
          data: <?= json_encode($temps) ?>,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59,130,246,0.1)',
          tension: 0.3,
          borderWidth: 2,
        },
        {
          label: 'Feels Like (°C)',
          data: <?= json_encode($feels) ?>,
          borderColor: '#f59e42',
          backgroundColor: 'rgba(245,158,66,0.1)',
          tension: 0.3,
          borderDash: [5, 5],
        },
        {
          label: 'Min (°C)',
          data: <?= json_encode($tempMin) ?>,
          borderColor: '#10b981',
          backgroundColor: 'rgba(16,185,129,0.1)',
          tension: 0.3,
          borderDash: [2, 2],
        },
        {
          label: 'Max (°C)',
          data: <?= json_encode($tempMax) ?>,
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239,68,68,0.1)',
          tension: 0.3,
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        y: {
          title: {
            display: true,
            text: 'Temperature (°C)'
          }
        }
      }
    }
  });

  new Chart(document.getElementById('humidityChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
          label: 'Humidity (%)',
          data: <?= json_encode($humidity) ?>,
          backgroundColor: '#3b82f6',
        },
        {
          label: 'Clouds (%)',
          data: <?= json_encode($clouds) ?>,
          backgroundColor: '#64748b',
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        y: {
          title: {
            display: true,
            text: '%'
          }
        }
      }
    }
  });

  new Chart(document.getElementById('windChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
          label: 'Wind Speed (m/s)',
          data: <?= json_encode($windSpeed) ?>,
          borderColor: '#6366f1',
          backgroundColor: 'rgba(99,102,241,0.1)',
          tension: 0.3,
        },
        {
          label: 'Wind Gust (m/s)',
          data: <?= json_encode($windGust) ?>,
          borderColor: '#f43f5e',
          backgroundColor: 'rgba(244,63,94,0.1)',
          tension: 0.3,
          borderDash: [4, 4],
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true
        }
      },
      scales: {
        y: {
          title: {
            display: true,
            text: 'm/s'
          }
        }
      }
    }
  });
</script>