<?php 
require 'helpers.php';
 /**
  * 
  */
 class ci_sphinx extends helpers
 {
 	
 	function __construct($config)
	{
		parent::__construct($config);
	}
	public function input($args)
	{
		$html='';
		if (array_key_exists('is_parent',$args)) {
			$is_parent=$args['is_parent'];
			$html.='<'.$is_parent['tag'] .' ';
			foreach ($is_parent as $key => $value) {
				if($key=='tag'){continue;}
				$html.= $key.'="'.$value.'" ';
			}
			$html.='>';
		
		}	
		$html.='<input ';
		foreach ($args as $key => $value) {
			if($key=='is_parent'){continue;}
			$html.= $key.'="'.$value.'" ';
		}
		$html.='>';
		if (array_key_exists('is_parent',$args)) {
			$is_parent=$args['is_parent'];
			$html.='</'.$is_parent['tag'] . '>';
		}
		return $html;
	}
	    /*
     * ------------------------------------------------------
     *  Login function
     * ------------------------------------------------------
     * $args=array(
     *    "submit_button" => "submit",
     *    "post"      => $_POST,
     *      "fields"        => array('email','password'),
     *    "location"    => 'index'
     *  );
     */
  public function login($args){

        session_start();
        $data=array();
        $submit_button=$args['submit_button'];
        $_POST=$args['post'];
        $location=$args['location'];
        $fields=$args['fields'];
        $locp=$args['locp'];
        $table=$args['table'];
        $session=$args['session'];
        if (isset($_POST[$submit_button])) {
          foreach ($fields as $key => $value) {
            if ($locp==$key) {
              $data[$value]=md5($_POST[$value]);
            }else{
              $data[$value]=$_POST[$value];
            }
          }
          var_dump($this->fetch($table,$data));
            $result=$this->row($this->fetch($table,$data));
            if (!empty($result) || $result!=NULL || $result!='') {
            	$_SESSION[$session]=$result;
            	echo "yes";
              header('Location:'.$location);
               
            }else{
                echo "ERROR";
            }

        }
    }
  public function make_array($array,$count)
  {
     
    for ($i=0; $i <= $count ; $i++) { 
      foreach ($array as $array_key => $array_value) {
         if($array_value){
                  foreach ($array_value as $array_value_key => $array_value_value) {
                       $return_array[$array_value_key][$array_key]=$array_value_value;

                    }
              }
      }
           
 
        }
        return $return_array;
  }
  public function make_fields($meta)
  {
      foreach ($meta as $key => $value) {
        if ($value['type']=='text' || $value['type']=='email' || $value['type']=='number' || $value['type']=='password' || $value['type']=='file' || $value['type']=='date') {
          echo '<div class="col-md-'.$value['input_parent_col'].'"><div class="form-group"><label for="">'.$value['input_label'].'</label>';
            echo '<input type="'.$value['type'].'"  class="form-control" placeholder="enter '.$value['input_label'].'">';
          echo '</div></div>';
        }elseif($value['type']=='textarea'){
           echo '<div class="col-md-'.$value['input_parent_col'].'"><div class="form-group"><label for="">'.$value['input_label'].'</label>';
            echo '<textarea class="form-control" placeholder="enter '.$value['input_label'].'"></textarea>';
          echo '</div></div>';
        }elseif($value['type']=='checkbox' || $value['type']=='radio' || $value['type']=='select'){
          $values=explode(PHP_EOL, $value['skuList']);
           echo '<div class="col-md-'.$value['input_parent_col'].'"><div class="form-group"><label for="">'.$value['input_label'].'</label>';
            
          echo '</div></div>';
        }
      }
    echo "<pre>";
    var_dump($meta);
    echo "</pre>";
  }
 }