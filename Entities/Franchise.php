<?php
  
  class Franchise {

    protected $franchiseId;
    protected $franchiseName;
    protected $franchiseUri;

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

    public function toArray() {
      $array = [
        'franchiseId' => $this->getFranchiseId(),
        'franchiseName' => $this->getFranchiseName(),
        'franchiseUri' => $this->getFranchiseUri()
      ];

      return $array;
    }
  }