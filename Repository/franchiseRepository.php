<?php

require dirname( __FILE__ ) . '/../Entities/Franchise.php';

class FranchiseRepository {
  protected $pdo;

  public function __construct() {
    $this->pdo = new PDO (
      'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME,
      DB_USER,
      DB_PASSWORD);
  }

  public function findById($id) {
    $statement = $this->pdo->prepare ( "
      select 
        f.id as franchiseId,
        f.franchise_name as franchiseName,
        f.franchise_uri as franchiseUri
      from mvvp_franchises as f
      where f.id = ?
    " );

    $statement->execute( [ $id ] );
    $n = $statement->rowCount();
    $franchise = $statement->fetch( PDO::FETCH_ASSOC );

    return $franchise;
  }

  public function findByReservationId($id) {
    $statement = $this->pdo->prepare ( "
      select 
        v.franchise_id as franchiseId,
        f.franchise_name as franchiseName,
        f.franchise_uri as franchiseUri
      from mvvp_reservations as v
      left join mvvp_franchises as f on v.franchise_id = f.id
      where v.id = ?
    " );

    $statement->execute( [ $id ] );
    $n = $statement->rowCount();
    $franchise = $statement->fetch( PDO::FETCH_ASSOC );

    return $franchise;
  }

  public function findByZipCode( $zipcode ) {
    $statement = $this->pdo->prepare ( "
      select 
        f.ID as franchiseId,
        f.franchise_name as franchiseName,
        f.franchise_uri as franchiseUri
      from mvvp_franchises_zipcodes as z
      left join mvvp_franchises as f on z.franchise_id = franchise_id
      where z.zip_code = ? and f.is_active = 1
    " );

    $statement->execute([$zipcode]);
    $n = $statement->rowCount();
    $franchise = $statement->fetch( PDO::FETCH_ASSOC );

    return $franchise;
  }

  public function recordUnservedZipcode( $zipcode ) {
    $statement = $this->pdo->prepare ( "
      insert into mvvp_unserved_zipcodes ( zip_code ) values ( ? )
    " );  

    $statement->execute( [ $zipcode ] );
  }
}
