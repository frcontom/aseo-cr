<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Class_data
{
    public $meses = array(
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    );

    public $dia = array(
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miercoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sabado',
        7 => 'Domingo',
    );

    public $type_person = array(
        1 => 'Fisica',
        2 => 'Juridica'
    );
    public $type_room = array(
        1 => 'Habitación',
        2 => 'Salon'
    );

    public $type_maintenance_date = array(
        1 => 'M. Express',
        2 => 'M. Programado'
    );

    public $permissions = array(
        1 => 'Todos',
        2 => 'Visualizar',
    );

    public $area = array(
        1 => 'Administrador A.',
        2 => 'Usuario',
        3 => 'Admin/Usuario',
    );

    public $bookingType = array(
        1 => 'Interno',
        2 => 'Clientes',
        3 => 'Restaurante',
        4 => 'Agencia',
    );

    public $walkin_type = array(
        1 => 'Ventas',
        2 => 'Restaurante',
    );

    public $booking_type = array(
        1 => 'Salones',
        2 => 'Habitaciones',
        3 => 'Mixto',
    );
    public $typeBox = [
        1 => 'Cenas',
        2 => 'box dinner',
        3 => 'box breakfast',
    ];

    public $status_hour = [
        1 => '[ Pendiente ]',
        2 => 'Aprobado',
        3 => 'Rechazado',
    ];

    public $horary_ocupation = [
        1 => '% Ocupación',
        2 => 'Entradas',
        3 => 'Salidas',
    ];

    public $dictionary = [
        'clean'         => [
            "super" => [
                "add" => "Tienes tareas pendientes en la habiracion #filial",
            ],
            "user"  => [
                "start"       => "Tarea Iniciada en la habitacion #filial",
                "end"         => "Tarea Finalizada en la habitacion #filial",
                "busy"        => "Tarea Finalizada NO MOLESTAR en la habitacion #filial",
                "no_busy"     => "Tarea Finalizada OCUPADA LIMPIA en la habitacion #filial",
            ],
        ],
        'maintenance'   => [
            "super" => [
                "add" => "Tienes tareas pendientes en la habitacion #filial",
                "check" => "Tienes tareas pendientes en la habitacion #filial",
            ],
            "user"  => [
                "start"       => "Tarea Iniciada en la habitacion #filial",
                "end"         => "Tarea Finalizada en la habitacion #filial",
            ],
            "add" => "Tienes tareas pendientes #codigo",
        ],
        'reception'     => [
            "assign" => ""
        ],
        'general'       => ["Tienes una nueva Solicitud Pendiente de asignación"],
    ];

    public $ext_img = 'jpeg|jpg|png|gif|bmp';
    public $ext_doc = 'pdf|doc|docx|xls|xlsx|ppt|pptx|txt|csv|zip|rar|7z|tar|gz|gzip|jpeg|jpg|png|gif|bmp';

    public $estado_default = array(
        1 => array('title' => 'Abierto','class'    => 'badge light badge-default'),
    );

    public $status_default = array(
        1 => array('title' => '-','class'    => 'badge light badge-default'),
    );

    public $money = array(
        1 => array('title' => 'CRC','language' => 'es-CR','symbol' => '₡','code' => '&cent;'),
        2 => array('title' => 'USD','language' => 'en-US' ,'symbol' => '$','code' => '$'),
    );

    public $proforma = array(
        1 => array('title' => 'Cocina'     ,'class'    => 'badge dark badge-dark'),
        2 => array('title' => 'Cliente'    ,'class'    => 'badge light badge-primary'),
        3 => array('title' => 'Proforma'   ,'class'    => 'badge light badge-success'),
        4 => array('title' => 'Evento'     ,'class'    => 'badge light badge-danger'),
    );

//    public $proforma = array(
//        1 => array('title' => 'Proforma'     ,'class'    => 'badge dark badge-dark'),
//        2 => array('title' => 'Evento'   ,'class'    => 'badge light badge-primary'),
//        3 => array('title' => 'Evento'       ,'class'    => 'badge light badge-success'),
//        4 => array('title' => 'Cancelado'    ,'class'    => 'badge light badge-danger'),
//    );

    public $status = array(
        1 => array('title' => 'Activa'     ,'class'    => 'badge light badge-success'),
        2 => array('title' => 'Inactiva'   ,'class'    => 'badge light badge-warning'),
        3 => array('title' => 'Bloqueada'   ,'class'    => 'badge light badge-danger'),
        4 => array('title' => 'No Molestar'   ,'class'    => 'badge light badge-danger'),
    );

    public $statusSimple = array(
        1 => array('title' => 'Activa'     ,'class'    => 'badge light badge-success'),
        2 => array('title' => 'Inactiva'   ,'class'    => 'badge light badge-danger'),
    );

    public $cleaning = array(
        1 => array('title' => 'Espera'     ,'color'  => '#fd6464','class'    => 'badge light badge-danger'),
        2 => array('title' => 'Iniciada'   ,'color'  => '#2953E8','class'    => 'badge light badge-primary'),
        3 => array('title' => 'Limpia' ,'color'  => '#007bff','class'    => 'badge light badge-success'),
        4 => array('title' => 'Finalizada' ,'color'  => '#28a745','class'    => 'badge light badge-success'),
        5 => array('title' => 'Re-Asignada' ,'color' => '#17a2b8','class'    => 'badge light badge-warning'),
        7 => array('title' => 'No Molestar' ,'color' => '#17a2b8','class'    => 'badge light badge-warning'),
    );

    public $working = array(
        1 => array('title' => 'Espera'     ,'color'  => '#fd6464','class'    => 'badge light badge-danger'),
        2 => array('title' => 'Iniciada'   ,'color'  => '#2953E8','class'    => 'badge light badge-primary'),
        3 => array('title' => 'Finalizada' ,'color'  => '#007bff','class'    => 'badge light badge-success'),
        4 => array('title' => 'Finalizada' ,'color'  => '#28a745','class'    => 'badge light badge-success'),
    );

    public $priority = array(
        1 => array('title' => 'Alta'   ,'class'    => 'badge light badge-danger'),
        2 => array('title' => 'Media'  ,'class'    => 'badge light badge-success'),
        3 => array('title' => 'Baja'   ,'class'    => 'badge light badge-primary'),
    );

    public $available = array(
        1 => array('title' => 'Disponible' ,'color' => '#2BC155','class'    => 'badge light badge-success'),
        2 => array('title' => 'Ocupada'  ,'color' => '#FF4C41','class'    => 'badge light badge-danger'),
    );

    public $accordance  = array(
        1 => array('title' => 'Aceptada' ,'color' => '#2BC155','class'    => 'badge light badge-success'),
        2 => array('title' => 'Rechazada'  ,'color' => '#FF4C41','class'    => 'badge light badge-danger'),
    );


}