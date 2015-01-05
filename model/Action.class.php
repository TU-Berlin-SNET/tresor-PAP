<?php

	class Action {
		private $id;
		private $desc;
		private $resource;
		private $methods;
		
		public function Action($desc, $resource, $methods) {
			$this->id = uniqid();
			$this->desc = $desc;
			$this->resource = $resource;
			$this->methods = $methods;
		}
		
		public function getID() {
			return $this->id;
		}		

		public function getDesc() {
			return $this->desc;
		}		
		
		public function getResource() {
			return $this->resource;
		}

		public function getMethods() {
			return $this->methods;
		}
	}

?>