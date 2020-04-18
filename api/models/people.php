<?php
$dbconn = null;
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $conn = pg_connect(getenv("DATABASE_URL"));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
    $dbconn = pg_connect("host=localhost dbname=petblog");
}

  class Person{
    public $id;
    public $name;
    public $avatar;
    public $about;
    public function __construct($id, $name, $avatar, $about){
      $this->id = $id;
      $this->name = $name;
      $this->avatar = $avatar;
      $this->body = $about;
    }
  }

  class People{
    static function all(){
      $people = array();

      $result = pg_query("SELECT * FROM people");
      $row_object = pg_fetch_object($result);
      while($row_object){
        $new_person = new Person(
          intval($row_object->id),
          $row_object->name,
          $row_object->avatar,
          $row_object->about
        );
        $people[]= $new_person;
        $row_object = pg_fetch_object($result);
      }
      return $people;
    }
  }

 ?>
