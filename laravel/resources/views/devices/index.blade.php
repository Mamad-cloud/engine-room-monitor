@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-6">
  <aside class="col-span-3">
    <div class="bg-white rounded shadow p-4">
      <h3 class="font-semibold mb-2">Devices</h3>
      <div id="device-list" class="space-y-2">
        @forelse($devices as $dev)
          <button data-device-id="{{ $dev->device_id }}" data-device-name="{{ $dev->name }}"
            class="device-item w-full text-left p-2 rounded border hover:bg-gray-50"
            onclick="selectDevice('{{ $dev->device_id }}')">
            <div class="text-sm font-medium">{{ $dev->name }}</div>
            <div class="text-xs text-gray-500">{{ $dev->device_id }}</div>
          </button>
        @empty
          <div class="text-sm text-gray-500">No devices yet</div>
        @endforelse
      </div>

      <hr class="my-3">

      <form id="create-device-form" method="POST" action="{{ route('devices.store') }}">
        @csrf
        <label class="block text-xs text-gray-600">Name</label>
        <input name="name" required class="w-full p-2 border rounded mb-2" placeholder="Device name">

        <label class="block text-xs text-gray-600">Device ID (optional)</label>
        <input name="device_id" class="w-full p-2 border rounded mb-2" placeholder="device-uuid or serial">

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Create device</button>
      </form>
    </div>
  </aside>

  <section class="col-span-9">
    <div class="bg-white rounded shadow p-4">
      <div class="flex items-center justify-between mb-4">
        <h2 id="selected-name" class="text-lg font-semibold">Select a device</h2>
        <div id="realtime-status" class="text-sm text-gray-500">Realtime: disconnected</div>
      </div>

      <div class="grid grid-cols-3 gap-4">
        @foreach($devices as $dev)
          <div id="card-{{ $dev->device_id }}" class="col-span-1 bg-slate-50 p-3 rounded shadow-sm device-card">
            <div class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $dev->name }}</div>
                <div class="text-xs text-gray-500">{{ $dev->device_id }}</div>
              </div>
              <div class="text-xs text-gray-400">last: <span class="last-ts">—</span></div>
            </div>

            <div class="mt-3 scroll-area">
              <pre class="text-xs text-left bg-white p-2 rounded message-content">No messages</pre>
            </div>

            <div class="mt-3">
              <form onsubmit="sendCommand(event, '{{ $dev->_id ?? $dev->id ?? $dev->device_id }}', '{{ $dev->device_id }}')">
                <input id="cmd-{{ $dev->device_id }}" placeholder="command" class="w-full p-2 border rounded text-sm" />
                <button type="submit" class="mt-2 w-full bg-green-600 text-white p-2 rounded text-sm">Send</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>
</div>

<script>
  // CSRF token for fetch
//   const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const CSRF_TOKEN = '{{ csrf_token() }}';

  // Pusher/Soketi configuration (from env via blade)
  const PUSHER_KEY = "{{ config('broadcasting.pusher.key') ?? env('PUSHER_APP_KEY') }}";
  const PUSHER_HOST = "{{ env('PUSHER_HOST', 'soketi') }}".replace(/^https?:\/\//,'');
  const PUSHER_PORT = {{ (int) env('PUSHER_PORT', 6001) }};
  const PUSHER_SCHEME = "{{ env('PUSHER_SCHEME', 'http') }}";

  // map device_id -> array messages
  const messages = {};

  // UI helpers
  function setStatus(text) {
    document.getElementById('realtime-status').textContent = 'Realtime: ' + text;
  }

  function updateCard(deviceId, payload) {
    try {
      const el = document.getElementById('card-' + deviceId);
      if (!el) return;
      const tsEl = el.querySelector('.last-ts');
      if (payload.ts) tsEl.textContent = new Date(payload.ts).toLocaleTimeString();
      else tsEl.textContent = new Date().toLocaleTimeString();

      const msgEl = el.querySelector('.message-content');
      const arr = messages[deviceId] ?? [];
      arr.unshift(payload); // newest first (prepending)
      // limit history
      if (arr.length > 20) arr.splice(20);
      messages[deviceId] = arr;

      // show compact JSON
      msgEl.textContent = JSON.stringify(arr.slice(0, 5), null, 2);
    } catch (e) {
      console.error(e);
    }
  }

  function selectDevice(deviceId) {
    // highlight selection
    document.querySelectorAll('.device-item').forEach(b => b.classList.remove('ring-2','ring-blue-300'));
    const btn = document.querySelector(`[data-device-id="${deviceId}"]`);
    if (btn) btn.classList.add('ring-2','ring-blue-300');

    const name = btn?.dataset?.deviceName || deviceId;
    document.getElementById('selected-name').textContent = name;
  }

  // send command to device: deviceDbId is your DB id (we allow using _id or id), deviceId is device.device_id
  async function sendCommand(e, deviceDbId, deviceId) {
    e.preventDefault();
    const input = document.getElementById('cmd-' + deviceId);
    const command = input.value.trim();
    if (!command) return alert('command required');

    const url = `/devices/${deviceDbId}/commands`;
    const resp = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ command })
    });

    if (!resp.ok) {
      const txt = await resp.text();
      alert('Send failed: ' + resp.status + ' ' + txt);
      return;
    }
    input.value = '';
  }

  // Initialize Pusher
  (function initRealtime() {
    if (!PUSHER_KEY) {
      setStatus('disabled');
      return;
    }

    try {
      // configure Pusher for Soketi
      const pusher = new Pusher(PUSHER_KEY, {
        cluster: 'mt1',
        forceTLS: (PUSHER_SCHEME === 'https'),
        wsHost: PUSHER_HOST,
        wsPort: PUSHER_PORT,
        wssPort: PUSHER_PORT,
        enabledTransports: ['ws','wss'],
        disableStats: true,
        // host/port are used for soketi
        encrypted: (PUSHER_SCHEME === 'https'),
      });

      setStatus('connected');

      // subscribe to global device announcements
      const devs = pusher.subscribe('devices');
      devs.bind('device-registered', data => {
        // simple approach: reload page to pick up new device
        // you can enhance to insert the new card dynamically
        console.log('Device registered', data);
        location.reload();
      });

      // subscribe per-device channels for all devices on the page
      document.querySelectorAll('.device-card').forEach(card => {
        const id = card.id.replace('card-', '');
        const ch = pusher.subscribe('device.' + id);

        ch.bind('sensor-data', payload => {
          console.log('sensor-data', id, payload);
          updateCard(id, payload);
        });

        ch.bind('command-sent', payload => {
          // command-sent: show ack or last command
          console.log('command-sent', id, payload);
          updateCard(id, { ts: payload.ts || new Date().toISOString(), sensors: { __last_cmd: payload } , raw: payload });
        });
      });

      // optional: connection events
      pusher.connection.bind('connected', () => setStatus('connected'));
      pusher.connection.bind('disconnected', () => setStatus('disconnected'));
      pusher.connection.bind('error', (err) => setStatus('error'));

    } catch (e) {
      console.error('Realtime init error', e);
      setStatus('error');
    }
  })();
</script>
@endsection
