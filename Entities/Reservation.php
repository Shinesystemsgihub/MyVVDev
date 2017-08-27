<?php
  
  class Reservation {

    protected $franchiseId;
    protected $franchiseName;
    protected $franchiseUri;
    protected $rentalAddress;
    protected $rentalId;
    protected $rentalName;
    protected $rentalIsPreArrival;
    protected $reservationId;
    protected $userId;
    protected $zipCode;

    public function getFranchiseId() {
      return $this->franchiseId;
    }

    public function setFranchiseId($value) {
      $this->franchiseId = $value ? $value : '';
    }

    public function getFranchiseName() {
      return $this->franchiseName;
    }

    public function setFranchiseName($value) {
      $this->franchiseName = $value ? $value : '';
    }

    public function getFranchiseUri() {
      return $this->franchiseUri;
    }

    public function setFranchiseUri($value) {
      $this->franchiseUri = $value ? $value : '';
    }

    public function getRentalId() {
      return $this->rentalId;
    }

    public function setRentalId($value) {
      $this->rentalId = $value ? $value : '';
    }

    public function getRentalName() {
      return $this->rentalName;
    }

    public function setRentalName($value) {
      $this->rentalName = $value ? $value : '';
    }

    public function getRentalIsPreArrival() {
      return $this->rentalIsPreArrival;
    }

    public function setRentalIsPreArrival($value) {
      $this->rentalIsPreArrival = $value ? $value : '';
    }

    public function getRentalAddress() {
      return $this->rentalAddress;
    }

    public function setRentalAddress($value) {
      $this->rentalAddress = $value ? $value : '';
    }

    public function getReservationId() {
      return $this->reservationId;
    }

    public function setReservationId($value) {
      $this->reservationId = $value ? $value : '';
    }

    public function getUserId() {
      return $this->userId;
    }

    public function setZipCode($value) {
      $this->zipCode = $value ? $value : '';
    }

    public function getZipCode() {
      return $this->zipCode;
    }

    public function setUserId($value) {
      $this->userId = $value ? $value : '';
    }

    public function toArray() {
      $array = [
        'franchiseId' => $this->getFranchiseId(),
        'franchiseName' => $this->getFranchiseName(),
        'franchiseUri' => $this->getFranchiseUri(),
        'rentalId' => $this->getRentalId(),
        'rentalName' => $this->getRentalName(),
        'rentalIsPreArrival' => $this->getRentalIsPreArrival(),
        'rentalAddress' => $this->getRentalAddress(),
        'reservationId' => $this->getReservationId(),
        'userId' => $this->getUserId(),
        'zipCode' => $this->getZipCode()
      ];

      return $array;
    }
  }