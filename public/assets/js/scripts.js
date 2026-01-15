/**
 * scripts.js
 * Client-side JavaScript for Student Information System
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Additional client-side validation can be added here
            const requiredFields = form.querySelectorAll('[required]');
            let hasEmptyFields = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    hasEmptyFields = true;
                    field.style.borderColor = '#e74c3c';
                } else {
                    field.style.borderColor = '#bdc3c7';
                }
            });
            
            if (hasEmptyFields) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
    
    // Auto-hide success messages after 5 seconds
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Confirm delete actions
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        if (!link.onclick) {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this record?')) {
                    e.preventDefault();
                }
            });
        }
    });
    
    // Table row highlighting
    const tableRows = document.querySelectorAll('.student-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't highlight if clicking on action buttons
            if (!e.target.classList.contains('btn')) {
                tableRows.forEach(r => r.style.backgroundColor = '');
                this.style.backgroundColor = '#e8f4f8';
            }
        });
    });
    
    // Mobile menu toggle (if needed in future)
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Input formatting helpers
    const matricNoInputs = document.querySelectorAll('input[name="matric_no"]');
    matricNoInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Convert to uppercase for matric numbers
            this.value = this.value.toUpperCase();
        });
    });
    
    const icNoInputs = document.querySelectorAll('input[name="ic_no"]');
    icNoInputs.forEach(input => {
        input.addEventListener('blur', function() {
            // Format IC number with dashes if not present
            let value = this.value.replace(/-/g, '');
            if (value.length === 12) {
                this.value = value.substring(0, 6) + '-' + 
                             value.substring(6, 8) + '-' + 
                             value.substring(8, 12);
            }
        });
    });
    
    // Semester input validation
    const semesterInputs = document.querySelectorAll('input[name="semester"]');
    semesterInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Ensure semester is between 1 and 14
            const value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 14) this.value = 14;
        });
    });
    
    // Add loading indicator for forms
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = submitBtn.dataset.originalText || 'Submit';
                }, 5000);
            }
        });
    });
    
    // Store original button text
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(btn => {
        btn.dataset.originalText = btn.textContent;
    });
    
    console.log('Student Information System initialized successfully.');
});

/**
 * Helper function to format date/time
 */
function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('en-MY', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Helper function to show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transition = 'opacity 0.5s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}
