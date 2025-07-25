<?php
// vistas/paginas/materias.php
// Esta vista muestra las tarjetas de las materias inscritas por el usuario.
// Es incluida por 'plantilla.php'.
ControladorMateria::ctrCrearMateria();
ControladorMateria::ctrEditarMateria(); // También procesa la edición
ControladorMateria::ctrBorrarMateria();

$materias = ControladorMateria::ctrMostrarMaterias();
?>

<!-- Cabecera de la sección de Materias -->
<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2">Mis Materias</h2>
    <p class="text-gray-600">En esta sección puedes ver y administrar todas las materias en las que estás inscrito. Puedes añadir nuevas materias para empezar a organizar tus actividades académicas.</p>
</div>

<!-- Fila de Controles: Botón para añadir una nueva materia -->
<div class="flex justify-end mb-6">
    <button id="add-subject-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors" onclick="openAddSubjectModal()">
        + Añadir Materia
    </button>
</div>

<!-- Contenedor para la lista de tarjetas de materias -->
<!-- Usamos un grid para que las tarjetas se organicen automáticamente. -->
<div id="subjects-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    
    <!--
        Las tarjetas de las materias se generarán aquí dinámicamente desde la base de datos.
        Un controlador se encargará de obtener los datos y pasarlos a esta vista.

        Ejemplo de una tarjeta estática para referencia visual:
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h4 class="text-lg font-bold text-indigo-800">Cálculo I</h4>
            <p class="text-sm text-gray-500 mt-2">5 actividades</p>
            <div class="mt-4 pt-4 border-t flex justify-end">
                <button class="delete-subject-btn text-red-600 hover:text-red-900 text-sm">Borrar</button>
            </div>
        </div>
    -->
    <?php if (!empty($materias)): ?>
            <?php foreach ($materias as $materia): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h4 class="text-lg font-bold text-indigo-800"><?php echo htmlspecialchars($materia['nombre_materia']); ?></h4>
                    <p class="text-sm text-gray-500 mt-2">X actividades</p>
                    <div class="mt-4 pt-4 border-t flex justify-end">
                        <button class="edit-subject-btn text-blue-600 hover:text-blue-900 text-sm mr-4"
                                data-id-materia="<?php echo $materia['id_materia']; ?>"
                                data-nombre-materia="<?php echo htmlspecialchars($materia['nombre_materia']); ?>"
                                onclick="openEditSubjectModal(this)">Editar</button>
                        <button class="delete-subject-btn text-red-600 hover:text-red-900 text-sm"
                                onclick="confirmDeleteMateria(<?php echo $materia['id_materia']; ?>)">Borrar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        
            <?php else: ?>
            <div id="no-subjects-message" class="text-center py-16 text-gray-500 col-span-full bg-white rounded-lg shadow-sm">
                <span class="text-4xl">📚</span>
                <p class="mt-4 font-semibold">Aún no has añadido ninguna materia.</p>
                <p class="text-sm">¡Haz clic en el botón "Añadir Materia" para empezar a organizarte!</p>
            </div>
        <?php endif; ?>
</div>

<div id="addSubjectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Añadir Nueva Materia</h3>
        <form method="post" action="index.php?ruta=materias">
            <div class="mb-4">
                <label for="nuevaMateria" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Materia:</label>
                <input type="text" id="nuevaMateria" name="nuevaMateria" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex justify-end">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg mr-2 hover:bg-gray-400" onclick="closeAddSubjectModal()">Cancelar</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Guardar Materia</button>
            </div>
        </form>
    </div>
</div>

<div id="editSubjectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Editar Materia</h3>
        <form method="post" action="index.php?ruta=materias">
            <input type="hidden" id="idMateriaEditar" name="idMateriaEditar">
            <div class="mb-4">
                <label for="editarNombreMateria" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Materia:</label>
                <input type="text" id="editarNombreMateria" name="editarNombreMateria" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex justify-end">
                <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg mr-2 hover:bg-gray-400" onclick="closeEditSubjectModal()">Cancelar</button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Actualizar Materia</button>
            </div>
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Funciones para el modal de añadir materia
    function openAddSubjectModal() {
        document.getElementById('addSubjectModal').classList.remove('hidden');
    }
    function closeAddSubjectModal() {
        document.getElementById('addSubjectModal').classList.add('hidden');
    }

    // Funciones para el modal de editar materia
    function openEditSubjectModal(button) {
        const idMateria = button.dataset.idMateria;
        const nombreMateria = button.dataset.nombreMateria;

        document.getElementById('idMateriaEditar').value = idMateria;
        document.getElementById('editarNombreMateria').value = nombreMateria;
        document.getElementById('editSubjectModal').classList.remove('hidden');
    }
    function closeEditSubjectModal() {
        document.getElementById('editSubjectModal').classList.add('hidden');
    }

    // Función para confirmar borrado con SweetAlert2
    function confirmDeleteMateria(idMateria) {
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
                window.location = "index.php?ruta=materias&idMateria=" + idMateria;
            }
        });
    }

    

</script>