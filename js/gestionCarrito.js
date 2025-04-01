document.addEventListener('DOMContentLoaded', function() {
    
    //#region funciones
    // AJAX gestion de carrito
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
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update cart display
                if (data.cartHtml) {
                    document.querySelectorAll(".cart-content").forEach(container => {
                        container.innerHTML = data.cartHtml;
                    });
                }
                // Update counter
                if (data.count !== undefined) {
                    document.querySelectorAll('.cart-count').forEach(el => {
                        el.textContent = data.count;
                    });
                }
            }
            return data;
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error updating cart");
            throw error;
        });
    }
    // funcion AJAX para agregar a carrito - OLD
    function addToCart(productId,button, cantidad = 1) {
        fetch('index.php?action=add_to_cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&cantidad=${cantidad}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // feedback de exito
                button.classList.add('added');
                alert('Producto añadido al carrito');
                // Contador de carrito (Implementacion en discusion)
            } else {
                alert(data.message || 'Error al añadir al carrito');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
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
        if (eve.target.classList.contains('cart-item-remove')) {
            eve.preventDefault();
            if (confirm("¿Eliminar este artículo?")) {
                updateCart("cart_remove", { 
                    cart_id: this.dataset.cartId,
                    from_dropdown: this.closest('.cart-dropdown-view') ? 1 : 0
                }).then(() => {
                    // Optional: Close dropdown if this was from dropdown
                    if (this.closest('.cart-dropdown-view')) {
                        this.closest('.dropdown-content').classList.remove('show');
                    }
                });
            }
        }
    });
    // document.querySelectorAll(".cart-item-remove").forEach(button => {
    //     button.addEventListener("click", function (e) {
    //         console.log("remove button clicked");
    //         e.preventDefault();
    //         console.log("What now?");
    //         if (confirm("¿Eliminar este artículo?")) {
    //             updateCart("cart_remove", { 
    //                 cart_id: this.dataset.cartId,
    //                 from_dropdown: this.closest('.cart-dropdown-view') ? 1 : 0
    //             }).then(() => {
    //                 // Optional: Close dropdown if this was from dropdown
    //                 if (this.closest('.cart-dropdown-view')) {
    //                     this.closest('.dropdown-content').classList.remove('show');
    //                 }
    //             });
    //         }
    //     });
    // });
    // 
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