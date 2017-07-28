<?php
  
  class User {

    protected $franchiseId;
    protected $franchiseName;
    protected $franchiseUri;
    protected $userId;
    protected $userName;

    public function getFranchiseId() {
      return $this->franchiseId;
    }

    public function setFranchiseId($value) {
      $this->franchiseId = $value;
    }

    public function getFranchiseName() {
      return $this->franchiseName;
    }

    public function setFranchiseName($value) {
      $this->franchiseName = $value;
    }

    public function getFranchiseUri() {
      return $this->franchiseUri;
    }

    public function setFranchiseUri($value) {
      $this->franchiseUri = $value;
    }

    public function getUserId() {
      return $this->userId;
    }

    public function setUserId($value) {
      $this->userId = $value;
    }

    public function getUserName() {
      return $this->userName;
    }

    public function setUserName($value) {
      $this->userName = $value;
    }

    public function toArray() {
      $array = [
        'userId' => $this->getUserId(),
        'userName' => $this->getUserName(),
        'franchiseId' => $this->getFranchiseId(),
        'franchiseName' => $this->getFranchiseName(),
        'franchiseUri' => $this->getFranchiseUri()
      ];

      return $array;
    }
  }