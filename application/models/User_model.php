<?php

class user_model extends CI_Model{

	public function ambil_data($id)
	{
		$this->db->where('username', $id);
		return $this->db->get('user')->row();
	}

    public function getUserOperator(){
        $this->db->select("id,username");
        $this->db->from("user");
        $this->db->where("job_id = 1 or job_id = 2");
        $this->db->order_by("username");
        return $this->db->get();
    }

    public function getOperatorOnly(){
        $this->db->select("id,username");
        $this->db->from("user");
        $this->db->where("job_id = 1");
        $this->db->order_by("username");
        return $this->db->get();
    }

    public function getDriver(){
        $this->db->select("id,username");
        $this->db->from("user");
        $this->db->where("job_id = 2");
        $this->db->order_by("username");
        return $this->db->get();
    }
    
    public function getMekanik(){
        $this->db->select("id,username");
        $this->db->from("user");
        $this->db->where("job_id = 3");
        $this->db->order_by("username");
        return $this->db->get();
    }

    public function getLabour(){
        $this->db->select("id,username");
        $this->db->from("user");
        $this->db->where("job_id = 4");
        $this->db->order_by("username");
        return $this->db->get();
    }

	public function getId($username)
	{
        $this->db->select("id");
		$this->db->where('username', $username);
        $this->db->from('user');
        $this->db->limit(1);
		return $this->db->get();
	}

    public function getDriverPlusMechanic(){
        $this->db->select("id,username");
        $this->db->from("user");
        $this->db->where("job_id = 2 or job_id = 3");
        $this->db->order_by("username");
        return $this->db->get();
    }

    public function getUserForKinerja(){
        $this->db->select("a.id as id,a.username as username,b.job_roleid as job_roleid,c.role_name as role_name");
        $this->db->from('user a');
        $this->db->join('alkal_user_job_role_lookup b','b.uid = a.id','inner');
        $this->db->join('alkal_user_job_role c','c.id = b.job_roleid','inner');
        $this->db->order_by('b.job_roleid');
        $this->db->order_by('a.username');
        return $this->db->get();
    }
}
