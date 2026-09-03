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

// Tab Switching Function with AJAX Loading
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

        // Load tab content if not already loaded
        if (!tabElement.dataset.loaded || tabElement.dataset.loaded === 'false') {
            loadTabContent(tabName);
        }
    }

    // Add active class to clicked button
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }
}

// Load tab content via AJAX
function loadTabContent(status) {
    const tabElement = document.getElementById(status);
    if (!tabElement) return;

    // Show loading
    tabElement.innerHTML = '<div class="tab-loading">Loading...</div>';

    // Get current filters
    const projectId = document.getElementById('listProjectSelect')?.value || 'all';
    const myItems = document.getElementById('myItemsToggle')?.checked ? '1' : '0';

    // Build URL with filters
    const url = new URL(`/project_manage/tab/${status}`, window.location.origin);
    url.searchParams.set('project_id', projectId);
    url.searchParams.set('my_items', myItems);

    // Fetch tab content
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTabContent(tabElement, data.items, status);
                tabElement.dataset.loaded = 'true';
            } else {
                tabElement.innerHTML = '<div class="empty-state"><p>Error loading items</p></div>';
            }
        })
        .catch(error => {
            console.error('Error loading tab:', error);
            tabElement.innerHTML = '<div class="empty-state"><p>Error loading items</p></div>';
        });
}

