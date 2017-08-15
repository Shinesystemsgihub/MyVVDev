<?php

class Rental {

}

class RentalList {

		protected $id;
		protected $description;

		public function getId() {
			return $this->id;
		}

		public function setID($value) {
			$this->id = $value ? $value : '';
		}

		public function getDescription() {
			return $this->description;
		}

		public function setDescription($value) {
			$this->description = $value ? $value : '';
		}
}
