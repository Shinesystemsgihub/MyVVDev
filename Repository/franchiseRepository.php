<?php

require dirname( __FILE__ ) . '/../Entities/Franchise.php';

class FranchiseRepository {
  protected $pdo;

  public function __construct() {

    $this->pdo = new PDO(
      'mysql:host=myvacayvalet.com; dbname=myvacayvalet_dev',
      'myvv_test',
      '8h4z95');

      // $this->pdo = new PDO(
      //   'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME,
      //   DB_USER,
      //   DB_PASSWORD);
    }

  public function findByZipcode($zipcode) {
    
    $statement = $this->pdo->prepare("
      SELECT 
        f.ID as franchise_id,
        f.franchise_Uri
        FROM mvvp_franchises_zipcodes AS z
            LEFT JOIN mvvp_franchises AS f ON z.franchise_id = franchise_id
        WHERE z.zipcode = ?
    ");

    $statement->execute([$zipcode]);
    $n = $statement->rowCount();
    $franchise = $statement->fetch();

    return $franchise;
  }
}
