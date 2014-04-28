<?php

	class Action {
		private $id;
		private $desc;
		private $resource;
		private $methods;
		private $params;
		
		public function Action($desc, $resource, $methods, $params) {
			$this->id = uniqid();
			$this->desc = $desc;
			$this->resource = $resource;
			$this->methods = explode(" ", $methods);
			$this->params = explode(" ", $params);
		}
		
		public function getResource() {
			return $this->resource;
		}

		public function getMethods() {
			return $this->methods;
		}

		public function getParams() {
			return $this->params;
		}
		
		private function methodsToHTML() {
			$output = "";
			foreach($this->methods as $value) {
				$output .= sprintf (
					file_get_contents(TEMPLATE_PATH."action/action-method.html"),
					$value,
					$this->id
				);
			}
			return $output;
		}
		
		private function paramsToHTML() {
			$output = "";
			foreach($this->params as $value) {
				$output .= sprintf (
					file_get_contents(TEMPLATE_PATH."action/action-param.html"),
					$value,
					$this->id
				);
			}
			return $output;		
		}
		
		static function subjectToHTML($roles) {
			$options = "";
			foreach($roles as $value) {
				$value = str_replace(" ", "_", $value);
				$options .= sprintf (
					file_get_contents(TEMPLATE_PATH."action/action-role.html"),
					$value
				);
			}
			return sprintf (
				file_get_contents(TEMPLATE_PATH."action/action-subject.html"),
				$options
			);
		}

		static function timeToHTML() {
			return sprintf (
				file_get_contents(TEMPLATE_PATH."action/action-time.html")
			);
		}

		static function locationToHTML() {
			return sprintf (
				file_get_contents(TEMPLATE_PATH."action/action-location.html")
			);
		}
		
		public function toHTML($subject = NULL, $time = NULL, $location = NULL) {
			if($subject == NULL)
				$subject = "{ roles: [], users: [] }";
			if($time == NULL)
				$time = "{ date: ['',''], time: ['',''] }";
			if($location == NULL)
				$location = "[]";
				
			return sprintf (
				file_get_contents(TEMPLATE_PATH."action/action-single.html"),
				$this->id,
				$this->desc,
				$this->resource,
				$this->methodsToHTML(),
				$this->paramsToHTML(),
				$subject,
				$time,
				$location
			);
		}
	}

?>