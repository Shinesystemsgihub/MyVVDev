<?php

require dirname( __FILE__ ) . '/../Entities/Reservation.php';

class ReservationRepository {
  protected $pdo;

  public function __construct() {

    $this->pdo = new PDO(
      'mysql:host=' . DB_HOST . '; dbname=' . DB_NAME,
      DB_USER,
      DB_PASSWORD);
    }

  
    public function findByUserId($userId) {
    if ( $userId < 1 ) return false;
        
    $statement = $this->pdo->prepare( "
      select 
        r.id as id,
        r.userId as userId, 
        r.rental_id as rentalId,
        r.ID as franchiseId
      from mvvp_reservations as r
      where r.user_id = ? and r.active = 1
    " );

    $statement->execute([$userId]);
    $n = $statement->rowCount();
    $user = $statement->fetch( PDO::FETCH_ASSOC );

    return $user;
  }

  public function getReservationsByUserId( $userId ) {
    if ( $userId < 1 ) return;
    
    $statement = $this->pdo->prepare( "
    select 
      v.id as reservationId,
      v.franchise_id as franchiseId,
      f.franchise_name as franchiseName,
      f.franchise_uri as franchiseUri,
      v.rental_id as rentalId,
      v.zip_code as zipCode,
      r.name as rentalName,
      r.is_pre_arrival as rentalIsPreArrival,
      concat(concat(concat(concat(r.address1, if(r.address2 <> '', 
      concat(', ', r.address2), '')), concat(', ', r.city)), 
      concat(', ', r.state)), concat(', ', r.zip_code)) as rentalAddress
    from mvvp_reservations as v
    left join mvvp_franchises as f on v.franchise_id = f.id
    left join mvvp_rentals as r on v.rental_id = r.id
    where v.user_id = ? and v.is_active = 1
    order by v.ts desc
    " );

    $success = $statement->execute( [ $userId ] );
    $n = $statement->rowCount();

    if ( $n > 0 ) {
      $reservations = $statement->fetchAll( PDO::FETCH_ASSOC );
    }
    else {
      $insert = $this->pdo->prepare ( "
        insert mvvp_reservations ( user_id ) values ( ? );
      " );

      $success = $insert->execute( [ $userId ] );
      $reservations = $success ? [ $this->getNewReservation() ] : false;
    }
    return $reservations;
  }

  public function getNewReservation() {
    return [
        'franchiseId' => 0,
        'franchiseName' => '',
        'franchiseUri' => '',
        'rentalId' => 0,
        'rentalName' => '',
        'rentalIsPreArrival' => 0,
        'rentalAddress' => '',
        'reservationId' => 0,
        'userId' => get_current_user_id(),
        'zipCode' => ''
    ];
}

  public function setUserFranchiseId( $franchiseId, $userId ) {
    if ( $userId < 1 ) return;
    
    $statement = $this->pdo->prepare( "
      update mvvp_reservations
      set franchise_id = ?
      where user_id = ?
    " ); //recast to single record with reservation_id if multiple reservations allowed
    $success = $statement->execute( [ $franchiseId, $userId ] );
  }

  public function setUserRentalId( $rentalId, $userId ) {
    if ( $userId < 1 ) return;

    $statement = $this->pdo->prepare( "
      update mvvp_reservations
      set rental_id = ?
      where user_id = ?
    " ); //recast to single record with reservation_id if multiple reservations allowed
    $success = $statement->execute( [ $rentalId, $userId ] );
  }

  public function setUserZipCode( $zipCode, $userId ) {
    if ( $userId < 1 ) return;

    $zip = substr('00000' . $zipCode, -5);
    $statement = $this->pdo->prepare( "
      update mvvp_reservations
      set zip_code = ?
      where user_id = ?
    " ); //recast to single record reservation_id if multiple reservations allowed
    $success = $statement->execute( [ $zip, $userId ] );
  }

  public function deleteByEmail( $email ) {
    $statement = $this->pdo->prepare( "DELETE FROM mvvp_users WHERE user_email = ?" );
    $success = $statement->execute( [ $email ] ) ;
  }
}