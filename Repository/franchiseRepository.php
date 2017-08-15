<?php

require dirname( __FILE__ ) . '/../Entities/Franchise.php';

class FranchiseRepository {
  protected $pdo;

  public function __construct() {

    $this->pdo = new PDO(
      'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME,
      DB_USER,
      DB_PASSWORD);
  }

  public function findByZipcode($zipcode) {
    
    $statement = $this->pdo->prepare("
      select 
        f.ID as franchiseId,
        f.franchise_name as franchiseName,
        f.franchise_uri as franchiseUri
      from mvvp_franchises_zipcodes as z
          left join mvvp_franchises as f on z.franchise_id = franchise_id
      where z.zip_code = ?
    ");

    $statement->execute([$zipcode]);
    $n = $statement->rowCount();
    $franchise = $statement->fetch(PDO::FETCH_ASSOC);

    return $franchise;
  }
}
