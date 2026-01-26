// Status Update via AJAX
function updateStatus(selectElement, itemId) {
    const newStatus = selectElement.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/project_manage/${itemId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ status: newStatus })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the select element's class to reflect new status
                selectElement.className = `status-select status-${newStatus}`;

                // Show success message
                showNotification('Status updated successfully!', 'success');

                // Optionally reload the page to update counts
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Failed to update status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
}

// Show notification
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

// Tab switching
function switchTab(event, tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.remove('active'));

    // Remove active class from all buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => button.classList.remove('active'));

    // Show selected tab and mark button as active
    document.getElementById(tabName).classList.add('active');
    event.currentTarget.classList.add('active');

    // Update URL parameter
    const url = new URL(window.location);
    url.searchParams.set('status', tabName);
    window.history.pushState({}, '', url);
}

// Show details page
function showDetails(itemId) {
    window.location.href = `/project_manage/${itemId}`;
}

// Delete item with confirmation
function deleteItem(itemId) {
    if (confirm('Are you sure you want to delete this item?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/project_manage/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
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

// Delete attachment
function deleteAttachment(attachmentId) {
    if (confirm('Are you sure you want to delete this attachment?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
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

// Filter by project
document.addEventListener('DOMContentLoaded', function () {
    const projectSelects = document.querySelectorAll('#listProjectSelect, #dashboardProjectSelect');

    projectSelects.forEach(select => {
        select.addEventListener('change', function () {
            const url = new URL(window.location);
            url.searchParams.set('project_id', this.value);
            window.location.href = url.toString();
        });
    });

    // Toggle "My Items" filter
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

    // Add animation styles
    const style = document.createElement('style');
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
    `;
    document.head.appendChild(style);
});
