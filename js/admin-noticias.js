$(document).ready(function() {
    cargarNoticias();

    $('#noticia_imagen').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#previewImagenNoticia').html(
                    '<img src="' + event.target.result + '" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">'
                );
            };
            reader.readAsDataURL(file);
        } else {
            $('#previewImagenNoticia').empty();
        }
    });
});

function cargarNoticias() {
    $.ajax({
        url: 'api/noticias.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarNoticias(response.data);
            } else {
                mostrarErrorNoticias(response.message || 'Error al cargar las noticias');
            }
        },
        error: function() {
            mostrarErrorNoticias('Error al cargar las noticias');
        }
    });
}

function mostrarNoticias(noticias) {
    const tbody = $('#tbodyNoticias');
    tbody.empty();

    if (!Array.isArray(noticias) || noticias.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center text-muted">No hay noticias registradas</td></tr>');
        return;
    }

    noticias.forEach(function(noticia) {
        const imagen = noticia.imagen
            ? `<img src="../Recursos/imagenes/noticias/${noticia.imagen}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">`
            : '<span class="text-muted">Sin imagen</span>';

        const estado = noticia.activo == 1
            ? '<span class="badge bg-success">Activa</span>'
            : '<span class="badge bg-secondary">Inactiva</span>';

        const fecha = noticia.fecha_publicacion
            ? new Date(noticia.fecha_publicacion).toLocaleDateString('es-AR')
            : '-';

        const row = `
            <tr>
                <td>${noticia.id}</td>
                <td>${imagen}</td>
                <td>${escapeHtml(noticia.titulo || '')}</td>
                <td>${fecha}</td>
                <td>${estado}</td>
                <td>${noticia.orden ?? 0}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editarNoticia(${noticia.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarNoticia(${noticia.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function mostrarErrorNoticias(mensaje) {
    $('#tbodyNoticias').html(`<tr><td colspan="7" class="text-center text-danger">${mensaje}</td></tr>`);
}

function abrirModalNuevaNoticia() {
    $('#formNoticia')[0].reset();
    $('#noticia_id').val('');
    $('#modalNoticiaLabel').text('Nueva Noticia');
    $('#previewImagenNoticia').empty();
    $('#noticia_activo').prop('checked', true);
}

function editarNoticia(id) {
    $.ajax({
        url: 'api/noticias.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const noticia = response.data.find(n => n.id == id);
                if (noticia) {
                    $('#noticia_id').val(noticia.id);
                    $('#noticia_titulo').val(noticia.titulo);
                    $('#noticia_resumen').val(noticia.resumen);
                    $('#noticia_contenido').val(noticia.contenido);
                    $('#noticia_fecha_publicacion').val(noticia.fecha_publicacion || '');
                    $('#noticia_orden').val(noticia.orden ?? 0);
                    $('#noticia_activo').prop('checked', noticia.activo == 1);

                    if (noticia.imagen) {
                        $('#previewImagenNoticia').html(
                            `<img src="../Recursos/imagenes/noticias/${noticia.imagen}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`
                        );
                    } else {
                        $('#previewImagenNoticia').empty();
                    }

                    $('#modalNoticiaLabel').text('Editar Noticia');
                    $('#modalNoticia').modal('show');
                }
            } else {
                alert(response.message || 'No fue posible obtener la noticia seleccionada.');
            }
        },
        error: function() {
            alert('Error al obtener la noticia seleccionada.');
        }
    });
}

function guardarNoticia() {
    const form = $('#formNoticia')[0];
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    const id = $('#noticia_id').val();
    if (id) {
        formData.append('id', id);
    }

    $.ajax({
        url: 'api/noticias.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalNoticia').modal('hide');
                cargarNoticias();
                alert('Noticia guardada exitosamente');
            } else {
                alert('Error: ' + (response.message || 'No se pudo guardar la noticia'));
            }
        },
        error: function(xhr) {
            let message = 'Error al guardar la noticia';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}

function eliminarNoticia(id) {
    if (!confirm('¿Está seguro de eliminar esta noticia?')) {
        return;
    }

    $.ajax({
        url: 'api/noticias.php?id=' + id,
        method: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                cargarNoticias();
                alert('Noticia eliminada exitosamente');
            } else {
                alert('Error: ' + (response.message || 'No se pudo eliminar la noticia'));
            }
        },
        error: function() {
            alert('Error al eliminar la noticia');
        }
    });
}

if (typeof window.escapeHtml !== 'function') {
    window.escapeHtml = function(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    };
}


