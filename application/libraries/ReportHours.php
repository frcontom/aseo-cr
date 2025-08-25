<?php

//namespace PHPMailer;

class ReportHours
{
     function getTiposHoras()
    {
        return [
            1 => ['descripcion' => 'Hora Ordinaria', 'desde' => '05:00:00', 'hasta' => '17:00:00', 'pago' => 1],
//            2 => ['descripcion' => 'Hora Ordinaria Extra', 'desde' => '05:00:00', 'hasta' => '17:00:00', 'pago' => 1],
            2 => ['descripcion' => 'Hora Extra  (1.5)', 'desde' => '05:00:00', 'hasta' => '19:00:00', 'pago' => 1.5],
            3 => ['descripcion' => 'Horas Mixta (1.7)', 'desde' => '19:00:00', 'hasta' => '22:00:00', 'pago' => 1.7],
            4 => ['descripcion' => 'Horas Nocturnas (2)', 'desde' => '22:00:00', 'hasta' => '05:00:00', 'pago' => 2],
//            5 => ['descripcion' => 'Horas Dobles (2)', 'desde' => '', 'hasta' => '', 'pago' => 2]
        ];
    }

    function report_employ_query($employee_id,$departament_id,$range_dates,$and_where = []){

        $CI = &get_instance();
        $CI->load->library('general');


        $employee_id = ($employee_id != 'all') ? $employee_id : '';
        $departament_id = ($departament_id != 'all') ? $departament_id : '';


        //generate range to dates
        $range_add = "SELECT DATE('".$range_dates[0]."') AS hes_date";
        for ($i = 1; $i < count($range_dates); $i++) {
            $range_add .= " UNION ALL SELECT DATE('".$range_dates[$i]."')";
        }

        $andwhereall = '';
        $andValidate = '';
        if(isset($and_where) and (count($and_where) > 0)){
            $andwhereall = "WHERE he.he_name LIKE '%".(isset($and_where['employ']) ? $and_where['employ'] : '')."%'";

            //validate user_id

//            if(isset($and_where['user_id']) and $and_where['user_id'] != ''){
//                $andwhereall .= " AND u.u_id = '".$and_where['user_id']."'";
//            }

//            $val_date = (isset($and_where['date'])) ? $and_where['date'] : '';
//            $employ_date = "%".."%";

//
//            $employ_hour1 = "%".isset($and_where['hour1']) ? $and_where['hour1'] : ''."%";
//            $employ_hour2 = "%".isset($and_where['hour2']) ? $and_where['hour2'] : ''."%";
//
//            $employ_hourm1 = "%".isset($and_where['date']) ? $and_where['date'] : ''."%";
//            $employ_hourm2 = "%".isset($and_where['date']) ? $and_where['date'] : ''."%";
//            $andwhereall = $employ_name.'';


            //validate
            if($and_where['validated'] == 'id_hour_null') {
                $andValidate .= " WHERE bm.hb_id IS NOT NULL AND bm.hb_id != '' OR hs.hs_hour1 IS NOT NULL  AND hs.hs_hour1 != ''";
            }elseif($and_where['validated'] == 'employ_id_only_extra_aprroved') {
                $andValidate .= " WHERE he.he_id = '{$employee_id}' and bm.hb_status = 2";
            }elseif($and_where['validated'] == 'employ_id_only_extra') {
                $andValidate .= " WHERE he.he_id = '{$employee_id}' and bm.hb_type != 1";
            }



        }else{
            $andwhereall = "WHERE (
            '{$employee_id}' = ''  -- Si no se pasa un ID específico, toma todos los empleados
            OR he.he_id = '{$employee_id}'  -- Si se pasa un ID, toma solo ese empleado
         ) AND (
            '{$departament_id}' = ''  -- Si no se pasa un ID específico, toma todos los empleados
            OR hc.hc_id = '{$departament_id}'  -- Si se pasa un ID, toma solo ese empleado
         )";
        }

//        echo $andwhereall;exit;

