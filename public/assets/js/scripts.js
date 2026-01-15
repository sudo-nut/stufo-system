/**
 * Student Information System JavaScript
 * 
 * Client-side enhancements for better user experience
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Debounced search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        
        // Add real-time search indicator
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Show search is being processed
            searchInput.style.backgroundColor = '#fff3cd';
            
            searchTimeout = setTimeout(function() {
                // Reset background after typing stops
                searchInput.style.backgroundColor = '';
            }, 500);
        });
    }
    
    // Delete confirmation enhancement
    const deleteLinks = document.querySelectorAll('a.btn-danger');
    deleteLinks.forEach(function(link) {
        if (link.href.includes('student_delete.php')) {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
    
    // Form validation enhancement
    const studentForm = document.getElementById('studentForm');
    if (studentForm) {
        studentForm.addEventListener('submit', function(e) {
            const errors = [];
            
            // Validate matric number
            const matricNo = document.getElementById('matric_no');
            if (matricNo && matricNo.value.trim() === '') {
                errors.push('Matric number is required');
                matricNo.style.borderColor = '#e74c3c';
            }
            
            // Validate IC number
            const icNo = document.getElementById('ic_no');
            if (icNo && icNo.value.trim() === '') {
                errors.push('IC number is required');
                icNo.style.borderColor = '#e74c3c';
            }
            
            // Validate full name
            const fullName = document.getElementById('full_name');
            if (fullName && fullName.value.trim() === '') {
                errors.push('Full name is required');
                fullName.style.borderColor = '#e74c3c';
            }
            
            // Validate email
            const email = document.getElementById('email');
            if (email) {
                const emailValue = email.value.trim();
                if (emailValue === '') {
                    errors.push('Email is required');
                    email.style.borderColor = '#e74c3c';
                } else if (!isValidEmail(emailValue)) {
                    errors.push('Invalid email format');
                    email.style.borderColor = '#e74c3c';
                }
            }
            
            // Validate gender
            const gender = document.getElementById('gender');
            if (gender && gender.value === '') {
                errors.push('Gender is required');
                gender.style.borderColor = '#e74c3c';
            }
            
            // Validate program
            const program = document.getElementById('program');
            if (program && program.value.trim() === '') {
                errors.push('Program is required');
                program.style.borderColor = '#e74c3c';
            }
            
            // Validate year of study
            const yearOfStudy = document.getElementById('year_of_study');
            if (yearOfStudy && yearOfStudy.value === '') {
                errors.push('Year of study is required');
                yearOfStudy.style.borderColor = '#e74c3c';
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Please correct the following errors:\n\n' + errors.join('\n'));
                return false;
            }
        });
        
        // Reset border color on input
        const formInputs = studentForm.querySelectorAll('input, select, textarea');
        formInputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.style.borderColor = '';
            });
        });
    }
    
    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Highlight active navigation item
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(function(link) {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
    
    // Auto-dismiss success messages after 5 seconds
    const successAlerts = document.querySelectorAll('.alert-success');
    successAlerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
    
    // Add loading indicator for form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                submitButton.disabled = true;
                submitButton.innerHTML = submitButton.innerHTML + ' <span>...</span>';
                
                // Re-enable after 5 seconds as fallback
                setTimeout(function() {
                    submitButton.disabled = false;
                }, 5000);
            }
        });
    });
    
    // Keyboard navigation for tables
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(function(row, index) {
        row.setAttribute('tabindex', '0');
        row.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const viewLink = row.querySelector('a.btn-info');
                if (viewLink) {
                    window.location.href = viewLink.href;
                }
            }
        });
    });
    
});
