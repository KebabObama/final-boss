<?php require_once __DIR__ . '/lib/Auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather IoT Portal</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style type="text/tailwindcss">
    .button {
      @apply px-4 py-2 rounded transition font-semibold border border-slate-600;
    }
  </style>
</head>

<body class="text-white min-h-screen font-sans bg-slate-800 overflow-hidden md:p-2">
  <nav class="px-2 h-16 flex justify-between items-center shadow-xl bg-slate-800">
    <h1 class="text-lg md:text-2xl font-bold tracking-tight text-blue-400">Final Boss XD</h1>
    <div class="flex items-center gap-4">
      <?php include './components/auth-controls.php'; ?>
    </div>
  </nav>
  <div class="md:border border-slate-700 w-full bg-slate-900 rounded-xl h-[calc(100dvh-4rem)] md:h-[calc(100dvh-5rem)] overflow-auto">
    <main class="flex flex-col gap-6 container mx-auto p-6">
      <?php
      if (Auth::isLoggedIn()):
        include './components/utility-bar.php';
        include './components/graphs.php';
      else:
        include './components/landing.php';
      endif;
      ?>
    </main>
  </div>

</body>

</html>