        return $CI->general->query("WITH 
    -- 1️⃣ Generamos las fechas dinámicamente dentro del rango
    week_dates AS (
        {$range_add}
    ),
    
    -- 2️⃣ Seleccionamos los empleados con un posible filtro por ID
    users AS (
        SELECT he.he_id AS hes_employ
        FROM horary_employ he
        INNER JOIN horary_category hc ON he.he_category = hc.hc_id
        JOIN users u ON hc.hc_profile = u.u_profile AND u.u_id = '21'  -- Filtrar por el usuario con ID '21'
        JOIN users_properties up ON u.u_id = up.up_user AND hc.hc_propertie = up.up_propertie
        $andwhereall
    )
    
    -- 3️⃣ Consulta principal
    SELECT
        wd.hes_date,
        u.hes_employ,
    
        -- Datos del empleado
        COALESCE(he.he_id, '') AS he_id,
        COALESCE(he.he_name, '') AS he_name,
        COALESCE(he.he_phone, '') AS he_phone,
        COALESCE(he.he_category, '') AS he_category,
        COALESCE(hc.hc_name, '') AS he_category_name,
        COALESCE(hc.hc_order, '') AS he_category_order,
        COALESCE(hc.hc_profile, '') AS he_category_profile,
        COALESCE(he.he_status, '') AS he_status,
        COALESCE(he.he_code, '') AS he_code,
        COALESCE(he.he_code, '') AS he_code,
    
        -- Datos del horario asignado
        COALESCE(hs.hs_id, '') AS hs_id,
        COALESCE(hs.hs_type, '') AS hs_type,
        COALESCE(hs.hs_value, '') AS hs_value,
        COALESCE(hs.hs_hour1, '') AS hs_hour1,
        COALESCE(hs.hs_hour2, '') AS hs_hour2,
        COALESCE(hs.hs_color, '') AS hs_color,
        COALESCE(hs.hs_sigla, '') AS hs_prefix,
        
        -- Datos del Biometric
        COALESCE(bm.hb_id, '') AS hb_id,
        COALESCE(bm.hb_date, '') AS hb_date,
        COALESCE(bm.hb_time_entry, '') AS hb_time_entry,
        COALESCE(bm.hb_time_exit, '') AS hb_time_exit,
        COALESCE(bm.hb_type, '') AS hb_type_houry,
        COALESCE(bm.hb_message, '') AS hb_message,
        COALESCE(bm.hb_status, '') AS hb_status
    
    FROM week_dates wd
    CROSS JOIN users u
    LEFT JOIN horary_employ he  ON u.hes_employ = he.he_id
    LEFT JOIN horary_category hc  ON he.he_category = hc.hc_id
    LEFT JOIN horary_employ_schedules hes   ON wd.hes_date = hes.hes_date  AND u.hes_employ = hes.hes_employ
    LEFT JOIN horary_schedules hs   ON COALESCE(hes.hes_schedules, 0) = hs.hs_id
    LEFT JOIN horary_biometric As bm ON he.he_id=bm.hb_employ and wd.hes_date=bm.hb_date 
    {$andValidate} 
    ORDER BY wd.hes_date DESC, hc.hc_order ASC;

            ");
    }
    private function obtenerTipoHora($horaEntrada)
    {
        $tipos = $this->getTiposHoras();
        $horaTimestamp = strtotime($horaEntrada);

        foreach ($tipos as $key => $tipo) {
            if ($key == 6) continue; // No evaluamos descanso aquí.

            $desdeTimestamp = strtotime($tipo['desde']);
            $hastaTimestamp = strtotime($tipo['hasta']);

            if ($desdeTimestamp > $hastaTimestamp) {
                // Cruza medianoche
                if (
                    ($horaTimestamp >= $desdeTimestamp && $horaTimestamp <= strtotime('23:59:59')) ||
                    ($horaTimestamp >= strtotime('00:00:00') && $horaTimestamp <= $hastaTimestamp)
                ) {
                    return $key;
                }
            } else {
                if ($horaTimestamp >= $desdeTimestamp && $horaTimestamp < $hastaTimestamp) {
                    return $key;
                }

                // Si marca justo en el límite inicial
                if ($horaEntrada === $tipo['desde']) {
                    return $key;
                }
            }
        }

        return null;
    }
    private function calcularHorasPorDia($registro, $tiposHoras)
    {
//        print_r($registro);exit;
        $fecha = $registro['fecha'];
        $resultado = [];

        // Variables de control
        $tieneJornadaNormal = !empty($registro['hora_entrada']) && !empty($registro['hora_salida']);
        $tipoDia = $registro['tipo_hora'] ?? 'normal';
        $esDiaLibre = in_array(strtolower($tipoDia), ['libre', 'vacaciones', 'incapacidad', 'descanso']);

        // Validar que si la fecha no ha pasado no se tiene ninguna acción sobre el dato
        $fecha_today = fecha(1);
        if ($fecha_today < $registro['fecha']) {
            // No validar aún
            $resultado[$fecha][] = [
                'hb_id' => $registro['hb_id'],
                'employ_id' => $registro['employ_id'],
                'employ_name' => $registro['employ_name'],
                'message' => $registro['message'],
                'status' => $registro['status'],
                'fecha' => $fecha,
                'hora_tipo' => 'sin_fecha',
                'prefix' => '-',
                'tipo_hora_id' => null,
                'hora_entrada' => $registro['hora_entrada'],
                'hora_salida' => $registro['hora_salida'],
                'hora_marcado_entrada' => '',
                'hora_marcado_salida' => '',
                'tipo_dia' => $tipoDia,
                'tipo_hora' => 'Fecha Proxima',
                'horas_trabajadas' => 0,
                'minutos_trabajados' => 0,
                'factor_pago' => 0
            ];
            return $resultado;
        } else {
            // Si NO MARCÓ ENTRADA/SALIDA
            if (empty($registro['hora_marcado_entrada']) || empty($registro['hora_marcado_salida'])) {
                if ($esDiaLibre) {
                    // Si es libre y no marcó nada
                    $resultado[$fecha][] = [
                        'hb_id' => $registro['hb_id'],
                        'employ_id' => $registro['employ_id'],
                        'employ_name' => $registro['employ_name'],
                        'message' => $registro['message'],
                        'status' => $registro['status'],
                        'fecha' => $fecha,
                        'hora_tipo' => $tipoDia,
                        'tipo_hora_id' => null,
                        'prefix' => $registro['prefix'],
                        'hora_entrada' => '',
                        'hora_salida' => '',
                        'hora_marcado_entrada' => '',
                        'hora_marcado_salida' => '',
                        'tipo_dia' => $tipoDia,
                        'tipo_hora' => $tipoDia,
                        'horas_trabajadas' => 0,
                        'minutos_trabajados' => 0,
                        'factor_pago' => 1
                    ];
                } elseif ($tieneJornadaNormal) {
                    // Si debía ir y no marcó, AUSENTE
                    $resultado[$fecha][] = [
                        'hb_id' => $registro['hb_id'],
                        'employ_id' => $registro['employ_id'],
                        'employ_name' => $registro['employ_name'],
                        'message' => $registro['message'],
                        'status' => $registro['status'],
                        'fecha' => $fecha,
                        'hora_tipo' => 'Aus',
                        'prefix' => 'Aus',
                        'tipo_hora_id' => null,
                        'hora_entrada' => $registro['hora_entrada'],
                        'hora_salida' => $registro['hora_salida'],
                        'hora_marcado_entrada' => '',
                        'hora_marcado_salida' => '',
                        'tipo_dia' => $tipoDia,
                        'tipo_hora' => 'Ausente',
                        'horas_trabajadas' => 0,
                        'minutos_trabajados' => 0,
                        'factor_pago' => 0
                    ];
                }
                return $resultado;
            }
        }

        // Si MARCÓ ENTRADA/SALIDA
        $horaEntradaMarcada = $registro['hora_marcado_entrada'];
        $horaSalidaMarcada = $registro['hora_marcado_salida'];

        $inicioTimestamp = strtotime($horaEntradaMarcada);
        $finTimestamp = strtotime($horaSalidaMarcada);

        // Cruce de medianoche
        if ($finTimestamp < $inicioTimestamp) {
            $finTimestamp += 86400;
        }

        $duracionSegundos = $finTimestamp - $inicioTimestamp;
        $horasTrabajadas = $duracionSegundos / 3600;
        $minutosTrabajados = $duracionSegundos / 60;

        // Determinar tipo de hora
        // Si el prefix tiene valor (una letra), asignar tipo de hora 5 (Horas Dobles)
        $tipoHoraId = (!empty($registro['prefix']) && strlen($registro['prefix']) == 1) ? 4 : $this->obtenerTipoHora($horaEntradaMarcada);
//        print_r($tiposHoras);exit;

        $tipoHoraDescripcion = $tiposHoras[$tipoHoraId]['descripcion'] ?? 'Desconocido';
        $factorPago = $tiposHoras[$tipoHoraId]['pago'] ?? 1;

        // 🚩 Si es jornada normal (tiene hora_entrada y hora_salida), SIEMPRE se paga 1
        if ($tieneJornadaNormal) {
            $factorPago = 1;
        } else {
            // 🚩 Si es día Libre/Vacaciones/Incapacidad/Descanso y trabajó
            if ($esDiaLibre) {
                if ($horasTrabajadas < 0.6) {
                    $factorPago *= 0.5; // Menos de 36 minutos, paga a la mitad
                }
            } else {
                // 🚩 Ajuste general para cualquier hora extra o jornada
                if ($horasTrabajadas < 0.6) {
                    $factorPago = 0.5; // Menos de 36 minutos, paga a la mitad
                }
            }
        }

        // Calcular los minutos a pagar
        if ($minutosTrabajados < 35) {
            $minutosPagar = 0;
        } elseif ($minutosTrabajados > 34 && $minutosTrabajados < 36) {
            $minutosPagar = 0.5;
        }
        elseif ($minutosTrabajados > 35 && $minutosTrabajados < 60) {
            $minutosPagar = 1;
        }
        elseif ($minutosTrabajados >= 60) {
            $minutosPagar = floor($minutosTrabajados / 60); // Redondear hacia abajo las horas completas
        }
        else {
            $minutosPagar = 0.5;
        }

        // Agregar resultado con minutos a pagar
        $resultado[$fecha][] = [
            'hb_id' => $registro['hb_id'],
            'employ_id' => $registro['employ_id'],
            'employ_name' => $registro['employ_name'],
            'message' => $registro['message'],
            'status' => $registro['status'],
            'fecha' => $fecha,
            'hora_tipo' => $tipoHoraDescripcion,
            'tipo_hora_id' => $tipoHoraId,
            'hora_entrada' => $registro['hora_entrada'],
            'hora_salida' => $registro['hora_salida'],
            'prefix' => $registro['prefix'],
            'bt_type_houry' => $registro['bt_type_houry'],
            'hora_marcado_entrada' => $horaEntradaMarcada,
            'hora_marcado_salida' => $horaSalidaMarcada,
            'tipo_dia' => $tipoDia,
            'tipo_hora' => $tipoHoraDescripcion,
            'horas_trabajadas' => round($horasTrabajadas, 2),
            'minutos_trabajados' => intval($minutosTrabajados),
            'factor_pago' => $factorPago,
            'minutos_pagar' => $minutosPagar,
        ];

        return $resultado;
    }
    function procesarRegistros($registros)
    {
        $resultado = [];


        if(empty($registros)) return $resultado;


        $tiposHoras = $this->getTiposHoras();  // Obtener tipos de horas

        foreach ($registros['users'] as $registro_user) {
            $employId = $registro_user['employ_id'];
            $employName = $registro_user['employ_name'];
            $category_name = $registro_user['he_category_name'];

            // Inicializar el array de empleado si no existe
            if (!isset($resultado[$employId])) {
                $resultado[$employId] = [
                    'employ_id' => $employId,
                    'employ_name' => $employName,
                    'category_name' => $category_name,
                    'dates' => []  // Inicializamos el array de fechas vacío
                ];
            }

            // Recorrer los registros de fechas
            foreach ($registro_user['dates'] as $registro) {
                $fecha = $registro['fecha'];

                // Inicializar el array de fechas si no existe
                if (!isset($resultado[$employId]['dates'][$fecha])) {
                    $resultado[$employId]['dates'][$fecha] = [];  // Creamos un array vacío para esa fecha
                }

                // Calcular horas por día usando la función externa
                $resultadoDia = $this->calcularHorasPorDia($registro, $tiposHoras);

                // Si se devuelve un resultado, agregarlo, si no, asignar 'sin_informacion'
                if ($resultadoDia) {
                    // En caso de que $resultadoDia sea un array, se agrega el primer elemento
                    // Aseguramos que sea un array de elementos y no una única asignación
                    foreach ($resultadoDia as $item) {
                        $resultado[$employId]['dates'][$fecha][] = $item[0];  // Agregar el item al array de la fecha
                    }
                } else {
                    // Si no hay datos para ese día, asignar estado 'sin_informacion'
                    $resultado[$employId]['dates'][$fecha] = [
                        'fecha' => $fecha,
                        'estado' => 'sin_informacion'  // Agregar el estado para el día sin información
                    ];
                }
            }
        }



        return $resultado;
    }

