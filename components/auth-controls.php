<?php
require_once __DIR__ . '/../lib/Popup.php';
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/auth-forms.php';

if (Auth::isLoggedIn()): ?>
  <span class="text-slate-400 text-xs md:text-sm italic">Logged in as <?= htmlspecialchars(Auth::userEmail()) ?></span>
  <a href="/api/auth/logout" class="bg-red-500 hover:bg-red-600 button">Logout</a>
<?php else: ?>
  <div class="flex gap-4 items-center">
    <?= (new Popup('<button class="text-slate-300 hover:text-white button">Login</button>', $loginForm))->render(); ?>
    <?= (new Popup('<button class="bg-blue-600 hover:bg-blue-700 button">Join Now</button>', $regForm))->render(); ?>
  </div>
<?php endif;
