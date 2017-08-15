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
      SELECT 
      u.ID as userId, 
      u.display_name as userName,
      u.rental_id as rentalId,
      u.zip_code as zipCode,
      f.ID as franchiseId, 
      f.franchise_name as franchiseName, 
      f.franchise_uri as franchiseUri
      FROM mvvp_users AS u
        LEFT JOIN mvvp_franchises AS f ON u.zip_code = f.zip
        WHERE u.user_email = ?
    " );

    $statement->execute([$email]);
    $n = $statement->rowCount();
    $user = $statement->fetch( PDO::FETCH_ASSOC );

    return $user;
  }

  public function findById($userId) {
    if ( $userId < 1 ) return false;
        
    $statement = $this->pdo->prepare( "
      SELECT 
        u.ID as userId, 
        u.display_name as userName,
        u.rental_id as rentalId,
        u.zip_code as zipCode,
        f.ID as franchiseId, 
        f.franchise_name as franchiseName, 
        f.franchise_uri as franchiseUri
      FROM mvvp_users AS u
      LEFT JOIN mvvp_franchises_zipcodes as z ON u.zip_code = z.zip_code
      LEFT JOIN mvvp_franchises AS f ON z.franchise_id = f.ID
      WHERE u.ID = ?
    " );

    $statement->execute([$userId]);
    $n = $statement->rowCount();
    $user = $statement->fetch( PDO::FETCH_ASSOC );

    return $user;
  }

  public function setUserFranchiseId( $franchiseId, $userId ) {
    if ( $userId < 1 ) return;
    
    $statement = $this->pdo->prepare( "
      UPDATE mvvp_users
        SET franchise_id = ?
        WHERE ID = ?
    " );
    $statement->execute( [ $franchiseId, $userId ] );
  }

  public function setUserRentalId( $rentalId, $userId ) {
    if ( $userId < 1 ) return;

    $statement = $this->pdo->prepare( "
      UPDATE mvvp_users
        SET rental_id = ?
        WHERE ID = ?
    " );
    $statement->execute( [ $rentalId, $userId ] );
  }

  public function setUserZipcode( $zipcode, $userId ) {
    if ( $userId < 1 ) return;
    
    $statement = $this->pdo->prepare( "
      UPDATE mvvp_users
        SET zip_code = ?
        WHERE ID = ?
    " );
    $statement->execute( [ $zipcode, $userId ] );
  }

  public function deleteByEmail( $email ) {

    $statement = $this->pdo->prepare( "DELETE FROM mvvp_users WHERE user_email = ?" );
    $statement->execute( [ $email ] ) ;
  }
 }