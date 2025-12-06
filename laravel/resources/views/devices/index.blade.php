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

        <form id="create-device-form" method="post" action="{{ route('devices.store') }}">
          @csrf
          <input type="hidden" name="engine_room_id" value="{{ $engine_room_id ?? '' }}" />
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
          <div>
            <h2 id="selected-name" class="text-lg font-semibold">
              @if(!empty($engine_room_id))
                Devices for Engine Room: <span class="font-medium text-sm">{{ $engine_room_id }}</span>
              @else
                Select a device
              @endif
            </h2>
          </div>
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
                <form
                  onsubmit="sendCommand(event, '{{ $dev->_id ?? $dev->id ?? $dev->device_id }}', '{{ $dev->device_id }}')">
                  <input id="cmd-{{ $dev->device_id }}" placeholder="command" class="w-full p-2 border rounded text-sm" />
                  <button type="submit" class="mt-2 w-full bg-green-600 text-white p-2 rounded text-sm">Send</button>
                </form>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-6 bg-white rounded shadow p-4">
          <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Recent Messages ({{ $messages->count() }})</h3>
            <div class="text-sm text-gray-500">Showing latest {{ $messages->count() }} messages</div>
          </div>

          <div class="overflow-x-auto">
            <table id="messages-table" class="min-w-full text-sm">
              <thead>
                <tr class="bg-gray-100 text-left">
                  <th class="px-3 py-2">Time</th>
                  <th class="px-3 py-2">Device ID</th>
                  <th class="px-3 py-2">Seq</th>
                  <th class="px-3 py-2">Sensors</th>
                  <th class="px-3 py-2">Raw</th>
                </tr>
              </thead>
              <tbody id="messages-body" class="divide-y">
                @foreach($messages as $msg)
                  <tr data-device="{{ $msg->device_id }}" data-ts="{{ $msg->ts }}">
                    <td class="px-3 py-2 align-top">{{ \Carbon\Carbon::parse($msg->ts)->toDateTimeString() }}</td>
                    <td class="px-3 py-2 align-top">{{ $msg->device_id }}</td>
                    <td class="px-3 py-2 align-top">{{ $msg->seq ?? '—' }}</td>
                    <td class="px-3 py-2 align-top">
                      <pre class="whitespace-pre-wrap break-words text-xs bg-white p-2 rounded" style="max-width:360px;">{{ json_encode($msg->sensors ?? [], JSON_PRETTY_PRINT) }}</pre>
                    </td>
                    <td class="px-3 py-2 align-top">
                      <button type="button" class="toggle-raw btn text-xs p-1 border rounded" data-target="raw-{{ $loop->index }}">Toggle</button>
                      <pre id="raw-{{ $loop->index }}" class="mt-1 text-xs bg-black text-green-200 p-2 rounded hidden" style="max-width:420px; overflow:auto;">{{ json_encode($msg->raw ?? [], JSON_PRETTY_PRINT) }}</pre>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <script>
          document.addEventListener('click', function (e) {
            const t = e.target;
            if (t.classList.contains('toggle-raw')) {
              const id = t.getAttribute('data-target');
              const el = document.getElementById(id);
              if (!el) return;
              el.classList.toggle('hidden');
            }
          });
        </script>

      </div>
    </section>
  </div>

  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

  <script>
    // debug ping
    fetch('/api/hello').then(res => res.json()).then(data => console.log('hello:', data)).catch(()=>{});

    const CSRF_TOKEN = '{{ csrf_token() }}';
    const PUSHER_KEY = "{{ config('broadcasting.pusher.key') ?? env('PUSHER_APP_KEY') }}";
    const PUSHER_PORT = {{ (int) env('PUSHER_PORT', 6001) }};
    const PUSHER_SCHEME = "{{ env('PUSHER_SCHEME', 'http') }}";
    const ENGINE_ROOM_ID = "{{ $engine_room_id ?? '' }}";

    const messages = {};

    function setStatus(text) {
      const el = document.getElementById('realtime-status');
      if (el) el.textContent = 'Realtime: ' + text;
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
        arr.unshift(payload);
        if (arr.length > 20) arr.splice(20);
        messages[deviceId] = arr;

        if (msgEl) {
          msgEl.textContent = JSON.stringify(arr.slice(0, 5), null, 2);
        }
      } catch (e) {
        console.error(e);
      }
    }

    function addMessageRow(payload) {
      try {
        const tbody = document.getElementById('messages-body');
        if (!tbody) return;
        const uid = 'm_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
        const ts = payload.ts ? new Date(payload.ts).toLocaleString() : new Date().toLocaleString();
        const seq = payload.seq ?? '—';
        const sensors = JSON.stringify(payload.sensors ?? {}, null, 2);
        const raw = JSON.stringify(payload.raw ?? payload, null, 2);
        const tr = document.createElement('tr');
        tr.setAttribute('data-device', payload.device_id ?? '');
        tr.setAttribute('data-ts', payload.ts ?? '');
        tr.innerHTML = `
          <td class="px-3 py-2 align-top">${escapeHtml(ts)}</td>
          <td class="px-3 py-2 align-top">${escapeHtml(payload.device_id ?? '')}</td>
          <td class="px-3 py-2 align-top">${escapeHtml(seq)}</td>
          <td class="px-3 py-2 align-top">
            <pre class="whitespace-pre-wrap break-words text-xs bg-white p-2 rounded" style="max-width:360px;">${escapeHtml(sensors)}</pre>
          </td>
          <td class="px-3 py-2 align-top">
            <button type="button" class="toggle-raw btn text-xs p-1 border rounded" data-target="${uid}">Toggle</button>
            <pre id="${uid}" class="mt-1 text-xs bg-black text-green-200 p-2 rounded hidden" style="max-width:420px; overflow:auto;">${escapeHtml(raw)}</pre>
          </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);
        const MAX = 50;
        while (tbody.children.length > MAX) {
          tbody.removeChild(tbody.lastChild);
        }
      } catch (e) {
        console.error('addMessageRow error', e);
      }
    }

    function escapeHtml(str) {
      if (str === undefined || str === null) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    }

    function selectDevice(deviceId) {
      document.querySelectorAll('.device-item').forEach(b => b.classList.remove('ring-2', 'ring-blue-300'));
      const btn = document.querySelector(`[data-device-id="${deviceId}"]`);
      if (btn) btn.classList.add('ring-2', 'ring-blue-300');
      const name = btn?.dataset?.deviceName || deviceId;
      const selectedNameEl = document.getElementById('selected-name');
      if (selectedNameEl) selectedNameEl.textContent = name;
    }

    async function sendCommand(e, deviceDbId, deviceId) {
      e.preventDefault();
      const input = document.getElementById('cmd-' + deviceId);
      const command = input.value.trim();
      if (!command) return alert('command required');

      const url = `/api/devices/${deviceDbId}/commands`;
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

    (function initRealtime() {
      if (!PUSHER_KEY) {
        setStatus('disabled');
        return;
      }

      try {
        if (window.Pusher) {
          window.Pusher.logToConsole = false;
        }

        const pusher = new Pusher(PUSHER_KEY, {
          cluster: 'mt1',
          wsHost: window.location.hostname,
          wsPort: PUSHER_PORT,
          wssPort: PUSHER_PORT,
          forceTLS: (PUSHER_SCHEME === 'https'),
          enabledTransports: ['ws', 'wss'],
          disableStats: true,
          encrypted: (PUSHER_SCHEME === 'https'),
        });

        setStatus('connected');

        // global devices announcements (optional)
        const devs = pusher.subscribe('devices');
        devs.bind('device-registered', data => {
          console.log('Device registered', data);
          location.reload();
        });

        // subscribe to engine-room channel if present
        if (ENGINE_ROOM_ID) {
          const er = pusher.subscribe('engine-room.' + ENGINE_ROOM_ID);
          er.bind('sensor-data', payload => {
            try {
              // payload should contain device_id
              updateCard(payload.device_id, payload);
              addMessageRow(payload);
            } catch (e) { console.error(e); }
          });
          er.bind('command-sent', payload => {
            try {
              updateCard(payload.device_id, { ts: payload.ts || new Date().toISOString(), sensors: { __last_cmd: payload }, raw: payload });
              addMessageRow({ device_id: payload.device_id, ts: payload.ts || new Date().toISOString(), seq: null, sensors: { __last_cmd: payload }, raw: payload });
            } catch (e) { console.error(e); }
          });
        }

        // per-device fallback subscriptions (for pages with few devices)
        document.querySelectorAll('.device-card').forEach(card => {
          const id = card.id.replace('card-', '');
          const ch = pusher.subscribe('device.' + id);

          ch.bind('sensor-data', payload => {
            try {
              updateCard(id, payload);
              addMessageRow(payload);
            } catch (e) { console.error('sensor-data handler error', e); }
          });

          ch.bind('command-sent', payload => {
            try {
              updateCard(id, { ts: payload.ts || new Date().toISOString(), sensors: { __last_cmd: payload }, raw: payload });
              addMessageRow({ device_id: id, ts: payload.ts || new Date().toISOString(), seq: null, sensors: { __last_cmd: payload }, raw: payload });
            } catch (e) { console.error('command-sent handler error', e); }
          });
        });

        pusher.connection.bind('connected', () => setStatus('connected'));
        pusher.connection.bind('disconnected', () => setStatus('disconnected'));
        pusher.connection.bind('error', (err) => { console.error('Pusher error', err); setStatus('error'); });

      } catch (e) {
        console.error('Realtime init error', e);
        setStatus('error');
      }
    })();
  </script>
@endsection
