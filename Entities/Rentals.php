<?php

class Rental {

}

class RentalList {

		protected $id;
		protected $rentalDescription;

		public function getId() {
			return $this->id;
		}

		public function setID($value) {
			$this->id = $value ? $value : '';
		}

		public function getRental() {
			return $this->rentalDescription;
		}

		public function setRental($value) {
			$this->rentalDescription = $value ? $value : '';
		}
}
