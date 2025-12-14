@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
  <div class="bg-white rounded shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Event Types</h2>

    <form id="event-type-form" class="grid grid-cols-12 gap-3 mb-6">
      @csrf
      <div class="col-span-4">
        <label class="block text-sm text-gray-600">Name</label>
        <input id="et-name" type="text" class="w-full p-2 border rounded" placeholder="e.g. register" required>
      </div>
      <div class="col-span-3">
        <label class="block text-sm text-gray-600">Code (optional)</label>
        <input id="et-code" type="text" class="w-full p-2 border rounded" placeholder="short code">
      </div>
      <div class="col-span-5">
        <label class="block text-sm text-gray-600">Description (optional)</label>
        <input id="et-desc" type="text" class="w-full p-2 border rounded" placeholder="human-friendly description">
      </div>

      <div class="col-span-12">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Event Type</button>
        <span id="et-msg" class="ml-4 text-sm text-green-600 hidden">Saved</span>
      </div>
    </form>

    <div>
      <h3 class="font-medium mb-2">Existing Event Types</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="px-3 py-2">Name</th>
              <th class="px-3 py-2">Code</th>
              <th class="px-3 py-2">Description</th>
            </tr>
          </thead>
          <tbody id="et-table" class="divide-y">
            @foreach($types as $t)
              <tr>
                <td class="px-3 py-2">{{ $t->name }}</td>
                <td class="px-3 py-2">{{ $t->code ?? '—' }}</td>
                <td class="px-3 py-2">{{ $t->description ?? '—' }}</td>
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
    const form = document.getElementById('event-type-form');
    const table = document.getElementById('et-table');
    const msg = document.getElementById('et-msg');
    const csrf = '{{ csrf_token() }}';

    form.addEventListener('submit', async function(e){
      e.preventDefault();
      const name = document.getElementById('et-name').value.trim();
      if (!name) return alert('Name required');
      const code = document.getElementById('et-code').value.trim();
      const desc = document.getElementById('et-desc').value.trim();

      const res = await fetch('/api/admin/event-types', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ name, code, description: desc })
      });

      if (!res.ok) {
        const text = await res.text();
        alert('Error: ' + res.status + ' ' + text);
        return;
      }

      const json = await res.json();

      // prepend row
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-3 py-2">${escapeHtml(json.name)}</td>
        <td class="px-3 py-2">${escapeHtml(json.code ?? '—')}</td>
        <td class="px-3 py-2">${escapeHtml(json.description ?? '—')}</td>
      `;
      table.insertBefore(tr, table.firstChild);

      // reset
      form.reset();
      msg.classList.remove('hidden');
      setTimeout(()=> msg.classList.add('hidden'), 1600);
    });

    function escapeHtml(s){ if (s==null) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
  })();
</script>
@endsection
