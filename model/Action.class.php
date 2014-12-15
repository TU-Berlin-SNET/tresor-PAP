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
			$this->methods = $methods;
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
		
		private function paramsToHTML() {
			$output = "";
			foreach($this->params as $value) {
				$output .= sprintf (
					pfile_get_contents(TEMPLATE_PATH."action/action-param.html"),
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
					pfile_get_contents(TEMPLATE_PATH."action/action-role.html"),
					$value
				);
			}
			return sprintf (
				pfile_get_contents(TEMPLATE_PATH."action/action-subject.html"),
				$options,
				Action::identitySourceToHTML()
			);
		}

		static function timeToHTML() {
			return sprintf (
				pfile_get_contents(TEMPLATE_PATH."action/action-time.html")
			);
		}

		static function locationToHTML() {
			return sprintf (
				pfile_get_contents(TEMPLATE_PATH."action/action-location.html")
			);
		}
		
		static function identitySourceToHTML() {
			$output = "";
			global $identityProvider;
			foreach($identityProvider as $value)
				$output .= sprintf (
					pfile_get_contents(TEMPLATE_PATH."action/action-identity-source.html"),
					$value,
					""
				);
			return $output;
		}
		
		public function toHTML($subject = NULL, $time = NULL, $location = NULL) {
			if($subject == NULL)
				$subject = "{ roles: [], users: [], idsrc: 'Keine' }";
			if($time == NULL)
				$time = "{ date: ['',''], time: ['',''] }";
			if($location == NULL)
				$location = "[]";
				
			return sprintf (
				pfile_get_contents(TEMPLATE_PATH."action/action-single.html"),
				$this->id,
				$this->desc,
				$this->resource,
				$this->methods,
				$this->paramsToHTML(),
				$subject,
				$time,
				$location
			);
		}
	}

?>