<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Engine Monitor') }}</title>

  <!-- Tailwind CDN (quick) — replace with your compiled CSS for production -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Pusher JS (client) -->
  <script src="https://js.pusher.com/8.0/pusher.min.js"></script>

  <style>
    /* small scrollbar style for message area */
    .scroll-area { overflow-y: auto; max-height: 40vh; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div id="app" class="min-h-screen">
    <nav class="bg-white shadow p-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="font-semibold">{{ config('app.name', 'Engine Monitor') }}</div>
        <div class="text-sm text-gray-500">Realtime MCUs — Laravel + Soketi</div>
      </div>
    </nav>

    <main class="py-6">
      <div class="max-w-7xl mx-auto px-4">
        @yield('content')
      </div>
    </main>
  </div>
</body>
</html>
