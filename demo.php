
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap">
    <style>
    .completed {
      text-decoration: line-through;
      color: gray;
    }
    .form-check-input {
        width: 1.5em;
        height: 1.5em;
        margin-top: 0.2em;
    }
  </style>
</head>
<body class="bg-light p-5">

    <div class="container">
        <h2 class="mb-4">Mis Tareas</h2>
        <ul class="list-group">
            <li class="list-group-item d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" onchange="toggleTask(this)">
                <span class="task-text">Comprar leche</span>
            </li>
            <li class="list-group-item d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" onchange="toggleTask(this)">
                <span class="task-text">Enviar correo al jefe</span>
            </li>
            <li class="list-group-item d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" onchange="toggleTask(this)">
                <span class="task-text">Revisar reporte mensual</span>
            </li>
        </ul>
    </div>

    <script>
        function toggleTask(checkbox) {
            const taskText = checkbox.nextElementSibling;
            if (checkbox.checked) {
                taskText.classList.add('completed');
            } else {
                taskText.classList.remove('completed');
            }
        }
    </script>

    </body>
</html>
