function showLoading() {
    loading.style.display = 'block';
}
// Función para ocultar la imagen de carga
function hideLoading() {
    loading.style.display = 'none';
}

function fetchJSON(url) {
    showLoading(); // Mostrar loading


    let ventanaModalLabel     = document.getElementById('ventanaModalLabel');
    let contenidoVentanaModal = document.getElementById('contenidoVentanaModal');



//NS QUE ESTOY HACIENDO -.-
    var formData = new FormData(ventanaModalLabel);
    formData.forEach(function(value,key) { 
        console.log(key + ": " + value)
    });


    //ESTO NO ES YA LA FUNCIÓN PNTAR??

    fetch('/mi-endpoint', {
        method: 'POST',
        body: formData 
    });


///////////

    fetch(url).then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json(); // Convertir respuesta a JSON
    })
    .then(data => {

        ventanaModalLabel.innerHTML = data.titulo;
        contenidoVentanaModal.innerHTML = data.contenido;
    })
    .catch(error => {
        ventanaModalLabel.innerHTML = 'Error insesperado';
        contenidoVentanaModal.innerHTML = `<p style="color: red;">Error:
        ${error.message}</p>`;
    })
    .finally(() => {
        hideLoading(); // Ocultar loading
    });
}