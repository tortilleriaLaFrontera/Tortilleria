<?php include_once './views/templates/header.php'; ?>

<!-- Sección de Contacto -->
<section class="contacto-container">
    <div class="contacto-info">
        <h2>Contáctanos</h2>
        
        <div class="contacto-card">
            <div class="contacto-icon">
                <img src="img/phone-icon.png" alt="Teléfono">
            </div>
            <div class="contacto-details">
                <h3>Pedidos al:</h3>
                <p>(686) 384 03 01</p>
                <p>(686) 394 41 05</p>
            </div>
        </div>
        
        <div class="contacto-card">
            <div class="contacto-icon">
                <img src="img/location-icon.png" alt="Ubicación">
            </div>
            <div class="contacto-details">
                <h3>Dirección:</h3>
                <p>Calle cuarta y palmar de santa Anita</p>
                <p>Mexicali II, Mexico</p>
                <a href="https://g.co/kgs/5k5bcpF" class="map-link">Cómo llegar</a>
            </div>
        </div>
        
        <div class="contacto-card">
            <div class="contacto-icon">
                <img src="img/clock-icon.png" alt="Horario">
            </div>
            <div class="contacto-details">
                <h3>Horario:</h3>
                <p>Abierto</p>
                <p>Lunes a Domingo</p>
            </div>
        </div>
        
        <div class="contacto-card">
            <div class="contacto-icon">
                <img src="img/facebook-icon.png" alt="Facebook">
            </div>
            <div class="contacto-details">
                <h3>Síguenos:</h3>
                <a href="https://www.facebook.com/profile.php?id=100063449689295" target="_blank" class="social-link">La Frontera Tortillería</a>
            </div>
        </div>
    </div>
    
    <div class="contacto-form">
        <h2>Envíanos un Mensaje</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono">
            </div>
            
            <div class="form-group">
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Enviar Mensaje</button>
        </form>
    </div>
    
    <div class="contacto-map">
        <h2>Nuestra Ubicación</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3359.0404403321105!2d-115.5!3d32.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDM2JzAwLjAiTiAxMTXCsDMwJzAwLjAiVw!5e0!3m2!1sen!2sus!4v1619749051072!5m2!1ses!2smx" 
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</section>

<?php include_once './views/templates/footer.php'; ?>