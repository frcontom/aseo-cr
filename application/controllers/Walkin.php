<?php

use PHPMailer\Phpspreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill; // Importar Fill
class Walkin extends CI_Controller
{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Walk-In';
    private $controller   = 'walkin/';
    public $project;
    public $Excel;
    private $result = array();

    public function __construct(){
        parent::__construct();
        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

        $this->user_data = $this->general->get('users',array('u_id' => $this->session_id));
    }

    function index() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'dataresult' => $this->general->all_get('status'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'datatable'     => base_url("{$this->controller}datatable"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js','plugins/numero/autoNumeric.js','cr/walkin.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('walkin/v_index',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function report() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'dataresult' => $this->general->all_get('status'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'datatable'     => base_url("{$this->controller}datatable_report"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/datatable_ajax.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('walkin/v_report',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function admon() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'sellers' => $this->general->all_get('seller'),
            'walks'   => $this->general->all_get('walk_in',[],[],'array'),
            'crud' => array(
                'url_modals'          => base_url("modal/"),
                'url_save_status'     => base_url("{$this->controller}save_status"),
                'url_save'            => base_url("{$this->controller}save_admon"),
                'url_save_restaurant'            => base_url("{$this->controller}save_admon_restaurant"),
                'url_datatable'           => base_url("{$this->controller}datatable_report_admon"),
                'url_sell_export'     => base_url("{$this->controller}sell_export"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/numero/autoNumeric.js','cr/walkin.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('walkin/v_report_admon',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function percentage() {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'user'          => $this->user_data
        );


        $data_body = array(
            'datas' => $this->general->query('select * from  walk_percentaje'),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save_percentaje"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/walkin.js','plugins/numero/autoNumeric.js'));

        $this->load->view('shared/template/v_header',$data_head);
        $this->load->view('shared/template/v_menu');
        $this->load->view('walkin/v_percentage',$data_body);
        $this->load->view('shared/template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('guest','seller','entry','exit','night','free','amount','comission','percentage'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $seller         = $this->class_security->data_form('seller');
                $guest          = $this->class_security->data_form('guest');
                $entry          = $this->class_security->data_form('entry');
                $exit           = $this->class_security->data_form('exit');
                $night          = $this->class_security->data_form('night');
                $number_booking          = $this->class_security->data_form('number_booking');
                //money
                $free           = $this->class_security->data_form('free','saldo');
                $amount         = $this->class_security->data_form('amount','saldo');
                $comission      = $this->class_security->data_form('comission','saldo');
                $percentage     = $this->class_security->data_form('percentage','saldo');

                //validation number booking
                $validate = $this->general->get('walk_in',['w_number_booking'  => trim($number_booking),'w_id != ' => $id],'array');
                if(isset($validate) and count($validate) > 0){
                    if($validate['w_id'] != $id){
                        $this->result = array('success' => 2,'msg' => 'El Numero de reserva ya existe');
                        api($this->result);
                        exit;
                    }
                }


                $data = array(
                    'w_user'            => $this->user_data->u_id,
                    'w_seller'          => $seller,
                    'w_guest'           => $guest,
                    'w_entry'           => $entry,
                    'w_exit'            => $exit,
                    'w_count_night'     => $night,
                    'w_fee'             => $free,
                    'w_amount'          => $amount,
                    'w_comission'       => $comission,
                    'w_percentage'      => $percentage,
                    'w_number_booking'  => $number_booking,
                    'w_aprroved'        => 0,
                    'w_status'          => 1,
                    'w_atcreate'        => fecha(2)
                );


                //validar la duplicidad del username or emailes
                $this->result =   $this->general->create_update('walk_in',array('w_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }
    function save_status(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('value','type','id'))){

                //campos post
                $id             = $this->class_security->data_form('id','decrypt_int');
                $type         = $this->class_security->data_form('type');
                $aprroved         = $this->class_security->data_form('value');


                if(in_array($type,['Restaurante','Venta'])){
                    if($type == 'Restaurante'){
                        $data = array('bill_approved' => ($aprroved == 1) ? 1 : 0);
                        $where = ['b_id' => $id];
                        $table = 'booking';
                    }else{
                        $data = array('w_aprroved' => ($aprroved == 1) ? 1 : 0);
                        $where = ['w_id' => $id];
                        $table = 'walk_in';
                    }
                     $this->general->update($table,$where,$data);
                }
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save_admon(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('edit','seller','entry','exit','night','free','amount','comission','percentage'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $seller         = $this->class_security->data_form('seller');
                $entry          = $this->class_security->data_form('entry');
                $exit           = $this->class_security->data_form('exit');
                $night          = $this->class_security->data_form('night');
                $edit          = $this->class_security->data_form('edit');
                //money
                $free           = $this->class_security->data_form('free','saldo');
                $amount         = $this->class_security->data_form('amount','saldo');
                $comission      = $this->class_security->data_form('comission','saldo');
                $percentage     = $this->class_security->data_form('percentage','saldo');

                $data = array(
                    'w_seller'          => $seller,
                    'w_entry'           => $entry,
                    'w_exit'            => $exit,
                    'w_count_night'     => $night,
                    'w_fee'             => $free,
                    'w_amount'          => $amount,
                    'w_comission'       => $comission,
                    'w_percentage'      => $percentage,
                    'w_lock'            => $edit
                );


                //validar la duplicidad del username or emailes
                $this->result =   $this->general->update('walk_in',array('w_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save_admon_restaurant(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('edit','data_id','comission','percentage'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $edit          = $this->class_security->data_form('edit');
                //money
                $comission      = $this->class_security->data_form('comission','saldo');
                $percentage     = $this->class_security->data_form('percentage','saldo');

                $data = array(
                    'bill_percentage'   => $percentage,
                    'bill_tip'          => $comission,
                    'bill_modify'       => $edit
                );


                //validar la duplicidad del username or emailes
                $this->result =   $this->general->update('booking',array('b_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save_percentaje(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('import'))){

                //validate is vector o single
                $import         = $this->class_security->data_form('import');

                if($import == 'single'){
                    $id         = $this->class_security->data_form('data_id','decrypt_int');
                    $type         = $this->class_security->data_form('type');
                    $name         = $this->class_security->data_form('name');
                    $day          = $this->class_security->data_form('day','saldo');
                    $major        = $this->class_security->data_form('major','saldo');

                    $data = [
                        'wp_name' => $name,
                        'wp_type' => $type,
                        'wp_percentaje_day' => $day,
                        'wp_percentaje_major' => $major
                    ];
                   $this->general->create_update('walk_percentaje',['wp_id' => $id],$data);




                }else{
                    //campos post
                    $percentajes         = $this->class_security->data_form('percentaje','alone');

                    if(isset($percentajes) and is_array($percentajes) and count($percentajes) > 0){
                        foreach($percentajes as $percentaje){
                            $id                 = $percentaje['id'];
                            $percentaje_day     = $percentaje['day'];
                            $percentaje_major   = $percentaje['major'];

                            $data = array(
                                'wp_percentaje_day'     => $percentaje_day,
                                'wp_percentaje_major'   => $percentaje_major,
                            );

                            //validar la duplicidad del username or emailes
                            $this->general->create_update('walk_percentaje',['wp_id' => $id],$data);
                        }
                }

                }
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function sell_export(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('seller','date1','date2'))){

                //campos post
                $seller  = $this->class_security->data_form('seller');
                $date_1  = $this->class_security->data_form('date1');
                $date_2  = $this->class_security->data_form('date2');



//        $date_1 = '2024-12-01';
//        $date_2 = '2024-12-31';
                //add seller
                $and_query = ($seller != '-1') ? "AND w_seller='".$seller."'" : '';
                $and_query2 = ($seller != '-1') ? "AND b.b_seller='".$seller."'" : '';

                $queryAlls = $this->general->query("select DATE_FORMAT(w.w_atcreate,'%Y-%m-%d') As w_atcreate,s.s_name,w_number_booking,w.w_guest,w.w_entry,w.w_exit,w.w_count_night,w_fee,w_amount,w_comission,w_percentage,w_aprroved,'Venta' As 'type_event' from walk_in As w
    JOIN seller As s ON w.w_seller=s.s_id
      WHERE  DATE_FORMAT(w.w_atcreate, '%Y-%m-%d') BETWEEN '".$date_1."' AND '".$date_2."' and w_aprroved='1'  {$and_query}

UNION ALL
select DATE_FORMAT(b.b_atcreate,'%Y-%m-%d') ,s.s_name,b.bill_number,b.b_client_name,t.bd_day,'-' As 'w_exit',b.bill_amount,'0' As 'w_amount',b.bill_tip,b.bill_approved,b.bill_percentage,b.bill_approved,'Restaurante' As 'type_event' from booking As b
    LEFT JOIN (select bd.bd_booking,MIN(bd.bd_day) As bd_day from booking_days As bd GROUP BY bd.bd_booking) As t On b.b_id=t.bd_booking
    JOIN seller As s ON b.b_seller=s.s_id
    where b_type=3 AND  DATE_FORMAT(b.b_atcreate, '%Y-%m-%d') BETWEEN '".$date_1."' AND '".$date_2."' and b.bill_approved='1'   {$and_query2}");

                // Agrupar datos por vendedor
                $vendedores = [];
                foreach ($queryAlls as $row) {
                    $s_name = $row['s_name'];
                    if (!isset($vendedores[$s_name])) {
                        $vendedores[$s_name] = [];
                    }
                    $vendedores[$s_name][] = $row;
                }

                $headers = [
                    'FECHA', 'RECEPCIONISTA VENDEDOR', 'Nº WALK-IN', 'HUESPED',
                    'INGRESO', 'SALIDA', 'CANT. NOCHES', 'TARIFA', 'MONTO DE VENTA', 'COMISION', 'PORCENTAJE', 'TIPO'
                ];

                // Definir el estilo para fondo verde
//                $greenBackgroundStyle = [
//                    'fill' => [
//                        'fillType' => 'solid',
//                        'startColor' => ['rgb' => '00FF00'], // Verde
//                    ],
//                    'alignment' => [
////                        'horizontal' => Alignment::HORIZONTAL_CENTER,
////                        'vertical' => Alignment::VERTICAL_CENTER,
//                    ],
//                ];

                $this->load->library('Excel');
                $this->Excel = new Excel();
                $spreadsheet = $this->Excel->createSpreadsheet();


                // Inicializar las variables para los totales generales
                $amount_sell = 0;
                $comission_sell = 0;

                foreach ($vendedores as   $vendedor => $rows) {
                    // Crear nueva hoja para cada vendedor
                    $sheet = $spreadsheet->createSheet();
                    $sheet->setTitle($vendedor);

                    // Agregar encabezados
                    foreach ($headers as $colIndex => $header) {
                        $cell = chr(65 + $colIndex) . '1'; // A1, B1, C1, etc.
                        $sheet->setCellValue($cell, $header);

                    }

                    // Agregar datos
                    foreach ($rows as $rowIndex => $row) {
                        $rowData = [
                            $row['w_atcreate'],        // FECHA
                            $vendedor,              // VENDEDOR
                            $row['w_number_booking'],           // Nº WALK-IN
                            $row['w_guest'],        // HUESPED
                            $row['w_entry'],        // INGRESO
                            $row['w_exit'],         // SALIDA
                            $row['w_count_night'],  // CANT. NOCHES
                            $row['w_fee'],          // TARIFA
                            $row['w_amount'],       // MONTO DE VENTA
                            $row['w_comission'],    // COMISION
                            $row['w_percentage'].'%',    // PERCENTAGE
                            $row['type_event'],    // COMISION
                        ];

                        foreach ($rowData as $colIndex => $value) {
                            $cell = chr(65 + $colIndex) . ($rowIndex + 2); // Filas desde la segunda
                            $sheet->setCellValue($cell, $value);

                            // Aplicar formato de moneda a columnas específicas
                            if (in_array($colIndex, [7, 8, 9])) { // Columnas de TARIFA (I), MONTO DE VENTA (J), COMISIÓN (K)
                                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('$#,##0.00'); // Formato de moneda
                            }
//                            elseif(in_array($colIndex, [10])) {
//                                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0%'); // Formato de porcentaje con dos decimales
//                            }
                        }

                        // Sumar totales solo si la venta está aprobada
                        if ($row['w_aprroved'] == 1) {

//                            $fechaCell = 'A' . ($rowIndex + 2); // Celda de la columna FECHA
//                            $sheet->getStyle($fechaCell)->applyFromArray($greenBackgroundStyle);

                            // Sumar a los totales generales
                            $amount_sell += $row['w_amount'];
                            $comission_sell += $row['w_comission'];
                        }
                    }

                    // Obtener el rango de celdas con datos
                    $lastColumn = chr(65 + count($headers) - 1); // Última columna (ejemplo: K)
                    $lastRow = count($rows) + 1; // Última fila de datos

                    // Agregar la fila de totales
                    $totalRow = $lastRow + 1; // Fila para totales
                    $sheet->setCellValue("I{$totalRow}", 'TOTAL'); // Columna de texto "TOTAL"

                    // Sumar Monto de Venta (J) y Comisión (K)
//                    $sheet->setCellValue("I{$totalRow}", "=SUM(I2:I{$lastRow})"); // Fórmula para Monto de Venta
//                    $sheet->setCellValue("J{$totalRow}", "=SUM(J2:J{$lastRow})"); // Fórmula para Comisión

                    $sheet->setCellValue("I{$totalRow}", $amount_sell); // Total de Monto de Venta por vendedor
                    $sheet->setCellValue("J{$totalRow}", $comission_sell); // Total de Comisión por vendedor



                    // Aplicar formato de moneda a los totales
                    $sheet->getStyle("I{$totalRow}")->getNumberFormat()->setFormatCode('$#,##0.00');
                    $sheet->getStyle("J{$totalRow}")->getNumberFormat()->setFormatCode('$#,##0.00');

                    // Aplicar bordes a la fila de totales
                    $totalRange = "A{$totalRow}:{$lastColumn}{$totalRow}";
                    $sheet->getStyle($totalRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($totalRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($totalRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


                    // Aplicar bordes al rango
                    $range = "A1:{$lastColumn}{$lastRow}";
                    $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                }


            // Eliminar la hoja inicial vacía
                $spreadsheet->removeSheetByIndex(0);

                $timestamp = mt_rand(1, 9); // Formato: Año_Mes_Día_Hora_Minuto
                $fileName = "reporte_{$timestamp}.xlsx";
                $this->Excel->createXlsxWriter($spreadsheet,$fileName);


                $filepathName = base_url() . "_files/exports/". $fileName;

                $this->result = array('success' => 1,'file' => $filepathName);

                //delete file
                $this->load->helper("file");

//                if(file_exists("_files/exports/". $fileName)){
//                    sleep(3);
//                    unlink("_files/exports/". $fileName);
//                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('walk_in',array('w_id' => $id))){
                        $this->result =  $this->general->update('walk_in',array('w_id' => $id),['w_status' => 99]);
                    }else{
                        $this->result = array('success' => 2,'msg' => 'Dato no existe');
                    }
                }else{
                    $this->result = array('success' => 2,'msg' => 'Dato no existe');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }

        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function datatable(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $valor      =  (isset($busqueda)) ? $this->class_security->letras_numeros_mas($busqueda['value']) : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();

        //tabla
        $consulta_primary =  "select * from walk_in where w_status != 99 AND (w_guest LIKE '%".$valor."%'
             OR w_entry LIKE '%".$valor."%'
             OR w_exit LIKE '%".$valor."%'
             OR w_fee LIKE '%".$valor."%'
             OR w_amount LIKE '%".$valor."%'
             OR w_comission LIKE '%".$valor."%'
             OR w_percentage LIKE '%".$valor."%'
             OR w_number_booking LIKE '%".$valor."%') ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->w_id);

            $data[]= array(
                $rows->w_number_booking,
                $rows->w_guest,
                $rows->w_entry,
                $rows->w_exit,
                $rows->w_count_night,
                '$ '.number_format($rows->w_fee,2),
                '$ '.number_format($rows->w_amount,2),
                '$ '.number_format($rows->w_comission,2),
                "<button type='button' onclick='$(this).forms_modal({\"page\" : \"walkin\",\"data1\" : \"{$id}\",\"title\" : \"Venta\"})' class='btn btn-primary btn-sm'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>
                "
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }

    function datatable_report(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $valor      =  (isset($busqueda)) ? $this->class_security->letras_numeros_mas($busqueda['value']) : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();


        //tabla
        $consulta_primary =  "select * from walk_in where w_status != 99 and (w_guest LIKE '%".$valor."%' OR  w_entry LIKE '%".$valor."%' OR w_exit LIKE '%".$valor."%') ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
        //    $id         = encriptar($rows->w_id);

            $data[]= array(
                $rows->w_guest,
                $rows->w_entry,
                $rows->w_exit,
                $rows->w_count_night,
                $rows->w_fee,
                $rows->w_amount,
                $rows->w_comission
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }
    function datatable_report_admon(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");

            $filter_0   = $this->class_security->letras_numeros_mas($this->input->post("column_0"));
            $filter_1   = $this->class_security->letras_numeros_mas($this->input->post("column_1"));
            $filter_2   = $this->class_security->letras_numeros_mas($this->input->post("column_2"));
            $filter_3   = $this->class_security->letras_numeros_mas($this->input->post("column_3"));
            $filter_4   = $this->class_security->letras_numeros_mas($this->input->post("column_4"));
            $filter_5   = $this->class_security->letras_numeros_mas($this->input->post("column_5"));
            $filter_6   = $this->class_security->letras_numeros_mas($this->input->post("column_6"));
            $filter_7   = $this->class_security->letras_numeros_mas($this->input->post("column_7"));
            $filter_8   = $this->class_security->letras_numeros_mas($this->input->post("column_8"));


        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $filter_0   = ''; // huesped
            $filter_1   = ''; // ingreso
            $filter_2   = ''; // salida
            $filter_3   = ''; // entrada
            $filter_4   = ''; // monto
            $filter_5   = ''; // porcentaje
            $filter_6   = ''; // comision
            $filter_7   = ''; // reserva
            $filter_8   = ''; // reserva
        }
        $data = array();


        //tabla
        $consulta_primary =  "(select w.w_id,s.s_name,w.w_guest,w.w_entry,w.w_exit,w_fee,w_amount,w_comission,w_percentage,w_number_booking,w_aprroved,'Venta' As 'type_event' from walk_in As w  JOIN seller As s ON w.w_seller=s.s_id
                WHERE s.s_name LIKE '%".$filter_0."%'
                             AND (w.w_guest LIKE '%".$filter_1."%'
                             AND w.w_entry LIKE '%".$filter_2."%'
                             AND w.w_exit LIKE '%".$filter_3."%'
                             AND w.w_fee LIKE '%".$filter_4."%'
                             AND w.w_amount LIKE '%".$filter_5."%'
                             AND w.w_comission LIKE '%".$filter_6."%'
                             AND w.w_percentage LIKE '%".$filter_7."%'
                             AND w.w_number_booking LIKE '%".$filter_8."%')
                )
                UNION ALL
                (select b.b_id,s.s_name,b.b_client_name,t.bd_day,'-' As 'w_exit',b.bill_amount,'0' As 'w_amount',b.bill_tip,b.bill_percentage,b.bill_number,b.bill_approved,'Restaurante' As 'type_event' from booking As b
                    LEFT JOIN (select bd.bd_booking,MIN(bd.bd_day) As bd_day from booking_days As bd GROUP BY bd.bd_booking) As t On b.b_id=t.bd_booking
                    JOIN seller As s ON b.b_seller=s.s_id
                where b_type=3 AND
                (
                    s.s_name LIKE '%".$filter_0."%'
                     AND t.bd_day LIKE '%".$filter_2."%'
                     AND b.bill_amount LIKE '%".$filter_4."%'   
                     AND b.bill_tip LIKE '%".$filter_6."%'  
                     AND b.bill_percentage LIKE '%".$filter_7."%'
                     AND b.bill_number LIKE '%".$filter_8."%'
                    ))
             ";

        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count     = $this->general->query($consulta_primary);
        $total_registros = count($query_count);


        foreach($dataget as $rows){
            $id         = encriptar($rows->w_id);
            $type       = encriptar($rows->type_event);

            $aproved = ($rows->w_aprroved == 1) ? 'checked' : '';
            $data[]= array(
                $rows->s_name,
                $rows->w_guest,
                $rows->w_entry,
                $rows->w_exit,
                '$'.number_format($rows->w_fee,2),
                '$'.number_format($rows->w_amount,2),
                '$'.number_format($rows->w_comission,2),
                $rows->w_percentage.'%',
                $rows->w_number_booking,
                $rows->type_event,
                "<div class='form-check form-switch'><input class='form-check-input' type='checkbox' data-id='{$id}' {$aproved} onchange='$(this).change_status(\"{$rows->type_event}\")' role='switch' id='flexSwitchCheckDefault'></div>",
                "<button type='button' onclick='$(this).forms_modal({\"page\" : \"walkin_admon\",\"data1\" : \"{$id}\",\"data2\" : \"{$type}\",\"title\" : \"Reporte de ventas por vendedor\"})' class='btn btn-success btn-sm'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-pencil text-white'></i></button>"
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }

}