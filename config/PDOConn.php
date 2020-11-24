<?php
class PDOConnection 
{
   private static $dbConnection = null;

 
  private function __construct() {
  }
 
  /**
   * Return DB connection or create initial connection
   * @return object (PDO connection)
   * @access public
   */
  public static function getConnection() {
    // if there isn't a connection already then create one
    if ( !self::$dbConnection ) {
        try {
            self::$dbConnection = new PDO('mysql:host=localhost;dbname=pizzagang','root', '');
            self::$dbConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
         }
         catch( PDOException $e ) {
            // in a production system you would log the error not display it
            echo $e->getMessage();
         }
    }
    // return the connection
    return self::$dbConnection;
  }








}//end of class 
?>