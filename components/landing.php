<section class="text-center py-20">
  <h2 class="text-6xl font-extrabold mb-6 bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">
    Track your micro-climate.
  </h2>
  <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
    Connect your IoT devices or use our OpenWeather integration to track atmospheric conditions
    specifically for your GPS coordinates.
  </p>
  <div class="inline-block bg-slate-800 p-8 rounded-2xl shadow-2xl border border-slate-700">
    <p class="text-slate-300 inline-block">Please
      <?php
      require_once __DIR__ . '/auth-forms.php';
      echo (new Popup('<button class="text-white font-bold cursor-pointer">Login</button>', $loginForm))->render() . ' or ' .
        (new Popup('<button class="text-white font-bold cursor-pointer">register</button>', $regForm))->render();
      ?>
      to view your personalized weather dashboard.</p>
  </div>
</section>