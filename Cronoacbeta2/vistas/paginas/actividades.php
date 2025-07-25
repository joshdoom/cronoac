<?php
// vistas/paginas/actividades.php
// Esta vista muestra la interfaz para gestionar las actividades del usuario.
// Es incluida por 'plantilla.php'.

// PROCESAR CREAR, EDITAR O BORRAR ACTIVIDAD
ControladorActividad::ctrCrearActividad();
ControladorActividad::ctrEditarActividad();
ControladorActividad::ctrBorrarActividad();

// OBTENER MATERIAS PARA EL FILTRO Y EL FORMULARIO DE AÑADIR/EDITAR
$materiasDisponibles = ControladorMateria::ctrMostrarMaterias();

// OBTENER ACTIVIDADES PARA MOSTRAR
// Se pueden pasar filtros desde la URL si se implementa AJAX para el filtrado.
// Si deseas un filtrado PHP con recarga, deberías pasar $_GET['filtroEstado'] y $_GET['filtroMateria'] a ctrMostrarActividades.
$filtroEstado = $_GET['filtroEstado'] ?? 'all';
$filtroMateria = $_GET['filtroMateria'] ?? 'all';
$actividades = ControladorActividad::ctrMostrarActividades($filtroEstado, $filtroMateria);


?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2">Mis Actividades</h2>
    <p class="text-gray-600">Aquí puedes gestionar todas tus actividades académicas. Usa los filtros para encontrar rápidamente lo que buscas y actualiza el estado de cada tarea a medida que avanzas.</p>
</div>

