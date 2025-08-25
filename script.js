let deferredPrompt;

// Detectamos si la PWA puede ser instalada
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevenir que el navegador muestre el banner automáticamente
    e.preventDefault();

    // Guardamos el evento para mostrar el banner cuando lo necesitemos
    deferredPrompt = e;

    // Llamamos a prompt() cuando quieras mostrar el banner
    // Aquí puedes agregar la lógica para mostrar un mensaje personalizado, etc.
    console.log('La app es instalable');

    // Mostrar el banner en un lugar adecuado (por ejemplo, un banner en la UI)
    showInstallBanner();
});

// Función para mostrar un banner personalizadobi
function showInstallBanner() {
    const installBanner = document.createElement('div');
    installBanner.innerHTML = `
    <div style="position: fixed; bottom: 10px; left: 0; right: 0; background: #333; color: white; padding: 10px; text-align: center;">
      ¡Puedes instalar esta aplicación! <button id="installButton">Instalar</button>
    </div>
  `;
    document.body.appendChild(installBanner);

    // Manejamos el clic en el botón para mostrar el prompt de instalación
    const installButton = document.getElementById('installButton');
    installButton.addEventListener('click', () => {
        // Mostramos el cuadro de instalación
        deferredPrompt.prompt();

        // Esperamos la respuesta del usuario
        deferredPrompt.userChoice
            .then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('El usuario ha instalado la PWA');
                } else {
                    console.log('El usuario ha rechazado la instalación');
                }
                // Limpiamos la referencia
                deferredPrompt = null;
            });
    });
}
