@extends('masterpage')

@section('content')
<div class="max-w-7xl mx-auto p-4">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-semibold">Engine Rooms</h1>
      @if($subscription)
        <div class="text-sm text-gray-500">For subscription: <strong>{{ $subscription->title ?? $subscription->uuid }}</strong></div>
      @else
        <div class="text-sm text-gray-500">No subscription selected. Click a subscription to view its engine rooms.</div>
      @endif
    </div>

    <form id="search-form" method="GET" action="{{ route('engine-rooms.index') }}" class="flex gap-2">
      @if($subscription)
        <input type="hidden" name="subscription" value="{{ $subscription->id }}">
      @endif
      <input name="q" value="{{ $q }}" placeholder="Search name" class="px-3 py-2 border rounded" />
      <button class="px-3 py-2 bg-gray-800 text-white rounded">Search</button>
    </form>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white shadow rounded p-4">
      <h2 class="text-lg font-medium mb-3">Create Engine Room</h2>

      <form id="create-er-form">
        <input type="hidden" name="subscription_id" value="{{ $subscription->id ?? '' }}" />

        <div class="mb-2">
          <label class="text-xs block mb-1">Name</label>
          <input name="name" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div class="mb-2">
          <label class="text-xs block mb-1">Location</label>
          <input name="location" class="w-full px-3 py-2 border rounded" />
        </div>

        <div id="create-result" class="text-sm mb-2"></div>

        <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded">Create</button>
      </form>
    </div>

    <div class="md:col-span-2 bg-white shadow rounded p-4">
      <h2 class="text-lg font-medium mb-3">Engine Rooms ({{ $engineRooms->total() }})</h2>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y">
          <thead>
            <tr class="bg-gray-50">
              <th class="p-2 text-left">#</th>
              <th class="p-2 text-left">Name</th>
              <th class="p-2 text-left">UUID</th>
              <th class="p-2 text-left">Location</th>
              <th class="p-2 text-left">Devices</th>
              <th class="p-2 text-left">Actions</th>
            </tr>
          </thead>
          <tbody id="ers-tbody" class="divide-y">
            @foreach($engineRooms as $er)
              <tr id="er-row-{{ $er->id }}">
                <td class="p-2 align-top">{{ $loop->iteration + ($engineRooms->currentPage()-1)* $engineRooms->perPage() }}</td>
                <td class="p-2">{{ $er->name }}</td>
                <td class="p-2 text-xs text-gray-600">{{ $er->uuid }}</td>
                <td class="p-2">{{ $er->location ?? '—' }}</td>
                <td class="p-2">
                  <a href="{{ url('/devices?engine_room=' . $er->id) }}" class="text-blue-600 underline">Devices</a>
                </td>
                <td class="p-2">
                  <button onclick="deleteER('{{ $er->id }}')" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $engineRooms->links() }}
      </div>
    </div>
  </div>
</div>

<script>
  const CSRF_TOKEN = '{{ csrf_token() }}';

  document.getElementById('create-er-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
      subscription_id: form.subscription_id.value || null,
      name: form.name.value.trim(),
      location: form.location.value.trim() || null
    };

    document.getElementById('create-result').textContent = 'Creating...';

    try {
      const resp = await fetch('{{ route("api.engine-rooms.store") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });

      if (!resp.ok) {
        const txt = await resp.text();
        document.getElementById('create-result').textContent = 'Error: ' + resp.status + ' ' + txt;
        return;
      }

      const json = await resp.json();
      const er = json.engine_room;
      document.getElementById('create-result').textContent = 'Created';
      prependERRow(er);
      form.name.value = '';
      form.location.value = '';
    } catch (err) {
      console.error(err);
      document.getElementById('create-result').textContent = 'Request failed';
    }
  });

  function prependERRow(er) {
    const tbody = document.getElementById('ers-tbody');
    const tr = document.createElement('tr');
    tr.id = 'er-row-' + er.id;
    tr.innerHTML = `
      <td class="p-2">—</td>
      <td class="p-2">${escapeHtml(er.name)}</td>
      <td class="p-2 text-xs text-gray-600">${escapeHtml(er.uuid)}</td>
      <td class="p-2">${escapeHtml(er.location ?? '—')}</td>
      <td class="p-2"><a href="/devices?engine_room=${encodeURIComponent(er.id)}" class="text-blue-600 underline">Devices</a></td>
      <td class="p-2"><button onclick="deleteER('${er.id}')" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Delete</button></td>
    `;
    tbody.insertBefore(tr, tbody.firstChild);
  }

  async function deleteER(id) {
    if (!confirm('Delete engine room?')) return;
    try {
      const resp = await fetch('/api/engine-rooms/' + encodeURIComponent(id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': CSRF_TOKEN,
          'Accept': 'application/json'
        }
      });
      if (!resp.ok) {
        const txt = await resp.text();
        alert('Delete failed: ' + resp.status + ' ' + txt);
        return;
      }
      const row = document.getElementById('er-row-' + id);
      if (row) row.remove();
    } catch (e) {
      console.error(e);
      alert('Delete failed');
    }
  }

  function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
</script>
@endsection
