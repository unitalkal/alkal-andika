<?php

class Operator_model extends CI_Model{
	public function tampil_data()
	{
		return $this->db->get('operator');
	}

	public function input_data($data)
	{
		$this->db->insert('operator', $data);
	}

	public function hapus_data($data){
		$this->db->delete('operator',$data);
	}

	public function get_keyword($keyword){
		$this->db->select('*');
		$this->db->from('operator');
		$this->db->like('nama', $keyword);
		$this->db->or_like('bidang',$keyword);
		$this->db->or_like('kegiatan',$keyword);
		$this->db->or_like('dokumentasi',$keyword);
		return $this->db->get()->result();
	}

	public function edit_data($where,$table)
	{
		return $this->db->get_where($table,$where);
	}

	public function update_data($where,$data,$table)
	{
		$this->db->where($where);
		$this->db->update($table,$data);
	}
}