class ProfileCartManager {
    constructor() {
        if (document.querySelector('#canasta-tab')) {
            this.initCartHandlers();
            this.loadCartDetails();
            this.formControl();
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
    formControl(){
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
    }
    
}
class ProfileOrdersManager {
    constructor() {
        this.ordersContainer = document.querySelector('.orders-container');
        this.init();
    }

    async init() {
        await this.loadOrders();
    }

    async loadOrders() {
        try {
            const response = await fetch('index.php?action=getOrders', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.renderOrders(data.orders);
            } else {
                this.showErrorMessage(data.message || 'Error al cargar los pedidos');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage('Error de conexión');
        }
    }

    renderOrders(orders) {
        // Clear existing content
        this.ordersContainer.innerHTML = '';
        
        if (!orders || orders.length === 0) {
            this.ordersContainer.innerHTML = `
                <div class="no-orders-message">
                    <p>No existe registro de órdenes del usuario</p>
                </div>
            `;
            return;
        }
        
        orders.forEach(order => {
            const orderElement = this.createOrderElement(order);
            this.ordersContainer.appendChild(orderElement);
        });
    }

    createOrderElement(order) {
        const orderElement = document.createElement('div');
        orderElement.className = 'order-card';
        
        // Format date
        const orderDate = new Date(order.created_at);
        const formattedDate = orderDate.toLocaleDateString('es-MX', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Create order HTML
        orderElement.innerHTML = `
            <div class="order-header">
                <div class="order-number">#${order.id.toString().padStart(5, '0')}</div>
                <div class="order-date">${formattedDate}</div>
                <div class="order-status ${order.estado.toLowerCase().replace(' ', '-')}">
                    ${order.estado}
                </div>
            </div>
            
            <div class="order-products">
                ${order.items.map(item => `
                    <div class="product-row">
                        <span class="product-name">${item.nombre}</span>
                        <span class="product-quantity">x${item.cantidad}</span>
                        <span class="product-price">$${(parseFloat(item.precio_unitario) || 0).toFixed(2)}</span>
                    </div>
                `).join('')}
            </div>
            
            <div class="order-footer">
                <div class="order-total">
                    Total: <span>$${(parseFloat(order.total) || 0).toFixed(2)}</span>
                </div>
                <div class="order-delivery">
                    ${order.tipo_entrega === 'envio' ? 'Envío a domicilio' : 'Recoger en tienda'}
                </div>
            </div>
        `;
        
        return orderElement;
    }

    showErrorMessage(message) {
        this.ordersContainer.innerHTML = `
            <div class="error-message">
                <p>${message}</p>
                <button class="retry-button">Reintentar</button>
            </div>
        `;
        
        // Add retry functionality
        this.ordersContainer.querySelector('.retry-button')?.addEventListener('click', () => {
            this.loadOrders();
        });
    }
}
class ProfileUserManager {
    constructor() {
        this.userForm = document.getElementById('userProfileForm');
        this.rightSideUsername = document.querySelector('.user-name p');
        this.rightSideDetails = document.querySelectorAll('.detail-item');
        this.init();
    }

    async init() {
        await this.loadUserInfo();
        this.setupEventListeners();
    }

    async loadUserInfo() {
        try {
            const response = await fetch('index.php?action=getUserInfo', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.updateAllUserDisplays(data.data);
            } else {
                this.showErrorMessage(data.message || 'Error al cargar información del usuario');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage('Error de conexión');
        }
    }

    updateAllUserDisplays(userData) {
        // Update form fields
        if (this.userForm) {
            this.userForm.username.value = userData.username || '';
            this.userForm.direccion.value = userData.direccion || '';
            this.userForm.telefono.value = userData.telefono || '';
        }
        
        // Update right sidebar
        if (this.rightSideUsername) {
            this.rightSideUsername.textContent = userData.username || 'Nombre del Usuario';
        }
        
        if (this.rightSideDetails.length >= 3) {
            this.rightSideDetails[0].textContent = userData.email || 'user@example.com';
            this.rightSideDetails[1].textContent = userData.telefono || '+52 55 1234 5678';
            this.rightSideDetails[2].textContent = userData.direccion || 'Calle Falsa 123, Col. Centro, CDMX';
        }
    }

    setupEventListeners() {
        if (this.userForm) {
            this.userForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.handleFormSubmit();
            });
            
            // Cancel button handler
            const cancelBtn = this.userForm.querySelector('.cancel-btn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    this.loadUserInfo(); // Reset form to current values
                });
            }
        }
    }

    async handleFormSubmit() {
        const formData = {
            username: this.userForm.username.value.trim(),
            direccion: this.userForm.direccion.value.trim(),
            telefono: this.userForm.telefono.value.trim(),
            current_password: this.userForm.current_password.value,
            new_password: this.userForm.new_password.value,
            confirm_password: this.userForm.confirm_password.value
        };

        // Basic client-side validation
        if (formData.new_password && formData.new_password !== formData.confirm_password) {
            this.showErrorMessage('Las contraseñas no coinciden');
            return;
        }

        try {
            const response = await fetch('index.php?action=updateUserInfo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccessMessage(data.message || 'Información actualizada correctamente');
                this.updateAllUserDisplays(data.user || data.data);
                this.userForm.current_password.value = '';
                this.userForm.new_password.value = '';
                this.userForm.confirm_password.value = '';
            } else {
                this.showErrorMessage(data.message || 'Error al actualizar la información');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showErrorMessage('Error de conexión');
        }
    }

    showSuccessMessage(message) {
        // Remove any existing messages
        this.removeMessages();
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'success-message';
        messageDiv.textContent = message;
        this.userForm.prepend(messageDiv);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }

    showErrorMessage(message) {
        // Remove any existing messages
        this.removeMessages();
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'error-message';
        messageDiv.textContent = message;
        this.userForm.prepend(messageDiv);
    }

    removeMessages() {
        const existingMessages = this.userForm.querySelectorAll('.error-message, .success-message');
        existingMessages.forEach(msg => msg.remove());
    }
}

// Initializa clases al cargar
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#canasta-tab')) {
        new ProfileCartManager();
    }
    if (document.querySelector('.orders-container')) {
        new ProfileOrdersManager();
    }
    if (document.getElementById('userProfileForm')) {
        new ProfileUserManager();
    }
});


