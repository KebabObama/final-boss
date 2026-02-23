<?php
$loginForm = <<<HTML
<form action="/api/auth/login" method="POST" class="flex flex-col gap-4 text-white">
  <h2 class="text-xl font-bold text-center">Sign In</h2>
  <input type="email" name="email" placeholder="Email" class="border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
  <input type="password" name="password" placeholder="Password" class="border p-2 rounded focus:ring-2 focus:ring-blue-400 outline-none" required>
  <button type="submit" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition font-bold shadow-md">
    Enter System
  </button>
</form>
HTML;

$regForm = <<<HTML
<form action="/api/auth/register" method="POST" id="regForm" class="flex flex-col gap-4 text-white">
  <div class="text-center">
    <h2 class="text-xl font-bold">Create Account</h2>
    <p class="text-xs text-slate-500 mt-1">Start tracking your weather data.</p>
  </div>
  <input type="email" name="email" placeholder="Email" class="border p-2 rounded focus:ring-2 focus:ring-green-400 outline-none" required>
  <input type="password" name="password" placeholder="Password" class="border p-2 rounded focus:ring-2 focus:ring-green-400 outline-none" required>
  <p class="text-[10px] text-slate-400 italic text-center px-4">
    Location settings can be configured in your dashboard after registration.
  </p>
  <button type="submit" class="bg-green-600 text-white p-2 rounded font-bold hover:bg-green-700 transition mt-2 shadow-md">
    Create Account
  </button>
</form>
HTML;
