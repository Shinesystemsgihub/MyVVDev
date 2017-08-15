<?php

require dirname( __FILE__ ) . '/../Entities/Rentals.php';

class RentalRepository {
  protected $pdo;

  public function __construct() {

      $this->pdo = new PDO(
        'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME,
        DB_USER,
        DB_PASSWORD );
    }

  public function findByZipcode( $zipcode ) {
    
    $statement = $this->pdo->prepare("
      select 
        id,
        concat(concat(concat(concat(concat(name, ' - '), 
        concat(if(is_pre_arrival, 'Pre-Arrival, ', '')), 
        concat(address1, if(address2 <> '', concat(', ', address2), ''))), 
        concat(', ', city)), ', ', state), concat(', ', zip_code)) as rentalDesription
      from mvvp_rentals
      where zip_code = ? and is_active = 1
    ");

    $statement->execute( [ $zipcode ]) ;
    $n = $statement->rowCount();
    $rentalList = $statement->fetchAll( PDO::FETCH_NUM );

    return $rentalList;
  }
}
