<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['ready_revition']    = 'assignment/ready_revition';
$route['cleaning_process']  = 'assignment/cleaning_process';
$route['clean']             = 'maintenance/clean_log';
$route['task']              = 'maintenance/task_log';
$route['job_rowk']          = 'maintenance/job_rowk';
$route['my_tickets']        = 'maintenance/my_tickets';
$route['tickets']           = 'maintenance/ticket_local';
$route['task_local']        = 'maintenance/task_local';
$route['bother_status']     = 'assignment/bother_status';
$route['chart']             = 'modular/chart';

//booking
$route['booking']   = 'booking';
$route['proformas']  = 'booking/proformas';
$route['events']    = 'booking/events';
$route['calendar']    = 'booking/calendar';
$route['reporting_events']    = 'booking/reporting_events';
$route['reporting_booking']   = 'booking/reporting_booking';

//Meeting
$route['meeting_daily']   = 'meeting';
$route['meeting_admon']   = 'meeting/admon';

//walk-in
$route['walkin_sales']        = 'walkin';
$route['walkin_report']       = 'walkin/report';
$route['walkin_percentage']   = 'walkin/percentage';
$route['walkin_admin']        = 'walkin/admon';

//apis
$route['api_assignment'] = 'assignment/api_assigmment';


//tickets
$route['ticket_create']   = 'ticket/ticket_create';
$route['ticket_admon']    = 'ticket/ticket_admon';
$route['ticket_resolved'] = 'ticket/ticket_resolved';

/*Route Asigne*/
$route['auth']       = 'login/auth';
$route['signup']     = 'login/signup';
$route['recovery']   = 'login/recovery';
$route['logout']     = 'login/logout';


#Biometric
$route['biometric']           = 'biometric';
$route['biometric_calcule']   = 'biometric/calcule';

//window open
$route['box_window']           = 'windowLogge/box_window';
$route['box_event_save']           = 'windowLogge/box_event_save';
