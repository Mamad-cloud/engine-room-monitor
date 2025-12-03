@extends('masterpage')

@section('content')
<div class="max-w-7xl mx-auto p-4" dir="ltr">
  <div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold">Users</h1>

    <form id="search-form" method="GET" action="{{ route('users') }}" class="flex items-center gap-2">
      <input name="q" value="{{ $q }}" placeholder="Search name or email" class="px-3 py-2 border rounded-md" />
      <button type="submit" class="px-3 py-2 bg-gray-800 text-white rounded-md">Search</button>
    </form>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Create form -->
    <div class="bg-white shadow rounded p-4">
      <h2 class="text-lg font-medium mb-3">Create User</h2>
      <form id="create-user-form">
        <div class="mb-2">
          <label class="text-xs block mb-1">Name</label>
          <input name="name" required class="w-full px-3 py-2 border rounded" />
        </div>
        <div class="mb-2">
          <label class="text-xs block mb-1">Email</label>
          <input name="email" type="email" class="w-full px-3 py-2 border rounded" />
        </div>
        <div class="mb-3">
          <label class="text-xs block mb-1">Password (optional)</label>
          <input name="password" type="password" class="w-full px-3 py-2 border rounded" />
          <p class="text-xs text-gray-500 mt-1">If left blank a random password will be generated.</p>
        </div>

        <div id="create-result" class="text-sm mb-2"></div>

        <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded">Create</button>
      </form>
    </div>

    <!-- Users table -->
    <div class="md:col-span-2">
      <div class="bg-white shadow rounded p-4">
        <h2 class="text-lg font-medium mb-3">All users</h2>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm divide-y">
            <thead>
              <tr class="bg-gray-50">
                <th class="p-2 text-left">#</th>
                <th class="p-2 text-left">Name</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-left">Subscriptions</th>
                <th class="p-2 text-left">Actions</th>
              </tr>
            </thead>
            <tbody id="users-tbody" class="divide-y">
              @foreach($users as $u)
                <tr id="user-row-{{ $u->id }}">
                  <td class="p-2 align-top">{{ $loop->iteration + ($users->currentPage()-1)* $users->perPage() }}</td>
                  <td class="p-2 align-top">{{ $u->name }}</td>
                  <td class="p-2 align-top">{{ $u->email ?? '—' }}</td>
                  <td class="p-2 align-top">
                    <a href="{{ url('/subscriptions?user='.$u->id) }}" class="text-blue-600 underline">Subscriptions</a>
                  </td>
                  <td class="p-2 align-top">
                    <button onclick="deleteUser('{{ $u->id }}')" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Delete</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-4">
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const CSRF_TOKEN = '{{ csrf_token() }}';

  // create user
  document.getElementById('create-user-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
      name: form.name.value.trim(),
      email: form.email.value.trim() || null,
      password: form.password.value || null
    };

    document.getElementById('create-result').textContent = 'Creating...';

    try {
      const resp = await fetch('{{ route("api.users.store") }}', {
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
      const user = json.user;
      document.getElementById('create-result').textContent = 'User created';

      // prepend row to table
      prependUserRow(user);
      form.reset();
    } catch (err) {
      console.error(err);
      document.getElementById('create-result').textContent = 'Request failed';
    }
  });

  function prependUserRow(user) {
    const tbody = document.getElementById('users-tbody');
    const tr = document.createElement('tr');
    tr.id = 'user-row-' + user.id;
    tr.innerHTML = `
      <td class="p-2 align-top">—</td>
      <td class="p-2 align-top">${escapeHtml(user.name)}</td>
      <td class="p-2 align-top">${escapeHtml(user.email || '')}</td>
      <td class="p-2 align-top"><a href="/subscriptions?user=${user.id}" class="text-blue-600 underline">Subscriptions</a></td>
      <td class="p-2 align-top"><button onclick="deleteUser('${user.id}')" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Delete</button></td>
    `;
    tbody.insertBefore(tr, tbody.firstChild);
    // keep max rows reasonable (optional)
    while (tbody.children.length > 100) tbody.removeChild(tbody.lastChild);
  }

  async function deleteUser(id) {
    if (!confirm('Delete user?')) return;
    try {
      const resp = await fetch('/api/users/' + encodeURIComponent(id), {
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
      const row = document.getElementById('user-row-' + id);
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
