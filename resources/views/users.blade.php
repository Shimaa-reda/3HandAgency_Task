@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Users</h2>
            <div class="space-x-2">
                <button id="add-user" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Add User</button>
                <button id="bulk-delete" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" disabled>Bulk Delete</button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            @if(count($users) > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3">
                                <input type="checkbox" id="select-all" class="rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" class="user-checkbox rounded" data-id="{{ $user['id'] }}">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user['email'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="edit-user text-blue-600 hover:text-blue-800 mr-2" data-id="{{ $user['id'] }}">Edit</button>
                                    <button class="delete-user text-red-600 hover:text-red-800" data-id="{{ $user['id'] }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 text-gray-600 dark:text-gray-300 font-semibold">
                    There are no users
                </div>
            @endif
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Add User</h2>
            <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2 text-gray-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2 text-gray-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2 text-gray-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addUserModal')" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Edit User</h2>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2 text-gray-900">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full border rounded px-3 py-2 text-gray-900">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open Add User Modal
        document.getElementById('add-user').addEventListener('click', () => {
            document.getElementById('addUserModal').classList.remove('hidden');
        });

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Edit user
        document.querySelectorAll('.edit-user').forEach(button => {
            button.addEventListener('click', () => {
                let userId = button.dataset.id;
                let row = button.closest('tr');
                let name = row.querySelector('td:nth-child(2)').innerText;
                let email = row.querySelector('td:nth-child(3)').innerText;

                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;

                document.getElementById('editUserForm').action = `/users/${userId}`;
                document.getElementById('editUserModal').classList.remove('hidden');
            });
        });

        // Delete user
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', () => {
                if (confirm("Are you sure you want to delete this user?")) {
                    let userId = button.dataset.id;
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/users/${userId}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Select all
        document.getElementById('select-all').addEventListener('change', function () {
            let checked = this.checked;
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = checked);
            toggleBulkDelete();
        });

        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', toggleBulkDelete);
        });

        function toggleBulkDelete() {
            let anyChecked = document.querySelectorAll('.user-checkbox:checked').length > 0;
            document.getElementById('bulk-delete').disabled = !anyChecked;
        }

     // Bulk delete
document.getElementById('bulk-delete').addEventListener('click', () => {
    if (confirm("Are you sure you want to delete selected users?")) {
        let ids = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.dataset.id);

        if (ids.length === 0) return;

        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('users.bulk-destroy') }}";

        // Add CSRF + method
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.innerHTML = `
            <input type="hidden" name="_token" value="${token}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        // Add all selected IDs
        ids.forEach(id => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
});


    </script>
@endsection