function renderTabContent(tabElement, items, status) {
    if (!items || items.length === 0) {
        tabElement.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No Items Found</h3>
                <p>There are no items with "${status.charAt(0).toUpperCase() + status.slice(1)}" status</p>
            </div>
        `;
        return;
    }

    const userId = document.querySelector('meta[name="user-id"]')?.content;

    const truncateText = (text, maxChars = 40) => {
        if (!text) return '-';
        return text.length > maxChars ? text.slice(0, maxChars) + '…' : text;
    };

    let tableHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width: 260px;">Title</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Assigned</th>
                    <th>Attachments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;

    items.forEach(item => {
        const itemId = String(item.id).padStart(3, '0');

        const dueDate = item.due_date
            ? new Date(item.due_date).toLocaleDateString('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            })
            : '-';

        const assignedTo = item.assigned_user ? item.assigned_user.name : 'Not Assigned';
        const attachmentCount = item.attachments ? item.attachments.length : 0;
        const isCreator = userId && item.created_by == userId;

        const titleText = truncateText(item.title, 400);

        tableHTML += `
    <tr onclick="showDetails(${item.id})" style="cursor: pointer;" class="hoverable-row">
        <td>#${itemId}</td>
        <td>
            <div style="
                    display: -webkit-box;
                    -webkit-box-orient: vertical;
                    -webkit-line-clamp: 2;
                    overflow: hidden;
                    width: max-content;
                    max-width: 250px;
                    min-width: 0;
                    font-weight: 500;
                    line-height: 1.4;
                    " title="${item.title}">
                    ${titleText}
                    ${item.is_private ? '<span style="color:#f56565;font-size:12px;margin-left:4px;display:inline-block;">🔒</span>' : ''}
            </div>
        </td>
        <td>
            <div style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500;">
                ${item.project.name}
            </div>
        </td>
        <td onclick="event.stopPropagation()">
            <select class="status-select status-${item.status}"
                onchange="updateStatus(this, ${item.id})"
                data-old-status="${item.status}">
                <option value="pending" ${item.status === 'pending' ? 'selected' : ''}>Pending</option>
                <option value="processing" ${item.status === 'processing' ? 'selected' : ''}>Processing</option>
                <option value="completed" ${item.status === 'completed' ? 'selected' : ''}>Completed</option>
                <option value="on-hold" ${item.status === 'on-hold' ? 'selected' : ''}>On Hold</option>
            </select>
        </td>
        <td>${item.priority.charAt(0).toUpperCase() + item.priority.slice(1)}</td>
        <td>
            <div style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500;">
                ${dueDate}
            </div>
        </td>
        <td>
            <div style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500;">
                ${assignedTo}
            </div>
        </td>
        <td>
            ${attachmentCount > 0
                ? `<span class="attachment-indicator">📎 ${attachmentCount}</span>`
                : '-'}
        </td>
        <td onclick="event.stopPropagation()">
            <div class="action-buttons">

                ${isCreator ? `
                    <button class="icon-btn pin ${item.is_pinned ? 'pinned' : ''}"
                        onclick="togglePin(${item.id})"
                        title="${item.is_pinned ? 'Unpin' : 'Pin'}">📌</button>
                    <button class="icon-btn edit"
                        onclick="window.location.href='/project_manage/${item.id}/edit'"
                        title="Edit">✏️</button>
                    <button class="icon-btn delete"
                        onclick="deleteItem(${item.id})"
                        title="Delete">🗑️</button>
                ` : ''}
            </div>
        </td>
    </tr>
`;
    });

    tableHTML += `
            </tbody>
        </table>
        <div class="pagination-container">
            <div class="pagination-info">
                Showing ${items.length} of ${items.length} items
            </div>
        </div>
    `;

    tabElement.innerHTML = tableHTML;
}


// Status Update Function - Updated with AJAX
function updateStatus(selectElement, itemId) {
    const newStatus = selectElement.value;
    const oldStatus = selectElement.dataset.oldStatus || selectElement.getAttribute('data-old-status');
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

                // Get the row element
                const row = selectElement.closest('tr');

                // Update tab counts
                updateTabCounts(oldStatus, newStatus);

                // Move the row to correct tab content
                moveRowToTab(row, itemId, oldStatus, newStatus);

                // Store new status
                selectElement.dataset.oldStatus = newStatus;
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

// Update tab counts without reloading
function updateTabCounts(oldStatus, newStatus) {
    // Decrease old status count
    if (oldStatus) {
        const oldBadge = document.getElementById(`badge-${oldStatus}`);
        if (oldBadge) {
            const oldCount = parseInt(oldBadge.textContent) || 0;
            oldBadge.textContent = Math.max(0, oldCount - 1);
        }
    }

    // Increase new status count
    const newBadge = document.getElementById(`badge-${newStatus}`);
    if (newBadge) {
        const newCount = parseInt(newBadge.textContent) || 0;
        newBadge.textContent = newCount + 1;
    }

    // Mark tabs as not loaded so they reload with fresh data
    document.querySelectorAll('.tab-content').forEach(tab => {
        if (tab.id === oldStatus || tab.id === newStatus) {
            tab.dataset.loaded = 'false';
        }
    });
}

// Move row to the correct tab
function moveRowToTab(row, itemId, oldStatus, newStatus) {
    if (!row) return;

    const currentTab = document.querySelector('.tab-content.active');
    const currentTabId = currentTab ? currentTab.id : null;

    // "All" always contains the item regardless of status — just mark the
    // affected status tabs stale so they refetch next time they're opened.
    if (currentTabId === 'all') {
        const oldTab = document.getElementById(oldStatus);
        const newTab = document.getElementById(newStatus);
        if (oldTab) oldTab.dataset.loaded = 'false';
        if (newTab) newTab.dataset.loaded = 'false';
        return;
    }

    // Just remove the row with fade animation
    row.style.opacity = '0';
    setTimeout(() => {
        row.remove();

        // Check if current tab is now empty
        const currentTab = document.querySelector('.tab-content.active');
        if (currentTab) {
            const tbody = currentTab.querySelector('tbody');
            if (tbody && tbody.children.length === 0) {
                // Show empty state
                const table = currentTab.querySelector('.data-table');
                if (table) {
                    table.style.display = 'none';
                }
                const paginationContainer = currentTab.querySelector('.pagination-container');
                if (paginationContainer) {
                    paginationContainer.style.display = 'none';
                }

                // Add empty state if not exists
                if (!currentTab.querySelector('.empty-state')) {
                    const emptyState = document.createElement('div');
                    emptyState.className = 'empty-state';
                    emptyState.innerHTML = `
                        <div class="empty-state-icon">📋</div>
                        <h3>No Items Found</h3>
                        <p>There are no items with this status</p>
                    `;
                    currentTab.appendChild(emptyState);
                }
            }
        }
    }, 300);

    // Mark the new status tab as not loaded so it reloads fresh data when clicked
    const newTab = document.getElementById(newStatus);
    if (newTab) {
        newTab.dataset.loaded = 'false';
    }
}

// Attach event listeners to a row
function attachRowEventListeners(row) {
    // Re-attach status change listener
    const statusSelect = row.querySelector('.status-select');
    if (statusSelect) {
        // Store current status
        const currentStatus = statusSelect.value;
        statusSelect.dataset.oldStatus = currentStatus;

        // Remove old listeners by cloning
        const newStatusSelect = statusSelect.cloneNode(true);
        statusSelect.parentNode.replaceChild(newStatusSelect, statusSelect);

        // Add new listener
        newStatusSelect.addEventListener('change', function () {
            const itemId = this.getAttribute('onchange').match(/\d+/)[0];
            updateStatus(this, itemId);
        });
    }
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
                    // setTimeout(() => location.reload(), 1000);
                    setTimeout(() => {
                        window.location.href = '/project_manage';
                    }, 1000);
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

    // Load "All" tab on page load
    loadTabContent('all');
    document.getElementById('all').dataset.loaded = 'true';

    // Initialize status selects with old status tracking
    document.querySelectorAll('.status-select').forEach(select => {
        select.dataset.oldStatus = select.value;
    });

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
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Shared: removes a pinned card, promotes a hidden one into the visible grid, and updates counts
function removePinnedCardWithFill(card) {
    card.style.transition = 'all 0.3s ease';
    card.style.opacity = '0';
    card.style.transform = 'scale(0.9)';

    setTimeout(() => {
        const wasInVisibleGrid = card.closest('#pinnedGrid') !== null;
        card.remove();

        // Update the pinned count badge
        const pinnedCount = document.querySelector('.pinned-count');
        if (pinnedCount) {
            const currentCount = parseInt(pinnedCount.textContent) || 0;
            pinnedCount.textContent = Math.max(0, currentCount - 1);
        }

        // If we removed a visible card, promote the first hidden card into its place
        if (wasInVisibleGrid) {
            const visibleGrid = document.getElementById('pinnedGrid');
            const expandableContainer = document.getElementById('expandablePinnedItems');
            const hiddenGrid = expandableContainer ? expandableContainer.querySelector('.pinned-grid') : null;
            const nextHiddenCard = hiddenGrid ? hiddenGrid.querySelector('.pinned-card') : null;

            if (visibleGrid && nextHiddenCard) {
                nextHiddenCard.style.opacity = '0';
                nextHiddenCard.style.transform = 'translateY(10px)';
                visibleGrid.appendChild(nextHiddenCard);

                requestAnimationFrame(() => {
                    nextHiddenCard.style.transition = 'all 0.3s ease';
                    nextHiddenCard.style.opacity = '1';
                    nextHiddenCard.style.transform = 'translateY(0)';
                });
            }
        }

        // Check if pinned section is now completely empty
        const remainingCards = document.querySelectorAll('.pinned-card').length;
        if (remainingCards === 0) {
            const pinnedSection = document.querySelector('.pinned-section');
            if (pinnedSection) {
                pinnedSection.style.transition = 'all 0.3s ease';
                pinnedSection.style.opacity = '0';
                setTimeout(() => pinnedSection.remove(), 300);
            }
        }

        // Hide the expand/collapse button once 6 or fewer items remain total
        if (remainingCards <= 6) {
            const expandBtn = document.getElementById('togglePinnedBtn');
            if (expandBtn) expandBtn.style.display = 'none';

            const expandableContainer = document.getElementById('expandablePinnedItems');
            if (expandableContainer) expandableContainer.classList.remove('expanded');
        }
    }, 300);
}

// Toggle Pin - Pin/Unpin item to dashboard (Now supports multiple pins with real-time removal)
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

                // Update the pin button appearance in assignments list
                const pinButtons = document.querySelectorAll('.icon-btn.pin');
                pinButtons.forEach(btn => {
                    const btnOnclick = btn.getAttribute('onclick');
                    if (btnOnclick && btnOnclick.includes(`togglePin(${itemId})`)) {
                        if (data.is_pinned) {
                            btn.classList.add('pinned');
                            btn.title = 'Unpin';
                        } else {
                            btn.classList.remove('pinned');
                            btn.title = 'Pin to Dashboard';
                        }
                    }
                });

                // NEW: If on dashboard and item was unpinned, remove the card in real-time
                const dashboardPage = document.getElementById('dashboard');
                console.log('Dashboard page found:', dashboardPage); // Debug
                console.log('Is pinned:', data.is_pinned); // Debug

                if (!data.is_pinned && dashboardPage) {
                    console.log('Looking for cards to remove...'); // Debug

                    // Find and remove the card from pinned section
                    const pinnedCards = document.querySelectorAll('.pinned-card');
                    console.log('Found pinned cards:', pinnedCards.length); // Debug

                    let cardRemoved = false;
                    pinnedCards.forEach(card => {
                        const cardOnclick = card.getAttribute('onclick');
                        console.log('Card onclick:', cardOnclick); // Debug

                        // Check if this card is for the current item
                        // Looking for either route format
                        if (cardOnclick && (
                            cardOnclick.includes(`projectmng.show', ${itemId}`) ||
                            cardOnclick.includes(`project_manage/${itemId}`)
                        )) {
                            cardRemoved = true;
                            removePinnedCardWithFill(card);
                        }
                    });

                    if (!cardRemoved) {
                        console.log('WARNING: No matching card found to remove!'); // Debug
                    }
                }
            } else {
                showNotification('Failed to update pin status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
}


// Toggle Note Pin - Pin/Unpin note to dashboard (supports multiple pins with real-time removal)
function toggleNotePin(noteId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Error: CSRF token not found');
        return;
    }

    fetch(`/notes/${noteId}/pin`, {
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

                // Update the pin button appearance in notes list
                const pinButtons = document.querySelectorAll('.icon-btn.pin');
                pinButtons.forEach(btn => {
                    const btnOnclick = btn.getAttribute('onclick');
                    if (btnOnclick && btnOnclick.includes(`toggleNotePin(${noteId})`)) {
                        if (data.is_pinned) {
                            btn.classList.add('pinned');
                            btn.title = 'Unpin';
                        } else {
                            btn.classList.remove('pinned');
                            btn.title = 'Pin to Dashboard';
                        }
                    }
                });

                // NEW: If on dashboard and item was unpinned, remove the card in real-time
                const dashboardPage = document.getElementById('dashboard');
                console.log('Dashboard page found:', dashboardPage); // Debug
                console.log('Is pinned:', data.is_pinned); // Debug

                if (!data.is_pinned && dashboardPage) {
                    console.log('Looking for note cards to remove...'); // Debug

                    // Find and remove the card from pinned section
                    const pinnedCards = document.querySelectorAll('.pinned-card');
                    console.log('Found pinned cards:', pinnedCards.length); // Debug

                    let cardRemoved = false;
                    pinnedCards.forEach(card => {
                        const cardOnclick = card.getAttribute('onclick');
                        console.log('Card onclick:', cardOnclick); // Debug

                        // Check if this card is for the current note
                        if (cardOnclick && (
                            cardOnclick.includes(`notes.show', ${noteId}`) ||
                            cardOnclick.includes(`notes/${noteId}`)
                        )) {
                            cardRemoved = true;
                            removePinnedCardWithFill(card);
                        }
                    });

                    if (!cardRemoved) {
                        console.log('WARNING: No matching note card found to remove!'); // Debug
                    }
                }
            } else {
                showNotification('Failed to update pin status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
}

// Delete Note
function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Error: CSRF token not found');
            return;
        }

        fetch(`/notes/${noteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            }
        })
            .then(response => {
                if (response.ok) {
                    showNotification('Note deleted successfully!', 'success');
                    // setTimeout(() => location.reload(), 1000);
                    setTimeout(() => {
                        window.location.href = '/notes';
                    }, 1000);
                } else {
                    showNotification('Failed to delete note', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            });
    }
}
