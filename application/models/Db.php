<?php
class Db extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    private function query($tabla, $mit="*", $cond=array(), $order=array(), $limit = array(), $join = array(), $joinCond = array()){
        $this->db->select($mit);
        $this->db->from($tabla);
        if(!empty($join) && !empty($joinCond)){ foreach($join as $k=>$v){ $this->db->join($v, $joinCond[$k]); }; };
        if(!empty($cond)){ $this->db->where($cond); };
        if(!empty($order)){ foreach($order as $k=>$v){$this->db->order_by($k, $v); }; };
        if(!empty($limit)){ $this->db->limit($limit[0], $limit[1]); };
        return $this->db->get();
    }
    public function sqla($tabla, $mit="*", $cond=array(), $order=array(), $limit = array(), $join = array(), $joinCond = array()){
        $query = $this->query($tabla,$mit,$cond,$order,$limit,$join,$joinCond);
        $return = array();
        foreach($query->result_array() as $row){
            array_push($return,$row);
        };
        return $return;
    }
    public function sqlo($tabla, $mit="*", $cond=array(), $order=array(), $limit = array(), $join = array(), $joinCond = array()){
        $query = $this->query($tabla,$mit,$cond,$order,$limit,$join,$joinCond);
        $return = array();
        foreach($query->result() as $row){
            array_push($return,$row);
        };
        return $return;
    }
    public function sqln($tabla, $mit="*", $cond=array(), $order=array(), $limit = array(), $join = array(), $joinCond = array()){
        $query = $this->query($tabla,$mit,$cond,$order,$limit,$join,$joinCond);
        $i = 0;
        foreach($query->result() as $row){
            $i++;
        };
        return $i;
    }
    public function insert($tabla, $data){
        $this->db->set($data);
        $this->db->insert($tabla);
        return $this->db->insert_id();
    }
    public function update($tabla, $data, $cond=array()){
        $this->db->set($data);
        $this->db->where($cond);
        $this->db->update($tabla);
        return $this->db->insert_id();
    }
    public function delete($tabla, $cond=array()){
        $this->db->where($cond);
        $this->db->delete($tabla);
        return true;
    }
}