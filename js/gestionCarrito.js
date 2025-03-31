document.addEventListener('DOMContentLoaded', function() {
    
    //#region funciones
    // AJAX gestion de carrito
    function updateCart(action, data) {
        fetch(`index.php?action=${action}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: new URLSearchParams(data).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.cartHtml) {
                document.querySelector("#cart-container").innerHTML = data.cartHtml;
            }
        })
        .catch(error => console.error("Error:", error));
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
        button.addEventListener("click", function (e) {
            e.preventDefault();
            updateCart("cart_add", { product_id: this.dataset.productId });
        });
    });
    // Remover producto
    document.querySelectorAll(".cart-item-remove").forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            if (confirm("¿Eliminar este artículo?")) {
                updateCart("cart_remove", { cart_id: this.dataset.cartId });
            }
        });
    });
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