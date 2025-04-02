class ProfileCartManager {
    constructor() {
        if (document.querySelector('#canasta-tab')) {
            this.initCartHandlers();
            this.loadCartDetails();
        }
    }

    // listeners de eventos en botones de productos
    async initCartHandlers() {
        document.addEventListener('click', async (e) => {
            const minusBtn = e.target.closest('.qty-btn.minus');
            const plusBtn = e.target.closest('.qty-btn.plus');
            const removeBtn = e.target.closest('.remove-item');
            
            if (minusBtn || plusBtn) {
                e.preventDefault();
                const action = minusBtn ? 'cart_decrease' : 'cart_increase';
                const cartId = (minusBtn || plusBtn).dataset.id;
                await this.updateCartItem(action, cartId);
            }
            
            if (removeBtn) {
                e.preventDefault();
                if (confirm("¿Eliminar este artículo?")) {
                    await this.updateCartItem('cart_remove', removeBtn.dataset.id);
                }
            }
        });
    }

    escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    showCartError() {
        const container = document.querySelector('.cart-container');
        if (container) {
            container.innerHTML = '<div class="cart-error">Error loading cart</div>';
        }
    }

    async loadCartDetails() {
        try {
            const response = await fetch('index.php?action=view_cart', {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const data = await response.json();
            
            if (data.success) {
                this.renderCartItems(data);
            } else {
                this.showCartError();
            }
        } catch (error) {
            console.error("Cart load error:", error);
            this.showCartError();
        }
    }

    //crea estructura de carrito en contenedor -> actualiza la cuenta de productos
    renderCartItems(data) {
        const container = document.querySelector('.cart-container');
        if (!container) return;
        
        if (!data.items || data.items.length === 0) {
            container.innerHTML = '<div class="empty-cart">El carrito está vacío</div>';
            return;
        }
        
        container.innerHTML = data.items.map(item => `
            <div class="cart-item" data-id="${item.id}">
                <img src="${this.escapeHtml(item.imagen)}" alt="${this.escapeHtml(item.nombre)}">
                <div class="item-details">
                    <h4>${this.escapeHtml(item.nombre)}</h4>
                    <p>${this.escapeHtml(item.descripcion)}</p>
                    <div class="item-controls">
                        <button class="qty-btn minus" data-id="${item.id}">-</button>
                        <span class="quantity">${item.cantidad}</span>
                        <button class="qty-btn plus" data-id="${item.id}">+</button>
                    </div>
                </div>
                <div class="item-price">$${(item.costo * item.cantidad).toFixed(2)}</div>
                <button class="remove-item" data-id="${item.id}">🗑️</button>
            </div>
        `).join('') + `<div class="cart-total">Total: $${data.total?.toFixed(2) || '0.00'}</div>`;
        
        this.updateCartCount(data.count);
    }
    //llama al enrutador para acciones de carrito -> si success? espera [success,count,total] si no [success,message]
    async updateCartItem(action, cartId) {
        try {
            const response = await fetch(`index.php?action=${action}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: new URLSearchParams({ cart_id: cartId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.loadCartDetails();
                this.updateCartCount(data.count);
                
                if (data.total !== undefined) {
                    this.updateCartTotal(data.total);
                }
            } else {
                alert(data.message || "Error al actualizar");
            }
        } catch (error) {
            console.error("Update error:", error);
            alert("Error de conexión");
        }
    }

    //afecta todo lo que tenga cart-count
    updateCartCount(count) {
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = count || 0;
        });
    }

    updateCartTotal(total) {
        const totalElement = document.querySelector('.cart-total');
        if (totalElement) {
            totalElement.textContent = `Total: $${total?.toFixed(2) || '0.00'} MXN`;
        }
    }
}

// Initialize when profile page loads
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#canasta-tab')) {
        new ProfileCartManager();
    }


    // Show/hide address field based on delivery type
    document.querySelectorAll('input[name="delivery_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('deliveryNotes').style.display = 
                this.value === 'envio' ? 'block' : 'none';
        });
    });

    // Handle form submission
    document.getElementById('orderForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const generateBtn = document.getElementById('generateOrderBtn');
        generateBtn.disabled = true;
        generateBtn.textContent = 'Procesando...';
        
        try {
            // 1. Recibir datos de usuario
            const userResponse = await fetch('index.php?action=userInfo', {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const usrData = await userResponse.json(); if (!usrData.success) { throw new Error('Los datos de usuario no fueron recibidos'); }
            
            // 2. recibir datos de carrito y de producto
            const cartResponse = await fetch('index.php?action=view_cart_full', {  
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            const cartData = await cartResponse.json();
            
            if (!cartData.success || !cartData.items?.length) {
                throw new Error('El carrito está vacío');
            }
    
            // 3. Prepare order data with ALL required fields
            const formData = new FormData(this);
            const orderData = {
                id_usuario: usrData.data.id,
                total: cartData.costoTotal,
                tipo_entrega: formData.get('delivery_type'),
                direccion_entrega: formData.get('delivery_type') === 'envio' //es envio?
                        ? formData.get('address').length > 0 //envio y length?
                            ? formData.get('address') : usrData.data.direccion //envio y length y truthy? 
                                ? usrData.data.direccion //envio, !length y falsy?
                                    : null //envio,!length y falsy = nulo
                                    : null, //!envio == nulo
                items: cartData.items.map(item => ({
                    cart_id: item.id,           
                    id_producto: item.producto_id,
                    id_usuario: item.user_id,      
                    cantidad: item.cantidad,
                    precio_unitario: item.costo
                }))
            };
            
            // 4. Submit order
            const response = await fetch('index.php?action=create_order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(orderData)
            });
    
            const result = await response.json();
            
            if (result.success) {
                alert(`Pedido #${result.orderId} creado!`);
                window.location.href = 'index.php?action=checkout';
            } else {
                throw new Error(result.message || 'Error al generar pedido');
            }
        } catch (error) {
            console.error('Order error:', error);
            alert('Error: ' + error.message);
        } finally {
            generateBtn.disabled = false;
            generateBtn.textContent = 'Generar Pedido';
        }
    });
});


