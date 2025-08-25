<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class M_datatable extends CI_Model{

    function getdata($filter,$valid_columns,$where,$where_in,$from,$join,$select = '')
    {

        $start  = intval( $filter["start"]);
        $length = intval( $filter["length"]);
        $order  = $filter["order"];
        $search = $filter['busqueda'];

        $search_value = $this->class_security->codificar($this->class_security->limpiar_form($search));

        $col = 0;
        $dir = "";

        if(!empty($order)){
            foreach($order as $o){
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc"){
            $dir = "desc";
        }

        if(!isset($valid_columns[$col])){
            $order = null;
        }else{
            $order = $valid_columns[$col];
        }

        if($order !=null){
            $this->db->order_by($order, $dir);
        }




        if(isset($search_value) AND strlen($search_value) >= 2) {
            $x=0;
            foreach($valid_columns as $sterm){
                if($x==0){
                    $this->db->like($sterm,$search_value);
                }else {
                    $this->db->or_like($sterm,$search_value);
                }
                $x++;
            }
        }

        if(is_array($where) AND count($where) >= 1){
            $this->db->where($where);
        }


        if(is_array($where_in) AND count($where_in) >= 1){

            if(count($where_in) == 3){

                if($where_in['0'] == 'in'){
                    $this->db->where_in($where_in[1],$where_in[2]);
                }else{
                    $this->db->where_not_in($where_in[1],$where_in[2]);
                }
            }else{


                foreach($where_in As $rr){
                    if($rr['0'] == 'in'){
                        $this->db->where_in($rr[1],$rr[2]);
                    }else{
                        $this->db->where_not_in($rr[1],$rr[2]);
                    }
                }
            }

        }

        if(!empty($select)){
         $this->db->select($select);
        }

        $this->db->limit($length,$start);
        $this->db->from( $from);
        if(is_array($join) and count($join) >= 1){
            foreach($join As $joindata){
                $type = isset($joindata['type']) ? $joindata['type'] : '';
                $this->db->join($joindata['tabla'],$joindata['join'],$type);
            }
        }

        $result = $this->db->get()->result();
        return $result;

    }

    function totalData($busqueda,$valid_columns,$where,$where_in,$from,$join)
    {
//        $busqueda = $search['busqueda'];

        if(is_array($where) AND count($where) >= 1){
            $this->db->where($where);
        }

        if(is_array($where_in) AND count($where_in) >= 1){

            if(count($where_in) == 3){

                if($where_in['0'] == 'in'){
                    $this->db->where_in($where_in[1],$where_in[2]);
                }else{
                    $this->db->where_not_in($where_in[1],$where_in[2]);
                }
            }else{


                foreach($where_in As $rr){
                    if($rr['0'] == 'in'){
                        $this->db->where_in($rr[1],$rr[2]);
                    }else{
                        $this->db->where_not_in($rr[1],$rr[2]);
                    }
                }
            }

        }

        if(isset($busqueda) AND strlen($busqueda) >= 2){
            $x=0;

            foreach($valid_columns as $sterm){

                if($x==0){
                    $this->db->like($sterm,$busqueda);
                }else{
                    $this->db->or_like($sterm,$busqueda);
                }
                $x++;
            }
        }

        $this->db->from( $from);
        if(is_array($join) and count($join) >= 1){
            foreach($join As $joindata){
                $this->db->join($joindata['tabla'],$joindata['join']);
            }
        }

        return  $this->db->count_all_results();
    }
}