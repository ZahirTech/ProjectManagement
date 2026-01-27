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
    const pageElement = document.getElementById(pageName);
    if (pageElement) {
        pageElement.classList.add('active');
    }

    // Add active class to clicked nav item
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }
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
    const tabElement = document.getElementById(tabName);
    if (tabElement) {
        tabElement.classList.add('active');
    }

    // Add active class to clicked button
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }

    // Update URL parameter for filtering
    const url = new URL(window.location);
    url.searchParams.set('status', tabName);
    window.history.pushState({}, '', url);
}

// Status Update Function - Updated with AJAX
function updateStatus(selectElement, itemId) {
    const newStatus = selectElement.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Error: CSRF token not found');
        return;
    }

    // Update the select element's class immediately for UI feedback
    selectElement.classList.remove('status-pending', 'status-processing', 'status-completed', 'status-on-hold');
    selectElement.classList.add('status-' + newStatus);

    // Send AJAX request to update status
    fetch(`/project_manage/${itemId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Status updated successfully!', 'success');

                // Reload page after short delay to update counts
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Failed to update status', 'error');
                // Revert the select element
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating status', 'error');
            // Revert the select element
            location.reload();
        });
}

// Show notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#48bb78' : '#f56565'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Show Details Page - Updated to use routes
function showDetails(itemId) {
    window.location.href = `/project_manage/${itemId}`;
}

// Delete item with confirmation - Updated with AJAX
function deleteItem(itemId) {
    if (confirm('Are you sure you want to delete this item?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Error: CSRF token not found');
            return;
        }

        fetch(`/project_manage/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        })
            .then(response => {
                if (response.ok) {
                    showNotification('Item deleted successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Failed to delete item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
    }
}

// Delete attachment with AJAX
function deleteAttachment(attachmentId) {
    if (confirm('Are you sure you want to delete this attachment?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Error: CSRF token not found');
            return;
        }

        fetch(`/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        })
            .then(response => {
                if (response.ok) {
                    showNotification('Attachment deleted successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Failed to delete attachment', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
    }
}

// Logout Function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logoutForm')?.submit();
    }
}

// Toggle private item and disable assignment
function toggleAssignment() {
    const isPrivate = document.getElementById('isPrivateCheckbox')?.checked;
    const assignmentGroup = document.getElementById('assignmentGroup');
    const assignedToSelect = document.getElementById('assignedToSelect');

    if (assignmentGroup && assignedToSelect) {
        if (isPrivate) {
            assignmentGroup.style.opacity = '0.5';
            assignedToSelect.disabled = true;
            assignedToSelect.value = '';
        } else {
            assignmentGroup.style.opacity = '1';
            assignedToSelect.disabled = false;
        }
    }
}

// Document Ready
document.addEventListener('DOMContentLoaded', function () {

    // Initialize private checkbox toggle
    const isPrivateCheckbox = document.getElementById('isPrivateCheckbox');
    if (isPrivateCheckbox) {
        toggleAssignment();
        isPrivateCheckbox.addEventListener('change', toggleAssignment);
    }

    // File Upload Handler for Create Page
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.addEventListener('change', function (e) {
            const filesList = document.getElementById('uploadedFilesList');
            if (!filesList) return;

            filesList.innerHTML = ''; // Clear previous files
            const files = Array.from(e.target.files);

            files.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.style.cssText = 'padding: 8px; margin-top: 8px; background: #f7fafc; border-radius: 4px; font-size: 14px; display: flex; justify-content: space-between; align-items: center;';
                fileItem.innerHTML = `
                    <div class="file-info">
                        <span>📄</span>
                        <span>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                    </div>
                    <button type="button" class="file-remove-btn" onclick="removeFileItem(this, ${index})" style="background: #f56565; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Remove</button>
                `;
                filesList.appendChild(fileItem);
            });
        });
    }

    // Project Selector Change Handlers
    const dashboardProjectSelect = document.getElementById('dashboardProjectSelect');
    if (dashboardProjectSelect) {
        dashboardProjectSelect.addEventListener('change', function (e) {
            const url = new URL(window.location);
            url.searchParams.set('project_id', this.value);
            window.location.href = url.toString();
        });
    }

    const listProjectSelect = document.getElementById('listProjectSelect');
    if (listProjectSelect) {
        listProjectSelect.addEventListener('change', function (e) {
            const url = new URL(window.location);
            url.searchParams.set('project_id', this.value);
            window.location.href = url.toString();
        });
    }

    // My Items Toggle
    const myItemsToggle = document.getElementById('myItemsToggle');
    if (myItemsToggle) {
        myItemsToggle.addEventListener('change', function () {
            const url = new URL(window.location);
            if (this.checked) {
                url.searchParams.set('my_items', '1');
            } else {
                url.searchParams.delete('my_items');
            }
            window.location.href = url.toString();
        });
    }

    // Details Page File Upload
    const detailsFileInput = document.getElementById('detailsFileInput');
    if (detailsFileInput) {
        detailsFileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            const itemId = window.location.pathname.split('/').pop();
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                alert('Error: CSRF token not found');
                return;
            }

            fetch(`/project_manage/${itemId}/attachments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('File uploaded successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Failed to upload file', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while uploading', 'error');
                });
        });
    }

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
        });
    });

    // Add animation styles for notifications
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            .file-item {
                padding: 8px;
                margin-top: 8px;
                background: #f7fafc;
                border-radius: 4px;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    }
});

// Remove file from upload list
function removeFileItem(button, index) {
    button.closest('.file-item').remove();

    // Note: This doesn't actually remove from input.files as it's read-only
    // The file will still be uploaded unless you rebuild the FileList
    // For better UX, you might want to track files separately
}

// Toggle Pin - Pin/Unpin item to dashboard
function togglePin(itemId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Error: CSRF token not found');
        return;
    }

    fetch(`/project_manage/${itemId}/pin`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');

                // Update the pin button appearance
                const pinButtons = document.querySelectorAll('.icon-btn.pin');
                pinButtons.forEach(btn => {
                    const btnItemId = btn.getAttribute('onclick').match(/\d+/)[0];
                    if (btnItemId == itemId) {
                        if (data.is_pinned) {
                            btn.classList.add('pinned');
                            btn.title = 'Unpin';
                        } else {
                            btn.classList.remove('pinned');
                            btn.title = 'Pin to Dashboard';
                        }
                    } else {
                        // Unpin all other buttons since only one can be pinned
                        btn.classList.remove('pinned');
                        btn.title = 'Pin to Dashboard';
                    }
                });
            } else {
                showNotification('Failed to update pin status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
}
