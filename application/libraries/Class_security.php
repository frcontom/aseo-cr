<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Class_security{

    private $CI;
    private $result;

    function __construct(){
        $this->CI = &get_instance();
    }

    function letras_numeros($limpiardato){
        return preg_replace('#[^a-zA-Z0-9]#i', '', strip_tags(trim($limpiardato ?? '')));
    }

    function letras_numeros_espace($limpiardato){
        return preg_replace('#[^a-zA-Z0-9-=._, ]#i', '', strip_tags(trim($limpiardato ?? '')));
    }

    function letras_numeros_mas($datos_filtrar)
    {
        $limpiar1 = strip_tags(trim($datos_filtrar ?? ''));
        $limpiar2 = preg_replace('#[^a-zA-Z0-9-=._]#i', '', $limpiar1);
        return $limpiar2;
    }

    function solo_numerico($datos_filtrar) {
        $limpiar1 = strip_tags(trim($datos_filtrar ?? ''));
        $limpiar2 = preg_replace('#[^0-9.]#i', '', $limpiar1);
        return $limpiar2;
    }

    function limpiar_form($limpiardato){
        $field = (isset($limpiardato) and gettype($limpiardato) == 'string') ? $limpiardato : '';
        return strip_tags(trim((string) $field));
    }

    function Int($datos_filtrar){
        return  preg_replace('#[^0-9]#i', '', strip_tags(trim($datos_filtrar ?? '')));
    }

    function capitalizar($limpiar){
        return htmlentities(ucfirst(trim($limpiar ?? '')), ENT_QUOTES, 'UTF-8');
    }

    function codificar($limpiar)  {
        return htmlentities(trim($limpiar ?? ''), ENT_QUOTES, 'UTF-8');
    }
    function mayuscula($limpiar)  {
        return mb_strtoupper($limpiar, 'UTF-8');
    }

    function decodificar($decodificar){
        return html_entity_decode((String)$decodificar, ENT_QUOTES, 'UTF-8');
    }

    function validate_post($post_information){
        $response=true;

        foreach($post_information as $post_input) {
            //explode

            //validar si se envia la ,
            if(strpos($post_input,',')){


                $post_ex = explode(',',$post_input);
                $post_ex_input = $post_ex['0'];
                $post_ex_type  = $post_ex['1'];

                //validate type data
                if($post_ex_type == 'str'){
                    $input_data = isset($_POST[$post_ex_input]) ?  $this->limpiar_form($_POST[$post_ex_input]) : '';
                }else{
                    $input_data = isset($_POST[$post_ex_input]) ?  $this->Int($_POST[$post_ex_input]) : '';
                }

            }else{
                $input_data = isset($_POST[$post_input]) ?  $this->limpiar_form($_POST[$post_input]) : '';
            }

            if (strlen($input_data) == 0) {
                $response = false;
            }
        }
        return $response;
    }

    function data_form($input,$type = 'str',$default = ''){

        if($type == 'str'){
            $this->result = $this->limpiar_form($this->CI->input->post($input));
        }elseif($type == 'str2'){
            $this->result = $this->letras_numeros_espace($this->CI->input->post($input));
        }elseif($type == 'clean'){
            $this->result = $this->letras_numeros($this->CI->input->post($input));
        }elseif($type == 'decrypt_int'){
            $this->result = ($this->Int(desencriptar($this->CI->input->post($input))));
        }elseif($type == 'decrypt_str'){
            $this->result = ($this->letras_numeros(desencriptar($this->CI->input->post($input))));
        }elseif($type == 'decrypt_all'){
            $this->result = ($this->letras_numeros_espace(desencriptar($this->CI->input->post($input))));
        }elseif($type == 'uft8'){
            $this->result = $this->codificar($this->CI->input->post($input));
        }elseif($type == 'saldo'){
            $this->result = $this->solo_numerico($this->CI->input->post($input));
        }elseif($type == 'alone'){
            $this->result = $this->CI->input->post($input);
        }elseif($type == 'pass_crypt'){
            $this->result = encriptar_password($this->CI->input->post($input));
        }else{
            $this->result = $this->Int($this->CI->input->post($input));
        }


        //clean data
        if($type == 'alone'){
            $this->result = $this->result;
        }else{
            $this->result =  (strlen($this->result) >= 1) ? $this->result :  $default;
        }


        return ($this->result);
    }

    //IP USER ADdRESS
    function get_user_ip_address(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }
        return $user_ip;
    }

    function generate_password() {
        $data = '1234567890abcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, 7);
    }

    function generate_code($size = 5) {
        $strength = $size;
        $rnd = mt_rand(0,9);
        $input = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        //md5 salt
        return $random_string.$rnd;
    }

    function array_data($key,$dataAr,$default = ''){

        $result =  '';
        if(count($dataAr) >= 1){
            if(isset($dataAr[$key])){
                $result = $dataAr[$key];
            }else{
                if(is_array($default)){
                    if(count($default) >= 1){
                        $result = $default[1];
                    }
                }else{
                    $result = $default;
                }

            }
        }
        return $result;
    }

    function sumar_dias($dias = 0){
        return date("Y-m-d",strtotime(date('Y-m-d')."+ {$dias} days"));
    }

    function sumar_meses($dias = 0,$fecha = "Y-m-d"){
        return date($fecha,strtotime(date($fecha)."+ {$dias} month"));
    }

    function restar_dias($mes = "Y-m-d",$dias = 0){
        return date($mes,strtotime(date($mes)."+ {$dias} days"));
    }

    function sumar_segundos($fecha = 0){
        $newDate = strtotime ( '+3 second' , strtotime ($fecha) ) ;
        $newDate = date ( 'Y-m-j H:i:s' , $newDate);
        return $newDate;
    }
    function sumar_minutos($fecha = 0,$minutos = 0){
        $newDate = strtotime ( "+{$minutos} minutes" , strtotime ($fecha) ) ;
        $newDate = date ( 'Y-m-d H:i:s' , $newDate);
        return $newDate;
    }
    function sumar_hora($fecha = 0,$hora = 0){
        $newDate = strtotime ( "+{$hora} hours" , strtotime ($fecha) ) ;
        $newDate = date ( 'Y-m-d H:i:s' , $newDate);
        return $newDate;
    }

    function random(){
        return md5(date("Y-m-d h:m:s".mt_rand(0,9999).md5(uniqid())));
    }

    function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }

    function eliminar_archivo($directorio,$documento){
        $documento_folder = $directorio.$documento;
        if ((strlen($documento) > 3) && file_exists($documento_folder)) {
            unlink($documento_folder);
        }
    }


    function upload_document($name,$directory,$extencion){
        $CI = &get_instance();
        $config['upload_path'] = $directory;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['allowed_types'] = $extencion;
        $CI->load->library('upload', $config);

        if ($CI->upload->do_upload($name)) {
            $data = $CI->upload->data();
            $result_documento = $data['file_name'];
        } else {
            $result_documento = '';
        }
        return $result_documento;
    }

    function upload_image($imagen,$directory){
        $CI = &get_instance();

        $config['upload_path'] = $directory;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
//        $config['file_name'] = $imagen;
        $CI->load->library('upload', $config);

        if ($CI->upload->do_upload($imagen)) {
//            print_r($CI->upload->display_errors());
            $data = $CI->upload->data();
            $result_documento = $data['file_name'];
        } else {
            $result_documento = '';
        }
        return $result_documento;
    }

    function imageresize($imagen_source, $directory_new, $width, $height){
        $this->CI = &get_instance();
        $this->CI->load->library('image_lib');
        $this->CI->image_lib->clear();
        $config['image_library']  = 'gd2';
        $config['source_image']   = $imagen_source;
        $config['new_image']      = $directory_new;
        $config['create_thumb']   = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['thumb_marker']    = '';

        $this->CI->image_lib->initialize($config);
        return  $this->CI->image_lib->resize();
    }


    //sacar la cantidad de cuotas

    function sacar_cuotas($date1,$date2){
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $diff = $date1->diff($date2);
        return $diff->m;
    }

    function validar_existe_archivo($directorio,$documento){
        $documento_folder = $directorio.$documento;
        $result = false;
        if ((strlen($documento) > 3) && file_exists($documento_folder)) {
            $result = true;
        }
        return $result;
    }


    function mover_archivo($foler1,$folder2,$archivo){

        $archivo_old = "{$foler1}{$archivo}";
        $archivo_move = "{$folder2}{$archivo}";
        if($this->validar_existe_archivo($foler1,$archivo)){
            rename($archivo_old, $archivo_move);
        }
    }

    function generar_codigo(){
        $timestamp = mt_rand(5, time());
        $randomDate = date("Ydms", $timestamp);
        return $randomDate;
    }

    function rango_fechas($startDate, $endDate) {
        $days = array();

        while (strtotime($startDate) <= strtotime($endDate)) {
            $days[] = date('Y-m-d', strtotime($startDate));
            $startDate = date('Y-m-d', strtotime($startDate . ' +1 day')); // Avanzamos 1 día
        }

        return $days;
    }

    function segmento_fechas($fechas) {
      return  $fechas_por_mes = array_reduce($fechas, function($resultado, $fecha) {
            // Obtén el mes y año de la fecha
            $mes = date('Y-m', strtotime($fecha));

            // Agrupa las fechas por mes
            if (!isset($resultado[$mes])) {
                $resultado[$mes] = [];
            }
            $resultado[$mes][] = $fecha;

            return $resultado;
        }, []);

    }
    function rango_dias($startDate, $endDate) {
        $dias = [];
        while (strtotime($startDate) <= strtotime($endDate)) {
            $dias[] = $startDate;
            $startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
        }
        return $dias;
    }

    function img_avatar($imagen = '',$tipo = 1){
        if(strlen($imagen) >= 10){
            $img2 = base_url("_files/{$imagen}");
            if($tipo == 1){
                $img = "<div class='user-with-avatar'><img class='rounded-circle' style='height: 40px;width: 40px;' src='$img2'></div>";
            }else{
                $img = "<a href='$img2' class='single_image'><img class='rounded-circle' style='height: 40px;width: 40px;' src='$img2'></a>";

            }
         }else{
            $img2 = base_url("_files/default.jpg");
            $img = "<div class='user-with-avatar'><img class='rounded-circle' style='height: 40px;width: 40px;' src='$img2'></div>";
        }
        return $img;
    }

     function asertarMoneda($valor,$x = ''){
        $result = $valor;
        if($valor != '-'){
            $result = ($x != '') ? '₡ ' : ''. $this->dinero($valor);
        }
        return $result;
    }




    function validate_var($arr,$dato = '',$default = ''){
        return   ($dato == 'all') ? $arr : (
            (isset($arr[$dato]) and $dato != '') ?
                $arr[$dato] :
                ((isset($arr) and $dato == '') ?
                $arr :
                    $default)
        );
    }

    function substr($palabra,$inicio,$final){
            return substr($palabra,$inicio,$final);
    }

    function isPayable( $payDate,  $startDate,  $endDate)
    {
        return $payDate > $startDate && $payDate < $endDate;
    }

    function format_date( $orgDate,  $format = 'Y-m-d')
    {
        if($orgDate != null or $orgDate != ''){
            return date($format, strtotime($orgDate));
        }
    }


    function dinero($saldo,$decimal = 2){
        $saldo = $this->solo_numerico($saldo);
        return number_format(round($saldo,$decimal,PHP_ROUND_HALF_DOWN),$decimal);
    }

    function numberformat($value = 0){
        if($value == 0 || $value == ''){
            return '';
        }
        return number_format($value,2);
    }

    function codeDeclaracion($id) {
        $strength = 24;
        $input = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        //md5 salt
        return strtoupper(md5($id.$random_string));

    }

    function codeGenerate() {
        $randCode  = (string)mt_rand(10000000,99999999);
        $randChar  = rand(65,90);
        $randInx   = rand(0,3);
        $randCode[$randInx] = chr($randChar);
        return $randCode;
    }

    function filter_array_simple($arrayf = [],$keyIn = '',$search = ''){
       return array_filter(($arrayf), function ($key) use ($keyIn, $search) {
           return strpos(strtolower($key[$keyIn]), strtolower($search)) !== FALSE;
//            return ($key['ct_id'] == $search);
        });
    }

    function filter_var($arrayf = [],$search = '',$value = ''){
        $allowed=array_filter($arrayf,  function($data) use ($search, $value){
            return ($data[$search] == $value);
        }, ARRAY_FILTER_USE_BOTH);

        $values_result = array_values($allowed);
        return (isset($values_result[0])) ? $values_result[0] : [];

//        return array_values($allowed)[0] || [];
    }

    function filter_all($arrayf = [],$search = '',$value = ''){
        $allowed=array_filter($arrayf,  function($data) use ($search, $value){
            return ($data[$search] == $value);
        }, ARRAY_FILTER_USE_BOTH);

        return  array_values($allowed);
    }

    function countdown($startdate,$enddate){
//        $now =  date('Y-m-d h:i:s', time());
//        $startdate = $now;
//        $enddate =   "2023-04-11 11:58:39";

        $diff = strtotime($enddate) - strtotime($startdate);

        $temp = $diff/86400; // (60 sec/min * 60 min/hr * 24 hr/day = 86400 sec/day)
        if($temp > 0){
            $dd = floor($temp); $temp = 24*($temp-$dd); //Days
            $hh = floor($temp); $temp = 60*($temp-$hh); //Hours
            $mm = floor($temp); $temp = 60*($temp-$mm); //Minutes
            $ss = date('s', (floor($temp))); //Seconds
        }else{
            $dd  = 0;
            $hh  = 0;
            $mm  = '00';
            $ss  = '00';
        }

        $Dia = ($dd > 0)  ?  "{$dd} Dias " : '';
        $hora = ($hh > 0)  ?  "{$hh}:" : '';
        return $Dia. $hora . $mm.":".$ss;
    }

    function add_time_cout($add,$date){
        $check = $this->sumar_hora($date,$add);
        return $this->countdown(fecha(2),$check);
    }

    function rndRGBColorCode() {
        return 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')'; #using the inbuilt random function
    }

    function formatNumberMoney($value,$currenty = 'Colones'){
        $CI = & get_instance();
        $CI->load->library('number_string');

//        $numbers = new NumberToString();

        return  $CI->number_string->numberToMoney($value,$currenty);

//        if($value == 0 || $value == ''){
//            return "";
//        }
//        $formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
//        $n = $value;
//        $izquierda = intval(floor($n));
//        $derecha = intval(($n - floor($n)) * 100);
//        return $formatterES->format($izquierda) . " Con " . $formatterES->format($derecha) . ' (centavos, centésimos o céntimos)';
    }


    function datehuman($date,$method = 1){
        $CI =& get_instance();
        $CI->load->library('class_data');
        $dater = '';
        if($date != null or $date != ''){

            $date = explode('-',$date);
            if($method == 1){
                $month = $CI->class_data->meses[ltrim($date[1], '0')];
                $dater = $date[2].' '.$month.' del '.$date[0];
            }else{
                $dater = $date[2].' '.$date[1].' del '.$date[0];
            }
        }
//
        return $dater;
    }

    function change_status_clean($value){
        if(in_array($value,[3,13])){
           return 1;
        }elseif ($value == 4){
            return 2;
        }elseif ($value == 7){
            return 6;
        }else{
            return 2;
        }
    }

    function not_bother($id,$status = ''){
        $id = encriptar($id);
        $title = 'Inactivo';
        $btn_color = 'btn btn-dark';
        if($status == 4){
            $title = 'ACTIVO';
            $btn_color = 'btn btn-success';
        }
    return    "  <div class='btn-group' role='group'>
                        <button type='button' class='{$btn_color} btn-sm dropdown-toggle' data-toggle='dropdown'>No Molestar ({$title})</button>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item' href='javascript:void(0)' onclick='$(this).change_bother(\"{$id}\",1)'> Activar</a>
                            <a class='dropdown-item' href='javascript:void(0)' onclick='$(this).change_bother(\"{$id}\",2)'> Desactivar</a>
                        </div>
                    </div>";
    }


    function day_reserved($proforma = '',$days = []){
        $this->result = [];
        $CI =& get_instance();
        $CI->load->model('general');

        if(isset($days) and is_array($days) and !empty($days)){
            $conditions = [];
            foreach ($days as $solicitud) {


                $date_find   = (isset($solicitud['date']) and $solicitud['date'] != '')  ? $solicitud['date']  : '1900-01-01';
                $room_find   = (isset($solicitud['room']) and $solicitud['room'] != '')  ? $solicitud['room']  : '';
                $date_init   = (isset($solicitud['hour1']) and $solicitud['hour1'] != '') ? $solicitud['hour1'] : '00:00';
                $date_finish = (isset($solicitud['hour2']) and $solicitud['hour2'] != '') ? $solicitud['hour2'] : '00:00';


                $conditions[] = "(bd_day = '" . $date_find . "' AND bd_room = '" . $room_find . "' AND (
                        '" . $date_init . "' BETWEEN bd_houri AND bd_hourf OR
                        '" . $date_finish . "' BETWEEN bd_houri AND bd_hourf OR
                        bd_houri BETWEEN '" . $date_init . "' AND '" . $date_finish . "' OR
                        bd_hourf BETWEEN '" . $date_init . "' AND '" . $date_finish . "'
                    ))";
            }

            //join all days
            $conditions_sql = implode(' OR ', $conditions);

//            WHERE p.pf_status = 2 AND
            // Preparar la consulta
            $sql = "SELECT p.b_event_name,pf.bd_day, pf.bd_houri, pf.bd_hourf 
                    FROM booking AS p
                    JOIN booking_days AS pf ON p.b_id = pf.bd_booking
                    WHERE p.b_booking_type IN (2,4) AND p.b_status  != 4 AND pf.bd_booking != '" .$proforma. "' AND ($conditions_sql) ORDER by bd_day ASC";

//            echo $sql;
            $queryReserved = $CI->general->query($sql,'array');
            return $queryReserved;
        }
    }


    function send_mailer($emailsSends = [],$subject = '',$template = '',$attachment = [],$data = []){
        $CI = &get_instance();

        if(isset($emailsSends) and count($emailsSends) > 0){
//            $emails[] = ['feconto@gmail.com'];
//            $emails[] = 'feconto@gmail.com';

            try {
                $emails = array_filter(array_column($emailsSends, 'u_email'), function($email) {
                    return !empty($email);
                });

//            $emails = array_column($emailsSends, 'u_email');
//            foreach($emailsSends As $areasmtp){
//                        $emails[] = $areasmtp['u_email'];
//            }
//            print_r($emails);
//            exit;

                $email_parameters=array(
                    "to"            => array_values($emails),
                    'email'         => $CI->config->config['project']['email'],
                    "subject"       => $subject,
                    "template"      => $template,
                    'attachment'    => $attachment,
                    'data'          => $data
                );
                $CI->load->library('Mailer');
                $CI->mailer->initialize($email_parameters);
                $CI->mailer->send_mail();
            }catch (Exception $e){
                // Manejo de excepciones si el envío falla
//                print_r('error', 'Error al enviar el correo: ' . $e->getMessage());
            }
        }

    }


    function forceUtf8($value) {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = forceUtf8($val);
            }
        } else if (is_string($value)) {
            // Primero, intenta convertir si se detecta una codificación
            $encoded = mb_convert_encoding($value, 'UTF-8', 'UTF-8');

            // Si la conversión falla, realiza un filtrado básico para asegurar UTF-8
            if ($encoded === false) {
                $encoded = utf8_encode($value); // Usa utf8_encode como último recurso
            }

            return $encoded;
        }
        return $value;
    }

    function events_modifid(){
        $CI =& get_instance();
        $CI->load->model('general');
        $fecha = fecha(1);
        $all_data = $CI->general->query("select  b.b_id,b.b_event_name, em_code, MAX(em_atcreate) AS em_atcreate from booking As b
JOIN booking_days As bd ON b.b_id=bd.bd_booking
 JOIN booking_modifid As bm ON b.b_code=bm.em_code
WHERE bd.bd_day >= '".$fecha."'
GROUP BY b.b_id,em_code, em_type ORDER BY MAX(em_id) DESC LIMIT 20");

        return [
            'count' => count($all_data),
            'data' => $all_data
        ];
    }

    function obtenerSemana($fecha, $offset = 0) {
//        $fecha = date('Y-m-d'); // Fecha actual
        // Calcular el inicio de la semana en el lunes según la fecha y el desplazamiento (offset)
        $inicioSemana = strtotime('monday this week', strtotime($fecha . " +$offset week"));
        return date('Y-m-d', $inicioSemana); // Devolver la fecha en formato YYYY-MM-DD
    }

    function obtenerSemanaPorFecha($fecha) {
        // Establecer el idioma español con IntlDateFormatter
        $locale = 'es_ES';
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        $formatter->setPattern('EEEE');  // Formato solo para el nombre del día (ej. lunes, martes, etc.)

        // Crear un objeto DateTime con la fecha proporcionada
        $fechaActual = new DateTime($fecha);

        // Ajustar a lunes de la semana correspondiente a la fecha proporcionada
        $fechaActual->modify('this week monday');

        // Inicializamos un array para guardar las fechas y los días
        $semana = [];

        // Agregar cada día de la semana (lunes a domingo)
        for ($i = 0; $i < 7; $i++) {
            $semana[] = [
                'fecha' => $fechaActual->format('Y-m-d'),  // Fecha en formato Año-Mes-Día
                'dia' =>  mb_strtoupper($formatter->format($fechaActual))  // Día de la semana en español
            ];
            $fechaActual->modify('+1 day');  // Avanzamos un día
        }
        return $semana;
    }

    function getWeekFromDate($date,$days = 7) {
        $dates = [];
        $currentDate = new DateTime($date);
        // Genera la fecha actual y los próximos 6 días
        for ($i = 0; $i < $days; $i++) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day');
        }
        return $dates;
    }

    function calculetePercentage($fee,$percentage){
        $calcule = 0;

        if($fee != 0 ){

            $calcule = number_format(($percentage / 100) * $fee);
        }
        return $calcule;
    }


    function verificar_puntualidad($hora_limite, $hora_actual, $margen = 5, $tipo = 'entrada') {
        // Convertir las horas a timestamps
        $hora_limite = strtotime($hora_limite);
        $hora_actual = strtotime($hora_actual);

        // Calcular la diferencia entre la hora límite y la hora actual
        $diferencia = $hora_actual - $hora_limite;

        // Lógica para la Entrada
        if ($tipo == 'entrada') {
            // Si el empleado llegó después de los X minutos de margen (retraso)
            if ($diferencia > $margen * 60) {
                $status = 2;  // Retraso
                $tiempo = round($diferencia / 60);  // Tiempo en minutos (positivo)
            } else {
                // Llegó a tiempo o dentro del margen
                $status = 1;  // A tiempo
                $tiempo = 0;
            }
        }

        // Lógica para la Salida
        else {
            // Si el empleado sale después de la hora pactada + margen (horas extras)
            if ($diferencia > $margen * 60) {
                $status = 2;  // Horas extras
                $tiempo = round($diferencia / 60);  // Tiempo positivo (trabajo extra)
            }
            // Si el empleado sale antes de la hora pactada (falta)
            elseif ($diferencia < 0) {
                $status = 3;  // Falta (salida anticipada)
                $tiempo = round($diferencia / 60);  // Tiempo negativo (salió antes)
            }
            // Si el empleado salió exactamente a la hora pactada (a tiempo)
            else {
                $status = 1;  // A tiempo
                $tiempo = 0;
            }
        }

        // Retornar los resultados
        return [
            'status' => $status,   // 1: A tiempo, 2: Horas extras, 3: Falta
            'timer' => $tiempo,    // El tiempo calculado (positivo o negativo)
        ];
    }

    function pass_minute_to_hours($minutos = 0){
        // Convertir minutos a horas, minutos y segundos
        $minutos = abs($minutos);
        $horas = floor($minutos / 60);
        $minutosRestantes = $minutos % 60;
        $segundos = 0; // Si quieres agregar segundos, puedes ajustarlo aquí

        // Crear un objeto DateTime con la hora calculada
        $tiempo = new DateTime();
        $tiempo->setTime($horas, $minutosRestantes, $segundos);

        // Mostrar el tiempo en formato G:i:s
        return $tiempo->format('G:i:s');
    }

    function aplanarDatosOptimizado($datos) {
        $resultado = [];
        foreach ($datos as $usuario) {
            if (isset($usuario['dates'])) {
                foreach ($usuario['dates'] as $entradas) {
                    foreach ($entradas as $entrada) {
                        // Solo agregamos lo que necesitamos
                        $resultado[] = $entrada;
                    }
                }
            }
        }
        return $resultado;
    }

    function minutosAHoras($minutos) {
        if ($minutos >= 60) {
            // Si son 60 minutos o más, mostrar en formato Hora
            return sprintf('%02d:%02d Hora', floor($minutos / 60), $minutos % 60);
        } else {
            // Si son menos de 60 minutos, mostrar en formato Min
            return sprintf('%02d Min', $minutos);
        }
    }

}