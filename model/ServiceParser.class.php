<?php

	class ServiceParser {
		private $xml;
		
    /**
     * Constructor to instantiate ServiceParser class
     * @param <String> $file - file name of the service's XML template
     */
		public function ServiceParser($file) {
			$this->xml = simplexml_load_file(SERVICES_PATH.$file);
		}
		
    /**
     * A function to receive all existing service templates.
     * @return <Array> containing all available service templates
     */
		static function getServicesFiles() {
			$services = array();
			
			// dir() returns Directory object containing of given path
			$d = dir(SERVICES_PATH);
			while(false != ($entry = $d->read())) {
				// remove directories '.' and '..'
				if($entry == "." || $entry == "..") 
					continue;
				
				// remove any directory except these with file extension "xml"
				$file_ext = explode(".", $entry);
				if($file_ext[count($file_ext)-1] != "xml") 
					continue;
				
				$services[] = $entry;
			}
			return $services;
		}
		
    /**
     * A function to retrieve a service by it's ID
     * @param <String> $id - the ID of the desired service
     * @return <ServiceParser> the requested service if existing else null
     */
		
		static function getServiceById($id) {
			$services = ServiceParser::getServicesFiles();
			foreach($services as $key => $value) {
				$s = new ServiceParser($value);
				if($s->getIdOfService() == $id)
					return $s;
			}
			return null;
		}

    /**
     * A function to receive the ID of the service
     * @return <String> the ID of the service
     */
		function getIdOfService() {
			return $this->xml->attributes()->id;
		}		
		
    /**
     * A function to receive the name of the service
     * @return <String> the name of the service
     */
		function getNameOfService() {
			return $this->xml->attributes()->name;
		}
		
		
    /**
     * A function to receive the base URL of the service.
     * @return <String> the base URL of the service
     */
		function getBaseUrlOfService() {
			return $this->xml->attributes()->url;
		}

    /**
     * A function to receive the description text of the service
     * @return <String> the description text of the service
     */
		function getDescOfService() {
			return $this->xml->description;
		}
		
		function getActionsOfService() {
			$actions = array();
			foreach($this->xml->paths->path as $value) {
				foreach(explode(" ", $value->attributes()->methods) as $method) {
					$a = new Action($value->desc, $value->attributes()->url, $method);
					$actions[] = $a;
				}
			}
			return $actions;
		}
	}

?>