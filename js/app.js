class CartManager {
    constructor() {
        this.cartCountElement = document.querySelector('.cart-count');
        this.init();
    }

    async init() {
        await this.updateCartCount();
        // Refresh count every 30 seconds to stay synced
        setInterval(() => this.updateCartCount(), 30000);
    }

    async updateCartCount() {
        try {
            const response = await fetch('index.php?action=get_cart_count', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (!response.ok) throw new Error('Network error');
            
            const data = await response.json();
            
            if (data.success) {
                this.cartCountElement.textContent = data.count;
                // Update badge visibility
                if (data.count > 0) {
                    this.cartCountElement.style.display = 'inline-block';
                } else {
                    this.cartCountElement.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Failed to update cart count:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.cart-count')) {
        new CartManager();
    }
    // =====================
    // MENU FUNCTIONS
    // =====================
    function toggleMobileMenu() {
        const enlaces = document.querySelector('.links');
        enlaces.classList.toggle('active');
    }

    function closeMenuOnClickOutside(event) {
        const menu = document.querySelector('.links');
        const btnMenu = document.querySelector('.menu');

        if (!menu.contains(event.target) && !btnMenu.contains(event.target)) {
            menu.classList.remove('active');
        }
    }

    // =====================
    // DROPDOWN
    // =====================
    function viewCart() {
        fetch('index.php?action=cart_view', {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector('.cart-content').innerHTML = data.cartHtml;

            if (data.count === 0) {
                document.querySelector('.dropdown-content').classList.remove('show');
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.querySelector('.cart-content').innerHTML = 
                '<div class="cart-error">Error loading cart</div>';
        });
    }
    
    function toggleDropdown(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const dropdown = this.closest('.dropdown');
        const content = dropdown.querySelector('.dropdown-content');
        
        // Close all other dropdowns first
        closeAllDropdowns(content);
        
        // Toggle current dropdown
        content.classList.toggle('show');

        if (this.classList.contains('cart-toggle') && content.classList.contains('show')) {
            viewCart(); 
        }
    }

    function closeAllDropdowns(exceptThisOne = null) {
        document.querySelectorAll('.dropdown-content').forEach(content => {
            if (content !== exceptThisOne) {
                content.classList.remove('show');
            }
        });
    }

    function closeDropdownsOnClickOutside(e) {
        if (!e.target.closest('.dropdown')) {
            closeAllDropdowns();
        }
    }

    function handleMobileCartRedirect(e) {
        if (window.innerWidth <= 768) {
            const cartLink = this.getAttribute('data-cart-link');
            if (cartLink) {
                window.location.href = cartLink;
            }
        }
    }

    // =====================
    // EVENT LISTENERS
    // =====================
    // Menu listeners
    document.querySelector('.menu').addEventListener('click', toggleMobileMenu);
    document.addEventListener('click', closeMenuOnClickOutside);

    // Dropdown listeners
    document.querySelectorAll('.cart-toggle, .profile-toggle').forEach(toggle => {
        toggle.addEventListener('click', toggleDropdown);
    });
    document.addEventListener('click', closeDropdownsOnClickOutside);
    
    // Mobile cart redirect
    document.querySelector('.cart-toggle')?.addEventListener('click', handleMobileCartRedirect);
});
