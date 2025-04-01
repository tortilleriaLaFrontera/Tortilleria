document.querySelector('.user-nav').addEventListener('click', (e) => {
    if (e.target.classList.contains('nav-button')) {
        e.preventDefault();
        const action = e.target.textContent.trim().toLowerCase();
        const actionMap = {
            'canasta': 'cartdetails',
            'pedidos': 'checkout',
            'información de usuario': 'perfil'
        };
        window.location.href = `index.php?action=${actionMap[action]}`;
    }
});

function loadProfileView(view) {
    fetch(`perfil.php?view=${view}&ajax=1`)
        .then(response => response.text())
        .then(html => {
            document.querySelector('.left-body-content').innerHTML = html;
        });
}

// Handle back/forward navigation
window.addEventListener('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const view = urlParams.get('view') || 'default';
    loadProfileView(view);
});

class ProfileManager {
    constructor() {
        this.currentSection = document.querySelector('.perfil-left h2').textContent.toLowerCase();
        this.initSectionSpecificHandlers();
    }
    
    initSectionSpecificHandlers() {
        switch(this.currentSection) {
            case 'carrito':
                this.initCartHandlers();
                break;
            case 'checkout':
                this.initCheckoutHandlers();
                break;
            // ... other sections
        }
    }
    
    initCartHandlers() {
        // Quantity controls
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', this.handleQuantityChange);
        });
        
        // Removal handlers
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', this.handleItemRemoval);
        });
    }
    
    handleQuantityChange(e) {
        const itemId = this.dataset.itemId;
        const action = this.classList.contains('plus') ? 'increase' : 'decrease';
        
        fetch(`index.php?action=update_qty`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `item_id=${itemId}&action=${action}`
        }).then(/* ... */);
    }
    
    handleItemRemoval(e) {
        // ... existing removal logic
    }
    
    // ... other section handlers
}

// Initialize when DOM loads
document.addEventListener('DOMContentLoaded', () => {
    new ProfileManager();
});