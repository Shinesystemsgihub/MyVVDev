<?php

require dirname( __FILE__ ) . '/../Entities/User.php';

class UserRepository {
  protected $pdo;

  public function __construct() {

    $this->pdo = new PDO(
      'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME,
      DB_USER,
      DB_PASSWORD);
    }

  public function findByEmail( $email ) {
    
    $statement = $this->pdo->prepare( "
      select 
      u.ID as userId, 
      u.display_name as userName,
      v.rental_id as rentalId,
      v.zip_code as zipCode,
      f.id as franchiseId, 
      f.franchise_name as franchiseName, 
      f.franchise_uri as franchiseUri
    from mvvp_users AS u
    left join mvvp_reservations as v on u.ID = v.user_id
    left join mvvp_franchises_zipcodes as z on v.zip_code = z.zip_code
    left join mvvp_franchises as f on f.id = z.franchise_id
  " );

    $statement->execute( [ $email ] );
    $n = $statement->rowCount();
    $user = $statement->fetch( PDO::FETCH_ASSOC );

    return $user;
  }

  public function findById($userId) {
    if ( $userId < 1 ) return false;
        
    $statement = $this->pdo->prepare( "
    select 
      u.ID as userId, 
      u.display_name as userName,
      v.rental_id as rentalId,
      v.zip_code as zipCode,
      f.id as franchiseId, 
      f.franchise_name as franchiseName, 
      f.franchise_uri as franchiseUri
    from mvvp_users AS u
    left join mvvp_reservations as v on u.ID = v.user_id
    left join mvvp_franchises_zipcodes as z on v.zip_code = z.zip_code
    left join mvvp_franchises AS f on f.id = z.franchise_id
    where u.ID = ?
    " );

    $statement->execute([$userId]);
    $n = $statement->rowCount();
    $user = $statement->fetch( PDO::FETCH_ASSOC );

    return $user;
  }

  public function setUserRentalId( $rentalId, $userId ) {
    if ( $userId < 1 ) return;

    $statement = $this->pdo->prepare( "
      update mvvp_users
      set rental_id = ?
      where ID = ?
    " );
    $statement->execute( [ $rentalId, $userId ] );
  }

  public function setUserZipcode( $zipcode, $userId ) {
    if ( $userId < 1 ) return;
    
    $statement = $this->pdo->prepare( "
      update mvvp_users
      set zip_code = ?
      where ID = ?
    " );
    $statement->execute( [ $zipcode, $userId ] );
  }

  public function deleteByEmail( $email ) {

    $statement = $this->pdo->prepare( "DELETE FROM mvvp_users WHERE user_email = ?" );
    $statement->execute( [ $email ] ) ;
  }
 }