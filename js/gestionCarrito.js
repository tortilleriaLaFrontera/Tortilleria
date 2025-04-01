document.addEventListener('DOMContentLoaded', function() {
    
    
    function updateCart(action, data) {
        return fetch(`index.php?action=${action}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: new URLSearchParams(data).toString()
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            // SUCCESS CASE - UNCHANGED (works for both cart_add/cart_remove)
            if (data.success) {
                // Update cart display (if HTML provided)
                if (data.cartHtml) {
                    document.querySelectorAll(".cart-content").forEach(container => {
                        container.innerHTML = data.cartHtml;
                    });
                }
                // Update counter (if count provided)
                if (data.count !== undefined) {
                    document.querySelectorAll('.cart-count').forEach(el => {
                        el.textContent = data.count;
                    });
                }
                // NEW: Handle dropdown closing (only for cart_remove)
                if (action === 'cart_remove' && data.from_dropdown) {
                    document.querySelector('.dropdown-content')?.classList.remove('show');
                }
            } 
            // ERROR CASE - IMPROVED (but still compatible)
            else {
                console.error("Server reported error:", data.message || "Unknown error");
                alert(data.message || "Operation failed"); // More specific error
            }
            return data; // Maintain chainability
        })
        .catch(error => {
            // IMPROVED ERROR HANDLING (non-breaking)
            console.error("Full error:", {
                error: error.message,
                action: action,
                data: data
            });
            
            // Show user-friendly message
            const message = error.response?.data?.message 
                || error.message 
                || "Network error";
                
            alert(`Error during ${action}: ${message}`);
            throw error; // Still propagates for other .catch()
        });
    }
    
    
    //#endregion
    
    //#region Eventos
    // Agregar producto
    document.querySelectorAll(".cart-item-add").forEach(button => {
        button.addEventListener("click", function (eve) {
            eve.preventDefault();
            updateCart("cart_add", { product_id: button.dataset.productId });
        });
    });
    // Remover producto
    document.addEventListener("click", function (eve) {
        const removeBtn = eve.target.closest('.cart-item-remove');
        if (removeBtn) {
            eve.preventDefault();
            
            if (confirm("¿Eliminar este artículo?")) {
                updateCart("cart_remove", { 
                    cart_id: removeBtn.dataset.cartId,
                    
                }).then(() => {
                    viewCart();
                });
            }
        }
    });
    document.querySelectorAll('.aCanasta').forEach(button => {
        button.addEventListener('click', function() {
            //extraer el id de producto del papa
            const productArea = this.closest('.producto-area');
            const productId = productArea.getAttribute('data-product-id');
            
            // mandar a servidor via AJAX
            addToCart(productId, this);
        });
    });
    
    //#endregion
    

    
});