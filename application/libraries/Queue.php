<?php


class Queue
{

    protected $CI;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->database();  // Cargar la base de datos
    }

    // Encolar un trabajo
    public function enqueue($queue_name, $job_type, $data) {
        $job_data = array(
            'queue_name' => $queue_name,
            'job_type' => $job_type,
            'payload' => json_encode($data),
            'status' => 'pending',
            'attempts' => 0
        );

        $this->CI->db->insert('job_queues', $job_data);  // Insertar el trabajo en la base de datos
        return $this->CI->db->insert_id();  // Retornar el ID del trabajo
    }

    // Obtener el siguiente trabajo pendiente
    public function dequeue($queue_name) {
        // Obtener el trabajo pendiente
        $this->CI->db->where('queue_name', $queue_name);
        $this->CI->db->where('status', 'pending');
        $this->CI->db->order_by('created_at', 'ASC');  // Obtener el trabajo más antiguo
        $query = $this->CI->db->get('job_queues');

//        if ($query->num_rows() > 0) {
        return $query->result_array();

            // Marcar el trabajo como 'processing' (en proceso)
//            $this->CI->db->update('job_queues', ['status' => 'processing'], ['id' => $job->id]);
//        }

        return [];  // Si no hay trabajos pendientes
    }

    // Marcar el trabajo como completado
    public function complete($job_id) {
        $this->CI->db->update('job_queues', ['status' => 'completed'], ['id' => $job_id]);
    }

    // Marcar el trabajo como fallido
    public function fail($job_id) {
        $this->CI->db->update('job_queues', ['status' => 'failed'], ['id' => $job_id]);
    }

    // Incrementar el número de intentos de un trabajo
    public function increment_attempts($job_id) {
        $this->CI->db->set('attempts', 'attempts+1', FALSE);
        $this->CI->db->where('id', $job_id);
        $this->CI->db->update('job_queues');
    }

    // Obtener el tamaño de la cola
    public function size($queue_name) {
        $this->CI->db->where('queue_name', $queue_name);
        $this->CI->db->where('status', 'pending');
        return $this->CI->db->count_all_results('job_queues');
    }
}