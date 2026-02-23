<?php if (isset($_GET['error'])): ?>
  <div id="auth-alert" class="fixed bottom-5 left-5 z-50 w-80 rounded-lg bg-slate-800 border border-gray-200 shadow-2xl transition-all duration-500 overflow-hidden transform translate-y-0">
    <div class="p-4 flex items-start">
      <div class="flex-shrink-0">
        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <div class="ml-3 pr-4">
        <p class="text-xs font-extrabold uppercase tracking-wider text-gray-300">Error</p>
        <p class="mt-1 text-sm font-semibold text-gray-500"><?= htmlspecialchars($_GET['error']) ?></p>
      </div>
    </div>

    <div class="h-1 w-full bg-gray-100">
      <div id="progress-bar" class="h-full bg-red-500 w-full transition-all duration-[5000ms] ease-linear"></div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const path = window.location.protocol + "//" + window.location.host + window.location.pathname;
      setTimeout(() => document.getElementById('progress-bar').style.width = '0%', 50);
      setTimeout(() => setTimeout(() => document.getElementById('auth-alert').remove(), 500), 5000);
      window.history.replaceState({
        path
      }, '', path);
    });
  </script>
<?php endif; ?>