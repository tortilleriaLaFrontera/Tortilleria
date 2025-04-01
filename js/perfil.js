document.querySelectorAll('.nav-button').forEach(button => {
    button.addEventListener('click', function() {
        const view = this.dataset.view;
        window.history.pushState({}, '', `perfil.php?view=${view}`);
        loadProfileView(view);
    });
});

function loadProfileView(view) {
    fetch(`perfil.php?view=${view}&ajax=1`)
        .then(response => response.text())
        .then(html => {
            document.querySelector('.left-body-content').innerHTML = html;
        });
}

// Handle back/forward navigation
window.addEventListener('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const view = urlParams.get('view') || 'default';
    loadProfileView(view);
});