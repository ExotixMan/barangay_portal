// Text size adjustment functionality
document.getElementById('increase-text').addEventListener('click', function() {
    changeFontSize(2);
});

document.getElementById('decrease-text').addEventListener('click', function() {
    changeFontSize(-2);
});

function changeFontSize(delta) {
    const currentSize = parseInt(getComputedStyle(document.body).fontSize);
    const newSize = currentSize + delta;
    
    // Limit font size between 12px and 24px
    if (newSize >= 12 && newSize <= 24) {
        document.body.style.fontSize = newSize + 'px';
    }
}

// High contrast mode toggle
document.getElementById('high-contrast-toggle').addEventListener('click', function() {
    document.body.classList.toggle('high-contrast');
    
    // Save preference to localStorage
    const isHighContrast = document.body.classList.contains('high-contrast');
    localStorage.setItem('highContrastMode', isHighContrast);
    
    // Toggle button text
    if (isHighContrast) {
        this.textContent = 'Normal Mode';
    } else {
        this.textContent = 'High Contrast';
    }
});

// Check for saved high contrast preference
document.addEventListener('DOMContentLoaded', function() {
    const highContrastMode = localStorage.getItem('highContrastMode');
    const highContrastToggle = document.getElementById('high-contrast-toggle');
    
    if (highContrastMode === 'true') {
        document.body.classList.add('high-contrast');
        highContrastToggle.textContent = 'Normal Mode';
    }
    
    // Simple form interaction for demonstration
    document.querySelectorAll('.btn-primary').forEach(button => {
        button.addEventListener('click', function() {
            alert('This functionality would open a service request form in a complete implementation. For demonstration purposes only.');
        });
    });
    
    // Navigation active state
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage) {
            link.classList.add('active');
        }
    });
});

// Simple form validation for future implementation
function validateForm(formData) {
    let isValid = true;
    const errors = [];
    
    // Example validation - would be expanded based on form requirements
    if (!formData.name || formData.name.trim().length < 2) {
        isValid = false;
        errors.push('Name is required and must be at least 2 characters long');
    }
    
    if (!formData.email || !formData.email.includes('@')) {
        isValid = false;
        errors.push('A valid email address is required');
    }
    
    return {
        isValid,
        errors
    };
}