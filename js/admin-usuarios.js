$(document).ready(function() {
    cargarUsuarios();
    cargarGrupos();
    cargarGruposParaCheckboxes();
});

// ========== USUARIOS ==========

function cargarUsuarios() {
    $.ajax({
        url: 'api/usuarios.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarUsuarios(response.data);
            }
        },
        error: function() {
            $('#tbodyUsuarios').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar los usuarios</td></tr>');
        }
    });
}

function mostrarUsuarios(usuarios) {
    const tbody = $('#tbodyUsuarios');
    tbody.empty();
    
    if (usuarios.length === 0) {
        tbody.html('<tr><td colspan="8" class="text-center text-muted">No hay usuarios registrados</td></tr>');
        return;
    }
    
    usuarios.forEach(function(usuario) {
        const estado = usuario.activo == 1 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-secondary">Inactivo</span>';
        
        const grupos = usuario.grupos && usuario.grupos.length > 0
            ? usuario.grupos.map(g => g.nombre).join(', ')
            : '<span class="text-muted">Sin grupos</span>';
        
        const ultimoAcceso = usuario.ultimo_acceso 
            ? new Date(usuario.ultimo_acceso).toLocaleString('es-AR')
            : '<span class="text-muted">Nunca</span>';
        
        const row = `
            <tr>
                <td>${usuario.id}</td>
                <td>${escapeHtml(usuario.username)}</td>
                <td>${escapeHtml(usuario.email)}</td>
                <td>${escapeHtml((usuario.nombre || '') + ' ' + (usuario.apellido || ''))}</td>
                <td>${grupos}</td>
                <td>${estado}</td>
                <td>${ultimoAcceso}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editarUsuario(${usuario.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${usuario.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function abrirModalNuevoUsuario() {
    $('#formUsuario')[0].reset();
    $('#usuario_id').val('');
    $('#modalUsuarioLabel').text('Nuevo Usuario');
    $('#passwordRequired').show();
    $('#password').prop('required', true);
    cargarGruposParaCheckboxes();
}

function editarUsuario(id) {
    $.ajax({
        url: 'api/usuarios.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const usuario = response.data.find(u => u.id == id);
                if (usuario) {
                    $('#usuario_id').val(usuario.id);
                    $('#username').val(usuario.username);
                    $('#email').val(usuario.email);
                    $('#nombre').val(usuario.nombre || '');
                    $('#apellido').val(usuario.apellido || '');
                    $('#usuario_activo').prop('checked', usuario.activo == 1);
                    $('#passwordRequired').hide();
                    $('#password').prop('required', false);
                    $('#modalUsuarioLabel').text('Editar Usuario');
                    
                    // Cargar grupos y marcar los del usuario
                    cargarGruposParaCheckboxes(usuario.grupos || []);
                    
                    $('#modalUsuario').modal('show');
                }
            }
        }
    });
}

function guardarUsuario() {
    const form = $('#formUsuario')[0];
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Obtener grupos seleccionados
    const gruposSeleccionados = [];
    $('input[name="grupo_checkbox"]:checked').each(function() {
        gruposSeleccionados.push($(this).val());
    });
    formData.append('grupos', JSON.stringify(gruposSeleccionados));
    
    const id = $('#usuario_id').val();
    if (id) {
        formData.append('id', id);
    }
    
    $.ajax({
        url: 'api/usuarios.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalUsuario').modal('hide');
                cargarUsuarios();
                alert('Usuario guardado exitosamente');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Error al guardar el usuario';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}

function eliminarUsuario(id) {
    if (!confirm('¿Está seguro de eliminar este usuario?')) {
        return;
    }
    
    $.ajax({
        url: 'api/usuarios.php?id=' + id,
        method: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                cargarUsuarios();
                alert('Usuario eliminado exitosamente');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error al eliminar el usuario');
        }
    });
}

function cargarGruposParaCheckboxes(gruposUsuario = []) {
    $.ajax({
        url: 'api/grupos.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const container = $('#gruposCheckboxes');
                container.empty();
                
                if (response.data.length === 0) {
                    container.html('<p class="text-muted">No hay grupos disponibles</p>');
                    return;
                }
                
                response.data.forEach(function(grupo) {
                    const checked = gruposUsuario.some(gu => gu.id == grupo.id) ? 'checked' : '';
                    const checkbox = `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="grupo_checkbox" 
                                   value="${grupo.id}" id="grupo_${grupo.id}" ${checked}>
                            <label class="form-check-label" for="grupo_${grupo.id}">
                                ${escapeHtml(grupo.nombre)}
                            </label>
                        </div>
                    `;
                    container.append(checkbox);
                });
            }
        }
    });
}

// ========== GRUPOS ==========

function cargarGrupos() {
    $.ajax({
        url: 'api/grupos.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarGrupos(response.data);
            }
        },
        error: function() {
            $('#tbodyGrupos').html('<tr><td colspan="6" class="text-center text-danger">Error al cargar los grupos</td></tr>');
        }
    });
}

function mostrarGrupos(grupos) {
    const tbody = $('#tbodyGrupos');
    tbody.empty();
    
    if (grupos.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center text-muted">No hay grupos registrados</td></tr>');
        return;
    }
    
    grupos.forEach(function(grupo) {
        const estado = grupo.activo == 1 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-secondary">Inactivo</span>';
        
        const row = `
            <tr>
                <td>${grupo.id}</td>
                <td>${escapeHtml(grupo.nombre)}</td>
                <td>${escapeHtml(grupo.descripcion || '')}</td>
                <td><span class="badge bg-info">${grupo.total_usuarios || 0}</span></td>
                <td>${estado}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editarGrupo(${grupo.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminarGrupo(${grupo.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function abrirModalNuevoGrupo() {
    $('#formGrupo')[0].reset();
    $('#grupo_id').val('');
    $('#modalGrupoLabel').text('Nuevo Grupo');
}

function editarGrupo(id) {
    $.ajax({
        url: 'api/grupos.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const grupo = response.data.find(g => g.id == id);
                if (grupo) {
                    $('#grupo_id').val(grupo.id);
                    $('#grupo_nombre').val(grupo.nombre);
                    $('#grupo_descripcion').val(grupo.descripcion || '');
                    $('#grupo_activo').prop('checked', grupo.activo == 1);
                    $('#modalGrupoLabel').text('Editar Grupo');
                    $('#modalGrupo').modal('show');
                }
            }
        }
    });
}

function guardarGrupo() {
    const form = $('#formGrupo')[0];
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const id = $('#grupo_id').val();
    if (id) {
        formData.append('id', id);
    }
    
    $.ajax({
        url: 'api/grupos.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#modalGrupo').modal('hide');
                cargarGrupos();
                cargarGruposParaCheckboxes(); // Recargar para checkboxes
                alert('Grupo guardado exitosamente');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Error al guardar el grupo';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}

function eliminarGrupo(id) {
    if (!confirm('¿Está seguro de eliminar este grupo?')) {
        return;
    }
    
    $.ajax({
        url: 'api/grupos.php?id=' + id,
        method: 'DELETE',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                cargarGrupos();
                cargarGruposParaCheckboxes(); // Recargar para checkboxes
                alert('Grupo eliminado exitosamente');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error al eliminar el grupo');
        }
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

