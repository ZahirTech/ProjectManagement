
// Sidebar Toggle Functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const menuToggle = document.getElementById('menuToggle');

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');

    // Hide menu toggle when sidebar is open
    if (sidebar.classList.contains('active')) {
        menuToggle.classList.add('hidden');
    } else {
        menuToggle.classList.remove('hidden');
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const menuToggle = document.getElementById('menuToggle');

    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    menuToggle.classList.remove('hidden');
}

// Navigation Functions
function showPage(pageName) {
    // Close sidebar on mobile after navigation
    if (window.innerWidth <= 768) {
        closeSidebar();
    }

    // Hide all pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });

    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Show selected page
    document.getElementById(pageName).classList.add('active');

    // Add active class to clicked nav item
    event.currentTarget.classList.add('active');
}

// Tab Switching Function
function switchTab(event, tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    document.getElementById(tabName).classList.add('active');

    // Add active class to clicked button
    event.currentTarget.classList.add('active');
}

// Status Update Function
function updateStatus(selectElement, itemId) {
    const newStatus = selectElement.value;
    const statusClass = 'status-' + newStatus;

    // Remove all status classes
    selectElement.classList.remove('status-pending', 'status-processing', 'status-completed', 'status-on-hold');

    // Add new status class
    selectElement.classList.add(statusClass);

    // Show confirmation message
    console.log(`Item #${itemId} status updated to: ${newStatus}`);

    // Here you would typically make an API call to update the status in your backend
    // Example:
    // fetch('/api/update-status', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify({ itemId: itemId, status: newStatus })
    // })
    // .then(response => response.json())
    // .then(data => {
    //     alert('Status updated successfully!');
    // });

    // For demo purposes, show alert
    const statusNames = {
        'pending': 'Pending',
        'processing': 'Processing',
        'completed': 'Completed',
        'on-hold': 'On Hold'
    };

    alert(`Status updated to "${statusNames[newStatus]}" for Item #${String(itemId).padStart(3, '0')}`);
}

// Show Details Page
function showDetails(itemId) {
    // Close sidebar on mobile
    if (window.innerWidth <= 768) {
        closeSidebar();
    }

    // Hide all pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });

    // Show details page
    document.getElementById('details').classList.add('active');

    // Here you would typically fetch and display the actual item details
    console.log('Showing details for item:', itemId);
}

// Logout Function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        alert('Logout functionality - Connect to your backend');
        // Add your logout logic here
    }
}

// Form Submit Handler
document.getElementById('createForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Form submitted! Connect this to your backend API.');
    // Add your form submission logic here
});

// File Upload Handler for Create Page
document.getElementById('fileInput')?.addEventListener('change', function (e) {
    const filesList = document.getElementById('uploadedFilesList');
    const files = Array.from(e.target.files);

    files.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
                    <div class="file-info">
                        <span>📄</span>
                        <span>${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                    </div>
                    <button type="button" class="file-remove-btn" onclick="this.parentElement.remove()">Remove</button>
                `;
        filesList.appendChild(fileItem);
    });
});

// Project Selector Change Handlers
document.getElementById('dashboardProjectSelect')?.addEventListener('change', function (e) {
    console.log('Dashboard filter changed to:', e.target.value);
    // Add your filtering logic here
});

document.getElementById('listProjectSelect')?.addEventListener('change', function (e) {
    console.log('List filter changed to:', e.target.value);
    // Add your filtering logic here
});

// Details Page File Upload
document.getElementById('detailsFileInput')?.addEventListener('change', function (e) {
    alert('File upload functionality - Connect to your backend');
    // Add your file upload logic here
});

// Sample action button handlers (you'll connect these to your backend)
document.querySelectorAll('.icon-btn.edit').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        alert('Edit functionality - Connect to your backend');
    });
});

document.querySelectorAll('.icon-btn.delete').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (confirm('Are you sure you want to delete this item?')) {
            alert('Delete functionality - Connect to your backend');
        }
    });
});

document.querySelectorAll('.download-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        alert('Download functionality - Connect to your backend');
    });
});

document.querySelectorAll('.delete-attach-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        if (confirm('Are you sure you want to delete this attachment?')) {
            alert('Delete attachment - Connect to your backend');
        }
    });
});

// Pagination Button Handlers
document.querySelectorAll('.pagination-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        if (this.disabled || this.classList.contains('dots') || this.classList.contains('active')) {
            return;
        }

        // Remove active class from all pagination buttons
        this.parentElement.querySelectorAll('.pagination-btn').forEach(b => {
            b.classList.remove('active');
        });

        // Add active class to clicked button (if it's a number)
        if (!this.textContent.includes('Prev') && !this.textContent.includes('Next')) {
            this.classList.add('active');
        }

        console.log('Page changed to:', this.textContent);
        // Add your pagination logic here to fetch new data
    });
});
