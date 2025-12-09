
const loading = document.getElementById('loading');
const result = document.getElementById('result');
// Función para mostrar la imagen de carga
function showLoading() {
    loading.style.display = 'block';
}
// Función para ocultar la imagen de carga
function hideLoading() {
    loading.style.display = 'none';
}

function fetchJSON() {
    showLoading(); // Mostrar loading
    fetch('server-json.php').then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.json(); // Convertir respuesta a JSON
    })
    .then(data => {
        result.innerHTML = `
            <p>Mensaje: ${data.mensaje}</p>
            <p>Fecha: ${data.fecha}</p>
        `;
    })
    .catch(error => {
        result.innerHTML = `<p style="color: red;">Error:
        ${error.message}</p>`;
    })
    .finally(() => {
        hideLoading(); // Ocultar loading
    });
}


function fetchText() {
    showLoading(); // Mostrar loading

fetch('/mi-endpoint');

    fetch('server-text.php', {
        method: 'POST',
        body: formData // El objeto FormData contiene los datos del
    }).then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        return response.text(); // Convertir respuesta a texto
    })
    .then(data => {
        result.innerHTML = `<p>${data}</p>`;
    })
    .catch(error => {
        result.innerHTML = `<p style="color: red;">Error:
        ${error.message}</p>`;
    })
    .finally(() => {
        hideLoading(); // Ocultar loading
    });
}

// ----------------------------
// Fichero: profesores.js
document.addEventListener('DOMContentLoaded', () => {
    const checkTutor = document.getElementById('es_tutor_check');
    const contenedorCursos = document.getElementById('contenedor_cursos');

    // Función para manejar el estado
    function toggleCurso() {
        if (checkTutor.checked) {
            contenedorCursos.style.display = 'block'; 
        } else {
            contenedorCursos.style.display = 'none'; 
        }
    }

    // Evento: Al hacer clic en el checkbox
    checkTutor.addEventListener('change', toggleCurso);

    // Ejecutar al inicio para manejar la carga inicial (ej: en UPDATE)
    toggleCurso();
});


// Llamada AJAX al hacer clic en el botón 'Ver Horario'
document.getElementById('enlace_horario').addEventListener('click', (e) => {
    e.preventDefault(); 
    const idProfesor = e.target.dataset.profesorId; // Asumiendo que el ID está en un atributo data-

    fetch(`api/horario.php?id_profesor=${idProfesor}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal_body_horario').innerHTML = data.html;
            // Mostrar modal de Bootstrap aquí
        })
        .catch(error => console.error('Error cargando horario:', error));
});