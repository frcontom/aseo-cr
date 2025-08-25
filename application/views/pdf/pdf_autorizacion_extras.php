<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Autorización de Horas Extras</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap');
        body {
            font-family: 'Roboto Condensed', sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }
        .page-container {
            max-width: 720px;
            margin: 32px auto;
            padding: 24px;
            /*border: 1px solid #9ca3af; !* Tailwind gray-400 *!*/
            position: relative;
            background-color: white;
        }
        /* Top-left corner lines */
        .corner-lines {
            position: absolute;
            top: 0;
            left: 0;
            width: 24px;
            height: 24px;
            border-top: 1px solid #9ca3af;
            border-left: 1px solid #9ca3af;
        }
        /* Logo circle */
        .logo-circle {
            width: 48px;
            height: 48px;
            background-color: #9ca3af;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.125rem; /* text-lg */
            font-family: 'Roboto Condensed', sans-serif;
        }
        /* Header text */
        .header-title {
            font-weight: 800;
            font-size: 1.25rem; /* text-xl */
            line-height: 1.1;
        }
        .header-subtitle {
            font-size: 0.625rem; /* text-xs */
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #4b5563; /* gray-600 */
            font-family: 'Roboto Condensed', sans-serif;
        }
        /* Centered heading */
        .center-heading {
            font-weight: 700;
            font-size: 1.049rem; /* text-sm */
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        /* Date table */
        .date-table {
            border-collapse: collapse;
            border: none;
            font-size: 0.75rem; /* text-xs */
            color: #374151; /* gray-700 */
            width: 9rem;
            margin-left: auto;
            margin-bottom: 1rem;
        }
        .date-table thead{
            background:#d1d5db;
            color: #000;
            font-weight: 600;
        }

        .date-table th,
        .date-table td {
            /*border: 1px solid #9ca3af;*/
            padding: 0.125rem 0.25rem;
            text-align: center;
            font-weight: 600;
        }
        /* Departamento label table */
        .dept-table {
            border-collapse: collapse;
            border: 1px solid #9ca3af;
            font-size: 0.75rem;
            width: 30%;
            margin-bottom: 1.5rem;
        }
        .dept-table th {
            background-color: #d1d5db; /* gray-300 */
            font-weight: 600;
            text-align: center;
            border: 1px solid #9ca3af;
            padding: 0.125rem 0.25rem;
        }
        .dept-table td {
            border: 1px solid #9ca3af;
            padding: 0.125rem 0.25rem;
        }
        /* Main table */
        .main-table {
            border-collapse: collapse;
            border: 1px solid #9ca3af;
            font-size: 0.75rem;
            width: 100%;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .main-table thead tr {
            background-color: #e5e7eb; /* gray-200 */
            font-weight: 600;
        }

        .main-table thead tr th {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #9ca3af;
            padding: 0.125rem 0.25rem;
            vertical-align: top;
        }
        .main-table th:nth-child(4),
        .main-table td:nth-child(4) {
            text-align: left;
            line-height: 1.1;
        }
        .main-table td:nth-child(1),
        .main-table td:nth-child(2),
        .main-table td:nth-child(3),
        .main-table td:nth-child(5),
        .main-table td:nth-child(6),
        .main-table td:nth-child(7) {
            text-align: center;
        }
        .main-table tfoot td {
            font-weight: 600;
            font-size: 0.75rem;
            padding-top: 0.25rem;
        }
        .main-table tfoot td:first-child {
            text-align: right;
            padding-right: 0.5rem;
            border: none;
        }
        /* Signatures container using table layout but with partial borders */
        .signatures-div {
            width: auto;
            /*font-size: 0.75rem;*/
            /*border-collapse: collapse;*/
            /*margin-top: auto;*/
            color: #374151;
            /*position: absolute;*/
            bottom: 24px;
            left: 24px;
            right: 24px;
            /*background: white;*/
            position: fixed;
            bottom: 0;
        }
        .signatures-table {
            width: 100%;
            font-size: 0.85rem;
            font-weight: 800 !important;
            border-collapse: collapse;
            margin-top: 2.702rem;
            color: #374151;
            /*background: red;*/
        }
        .signatures-table td {
            vertical-align: top;
            padding: 0;
        }
        .sign-block {
            width: 25%;
            /*border-top: 1px solid #9ca3af;*/
            /*border-left: 1px solid #9ca3af;*/
            /*border-bottom: 1px solid #9ca3af;*/
            /*border-right: 1px solid #9ca3af;*/
            /*padding: 0 0.5rem;*/
            box-sizing: border-box;
            /*height: 50px;*/
            text-align: center;
            position: relative;
        }
        /* Remove right border on last block in row */
        .sign-block:last-child {
            /*border-right: 1px solid #9ca3af;*/
        }
        /* Inner lines inside each block */
        .sign-block div {
            /*border-top: 1px solid #9ca3af;*/
            /*padding: 0.125rem 0;*/
        }
        .sign-block div:first-child {
            border-top: none;
            /*padding-top: 0.25rem;*/
            /*padding-bottom: 0.25rem;*/
        }
        /* Signature line spacing */
        .signature-line {
            border-top: 1px solid #9ca3af !important;
            position: absolute;
            /*bottom: 0.5rem;*/
            left: 0.5rem;
            right: 0.5rem;
            /*border-top: none;*/
            padding-top: 0;
            margin-top: 0;
        }
    </style>
</head>
<body>
<div class="page-container">
    <div class="corner-lines"></div>

    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
        <div class="logo-circle">LS</div>
        <div>
            <h1 class="header-title">PALMA REAL</h1>
            <p class="header-subtitle">HOTEL SAN JOSE URBAN STYLE</p>
        </div>
    </div>

    <h2 class="center-heading">AUTORIZACIÓN DE HORAS EXTRAS</h2>

    <table class="date-table" aria-label="Fecha">
        <thead>
        <tr>
            <th>Día</th>
            <th>Mes</th>
            <th>Año</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php
                $today = explode('-', fecha(1));
                echo '<td>'.$today[2].'</td>';
                echo '<td>'.$today[1].'</td>';
                echo '<td>'.$today[0].'</td>';
            ?>

        </tr>
        </tbody>
    </table>

    <table class="dept-table" aria-label="Departamento">
        <thead>
        <tr>
            <th style="width:6rem;">Departamento</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$data['category_name']?></td>
        </tr>
        </tbody>
    </table>

    <table class="main-table" aria-label="Detalle de horas extras">
        <thead>
        <tr>
            <th  colspan="3" rowspan="1">Fechas</th>
            <th rowspan="2">Descripción de tareas que originaron Horas Extras</th>
            <th  rowspan="2" style="width:3rem;">Desde</th>
            <th  rowspan="2" style="width:3rem;">Hasta</th>
            <th  rowspan="2" style="width:4rem;">Total Horas</th>
        </tr>
        <tr>

            <th style="width:1.5rem;">D</th>
            <th style="width:1.5rem;">M</th>
            <th style="width:1.5rem;">A</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $sum_minutes = 0;
            if(isset($data['dates'])){
                foreach($data['dates'] as $key => $value){
                    $day_extra = explode('-', $value['fecha']);
                    $sum_minutes += $value['minutos_trabajados'];
                    $minute_hour = $this->class_security->pass_minute_to_hours($value['minutos_trabajados']);
                    echo '<tr>';
                    echo '<td>'.$day_extra['2'].'</td>';
                    echo '<td>'.$day_extra['1'].'</td>';
                    echo '<td>'.$day_extra['0'].'</td>';
                    echo '<td>'.$value['message'].'</td>';
                    echo '<td>'.$value['hora_marcado_entrada'].'</td>';
                    echo '<td>'.$value['hora_marcado_salida'].'</td>';
                    echo '<td>'.$minute_hour.'</td>';
                    echo '</tr>';
                }
            }
        ?>

<!--        <tr>-->
<!--            <td>06</td>-->
<!--            <td>02</td>-->
<!--            <td>25</td>-->
<!--            <td>Ingreso antes ayudar, ya que solo hay 1 en la recepción.</td>-->
<!--            <td>2:37</td>-->
<!--            <td>3:00</td>-->
<!--            <td>0.30</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>11</td>-->
<!--            <td>02</td>-->
<!--            <td>25</td>-->
<!--            <td>Se queda un poco cubriendo almuerzo de compañero por ingreso de grupos de Overseas ayudar con las maletas</td>-->
<!--            <td>2:00</td>-->
<!--            <td>2:30</td>-->
<!--            <td>0.30</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>12</td>-->
<!--            <td>02</td>-->
<!--            <td>25</td>-->
<!--            <td>Se queda un poco cubriendo almuerzo de compañero por ingreso de grupos de Overseas ayudar con las maletas</td>-->
<!--            <td>2:00</td>-->
<!--            <td>2:30</td>-->
<!--            <td>0.30</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td>14</td>-->
<!--            <td>02</td>-->
<!--            <td>25</td>-->
<!--            <td>Alta ocupación y solo había 1 en turno</td>-->
<!--            <td>2:00</td>-->
<!--            <td>2:30</td>-->
<!--            <td>0.30</td>-->
<!--        </tr>-->
        <tr>
            <td colspan="6" style="text-align:right; font-weight:600; padding-top:0.25rem;">Total, Horas Autorizadas</td>
            <td style="text-align:center; font-weight:600; padding-top:0.25rem;"><?=$this->class_security->pass_minute_to_hours($sum_minutes)?></td>
        </tr>
        </tbody>
    </table>

    <div class="signatures-div">

        <table class="signatures-table" aria-label="Firmas">
            <tr>
                <td class="sign-block">
                    <div style="margin-bottom: 25px">Empleado Solicitante</div>
                    <div>Nombre</div>
                    <div style="border: 1px solid #9ca3af"><?=$data['employ_name']?></div>
                </td>
                <td class="sign-block">
                    <div style="margin-bottom: 25px">Autorizado Por:</div>
                    <div>Nombre</div>
                    <div style="border: 1px solid #9ca3af">Francine Obando</div>
                </td>
                <td class="sign-block">
                    <div style="margin-bottom: 25px">Aprobado por:</div>
                    <div>Nombre</div>
                    <div style="border: 1px solid #9ca3af">Nicole Schmid</div>
                </td>
                <td class="sign-block">
                    <div style="margin-bottom: 25px">Revisado Por:</div>
                    <div>Nombre</div>
                    <div style="border: 1px solid #9ca3af">Geanina Somarribas</div>
                </td>
            </tr>
        </table>

        <table class="signatures-table" aria-label="Firmas">
            <tr>
                <td class="sign-block">
                    <div>Cargo</div>
                    <div style="border: 1px solid #9ca3af">Botones</div>
                </td>
                <td class="sign-block">
                    <div>Cargo</div>
                    <div style="border: 1px solid #9ca3af">Jefe recepción</div>
                </td>
                <td class="sign-block">
                    <div>Cargo</div>
                    <div style="border: 1px solid #9ca3af">Gerente General</div>
                </td>
                <td class="sign-block">
                    <div>Cargo</div>
                    <div style="border: 1px solid #9ca3af">Asistente contable</div>
                </td>
            </tr>
        </table>

        <table class="signatures-table" style="margin-top: 70px" aria-label="Firmas">
            <tr>
                <td class="sign-block">
                    <div class="signature-line">Firma del Solicitante</div>
                </td>
                <td class="sign-block">
                    <div class="signature-line">Firma Jefe de Área</div>
                </td>
                <td class="sign-block">
                    <div class="signature-line">Firma del Gerente</div>
                </td>
                <td class="sign-block">
                    <div class="signature-line">Firma de RRHH</div>
                </td>
            </tr>
        </table>

    </div>
</div>
</body>
</html>