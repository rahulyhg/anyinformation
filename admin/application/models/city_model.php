<?php
if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );
class City_model extends CI_Model
{
	
	public function create($name)
	{
		$data  = array(
			'name' => $name
		);
		$query=$this->db->insert( 'city', $data );
//		$id = $this->db->insert_id();
//		if($query)
//		{
//			$this->savelog($id,'Event Created');
//		}
		if(!$query)
			return  0;
		else
			return  1;
	}
    public function createlocation($name,$cityid,$pincode)
	{
		$data  = array(
			'name' => $name,
			'pincode' => $pincode,
            'cityid'=> $cityid
		);
		$query=$this->db->insert( 'location', $data );
//		$id = $this->db->insert_id();
//		if($query)
//		{
//			$this->savelog($id,'Event Created');
//		}
		if(!$query)
			return  0;
		else
			return  1;
	}

        
    function getalllocationcityvise($id)
    {
        $query=$this->db->query("SELECT * FROM `location` WHERE `cityid`='$id'")->result();
        return $query;
    }
    

	function viewcity()
	{
		$query="SELECT `city`.`id`, `city`.`name` FROM `city`";
		$query=$this->db->query($query)->result();
		return $query;
	}
	function viewonecitylocations($id)
	{
		$query="SELECT `location`.`id`,`location`.`name`,`location`.`pincode`,`location`.`cityid`, `city`.`name` AS `cityname` FROM `location`
        INNER JOIN `city` ON `city`.`id` = `location`.`cityid` WHERE `location`.`cityid`='$id'";
		$query=$this->db->query($query)->result();
		return $query;
	}
	public function beforeedit( $id )
	{
		$this->db->where( 'id', $id );
		$query['city']=$this->db->get( 'city' )->row();
//		$query['eventcategory']=array();
//		$eventcategory=$this->db->query("SELECT `category` FROM `eventcategory` WHERE `eventcategory`.`event`='$id'")->result();
//		foreach($eventcategory as $cat)
//		{
//			$query['eventcategory'][]=$cat->category;
//		}
//		$query['eventtopic']=array();
//		$eventtopic=$this->db->query("SELECT `topic` FROM `eventtopic` WHERE `eventtopic`.`event`='$id'")->result();
//		foreach($eventtopic as $top)
//		{
//			$query['eventtopic'][]=$top->topic;
//		}
		return $query;
	}
	public function beforeeditlocation( $id )
	{
		$this->db->where( 'id', $id );
		$query['location']=$this->db->get( 'location' )->row();
//		$query['eventcategory']=array();
//		$eventcategory=$this->db->query("SELECT `category` FROM `eventcategory` WHERE `eventcategory`.`event`='$id'")->result();
//		foreach($eventcategory as $cat)
//		{
//			$query['eventcategory'][]=$cat->category;
//		}
//		$query['eventtopic']=array();
//		$eventtopic=$this->db->query("SELECT `topic` FROM `eventtopic` WHERE `eventtopic`.`event`='$id'")->result();
//		foreach($eventtopic as $top)
//		{
//			$query['eventtopic'][]=$top->topic;
//		}
		return $query;
	}
	
	public function edit($id,$name)
	{
		$data  = array(
			'name' => $name
		);
		
		$this->db->where( 'id', $id );
		$query=$this->db->update( 'city', $data );
		if($query)
		{
			$this->savelog($id,'City Edited');
		}
		return 1;
	}
	public function editlocation($id,$cityid,$name,$pincode)
	{
		$data  = array(
			'cityid' => $cityid,
			'pincode' => $pincode,
			'name' => $name
		);
		
		$this->db->where( 'id', $id );
		$query=$this->db->update( 'location', $data );
		if($query)
		{
			$this->savelog($id,'Location Edited');
		}
		return 1;
	}
	function deletecity($id)
	{
		$query=$this->db->query("DELETE FROM `city` WHERE `id`='$id'");
	}
	function deletelocation($id)
	{
		$query=$this->db->query("DELETE FROM `location` WHERE `id`='$id'");
	}
	
	public function getevent()
	{
		$return=array();
		$query=$this->db->query("SELECT * FROM `event` ORDER BY `id` ASC")->result();
		foreach($query as $row)
		{
			$return[$row->id]=$row->title;
		}
	
		return $return;
	}
	public function getlistingtype()
	{
		$return=array();
		$return = array(
			"1" => "Private",
			"2" => "Public"
		);
		return $return;
	}
	public function getremainingticket()
	{
		$return=array();
		$return = array(
			"1" => "Yes",
			"0" => "No"
		);
		return $return;
	}
	function editeventcategorytopic($id,$category,$topic)
	{
		$this->db->query("DELETE FROM `eventcategory` WHERE `event`='$id'");
		$this->db->query("DELETE FROM `eventtopic` WHERE `event`='$id'");
		if(!empty($category))
		{
			foreach($category as $key => $cat)
			{
				$data  = array(
					'event' => $id,
					'category' => $cat,
				);
				$query=$this->db->insert( 'eventcategory', $data );
			}
		}
		if(!empty($topic))
		{
			foreach($topic as $key => $top)
			{
				$data2  = array(
					'event' => $id,
					'topic' => $top,
				);
				$query=$this->db->insert( 'eventtopic', $data2 );
			}
		}
		{
			$this->savelog($id,"Event Category ,Topic updated");
		}
		return 1;
	}
	function savelog($id,$action)
	{
		$fromuser = $this->session->userdata('id');
		$data2  = array(
			'user' => $id,
			'event' => $id,
			'description' => $action,
		);
		$query2=$this->db->insert( 'eventlog', $data2 );
	}
    public function getcitydropdown()
	{
		$query=$this->db->query("SELECT * FROM `city`  ORDER BY `id` ASC")->result();
		$return=array(
		"" => ""
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->name;
		}
		
		return $return;
	}
    public function getareadropdown()
	{
		$query=$this->db->query("SELECT * FROM `location`  ORDER BY `id` ASC")->result();
		$return=array(
		"" => ""
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->name;
		}
		
		return $return;
	}
    
    
        function getareabycity($cityid)
        {
        	$query=$this->db->query("SELECT * FROM `location` WHERE `cityid`='$cityid'")->result();
		return $query;
        }
        
    function uploadareacsv()
	{
		$access = array("5","1");
		$this->checkaccess($access);
		$data[ 'page' ] = 'uploadareacsv';
		$data[ 'title' ] = 'Upload area';
		$this->load->view( 'template', $data );
	} 
    
    
	public function createbycsv($file)
	{
        foreach ($file as $row)
        {
            $city=$row['city'];
            $cityquery=$this->db->query("SELECT * FROM `city` WHERE `name`LIKE '$city'")->row();
            if(empty($cityquery))
            {
                $this->db->query("INSERT INTO `city`(`name`) VALUES ('$city')");
                $cityid=$this->db->insert_id();
            }
            else
            {
                $cityid=$cityquery->id;
            }
            
            $area=$row['area'];
            $pincode=$row['pincode'];
            $area=trim($area);
            $pincode=trim($pincode);
            $areaquery=$this->db->query("SELECT * FROM `location` WHERE `name` LIKE '$area' AND `cityid`='$cityid'")->row();
            if(empty($areaquery))
            {
                $this->db->query("INSERT INTO `location`(`name`,`cityid`,`pincode`) VALUES ('$area','$cityid','$pincode')");
                $areaid=$this->db->insert_id();
            }
            else
            {
                $areaid=$areaquery->id;
            }
            
        }
			return  1;
	}
    
    //-----------------Changes made avinash
    
    
    //------------------------
}
?>