<?php
// vistas/paginas/dashboard.php
// Este archivo representa el contenido específico del panel principal.
// Se asume que este archivo es incluido por 'plantilla.php' cuando el usuario ha iniciado sesión.

// Obtener conteo de actividades por estado
$conteoActividades = ControladorActividad::ctrContarActividadesPorEstado();

// Obtener las 4 actividades pendientes/en progreso más próximas
$proximasActividades = ControladorActividad::ctrObtenerProximasActividades();

// Preparar datos para la gráfica de estados de actividades
$labelsStatus = json_encode(array_keys($conteoActividades));
$dataStatus = json_encode(array_values($conteoActividades));

?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2">Bienvenido, <?php echo $_SESSION["nombre"]; ?></h2>
    <p class="text-gray-600">Este es tu centro de control académico. Aquí tienes un resumen de tu progreso.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
        <div class="bg-blue-100 p-3 rounded-full mr-4">
            <span class="text-xl">🎯</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pendientes</p>
            <p id="kpi-pending" class="text-2xl font-bold"><?php echo $conteoActividades['Pendiente']; ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
        <div class="bg-yellow-100 p-3 rounded-full mr-4">
            <span class="text-xl">⏳</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">En Progreso</p>
            <p id="kpi-in-progress" class="text-2xl font-bold"><?php echo $conteoActividades['En Progreso']; ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
        <div class="bg-green-100 p-3 rounded-full mr-4">
            <span class="text-xl">✅</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Completadas</p>
            <p id="kpi-completed" class="text-2xl font-bold"><?php echo $conteoActividades['Completada']; ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center">
         <div class="bg-red-100 p-3 rounded-full mr-4">
            <span class="text-xl">❌</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">No Entregadas</p>
            <p id="kpi-not-delivered" class="text-2xl font-bold"><?php echo $conteoActividades['No Entregada']; ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-semibold mb-4">Resumen de Actividades</h3>
        <p class="text-sm text-gray-600 mb-4">Esta gráfica muestra la distribución de tus actividades según su estado actual.</p>

        <div class="h-80">
            <canvas id="activitiesStatusChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-xl font-semibold mb-4">Próximas Actividades</h3>
        <p class="text-sm text-gray-600 mb-4">Aquí puedes ver tus 4 actividades pendientes o en progreso con la fecha de entrega más cercana.</p>

        <?php if (!empty($proximasActividades)): ?>
            <ul class="space-y-3">
                <?php foreach ($proximasActividades as $actividad): ?>
                    <li class="p-3 bg-gray-50 rounded-md border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($actividad['objetivo']); ?></p>
                            <p class="text-xs text-gray-500">Materia: <?php echo htmlspecialchars($actividad['nombre_materia']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold <?php echo ($actividad['estado'] == 'Pendiente' ? 'text-blue-600' : 'text-yellow-600'); ?>"><?php echo htmlspecialchars($actividad['estado']); ?></p>
                            <p class="text-xs text-gray-500">Fecha: <?php echo htmlspecialchars(date('d/m/Y', strtotime($actividad['fecha_entrega']))); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="text-center py-10 text-gray-500">
                <span class="text-4xl">🎉</span>
                <p class="mt-4 font-semibold">¡No hay actividades próximas!</p>
                <p class="text-sm">Todo en orden o es hora de añadir nuevas actividades.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para la gráfica de estado de actividades
    const labelsStatus = <?php echo $labelsStatus; ?>;
    const dataStatus = <?php echo $dataStatus; ?>;

    const ctxStatus = document.getElementById('activitiesStatusChart').getContext('2d');
    const activitiesStatusChart = new Chart(ctxStatus, {
        type: 'pie', // O 'doughnut' si prefieres
        data: {
            labels: labelsStatus,
            datasets: [{
                data: dataStatus,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)', // blue-500 - Pendiente
                    'rgba(245, 158, 11, 0.7)', // yellow-500 - En Progreso
                    'rgba(16, 185, 129, 0.7)', // green-500 - Completada
                    'rgba(239, 68, 68, 0.7)'  // red-500 - No Entregada
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                }
            }
        }
    });

    // La gráfica de rendimiento por materia ha sido eliminada.
    // Si en el futuro necesitas añadir otras gráficas, este es el lugar.
});
</script>