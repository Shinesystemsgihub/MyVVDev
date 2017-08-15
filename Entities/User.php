<?php
  
  class User {

    protected $franchiseId;
    protected $franchiseName;
    protected $franchiseUri;
    protected $rentalId;
    protected $userId;
    protected $userName;
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

    public function getUserId() {
      return $this->userId;
    }

    public function setUserId($value) {
      $this->userId = $value ? $value : '';
    }

    public function getUserName() {
      return $this->userName;
    }

    public function setUserName($value) {
      $this->userName = $value ? $value : '';
    }

    public function getZipcode() {
      return $this->zipCode;
    }

    public function setZipcode($value) {
      $this->zipCode = $value ? $value : '';
    }

    public function toArray() {
      $array = [
        'userId' => $this->getUserId(),
        'userName' => $this->getUserName(),
        'zipCode' => $this->getZipcode(),
        'franchiseId' => $this->getFranchiseId(),
        'franchiseName' => $this->getFranchiseName(),
        'franchiseUri' => $this->getFranchiseUri(),
        'rentalId' => $this->getRentalId()
      ];

      return $array;
    }
  }