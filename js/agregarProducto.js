document.addEventListener('DOMContentLoaded', function() {
    // Handle all "Add to Cart" buttons
    document.querySelectorAll('.aCanasta').forEach(button => {
        button.addEventListener('click', function() {
            // Get the product ID from parent element
            const productArea = this.closest('.producto-area');
            const productId = productArea.getAttribute('data-product-id');
            
            // mandar a servidor via AJAX
            addToCart(productId);
        });
    });

    // AJAX function to add to cart
    function addToCart(productId, cantidad = 1) {
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
                // Show success feedback
                alert('Producto añadido al carrito');
                // You could update a cart counter here
            } else {
                alert(data.message || 'Error al añadir al carrito');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
});