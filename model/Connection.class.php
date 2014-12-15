<?php                   

  class Connection
  {
    private $server = DB_HOST;
    private $database = DB_NAME;
    private $user = DB_USER;
    private $pw = DB_PASSWORD;
    private $resource;
  
    function connection() {
			if(!($this->resource = mysqli_connect($this->server, $this->user, $this->pw, $this->database))) { 
				/* hint to the error log */ 
			} 
    }
    
    function query($query) {
      if(!($result = $this->resource->query($query))) { 
				/* hint to the error log */ 
			}
      else { 
				return $result;
			}
    }
    
    function fetch_object($result, $class) {
      if(!($result = mysqli_fetch_object($result, $class))) { 
				/* hint to the error log */ 
			}
      else { 
				return $result; 
			}
    }
		
		function fetch_object_set($result, $class) {
			$i = 0;
			$array = array();
			while($o = $this->fetch_object($result, $class)) {
				$array[$i] = $o;
				$i++;
			}
			return $array;
		}
    
    function fetch_array($result) {
			if(!($result = mysqli_fetch_assoc($result))) { 
				/* hint to the error log */ 
			}
      else { 
				return $result;
			}
    }
		
    function fetch_row($result) {
			$i = 0;
			$array = array();
			while($row = $this->fetch_array($result)) {
				$array[$i] = $row;
				$i++;								
			}
			return $array;
    }      

    function fetch_row_set($result) {
			$array = array();
			while($row = $this->fetch_row($result)) {
				$array[] = $row;
			}
			return $array;
    } 		
		
  }

?>