<?php
defined('BASEPATH') or exit('No direct script access allowed');


class General extends CI_Model{

    function create($tabla,$data = array()){
        $this->db->insert($tabla,$data);
        $id     = $this->db->insert_id();
        return array('success' => 1,'msg' => 'Se Creo el dato','id' => $id,'crypt' => encriptar($id));
    }

//    function create_update2($tabla,$where = array(),$data = array()){
//        if(!$this->exist($tabla,$where) or (count($where) == 0)){
//            //create
//            $this->db->insert($tabla,$data);
//            $id     = $this->db->insert_id();
//            $result = array('success' => 1,'msg' => 'Se Creo el dato','data' => $id,'id' => encriptar($id),'type' => 'create');
//        }else{
//            if(count($where) >= 1){
//                $this->db->where($where)->update($tabla,$data);
//                $arvalues = array_values($where);
//                $id = $arvalues[0];
//                $result = array('success' => 1,'msg' => 'Se Actualizo El dato','data' => $id,'id' => encriptar($id),'type' => 'update');
//            }else{
//                $result = array('success' => 2,'msg' => 'Validar Datos','data' => '','type' => '');
//            }
//        }
//        return $result;
//    }



    private function get_id_column($tabla) {
        $fields = $this->db->list_fields($tabla);

        foreach ($fields as $field) {
            // Busca campos que contengan 'id' y tengan longitud corta (para evitar claves foráneas como 'role_id')
            if (preg_match('/(^id$|^.*_id$|^id_.*$)/', $field)) {
                return $field; // Devuelve el primero que haga match
            }
        }

        return null; // Si no encuentra ninguno
    }




    function create_update($tabla, $where = array(), $data = array()) {
        $id_column = $this->get_id_column($tabla);

        if (!$this->exist($tabla, $where) || count($where) == 0) {
            // Crear nuevo registro
            $this->db->insert($tabla, $data);
            $id = $this->db->insert_id();
            $result = array(
                'success' => 1,
                'msg'     => 'Se creó el dato',
                'data'    => $id,
                'id'      => encriptar($id),
                'type'    => 'create'
            );
        } else {
            // Actualizar registro existente
            $this->db->where($where)->update($tabla, $data);

            // Obtener el ID real del registro actualizado
            $query = $this->db->get_where($tabla, $where);
            $row = $query->row();
            $id = ($row && $id_column && isset($row->$id_column)) ? $row->$id_column : null;

            $result = array(
                'success' => 1,
                'msg'     => 'Se actualizó el dato',
                'data'    => $id,
                'id'      => encriptar($id),
                'type'    => 'update'
            );
        }

        return $result;
    }



    function create_update_equals($tabla,$where = array()){
        if(!$this->exist($tabla,$where) or (count($where) == 0)){
            //create
            $this->db->insert($tabla,$where);
            $id     = $this->db->insert_id();
            $result = array('success' => 1,'msg' => 'Se Creo el dato','data' => $id,'id' => encriptar($id),'type' => 'create');
        }else{
            if(count($where) >= 1){
                $this->db->where($where)->update($tabla,$where);
                $arvalues = array_values($where);
                $id = $arvalues[0];
                $result = array('success' => 1,'msg' => 'Se Actualizo El dato','data' => $id,'id' => encriptar($id),'type' => 'update');
            }else{
                $result = array('success' => 2,'msg' => 'Validar Datos','data' => '','type' => '');
            }
        }
        return $result;
    }

    function update($table = '',$where_id = [],$data = [],$where_in = []){
        $this->db->where($where_id);
        if(count($where_in) >= 1){
            $this->db->where_in($where_in[0],$where_in[1]);
        }
        $this->db->update($table,$data);
        return  array('success' => 1);
    }

    function delete($tabla,$where){
        if($this->exist($tabla,$where)){
            $this->db->delete($tabla,$where);
        }
        return  array('success' => 1);
    }

    function nique_value($table,$valor,$wheredt){
        if(strlen($valor) >= 1){
            $cantidad = $this->db->where($wheredt)->from($table)->count_all_results();
            if($cantidad >= 1){
                $data =  $this->db->where($wheredt)->from($table)->get()->row_array();
                $result = $data[$valor];
            }else{
                $result = '';
            }
        }else{
            $result = '';
        }
        return $result;
    }

