@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
  <div class="bg-white rounded shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Peripheral Modes</h2>

    <form id="pm-form" class="grid grid-cols-12 gap-3 mb-6">
      @csrf
      <div class="col-span-4">
        <label class="block text-sm text-gray-600">Mode name</label>
        <input id="pm-mode" type="text" class="w-full p-2 border rounded" placeholder="e.g. relay" required>
      </div>
      <div class="col-span-2">
        <label class="block text-sm text-gray-600">Legacy ID (optional)</label>
        <input id="pm-legacy" type="number" class="w-full p-2 border rounded" placeholder="e.g. 1">
      </div>
      <div class="col-span-3">
        <label class="block text-sm text-gray-600">Unit (optional)</label>
        <input id="pm-unit" type="text" class="w-full p-2 border rounded" placeholder="e.g. boolean, °C">
      </div>
      <div class="col-span-3">
        <label class="block text-sm text-gray-600">Slug (optional)</label>
        <input id="pm-slug" type="text" class="w-full p-2 border rounded" placeholder="custom-slug (auto from name)">
      </div>

      <div class="col-span-12">
        <label class="block text-sm text-gray-600">Description (optional)</label>
        <input id="pm-desc" type="text" class="w-full p-2 border rounded" placeholder="description">
      </div>

      <div class="col-span-12">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Mode</button>
        <span id="pm-msg" class="ml-4 text-sm text-green-600 hidden">Saved</span>
      </div>
    </form>

    <div>
      <h3 class="font-medium mb-2">Existing Modes</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-3 py-2">Mode</th>
              <th class="px-3 py-2">Slug</th>
              <th class="px-3 py-2">Legacy ID</th>
              <th class="px-3 py-2">Unit</th>
              <th class="px-3 py-2">Description</th>
            </tr>
          </thead>
          <tbody id="pm-table" class="divide-y">
            @foreach($modes as $m)
              <tr>
                <td class="px-3 py-2">{{ $m->mode }}</td>
                <td class="px-3 py-2">{{ $m->slug ?? '—' }}</td>
                <td class="px-3 py-2">{{ $m->legacy_id ?? '—' }}</td>
                <td class="px-3 py-2">{{ $m->unit ?? '—' }}</td>
                <td class="px-3 py-2">{{ $m->description ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    const form = document.getElementById('pm-form');
    const table = document.getElementById('pm-table');
    const msg = document.getElementById('pm-msg');
    const csrf = '{{ csrf_token() }}';

    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const mode = document.getElementById('pm-mode').value.trim();
      if (!mode) return alert('Mode name required');
      const legacy = document.getElementById('pm-legacy').value;
      const unit = document.getElementById('pm-unit').value.trim();
      const slug = document.getElementById('pm-slug').value.trim();
      const desc = document.getElementById('pm-desc').value.trim();

      const payload = { mode, unit, description: desc };
      if (legacy !== '') payload.legacy_id = parseInt(legacy, 10);
      if (slug) payload.slug = slug;

      const res = await fetch('/api/admin/peripheral-modes', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      if (!res.ok) {
        const text = await res.text();
        alert('Error: ' + res.status + ' ' + text);
        return;
      }

      const json = await res.json();

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-3 py-2">${escapeHtml(json.mode)}</td>
        <td class="px-3 py-2">${escapeHtml(json.slug ?? '—')}</td>
        <td class="px-3 py-2">${escapeHtml(json.legacy_id ?? '—')}</td>
        <td class="px-3 py-2">${escapeHtml(json.unit ?? '—')}</td>
        <td class="px-3 py-2">${escapeHtml(json.description ?? '—')}</td>
      `;
      table.insertBefore(tr, table.firstChild);

      form.reset();
      msg.classList.remove('hidden');
      setTimeout(()=> msg.classList.add('hidden'), 1600);
    });

    function escapeHtml(s){ if (s==null) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
  })();
</script>
@endsection
