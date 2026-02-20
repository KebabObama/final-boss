<?php
require_once __DIR__ . '/../lib/Popup.php';
require_once __DIR__ . '/../lib/Location.php';

$loc = Location::getInstance();
$currentLat = $loc->getLat();
$currentLon = $loc->getLon();
$currentCity = $loc->getCity();
$currentPostal = $loc->getPostalCode();
$currentAddress = $loc->getAddress();

$currentPostal = $currentPostal && strlen($currentPostal) == 5 ? substr($currentPostal, 0, 3) . ' ' . substr($currentPostal, 3) : $currentPostal;

$statusText = $loc->hasCoords()
  ? "Currently set to: <span class='text-blue-400 font-mono'>" . ($currentCity ? ($currentCity . ($currentPostal ? ', ' . $currentPostal : '')) : ($currentAddress ?: "$currentLat, $currentLon")) . "</span>"
  : "No location set. Weather data cannot be fetched.";

$btnHtml = <<<HTML
<button class="bg-slate-800 hover:bg-slate-600 text-white button gap-2 w-full text-center">
  Edit Location
</button>
HTML;

$formHtml = <<<HTML
<form action="/api/location" method="POST" class="flex flex-col gap-4 text-white">
  <div>
    <h2 class="text-xl font-bold">Location Settings</h2>
    <p class="text-xs text-slate-500">Enter coordinates or an address to track local weather.</p>
  </div>

  <div class="flex gap-2">
    <div class="flex-1">
      <label class="text-[10px] uppercase font-bold text-slate-400">Latitude</label>
      <input type="number" step="any" name="lat" id="set-lat" value="{$currentLat}" placeholder="e.g. 49.19" class="border p-2 rounded w-full">
    </div>
    <div class="flex-1">
      <label class="text-[10px] uppercase font-bold text-slate-400">Longitude</label>
      <input type="number" step="any" name="lon" id="set-lon" value="{$currentLon}" placeholder="e.g. 16.60" class="border p-2 rounded w-full">
    </div>
  </div>

  <div>
    <label class="text-[10px] uppercase font-bold text-slate-400">Address / City / Postal Code</label>
    <input type="text" name="address" id="set-address" placeholder="e.g. Brno, Czech Republic or 60200" class="border p-2 rounded w-full">
  </div>

  <button type="button" id="auto-detect-btn" class="text-xs text-blue-600 font-semibold hover:underline text-left">
    Detect current position
  </button>

  <button type="submit" class="bg-blue-600 text-white p-2 rounded font-bold hover:bg-blue-700 transition shadow-lg">
    Save Changes
  </button>
</form>

<script>
    document.getElementById('auto-detect-btn').addEventListener('click', () => {
      const btn = document.querySelector('#auto-detect-btn');
      btn.innerText = "Detecting...";
      navigator.geolocation.getCurrentPosition((pos) => {
          document.getElementById('set-lat').value = pos.coords.latitude;
          document.getElementById('set-lon').value = pos.coords.longitude;
          btn.innerText = "Detected!";
          btn.className = "text-xs text-green-600 font-semibold";
        },
        (err) => {
          btn.innerText = `Error: \${err.message}`;
          console.error(err);
        }
      );
    });
</script>
HTML;

$locationPopup = new Popup($btnHtml, $formHtml);
?>

<div class="bg-slate-800 p-4 gap-4 rounded-xl border border-slate-700 flex flex-col md:flex-row justify-between items-center">
  <p class="text-sm text-slate-300"><?= $statusText ?></p>
  <div class="grid grid-cols-2 w-full md:w-auto gap-4">
    <?= $locationPopup->render() ?>
    <button
      <?= $loc->hasCoords() ? "" : "disabled" ?>
      onclick="window.location.href = '/api/weather'"
      class="disabled:opacity-20 disabled:cursor-not-allowed bg-slate-800 hover:bg-slate-600 button">
      Refresh
    </button>
  </div>
</div>