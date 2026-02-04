'use strict';

class ContextMenuManager {
    constructor() {
        this.menu = null;
        this.selectedRow = null;
        this.showDeleted = false;
        this.dataTable = null;
        this.init();
    }
    
    init() {
        // Check if we're showing deleted records
        this.showDeleted = document.getElementById('showDeletedToggle')?.checked || 
                          window.location.href.includes('show_deleted=1');
        
        // Get DataTable instance if available
        this.initializeDataTable();
        
        // Initialize menu element
        this.initializeMenuElement();
        
        // Bind event listeners
        this.bindEvents();
    
    }
    
    initializeDataTable() {
        // Try to get DataTable instance
        if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable) {
            const table = jQuery('.datatable-PatientRecord');
            if (table.length && jQuery.fn.DataTable.isDataTable(table)) {
                this.dataTable = table.DataTable();
            }
        }
    }
    
    initializeMenuElement() {
        // Create or get menu element
        let menu = document.getElementById('contextMenu');
        if (!menu) {
            menu = document.createElement('div');
            menu.id = 'contextMenu';
            menu.className = 'context-menu';
            document.body.appendChild(menu);
        }
        this.menu = menu;
        
        // Set menu content based on whether we're showing deleted records
        this.menu.innerHTML = this.getMenuTemplate();
    }
    
    getMenuTemplate() {
        if (this.showDeleted) {
            return `
                <div class="context-menu-header">Deleted Record Actions</div>
                <div class="context-menu-item restore-action" data-action="restore">
                    <i class="fas fa-undo text-success"></i>
                    <span>Restore</span>
                </div>
                <div class="context-menu-divider"></div>
                <div class="context-menu-item force-delete-action" data-action="force-delete">
                    <i class="fas fa-trash-alt text-danger"></i>
                    <span>Delete Permanently</span>
                </div>
            `;
        } else {
            return `
                <div class="context-menu-header">Record Actions</div>
                <div class="context-menu-item view-action" data-action="view">
                    <i class="fas fa-eye"></i>
                    <span>View</span>
                </div>
                <div class="context-menu-divider"></div>
                <div class="context-menu-item edit-action" data-action="edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </div>
                <div class="context-menu-divider"></div>
                <div class="context-menu-item delete-action" data-action="delete">
                    <i class="fas fa-trash-alt text-danger"></i>
                    <span>Delete</span>
                </div>
            `;
        }
    }
    
    bindEvents() {
        // Prevent right-click on table rows and show custom menu
        document.addEventListener('contextmenu', (e) => {
            this.handleRightClick(e);
        }, true);
        
        // Handle left click to hide menu
        document.addEventListener('click', (e) => {
            this.handleLeftClick(e);
        });
        
        // Handle menu item clicks
        this.menu.addEventListener('click', (e) => {
            this.handleMenuClick(e);
        });
        
        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideMenu();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            this.hideMenu();
        });
    }
    
    handleRightClick(e) {
        // Check if the click is on a table row
        const row = e.target.closest('tr[data-entry-id]');
        if (row) {
            // Prevent default browser context menu
            e.preventDefault();
            e.stopPropagation();
            
            // IMPORTANT: Prevent the click from bubbling to the checkbox
            this.preventCheckboxSelection(e, row);
            
            // Show our custom menu
            this.showMenu(e, row);
            return false;
        }
    }
    
    preventCheckboxSelection(e, row) {
        // If the click was on or near the checkbox column, prevent it from toggling
        const firstCell = row.querySelector('td:first-child');
        if (firstCell && firstCell.contains(e.target)) {
            // This prevents the DataTable from selecting/deselecting the row
            e.stopImmediatePropagation();
            
            // Also prevent any checkbox changes
            const checkbox = firstCell.querySelector('input[type="checkbox"], .select-checkbox');
            if (checkbox) {
                checkbox.blur(); // Remove focus
            }
        }
    }
    
    handleLeftClick(e) {
        // Hide menu if clicking outside of it
        if (!e.target.closest('#contextMenu')) {
            this.hideMenu();
        }
    }
    
    handleMenuClick(e) {
        const menuItem = e.target.closest('.context-menu-item');
        if (menuItem && !menuItem.classList.contains('disabled')) {
            const action = menuItem.dataset.action;
            this.handleAction(action);
        }
    }
    
    showMenu(e, row) {
        // Clear any previous selections
        this.clearPreviousSelection();
        
        // Store the clicked row
        this.selectedRow = row;
        const recordId = row.dataset.entryId;
        
        // IMPORTANT: DO NOT select the row in DataTable on right-click
        // This prevents the checkbox from being checked
        
        // Position and show menu
        this.positionMenu(e);
        this.menu.classList.add('show');
        
        // Highlight the row visually (but don't select it in DataTable)
        jQuery(row).addClass('context-menu-active');
    }
    
    clearPreviousSelection() {
        // Remove active class from all rows
        jQuery('.context-menu-active').removeClass('context-menu-active');
    }
    
    hideMenu() {
        this.menu.classList.remove('show');
        this.clearPreviousSelection();
    }
    
    positionMenu(e) {
    const menu = this.menu;
    const padding = 10; // Padding from screen edges
    
    // Get mouse coordinates - use pageX/pageY which includes scroll position
    // Fallback to clientX/clientY + scroll for older browsers
    let x = e.pageX || (e.clientX + document.documentElement.scrollLeft);
    let y = e.pageY || (e.clientY + document.documentElement.scrollTop);
    
    // Get menu dimensions
    menu.style.display = 'block';
    const menuWidth = menu.offsetWidth;
    const menuHeight = menu.offsetHeight;
    menu.style.display = '';
    
    // Get viewport dimensions including scroll position
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    
    // Get current scroll position
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Calculate maximum position considering viewport and scroll
    const maxX = scrollLeft + viewportWidth - menuWidth - padding;
    const maxY = scrollTop + viewportHeight - menuHeight - padding;
    
    // Adjust horizontal position if menu would go off screen
    if (x > maxX) {
        x = maxX;
    }
    
    // Adjust vertical position if menu would go off screen
    if (y > maxY) {
        y = maxY;
    }
    
    // Ensure minimum position (considering scroll)
    x = Math.max(scrollLeft + padding, x);
    y = Math.max(scrollTop + padding, y);
    
    // Apply position (using fixed positioning which is relative to viewport)
    menu.style.left = (x - scrollLeft) + 'px';
    menu.style.top = (y - scrollTop) + 'px';
    

}
    
    handleAction(action) {
        if (!this.selectedRow) return;
        
        const recordId = this.selectedRow.dataset.entryId;
        const controlNumber = this.getControlNumberFromRow(this.selectedRow);
        
        this.hideMenu();
        
        switch(action) {
            case 'view':
                this.handleView(recordId);
                break;
            case 'edit':
                this.handleEdit(recordId);
                break;
            case 'delete':
                this.handleDelete(recordId, controlNumber);
                break;
            case 'restore':
                this.handleRestore(recordId, controlNumber);
                break;
            case 'force-delete':
                this.handleForceDelete(recordId, controlNumber);
                break;
        }
    }
    
    getControlNumberFromRow(row) {
        // Find the control number cell (4th cell in the row)
        const cells = row.querySelectorAll('td');
        if (cells.length > 3) {
            return cells[3].textContent.trim();
        }
        return '';
    }
    
    handleView(recordId) {
        window.location.href = `/admin/patient-records/${recordId}`;
    }
    
    handleEdit(recordId) {
        window.location.href = `/admin/patient-records/${recordId}/edit`;
    }
    
    handleDelete(recordId, controlNumber) {
        // Trigger the existing delete modal
        const deleteBtn = document.querySelector(`.delete-single-btn[data-id="${recordId}"]`);
        if (deleteBtn) {
            deleteBtn.click();
        } else {
            // Fallback: show alert and redirect
            if (confirm(`Are you sure you want to delete patient record with Control Number: ${controlNumber}?`)) {
                this.submitForm('DELETE', `/admin/patient-records/${recordId}`);
            }
        }
    }
    
    handleRestore(recordId, controlNumber) {
        // Trigger the existing restore modal
        const restoreBtn = document.querySelector(`.restore-single-btn[data-id="${recordId}"]`);
        if (restoreBtn) {
            restoreBtn.click();
        } else {
            // Fallback
            if (confirm(`Are you sure you want to restore patient record with Control Number: ${controlNumber}?`)) {
                this.submitForm('PUT', `/admin/patient-records/${recordId}/restore`);
            }
        }
    }
    
    handleForceDelete(recordId, controlNumber) {
        // Trigger the existing force delete modal
        const forceDeleteBtn = document.querySelector(`.force-delete-single-btn[data-id="${recordId}"]`);
        if (forceDeleteBtn) {
            forceDeleteBtn.click();
        } else {
            // Fallback
            if (confirm(`Are you sure you want to permanently delete patient record with Control Number: ${controlNumber}? This cannot be undone.`)) {
                this.submitForm('DELETE', `/admin/patient-records/${recordId}/force-delete`);
            }
        }
    }
    
    submitForm(method, action) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = method;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        form.appendChild(methodInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize context menu when DOM is ready
function initializeContextMenu() {
    // Only initialize on pages with the patient records table
    const hasPatientTable = document.querySelector('.datatable-PatientRecord');
    if (!hasPatientTable) return;
    
    // Initialize context menu
    window.contextMenuManager = new ContextMenuManager();
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeContextMenu);
} else {
    initializeContextMenu();
}

// Handle Turbolinks or other SPA frameworks
if (typeof Turbolinks !== 'undefined') {
    document.addEventListener('turbolinks:load', function() {
        if (window.contextMenuManager) {
            // Clean up
            const menu = document.getElementById('contextMenu');
            if (menu) menu.remove();
            window.contextMenuManager = null;
        }
        initializeContextMenu();
    });
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ContextMenuManager;
} else {
    window.ContextMenuManager = ContextMenuManager;
}