    function get_filter($table,$wheres,$filter,$limit = 40){

        $this->db->from($table);
        $this->db->limit($limit);
        if(count($wheres) >= 1){
            $this->db->where($wheres);
        }

        if(count($filter) >= 1){
            foreach($filter As $filt){
                $this->db->like($filt[0],$filt[1]);
            }
        }
        return $this->db->get()->result_array();
    }

    function get_filter_join($tabla,$tabla_join = array(),$whered = array(),$filter = array(),$select = '',$distinct = ''){

        if(strlen($select) >= 2){
            $this->db->select($select);
        }

        if(strlen($distinct) >= 2){
            $this->db->distinct($distinct);
        }

        if(count($whered) >= 1){
            $this->db->where($whered);
        }


        if(count($filter) >= 1){
            foreach($filter As $filt){
                $this->db->like($filt[0],$filt[1]);
            }
        }


        $this->db->from($tabla);

        if(count($tabla_join) >= 1){
            foreach($tabla_join As $tjoin){
                $this->db->join($tjoin[0],$tjoin[1]);
            }
        }
        return $this->db->get()->result_array();
    }

    function get($table,$wheredt,$type = 'json',$select = '',$order = array()){
        if($this->exist($table,$wheredt)){
            $this->db->where($wheredt)->limit(1);

            if(count($order) >= 1){
                $this->db->order_by($order[0],$order[1]);
            }

            if(strlen($select) >= 5){
                $this->db->select($select);
            }

            $this->db->limit(1);
            $data =  $this->db->from($table)->get();
            if($type == 'json'){
                $result = $data->row();
            }else{
                $result = $data->row_array();
            }
        }else{
            $result = array();
        }
        return $result;
    }

    function sum_data($table = '',$wheredt = [],$columna = '') {

        if(strlen($columna) >= 2){
            $this->db->select("SUM({$columna}) As suma");
        }

        if(is_array($wheredt) AND (count($wheredt) >= 1)){
            $this->db->where($wheredt);
        }

        $this->db->from($table);

        $result = $this->db->get()->row();

        return $result;
    }


    function all_get($table,$wheredt = array(),$order = array(),$type = 'json',$where_in = array(),$or_where = array(), $select = '',$where_not = []) {

        if(strlen($select) >= 2){
            $this->db->select($select);
        }

        if(is_array($wheredt) AND (count($wheredt) >= 1)){
            $this->db->where($wheredt);
        }

        if(count($where_in) >= 1){
            $this->db->where_in($where_in[0],$where_in[1]);
        }

        if(count($where_not) >= 1){
            $this->db->where_not_in($where_not[0],$where_not[1]);
        }

        if(count($or_where) >= 1){
            $this->db->or_where( $or_where['0'],$or_where['1']);
        }

        if((isset($order)) AND (count($order) == 2)){
            $this->db->order_by($order[0],$order[1]);
        }

        $this->db->from($table);

        if($type == 'json'){
            $result = $this->db->get()->result();
        }else{
            $result = $this->db->get()->result_array();
        }

        return $result;
    }

    function all_get_join($tabla,$tabla_join = array(),$whered = array(),$where_in = array(),$data_get = 'all',$type = 'json',$select = '',$distinct = '',$likes_in = array()){

        if(strlen($select) >= 2){
            $this->db->select($select);
        }

        if(strlen($distinct) >= 2){
            $this->db->distinct($distinct);
        }

        if(count($whered) >= 1){
            $this->db->where($whered);
        }
        $this->db->from($tabla);

        if(count($tabla_join) >= 1){
            foreach($tabla_join As $tjoin){
                $this->db->join($tjoin[0],$tjoin[1],$tjoin[2] ?? '');
            }
        }

//        if(count($where_in) >= 1){
//            foreach($where_in As $twherein){
//                $this->db->where_in($twherein[0],$twherein[1]);
//            }
//        }
//
//        if(count($likes_in) >= 1){
//            foreach($likes_in As $likes){
//                $this->db->like($likes[0],$likes[1]);
//            }
//        }


        if($data_get == 'alone'){
            if($type == 'json'){
                return  $this->db->get()->row();
            }else{
                return $this->db->get()->row_array();
            }
        }else{
            if($type == 'json'){
                return  $this->db->get()->result();
            }else{
                return $this->db->get()->result_array();
            }
        }
    }

