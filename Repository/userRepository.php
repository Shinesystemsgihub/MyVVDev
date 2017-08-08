<?php

require dirname( __FILE__ ) . '/../Entities/User.php';

class UserRepository {
  protected $pdo;

  public function __construct() {

    $this->pdo = new PDO(
      'mysql:host=myvacayvalet.com;dbname=myvacayvalet_dev',
      'myvv_test',
      '8h4z95');
  }

  public function findByEmail( $email ) {
    
    $statement = $this->pdo->prepare( "
      SELECT 
          u.ID as userId, 
          u.display_name as userName, 
          f.ID as franchiseId, 
          f.franchise_name as franchiseName, 
          f.franchise_uri as franchiseUri
        FROM mvvp_users AS u
        LEFT JOIN mvvp_franchises AS f ON u.franchise_id = f.ID
        WHERE u.user_email = ?
    " );

    $statement->execute([$email]);
    $n = $statement->rowCount();
    $user = $statement->fetch();

    return $user;
  }

  public function setUserFranchiseId( $franchiseId, $userId ) {
    $statement = $this->pdo->prepare( "
      UPDATE mvvp_users
        SET franchise_id = ?
        WHERE ID = ?
    " );
    $statement->execute( [ $franchiseId, $userId ] );
  }

  public function findById($userId) {
    
    $statement = $this->pdo->prepare( "
      SELECT 
          u.ID as userId, 
          u.display_name as userName, 
          f.ID as franchiseId, 
          f.franchise_name as franchiseName, 
          f.franchise_uri as franchiseUri
        FROM mvvp_users AS u
        LEFT JOIN mvvp_franchises AS f ON u.franchise_id = f.ID
        WHERE u.ID = ?
    " );

    $statement->execute([$userId]);
    $n = $statement->rowCount();
    $user = $statement->fetch();

    return $user;
  }

  public function deleteByEmail($email) {

    $statement = $this->pdo->prepare("DELETE FROM mvvp_users WHERE user_email = ?");
    $statement->execute([$email]);
  }
}