<div class="bg-white p-6 rounded-lg shadow-sm">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

        <div class="flex items-center space-x-4">
            <select id="filter-status" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" onchange="filterActivities()">
                <option value="all" <?php echo ($filtroEstado == 'all') ? 'selected' : ''; ?>>Todos los estados</option>
                <option value="Pendiente" <?php echo ($filtroEstado == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                <option value="En Progreso" <?php echo ($filtroEstado == 'En Progreso') ? 'selected' : ''; ?>>En Progreso</option>
                <option value="Completada" <?php echo ($filtroEstado == 'Completada') ? 'selected' : ''; ?>>Completada</option>
                <option value="No Entregada" <?php echo ($filtroEstado == 'No Entregada') ? 'selected' : ''; ?>>No Entregada</option>
            </select>

            <select id="filter-subject" class="border rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" onchange="filterActivities()">
                <option value="all" <?php echo ($filtroMateria == 'all') ? 'selected' : ''; ?>>Todas las materias</option>
                <?php foreach ($materiasDisponibles as $materia): ?>
                    <option value="<?php echo $materia['id_materia']; ?>" <?php echo ($filtroMateria == $materia['id_materia']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($materia['nombre_materia']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button id="add-activity-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors w-full md:w-auto" onclick="openAddActivityModal()">
            + Añadir Actividad
        </button>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left">

            <thead>
                <tr class="border-b bg-gray-50 text-sm text-gray-600">
                    <th class="p-4 font-medium">Objetivo</th>
                    <th class="p-4 font-medium">Tipo</th> <th class="p-4 font-medium">Materia</th>
                    <th class="p-4 font-medium">Fecha Entrega</th>
                    <th class="p-4 font-medium">Estado</th>
                    <th class="p-4 font-medium">Nota</th>
                    <th class="p-4 font-medium">Acciones</th>
                </tr>
            </thead>

            <tbody id="activities-table-body">
                <?php if (!empty($actividades)): ?>
                    <?php foreach ($actividades as $actividad): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4"><?php echo htmlspecialchars($actividad['objetivo']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($actividad['tipo']); ?></td> <td class="p-4"><?php echo htmlspecialchars($actividad['nombre_materia']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars(date('d/m/Y', strtotime($actividad['fecha_entrega']))); ?></td>
                            <td class="p-4">
                                <?php
                                $estadoClass = '';
                                switch ($actividad['estado']) {
                                    case 'Pendiente': $estadoClass = 'bg-blue-100 text-blue-800'; break;
                                    case 'En Progreso': $estadoClass = 'bg-yellow-100 text-yellow-800'; break;
                                    case 'Completada': $estadoClass = 'bg-green-100 text-green-800'; break;
                                    case 'No Entregada': $estadoClass = 'bg-red-100 text-red-800'; break;
                                }
                                ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $estadoClass; ?>">
                                    <?php echo htmlspecialchars($actividad['estado']); ?>
                                </span>
                            </td>
                            <td class="p-4"><?php echo $actividad['nota'] ?? 'N/A'; ?></td>
                            <td class="p-4">
                                <button class="text-indigo-600 hover:text-indigo-900 mr-2 edit-activity-btn"
                                        data-id-actividad="<?php echo $actividad['id_actividad']; ?>"
                                        data-id-materia="<?php echo $actividad['id_materia']; ?>"
                                        data-objetivo="<?php echo htmlspecialchars($actividad['objetivo']); ?>"
                                        data-tipo="<?php echo htmlspecialchars($actividad['tipo']); ?>"
                                        data-fecha-entrega="<?php echo htmlspecialchars($actividad['fecha_entrega']); ?>"
                                        data-valor="<?php echo htmlspecialchars($actividad['valor']); ?>"
                                        data-estado="<?php echo htmlspecialchars($actividad['estado']); ?>"
                                        data-nota="<?php echo htmlspecialchars($actividad['nota']); ?>"
                                        onclick="openEditActivityModal(this)">Editar</button>
                                <button class="text-red-600 hover:text-red-900 delete-activity-btn"
                                        onclick="confirmDeleteActividad(<?php echo $actividad['id_actividad']; ?>)">Borrar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7"> <div id="no-activities-message" class="text-center py-10 text-gray-500">
                                <p>No se encontraron actividades.</p>
                                <p class="text-sm">¡Prueba a cambiar los filtros o a añadir una nueva actividad!</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="addActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-lg">
        <h3 class="text-xl font-bold mb-4">Añadir Nueva Actividad</h3>
        <form method="post" action="index.php?ruta=actividades">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="nuevaActividadObjetivo" class="block text-gray-700 text-sm font-bold mb-2">Objetivo:</label>
                    <input type="text" id="nuevaActividadObjetivo" name="nuevaActividadObjetivo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="idMateriaActividad" class="block text-gray-700 text-sm font-bold mb-2">Materia:</label>
                    <select id="idMateriaActividad" name="idMateriaActividad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Selecciona una materia</option>
                        <?php foreach ($materiasDisponibles as $materia): ?>
                            <option value="<?php echo $materia['id_materia']; ?>"><?php echo htmlspecialchars($materia['nombre_materia']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nuevaActividadTipo" class="block text-gray-700 text-sm font-bold mb-2">Tipo:</label>
                    <input type="text" id="nuevaActividadTipo" name="nuevaActividadTipo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ej: Tarea, Examen, Proyecto">
                </div>
                <div class="mb-4">
                    <label for="nuevaActividadFecha" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Entrega:</label>
                    <input type="date" id="nuevaActividadFecha" name="nuevaActividadFecha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="nuevaActividadValor" class="block text-gray-700 text-sm font-bold mb-2">Valor (Puntos/Porcentaje):</label>
                    <input type="number" step="0.01" id="nuevaActividadValor" name="nuevaActividadValor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="nuevaActividadEstado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                    <select id="nuevaActividadEstado" name="nuevaActividadEstado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Progreso">En Progreso</option>
                        <option value="Completada">Completada</option>
                        <option value="No Entregada">No Entregada</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="nuevaActividadNota" class="block text-gray-700 text-sm font-bold mb-2">Nota (Opcional):</label>
                    <input type="number" step="0.01" id="nuevaActividadNota" name="nuevaActividadNota" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg mr-2 hover:bg-gray-400" onclick="closeAddActivityModal()">Cancelar</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Guardar Actividad</button>
            </div>
        </form>
    </div>
</div>

<div id="editActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-lg">
        <h3 class="text-xl font-bold mb-4">Editar Actividad</h3>
        <form method="post" action="index.php?ruta=actividades">
            <input type="hidden" id="idActividadEditar" name="idActividadEditar">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="editarActividadObjetivo" class="block text-gray-700 text-sm font-bold mb-2">Objetivo:</label>
                    <input type="text" id="editarActividadObjetivo" name="editarActividadObjetivo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="editarIdMateriaActividad" class="block text-gray-700 text-sm font-bold mb-2">Materia:</label>
                    <select id="editarIdMateriaActividad" name="editarIdMateriaActividad" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <?php foreach ($materiasDisponibles as $materia): ?>
                            <option value="<?php echo $materia['id_materia']; ?>"><?php echo htmlspecialchars($materia['nombre_materia']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editarActividadTipo" class="block text-gray-700 text-sm font-bold mb-2">Tipo:</label>
                    <input type="text" id="editarActividadTipo" name="editarActividadTipo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="editarActividadFecha" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Entrega:</label>
                    <input type="date" id="editarActividadFecha" name="editarActividadFecha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="editarActividadValor" class="block text-gray-700 text-sm font-bold mb-2">Valor (Puntos/Porcentaje):</label>
                    <input type="number" step="0.01" id="editarActividadValor" name="editarActividadValor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="editarActividadEstado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                    <select id="editarActividadEstado" name="editarActividadEstado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Progreso">En Progreso</option>
                        <option value="Completada">Completada</option>
                        <option value="No Entregada">No Entregada</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editarActividadNota" class="block text-gray-700 text-sm font-bold mb-2">Nota (Opcional):</label>
                    <input type="number" step="0.01" id="editarActividadNota" name="editarActividadNota" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg mr-2 hover:bg-gray-400" onclick="closeEditActivityModal()">Cancelar</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Actualizar Actividad</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Funciones para el modal de añadir actividad
    function openAddActivityModal() {
        document.getElementById('addActivityModal').classList.remove('hidden');
    }
    function closeAddActivityModal() {
        document.getElementById('addActivityModal').classList.add('hidden');
    }

    // Funciones para el modal de editar actividad
    function openEditActivityModal(button) {
        const data = button.dataset;
        document.getElementById('idActividadEditar').value = data.idActividad;
        document.getElementById('editarActividadObjetivo').value = data.objetivo;
        document.getElementById('editarIdMateriaActividad').value = data.idMateria;
        document.getElementById('editarActividadTipo').value = data.tipo;
        document.getElementById('editarActividadFecha').value = data.fechaEntrega; // input type="date" necesita formato YYYY-MM-DD
        document.getElementById('editarActividadValor').value = data.valor;
        document.getElementById('editarActividadEstado').value = data.estado;
        document.getElementById('editarActividadNota').value = data.nota;

        document.getElementById('editActivityModal').classList.remove('hidden');
    }
    function closeEditActivityModal() {
        document.getElementById('editActivityModal').classList.add('hidden');
    }

    // Función para confirmar borrado con SweetAlert2
    function confirmDeleteActividad(idActividad) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "index.php?ruta=actividades&idActividad=" + idActividad;
            }
        });
    }

    // Función para aplicar filtros (simplemente recarga la página con parámetros GET)
    function filterActivities() {
        const estado = document.getElementById('filter-status').value;
        const materia = document.getElementById('filter-subject').value;
        window.location = `index.php?ruta=actividades&filtroEstado=${estado}&filtroMateria=${materia}`;
    }

    // Mostrar el mensaje de "no hay actividades" si la tabla está vacía
    document.addEventListener('DOMContentLoaded', function() {
        const activitiesTableBody = document.getElementById('activities-table-body');
        const noActivitiesMessage = document.getElementById('no-activities-message');
        // Si no hay hijos en el tbody (o solo el mensaje si está fuera del if/else PHP), muestra el mensaje
        // Se ajusta la condición para considerar la fila "no-activities-message"
        if (activitiesTableBody.children.length === 0 || 
            (activitiesTableBody.children.length === 1 && 
             activitiesTableBody.children[0].tagName === 'TR' && 
             activitiesTableBody.children[0].querySelector('td[colspan="7"]'))) { // Colspan ahora es 7
            noActivitiesMessage.classList.remove('hidden');
        } else {
             noActivitiesMessage.classList.add('hidden');
        }
    });

</script>