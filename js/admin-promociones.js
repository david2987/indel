$(document).ready(function() {
    cargarPromociones();
    
    // Preview de imagen
    $('#imagen').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImagen').html(
                    '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">'
                );
            };
            reader.readAsDataURL(file);
        }
    });
});

function cargarPromociones() {
    $.ajax({
        url: 'api/promociones.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarPromociones(response.data);
            }
        },
        error: function() {
            $('#tbodyPromociones').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar las promociones</td></tr>');
        }
    });
}

function mostrarPromociones(promociones) {
    const tbody = $('#tbodyPromociones');
    tbody.empty();
    
    if (promociones.length === 0) {
        tbody.html('<tr><td colspan="8" class="text-center text-muted">No hay promociones registradas</td></tr>');
        return;
    }
    
    promociones.forEach(function(promocion) {
        const imagen = promocion.imagen 
            ? `<img src="../Recursos/imagenes/promociones/${promocion.imagen}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">`
            : '<span class="text-muted">Sin imagen</span>';
        
        const estado = promocion.activo == 1 
            ? '<span class="badge bg-success">Activa</span>' 
            : '<span class="badge bg-secondary">Inactiva</span>';
        
        // const fechaInicio = promocion.fecha_inicio ? new Date(promocion.fecha_inicio).toLocaleDateString('es-AR') : '-';
        // const fechaFin = promocion.fecha_fin ? new Date(promocion.fecha_fin).toLocaleDateString('es-AR') : '-';
        const fechaInicio = formatearFechaDMY(promocion.fecha_inicio)  ;
        const fechaFin = formatearFechaDMY(promocion.fecha_fin);
        const row = `
            <tr>
                <td>${promocion.id}</td>
                <td>${imagen}</td>
                <td>${escapeHtml(promocion.titulo)}</td>
                <td>${fechaInicio}</td>
                <td>${fechaFin}</td>
                <td>${estado}</td>
                <td>${promocion.orden}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editarPromocion(${promocion.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarPromocion(${promocion.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function abrirModalNuevo() {
    $('#formPromocion')[0].reset();
    $('#promocion_id').val('');
    $('#modalPromocionLabel').text('Nueva Promoción');
    $('#previewImagen').empty();
    $('#activo').prop('checked', true);
}

function editarPromocion(id) {
    $.ajax({
        url: 'api/promociones.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const promocion = response.data.find(p => p.id == id);
                if (promocion) {
                    $('#promocion_id').val(promocion.id);
                    $('#titulo').val(promocion.titulo);
                    $('#descripcion').val(promocion.descripcion);
                    $('#fecha_inicio').val(promocion.fecha_inicio || '');
                    $('#fecha_fin').val(promocion.fecha_fin || '');
                    $('#orden').val(promocion.orden || 0);
                    $('#activo').prop('checked', promocion.activo == 1);
                    
                    if (promocion.imagen) {
                        $('#previewImagen').html(
                            `<img src="../Recursos/imagenes/promociones/${promocion.imagen}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`
                        );
                    } else {
                        $('#previewImagen').empty();
                    }
                    
                    $('#modalPromocionLabel').text('Editar Promoción');
                    $('#modalPromocion').modal('show');
                }
            }
        }
    });
}

function guardarPromocion() {
    const form = $('#formPromocion')[0];
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const id = $('#promocion_id').val();
    // Siempre usar POST, el servidor detectará si es creación o actualización por el ID
    const url = 'api/promociones.php';
    
    // Si hay ID, agregarlo al FormData para indicar que es actualización
    if (id) {
        formData.append('id', id);
    }
    
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalPromocion').modal('hide');
                cargarPromociones();
                alert('Promoción guardada exitosamente');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Error al guardar la promoción';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}

function eliminarPromocion(id) {
    if (!confirm('¿Está seguro de eliminar esta promoción?')) {
        return;
    }
    
    $.ajax({
        url: 'api/promociones.php?id=' + id,
        method: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                cargarPromociones();
                alert('Promoción eliminada exitosamente');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error al eliminar la promoción');
        }
    });
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function formatearFechaDMY(fechaYMD) {
    if (!fechaYMD) return "";
    const [anio, mes, dia] = fechaYMD.split("-");
    return `${dia}/${mes}/${anio}`;
}


