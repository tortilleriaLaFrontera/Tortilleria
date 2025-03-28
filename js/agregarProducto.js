document.addEventListener('DOMContentLoaded', function() {
    // Manejo de botones "Agregar a carrito"
    document.querySelectorAll('.aCanasta').forEach(button => {
        button.addEventListener('click', function() {
            //extraer el id de producto del papa
            const productArea = this.closest('.producto-area');
            const productId = productArea.getAttribute('data-product-id');
            
            // mandar a servidor via AJAX
            addToCart(productId, this);
        });
    });

    // funcion AJAX para agregar a carrito
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

    
});