    function BackReportEmploy($datas){
//        report_employ

        $result = [];
//            print_r($datas);exit;

        if(empty($datas)) return $result;


        foreach ($datas as $entry) {
            $employId = $entry['he_id'];
            $date = $entry['hes_date'] ?? $entry['hb_date'] ?? null;
            if (!$date) continue;

            $type = $entry['hb_type_houry'] ?: '';

            if (!isset($result['users'][$employId])) {
                $result['users'][$employId] = [
                    'employ_id' => $employId,
                    'employ_name' => $entry['he_name'],
                    'he_category_name' => $entry['he_category_name'],
                    'schedule_id' => $entry['hs_id'],
                    'schedule_type' => $entry['hs_type'],
                    'schedule_value' => $entry['hs_value'],
                    'color' => $entry['hs_color'],
                    'dates' => []
                ];
            }


//            $groupKey = $date . '_' . $code;
//
//            // Buscamos si ya existe un registro para este date+code
//            $existingKey = null;
//            foreach ($result['users'][$employId]['dates'] as $k => $reg) {
//                if ($reg['__group_key'] === $groupKey) {
//                    $existingKey = $k;
//                    break;
//                }
//            }


            //validate hour assigne
            $hour1 = '';
            $hour2 = '';
            if(($entry['hs_hour1'] != '' and $entry['hs_hour2'] != '')){
                //Validar si tiene asignacion del dia
                if($entry['hs_id'] != ''){
                    // se valida el tipo de entrada si la persona no ha entrado se le deja la hora

                    if($entry['hb_type_houry'] == 1){
                        $hour1 = $entry['hs_hour1'];
                        $hour2 = $entry['hs_hour2'];
                    }else{
                        if($entry['hb_type_houry'] == ''){
                            $hour1 = $entry['hs_hour1'];
                            $hour2 = $entry['hs_hour2'];
                        }
                    }
                }
            }

//            $existingKey = '';
//            if ($existingKey === null) {
                $result['users'][$employId]['dates'][] = [
                    'employ_id' => $employId,
                    'hb_id' => $entry['hb_id'],
                    'employ_name' => $entry['he_name'],
                    'fecha' => $date,
                    'tipo_hora' => $entry['hs_value'] ?? '',
                    'hora_entrada'  => $hour1,
                    'hora_salida'   => $hour2,
                    'prefix' => $entry['hs_prefix'],
//                    'hora_entrada'  => (!empty($type) and $type == 1) ? $entry['hs_hour1'] : '',
//                    'hora_salida'   => (!empty($type) and $type == 1) ? $entry['hs_hour2'] : '',

                    'hora_marcado_entrada' => $entry['hb_time_entry'],
                    'hora_marcado_salida' => $entry['hb_time_exit'],
                    'message' => $entry['hb_message'],
                    'status' => $entry['hb_status'],
                    'bt_type_houry' => $type,
                ];
               array_key_last($result['users'][$employId]['dates']);


        }

// Eliminar __group_key temporal y ordenar por fecha y hora_marcado_entrada
        foreach ($result['users'] as &$user) {
            $user['dates'] = array_map(function ($row) {
                return $row;
            }, $user['dates']);

            usort($user['dates'], function ($a, $b) {
                $cmp = strcmp($a['fecha'], $b['fecha']);
                if ($cmp !== 0) return $cmp;
                return strcmp($a['hora_marcado_entrada'] ?? '', $b['hora_marcado_entrada'] ?? '');
            });
        }

        return $result;

    }

}