    function exist($tabla = '',$where = array(),$where_in = array(),$or_where = array()){
        if(count($where) >= 1){
            $this->db->where( $where);
        }
        if(count($where_in) >= 1){
            $this->db->where_in( $where_in['0'],$where_in['1']);
        }
        if(count($or_where) >= 1){
            $this->db->or_where( $or_where['0'],$or_where['1']);
        }
        $this->db->from($tabla);
        $cantidad = $this->db->count_all_results();
        if ($cantidad >= 1){
            $result = true;
        }
        else{
            $result = false;
        }
        return $result;
    }

    function count($tabla = '',$where = array(),$where_in = array(),$or_where = array()){
        if(count($where) >= 1){
            $this->db->where( $where);
        }
        if(count($where_in) >= 1){
            $this->db->where_in( $where_in['0'],$where_in['1']);
        }
        if(count($or_where) >= 1){
            $this->db->or_where( $or_where['0'],$or_where['1']);
        }
        $this->db->from($tabla);
        $cantidad = $this->db->count_all_results();
        return $cantidad;
    }

    function search_data($tabla,$wheres = array(),$likes = array(),$orlikes = array(),$limit = 'all'){
        //where
        if(count($likes) >= 1){
            $this->db->like($likes[0],$likes[1]);
        }

        //like
        if(count($wheres) >= 1){
            $this->db->where($wheres);
        }

        //or like
        if(count($orlikes) >= 1){
            foreach($orlikes As $orlike):
                $this->db->or_like($orlike['0'],$orlike['1']);
            endforeach;
        }

        if($limit != 'all'){
            $this->db->limit($limit);
        }

        $this->db->from($tabla);
        $result = $this->db->get()->result();
        return $result;
    }

    function search_data_select($tabla,$select = '',$wheres = array(),$likes = array(),$orlikes = array()){
        //where

        if(strlen($select) >= 4){
            $this->db->select($select);
        }

        if(count($likes) >= 1){
            $this->db->like($likes[0],$likes[1]);
        }

        //like
        if(count($wheres) >= 1){
            $this->db->where($wheres);
        }

        //or like
        if(count($orlikes) >= 1){
            foreach($orlikes As $orlike):
                $this->db->or_like($orlike['0'],$orlike['1']);
            endforeach;
        }

//        $valor_elemento = array_values($likes);
//        if(strlen($valor_elemento['1']) <= 2){
//            $this->db->limit(40);
//        }


        $this->db->from($tabla);
        $result = $this->db->get()->result();
        return $result;
    }

    function query($consult,$type = 'array',$multi = false,$result = true){
        $query_data =  $this->db->query($consult);
        if($result == true){

            if(!$multi){
                return ($type == 'array') ? $query_data->result_array() : $query_data->result_object();
            }else{
                return ($type == 'array') ? $query_data->row_array() : $query_data->row();
            }
        }else{
            $cantidad = $query_data->num_rows();
            if ($cantidad >= 1){
                return true;
            }
            else{
                return false;
            }
        }
    }

    function queryDinamycal($principal,$join = [],$where = [],$select = '',$type = 'array',$multi = true,$like = [],$or_like = [],$limit = 'all',$order = [],$group = []){
        if(count($join) >= 1){
            foreach($join As $tjoin){
                $this->db->join($tjoin[0],$tjoin[1], (isset($tjoin[2])) ? $tjoin[2] : '');
            }
        }
        if(count($where) >= 1){
            $this->db->where($where);
        }
        if(count($like) >= 1){
            $this->db->like($like[0],$like[1]);
        }
        if(count($or_like) >= 1){
            foreach($or_like As $orlike):
                $this->db->or_like($orlike['0'],$orlike['1']);
            endforeach;
        }
        if(count($order) >= 1){
            $this->db->order_by($order[0],$order[1]);
        }
        if(count($group) >= 1){
            $this->db->group_by($group);
        }
        if($limit != 'all'){
            $this->db->limit($limit);
        }
        if(strlen($select) >= 4){
            $this->db->select($select);
        }
        $this->db->from($principal);
        if(!$multi){
            return ($type == 'array') ? $this->db->get()->result_array() : $this->db->get()->result_object();
        }else{
            return ($type == 'array') ? $this->db->get()->row_array() : $this->db->get()->row();
        }
    }


}