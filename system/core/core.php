<?php

/**
 * 
 */
class core
{
	private $connect;

	function __construct($config)
	{
		$this->dbhost=$config['connect'][0];
		$this->dbuser=$config['connect'][1];
		$this->dbpass=$config['connect'][2];
		$this->db=$config['connect'][3];

		$this->connect=$this->connection($this->dbhost,$this->dbuser,$this->dbpass,$this->db);
	}
	/*
     * ------------------------------------------------------
     * connection
     * ------------------------------------------------------
     */
	public function connection($dbhost,$dbuser,$dbpass,$db){
    
        $connect=mysqli_connect($dbhost,$dbuser,$dbpass,$db);
        if(!$connect){
          die("Connection Failed" . mysqli_error($connect));
        }else{
            return $connect;
        }

    }
    public function check_connection(){
    
        if($this->dbhost=='' && $this->dbuser=='' && $this->dbpass=='' && $this->db=='') {
          return false;
        }else{
           
            return true;
        }


    }
    /*
     * ------------------------------------------------------
     *  Starting Queries function
     * ------------------------------------------------------
     */
    public function create_table()
    {
        
    }
    
    /*
     * ------------------------------------------------------
     *  Insert function
     * ------------------------------------------------------
     */
    public function insert($table,$data){
       
        $query="";
        $keys="(";
        $values="(";
        $i=0;
        $j=0;
        $final_query="";
        $count=count($data);
    
        
        $query.="INSERT INTO " . $table . " ";
       
        foreach($data as $key => $value){
            $i++;
            $j++;
            
          
            $keys.= "`" . $key . "`" ;
            if($i<$count){
           
                $keys.=",";
            }
            $values.= "'" . $value . "'" ;
            if($i<$count){
        
                $values.=",";
            }
            
            
        }
         $values.=")";
         $keys.=")";
        
         $final_query.=$query . $keys . " VALUES " . $values;
    
       
         $result=mysqli_query($this->connect,$final_query);
         if(!$result){
             die("Query Failed") . mysqli_error($connect);
         }
    }
    /*
     * ------------------------------------------------------
     *  Update function
     * ------------------------------------------------------
     */
    public function update($table,$data,$where=null){
          
            $query="";
           
            $values="";
            $i=0;
            $j=0;
            
            $final_query="";
            $count=count($data);
        
            $query.="UPDATE `" . $table . "` SET ";
           
            foreach($data as $key => $value){
                $i++;
              
                
             $values.= " " . $key . "='" . $value . "' ";
                 if($i<$count){
                    $values.=",";
                }
                
            }
            
        $count1=count($where);
            $where_values=" WHERE";
              foreach($where as $key_where => $value_where){
                  $j++;
                $where_values.=" " . $key_where . "='" . $value_where . "' ";
                if($j<$count1){
                    $where_values.="AND";
                }
            }
       
            
             $final_query.=$query . $values . $where_values; 
        
             //var_dump($final_query);
             $result=mysqli_query($this->connect,$final_query);
             if(!$result){
                 die("Query Failed") . mysqli_error($connect);
             }
        }
    /*
     * ------------------------------------------------------
     *  Fetch function
     * ------------------------------------------------------
     */
    public function fetch($table,$where=null){
       
        $query="SELECT * FROM `". $table ."`";
        if($where){
            $count=count($where);
              $i=0;
            
              $where_values=" WHERE";
              foreach($where as $key => $value){
                  $i++;
                $where_values.=" " . $key . "='" . mysqli_real_escape_string($this->connect,$value) . "' ";
                if($i<$count){
                    $where_values.="AND";
                }
            }
            $query.=$where_values;
        }
        
        $result=mysqli_query($this->connect,$query);
         if(!$result){
             die("Query Failed") . mysqli_error($connect);
         }else{
             return $this->result_array($result);
           
         }
    }
    /*
     * ------------------------------------------------------
     *  Delete function
     * ------------------------------------------------------
     */
    public function delete($table,$data)
    {
       
            $query="";
           
            $values="";
            $i=0;
            
            $final_query="";
            $count=count($data);
        
            $query.="DELETE FROM `" . $table . "` WHERE ";
           
            foreach($data as $key => $value){
                $i++;
              
                
             $values.= " " . $key . "=" . $value . " ";
                 if($i<$count){
                    $values.=",";
                }
                
            }
            $final_query.=$query . $values;

             //var_dump($final_query);
             $result=mysqli_query($this->connect,$final_query);
             if(!$result){
                 die("Query Failed") . mysqli_error($connect);
             }else{
                return 'success';
             }
    }
    /*
     * ------------------------------------------------------
     *  Result Array function
     * ------------------------------------------------------
     */
    public function result_array($result){
        $array=array();
        while($row=mysqli_fetch_assoc($result)){
            //var_dump($row);
            $array[]=$row;
        }
        return $array;
    }
    /*
     * ------------------------------------------------------
     *  Row Array function
     * ------------------------------------------------------
     */
    public function row_array($result){
        $array=array();
        while($row=mysqli_fetch_assoc($result)){
            //var_dump($row);
            $array=$row;
        }
        return $array;
    }

