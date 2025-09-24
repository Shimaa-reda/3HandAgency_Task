document.addEventListener('DOMContentLoaded', () => {
    // Theme Toggle
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.querySelector('.sun-icon');
    const moonIcon = document.querySelector('.moon-icon');

    themeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        sunIcon.classList.toggle('hidden');
        moonIcon.classList.toggle('hidden');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });

    // Initialize theme
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
    }

    // Language Switch (Placeholder)
    const languageSwitch = document.getElementById('language-switch');
    languageSwitch.addEventListener('change', (e) => {
        console.log(`Language changed to: ${e.target.value}`);
        // Add actual language change logic here
    });

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.altKey && e.key === 'h') {
            window.location.href = '/';
        }
        if (e.altKey && e.key === 'u') {
            window.location.href = '/users';
        }
    });

    // User Actions
    const selectAll = document.getElementById('select-all');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkDelete = document.getElementById('bulk-delete');

    selectAll.addEventListener('change', () => {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        bulkDelete.disabled = !selectAll.checked;
    });

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            bulkDelete.disabled = !Array.from(userCheckboxes).some(cb => cb.checked);
        });
    });

    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', () => {
            alert(`Edit user with ID: ${button.dataset.id}`);
            // Add edit logic here
        });
    });

    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', () => {
            if (confirm(`Delete user with ID: ${button.dataset.id}?`)) {
                console.log(`Deleted user with ID: ${button.dataset.id}`);
                // Add delete logic here
            }
        });
    });

    document.getElementById('add-user').addEventListener('click', () => {
        alert('Add new user');
        // Add user creation logic here
    });

    bulkDelete.addEventListener('click', () => {
        const selectedIds = Array.from(userCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.dataset.id);
        if (selectedIds.length > 0 && confirm(`Delete ${selectedIds.length} selected users?`)) {
            console.log(`Bulk deleted users with IDs: ${selectedIds.join(', ')}`);
            // Add bulk delete logic here
        }
    });
});
