class CartManager {
    static async getCartData() {
        try {
            const response = await fetch('index.php?action=cart_view', {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            return await response.json();
        } catch (error) {
            console.error("Cart fetch error:", error);
            return { success: false, error: "Error loading cart" };
        }
    }

    static async refreshCartDisplay(containerSelector = '.cart-content') {
        const data = await this.getCartData();
        const container = document.querySelector(containerSelector);
        
        if (data.success && container) {
            container.innerHTML = data.cartHtml;
            return data.count || 0;
        } else if (container) {
            container.innerHTML = data.error || '<div class="cart-error">Error loading cart</div>';
        }
        return 0;
    }
}
class ProfileManager {
    constructor() {
        this.initCartHandlers();
    }

    initCartHandlers() {
        // For cart details (canasta)
        if (document.querySelector('#canasta-tab')) {
            this.loadCartDetails();
        }

        // For checkout
        if (document.querySelector('#checkout-tab')) {
            this.loadCheckoutCart();
        }
    }

    async loadCartDetails() {
        const data = await CartManager.getCartData();
        const container = document.querySelector('.left-body-content');
        
        if (data.success) {
            // Render cart details specific view
            container.innerHTML = this.createCartDetailsView(data.items);
            this.initQuantityControls();
        }
    }

    createCartDetailsView(items) {
        return `
            <div class="cart-details-view">
                ${items.map(item => `
                    <div class="cart-item">
                        <img src="${item.imagen}" alt="${item.nombre}">
                        <div class="item-info">
                            <h4>${item.nombre}</h4>
                            <div class="item-controls">
                                <button class="qty-btn minus" data-id="${item.id}">-</button>
                                <span>${item.cantidad}</span>
                                <button class="qty-btn plus" data-id="${item.id}">+</button>
                            </div>
                        </div>
                        <span class="item-price">$${(item.costo * item.cantidad).toFixed(2)}</span>
                    </div>
                `).join('')}
                <div class="cart-total">
                    Total: $${data.total || 0}
                </div>
            </div>
        `;
    }

    initQuantityControls() {
        // Add your quantity change handlers here
    }
}
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



// Initialize when DOM loads
document.addEventListener('DOMContentLoaded', () => {
    
});