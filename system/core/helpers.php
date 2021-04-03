<?php 
require 'core.php';
 /**
  * 
  */
 class helpers extends core
 {
 	private $header_config;
    private $footer_config;
 	
 	function __construct($config)
	{
		parent::__construct($config);
        $this->header_config=$config['header'];
        $this->footer_config=$config['footer'];
        $this->default_config=$config['default_file'];
        $this->file_include=$config['file_include'];
        $this->base=$config['base_url'];
        $this->system_name=$config['system_name'];
	}
    public function get_header($name=null,$var=null){
      if($this->header_config=='/'){
        if($name){
             $this->include_with_var(__DIR__ .'/../../app/header-' . $name .'.php',$var);
        }else{
            $this->include_with_var(__DIR__ .'/../../app/header.php',$var);
        }
      }else{
        if($name){
             $this->include_with_var( __DIR__ .'/../../'.$this->header_config.'/app/header-' . $name .'.php',$var);
        }else{
            $this->include_with_var( __DIR__ .'/../../'.$this->header_config.'/app/header.php',$var);
        }
      }   
    }
    public function get_footer($name=null,$var=null){
      if($this->footer_config=='/'){
        if($name){
             $this->include_with_var(__DIR__ .'/../../app/footer-' . $name .'.php',$var);
        }else{
            $this->include_with_var(__DIR__ .'/../../app/footer.php',$var);
        }
      }else{
        if($name){
             $this->include_with_var( __DIR__ .'/../../'.$this->footer_config.'/footer-' . $name .'.php',$var);
        }else{
            $this->include_with_var( __DIR__ .'/../../'.$this->footer_config.'/footer.php',$var);
        }
      }  
    }
    public function include_with_var($fileName, $variables=null) {
        if ($variables!=null) {
            extract($variables);
        }
       include($fileName);
    }
    public function base_url($path=null)
    {
        if ($path) {
            return $this->base .'app/'. $path;
        }else{
            return $this->base .'app/';
        }
        
    }
    public function get_files($type=null){
        if($type=='style'){
             include(__DIR__ .'/../../app/inc/style.php');
        }elseif ($type=='script') {
          
            include(__DIR__ .'/../../app/inc/script.php');
        }else{
            echo "ERROR KINDLY SELECT FORM {style,script}";
        }
  
    }
    public function check_entity_has_meta($meta)
    {
        $test=array();
        $count=count($meta);
        foreach ($meta as $key => $value) {
            if ($value=="") {
                $test[]='true';
            }else{
                 $test[]='false';
            }
        }
        //var_dump(in_array('false',$test));
        if (!in_array('false',$test) && $count==1) {
            return false;
        }else{
            return true;
        }
    }
    public function default()
    {
        if($this->default_config=='/'){
            return 'index.php';
        }else{
            return $this->default_config .'.php';
        }
    }
    public function is_login($value,$location,$session){
     
        session_start();
        if(!isset($_SESSION[$session][$value])){
           header("Location:".$location);
        }
    }
    public function rand()
    {
        return substr(md5(microtime()),rand(0,26),5);
    }
    public function row($data)
    {
        foreach ($data as $key => $value) {
            return $value;
        }
    }
    public function file()
    {
        if($this->file_include){
            echo $this->get_files('style');
            echo $this->get_files('script');
        }
    }
    function title($name){
        echo "<title>".$name."</title>";
    }
	public function array_flatten($array) {
      
      $result = array();
      foreach ($array as $key => $value) {
        foreach($value as $v){
            $result[]=$v;
        }
      }
      return $result;
    }
    public function fetch_query($query,$filter=null){
        $connect= $this->connect();
        $result=mysqli_query($connect,$query);
         if(!$result){
             die("Query Failed") . mysqli_error($connect);
         }else{
             if($filter=="-1"){
                 return $this->row_array($result);
             }else{
                 return $this->result_array($result);
             }
         }
    }
    public  function json_error($json, $assoc = false)
    {
        $ret = json_decode($json, $assoc);
        if ($error = json_last_error())
        {
            $errorReference = [
                JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded.',
                JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON.',
                JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded.',
                JSON_ERROR_SYNTAX => 'Syntax error.',
                JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded.',
                JSON_ERROR_RECURSION => 'One or more recursive references in the value to be encoded.',
                JSON_ERROR_INF_OR_NAN => 'One or more NAN or INF values in the value to be encoded.',
                JSON_ERROR_UNSUPPORTED_TYPE => 'A value of a type that cannot be encoded was given.',
            ];
            $errStr = isset($errorReference[$error]) ? $errorReference[$error] : "Unknown error ($error)";
            throw new \Exception("JSON decode error ($error): $errStr");
        }
        return $ret;
    }
    public function get_uri_segment($value)
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
             $url = "https://";   
        else  
             $url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url.= $_SERVER['HTTP_HOST'];   
        
        // Append the requested resource location to the URL   
        $url.= $_SERVER['REQUEST_URI'];    
          
        //echo $url;
        $array=explode('/', $url);
        return $array[$value];
    }
     public function utf8_serialize($txt)
     {
       return base64_encode(serialize($txt));
     }
      public function utf8_unserialize($txt)
     {
       return  unserialize(base64_decode($txt));
     }
    public  function send_mail($from,$to,$subject,$message){
     
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL );
        $headers = "From: ".$from."\r\n";
        $headers .= "Reply-To: ".$from."\r\n";
        $headers .= "Return-Path: ".$from."\r\n";
        $headers .= "CC: ".$from."\r\n";
        $headers .= "BCC: ".$from."\r\n";
          $headers .= "Organization: Sender Organization\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
          $headers .= "X-Priority: 3\r\n";
          $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;
        
        // mail($to,$subject,$message, $headers);
        if ( mail($to,$subject,$message,$headers) ) {
		   //echo "The email has been sent!";
		   } else {
		   //echo "The email has failed!";
		   }
		        //return  "The email message was sent.";

     }
     public function each($args,$display,$type)
     {
       if ($type=='table') {
         foreach ($args as $key => $values) {
            echo '<tr>';
           foreach ($values as $k => $value) {
            if(!in_array($k,$display)){continue;}
            echo '<td>'.$value.'</td>';
           }
            echo '</tr>';
         }
       }elseif ($type=='ul') {
          foreach ($args as $key => $values) {
            echo '<ul>';
           foreach ($values as $k => $value) {
            if(!in_array($k,$display)){continue;}
            echo '<li>'.$value.'</li>';
           }
            echo '</ul>';
         }
       }
     }

 }