    /* 
    * It creats a new entity type
    * use this in your constroller  $sphinx->insert_entity($data);
    * $sphinx->register_entity_type('entity_type_name');
    */
    function register_entity_type($entity_type_name,$entity_type,$entity_metas,$entity_parent)
    {
        if (empty($entity_parent) || $entity_parent==NULL || $entity_parent=="") {
            $data= array(
                'entity_type_name' => $entity_type_name ,
                'entity_type' => $entity_type,
                'entity_metas' => serialize($entity_metas)
            );
        }else{
            $data= array(
                'entity_type_name' => $entity_type_name ,
                'entity_type' => $entity_type,
                'entity_metas' => serialize($entity_metas),
                'entity_parent' => $entity_parent,
            );
        }
        
        $sql = "SELECT * FROM entity_type WHERE entity_type_name='$entity_type_name'";
         $result=mysqli_query($this->connect,$sql);

        if(count($this->result_array($result)) > 0){
            return;
        }else{
            $create = $this->insert('entity_type', $data);
            return ($create == true) ? true : false;
        }

    }
    /* 
    * It creats a new entity
    * Data structure follow this formate and entity_parent can be null if null remove it from the array
    * date formate can also be changed
    * $data = array(
            'entity_title'    => 'Testing', 
            'entity_content'  => 'text',
            'entity_date'     =>  date('Y,M,D'),
            'entity_author'   =>  'Test',
            'entity_type'     =>  'entity',
            'entity_parent'   =>  ''
        );
    * * use this   $sphinx->insert_entity($data);
    */
    function insert_entity($data)
    {
        $create = $this->insert('entity', $data);
        return ($create == true) ? true : false;    
    }
    /* 
    * It will delete the entity by entity id
    * use this in your constroller $sphinx->del_entity('4');
    */
    function del_entity($entity_id)
    {
        $delete_meta = $this->delete('entity_meta', array('entity_id' => $entity_id ));
        $delete = $this->delete('entity', array('entity_id' => $entity_id ));
        
        return ($delete == true && $delete_meta==true) ? true : false;  
    }
    function last_entity_id(){
        $sql="SELECT entity_id FROM entity ORDER BY entity_id DESC LIMIT 1 ";
        $result=mysqli_query($this->connect,$sql);
      
            return $this->row_array($result)['entity_id'];
        
    }
    /* 
    * It updates a new entity
    * Data structure follow this formate and entity_parent can be null if null remove it from the array
    * date formate can also be changed
    * $data = array(
            'entity_title'    => 'Testing', 
            'entity_content'  => 'text',
            'entity_date'     =>  date('Y,M,D'),
            'entity_author'   =>  'Test',
            'entity_type'     =>  'entity',
            'entity_parent'   =>  ''
        );
    * use this in your controller $sphinx->update_entity($data,'5');
    */
    function update_entity($data,$entity_id)
    {
        $update = $this->update('entity', $data, array('entity_id' => $entity_id ));
        return ($update == true) ? true : false;    
    }
    /* 
    * It fetch entity for you by id or simple
    * use this in your constroller $sphinx->get_entity()
    */
    function get_entity($entity_id=null,$entity_type=null){
        if($entity_id){
            if($entity_type){
            
            $sql = "SELECT * FROM entity WHERE entity_type='$entity_type' AND entity_id='$entity_id'";
            $result=mysqli_query($this->connect,$sql);
            }else{
                $sql = "SELECT * FROM entity WHERE entity_id='$entity_id'";
                $result=mysqli_query($this->connect,$sql);
            }
            return $this->row_array($result);
        }else{
            if($entity_type){
            
            $sql = "SELECT * FROM entity WHERE entity_type='$entity_type'";
            $result=mysqli_query($this->connect,$sql);
            }else{
                $sql = "SELECT * FROM entity";
                $result=mysqli_query($this->connect,$sql);
            }
            return $this->result_array($result);
        }
    
        
    }
        /* 
    * It fetch entity type for you by id or simple
    * use this in your constroller $sphinx->get_entity_type()
    */
    function get_entity_type($entity_id=null,$entity_type=null){
        if($entity_id){
            if($entity_type){
            
            $sql = "SELECT * FROM entity_type WHERE entity_type='$entity_type' AND entity_type_id='$entity_id'";
            $result=mysqli_query($this->connect,$sql);
            }else{
                $sql = "SELECT * FROM entity_type WHERE entity_type_id='$entity_id'";
                $result=mysqli_query($this->connect,$sql);
            }
            return $this->row_array($result);
        }else{
            if($entity_type){
            
            $sql = "SELECT * FROM entity_type WHERE entity_type='$entity_type'";
            $result=mysqli_query($this->connect,$sql);
            }else{
                $sql = "SELECT * FROM entity_type";
                $result=mysqli_query($this->connect,$sql);
            }
            return $this->result_array($result);
        }
    
        
    }
    /* 
    * It get entity with all of its meta data
    * use this in your constroller $sphinx->get_entity_with_meta($entity_type=null,$meta_array,$entity_id=null)
    * Data Set Example
    *       $meta_array = array(
            'meta_starting_date',
            'meta_customer',
            'meta_end_date',
            'meta_address',
            'meta_cnic',
            'meta_cnic_img_f',
            'meta_cnic_img_b',
            'meta_booking_extras_name',
            'meta_booking_extras_qty',
            'meta_booking_extras_price',
            'meta_booking_price',
            'meta_status',
            'meta_room_id'
        );
    */
    function get_entity_with_meta($entity_id=null,$meta_array,$entity_type=null){
        if ($entity_id) {
            $entity=$this->get_entity($entity_id,$entity_type); 
            foreach ($meta_array as $meta_array_key => $meta_array_value) {
                $entity[$meta_array_value]=$this->get_entity_meta($entity_id,$meta_array_value);
            }
        }else{
            $entity=$this->get_entity(null,$entity_type);    
            foreach ($meta_array as $meta_array_key => $meta_array_value) {
              
                     foreach ($entity as $entity_key => $entity_value) {
                        $entity_id= $entity_value['entity_id'];
                        $entity[$entity_key][$meta_array_value]=$this->get_entity_meta($entity_id,$meta_array_value);
                    }
                
                
               
            }
            
        }
        return $entity;
        
    }
    /* 
    * It create entity with all of its meta data
    * use this in your constroller $sphinx->insert_entity_with_meta($data,$meta_array)
    * Data set Example
    * $meta_array = array(
            'meta_starting_date'        => $this->input->entity('meta_starting_date'), 
            'meta_end_date'             => $this->input->entity('meta_end_date'),
            'meta_customer'             => $this->input->entity('meta_customer'),
            'meta_address'              => $this->input->entity('meta_address'),
            'meta_cnic'                 => $this->input->entity('meta_cnic'),
            'meta_room_id'              => $room_id,
            'meta_status'               => $this->input->entity('meta_status'),
            'meta_booking_extras_name'  => json_encode($this->input->entity('meta_booking_extras_name')),
            'meta_booking_extras_qty'   => json_encode($this->input->entity('meta_booking_extras_qty')),
            'meta_booking_extras_price' => json_encode($this->input->entity('meta_booking_extras_price')),
            'meta_booking_price'        => $this->input->entity('meta_booking_price'),

        );
    */
    function insert_entity_with_meta($data,$meta_array){
        $create=$this->insert_entity($data);
        $entity_id=$this->last_entity_id(); 
        foreach ($meta_array as $meta_array_key => $meta_array_value) {
             $this->update_entity_meta( $entity_id,$meta_array_key,$meta_array_value);
        }
        return $create;
    }
        /* 
    * It Update entity with all of its meta data
    * use this in your constroller $sphinx->update_entity_with_meta($data,$meta_array,$entity_id)
    * Data set Example
    * $meta_array = array(
            'meta_starting_date'        => $this->input->entity('meta_starting_date'), 
            'meta_end_date'             => $this->input->entity('meta_end_date'),
            'meta_customer'             => $this->input->entity('meta_customer'),
            'meta_address'              => $this->input->entity('meta_address'),
            'meta_cnic'                 => $this->input->entity('meta_cnic'),
            'meta_room_id'              => $room_id,
            'meta_status'               => $this->input->entity('meta_status'),
            'meta_booking_extras_name'  => json_encode($this->input->entity('meta_booking_extras_name')),
            'meta_booking_extras_qty'   => json_encode($this->input->entity('meta_booking_extras_qty')),
            'meta_booking_extras_price' => json_encode($this->input->entity('meta_booking_extras_price')),
            'meta_booking_price'        => $this->input->entity('meta_booking_price'),

        );
    */
    function update_entity_with_meta($data,$meta_array,$entity_id)
    {
        $update=$this->update_entity($data,$entity_id);
        foreach ($meta_array as $meta_array_key => $meta_array_value) {
             $this->update_entity_meta( $entity_id,$meta_array_key,$meta_array_value);
        }
        return $update; 
    }
    /* 
    * It updates or add the new entity meta in database 
    * use this in your constroller $sphinx->update_entity_meta('1','somekey','somevalue')
    */
    function update_entity_meta( $entity_id, $meta_key, $meta_value)
    {
        $data= array(
            'entity_id' => $entity_id,
            'meta_key' => $meta_key ,
            'meta_value' => $meta_value
        );
        
        $sql = "SELECT * FROM entity_meta WHERE entity_id='$entity_id' AND meta_key='$meta_key'";
        $result=mysqli_query($this->connect,$sql);
        if(count($this->result_array($result)) > 0){
            
            $update = $this->update('entity_meta', $data, array('entity_id' => $entity_id , 'meta_key' => $meta_key ));
            return ($update == true) ? true : false;
        }else{
            
            $create = $this->insert('entity_meta', $data);
            return ($create == true) ? true : false;
        }
    }
    /* 
    * It fetch related entity meta data 
    * use this in your constroller $sphinx->get_entity_meta('1')
    */
    function get_entity_meta($entity_id,$meta_key)
    {
        $sql = "SELECT * FROM entity_meta WHERE entity_id='$entity_id' AND meta_key='$meta_key'";
        $result=mysqli_query($this->connect,$sql);
        if($result->num_rows>0){
            return $this->row_array($result)['meta_value'];
        }else{
            return '';
        }
            
        
    }
    /* 
    * It fetch entity name by entity id 
    * use this in your constroller $sphinx->entity_name_by_id('1')
    */
    function entity_name_by_id($entity_id)
    { 
            $sql = "SELECT * FROM entity WHERE entity_id='$entity_id'";
            $result=mysqli_query($this->connect,$sql);
            if($result->num_rows > 0){
                return $this->row_array($result)['entity_title'];
            }else{
                return '';
            }
            
    }


}