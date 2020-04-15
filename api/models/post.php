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

class Post {
  public $id;
  public $name;
  public $image;
  public $body;

  public function __construct($id, $name, $image, $body){
    $this->id = $id;
    $this->name = $name;
    $this->image = $image;
    $this->body = $body;
  }
}

class Posts {
  static function all(){
    $posts = array();

    $result = pg_query("SELECT * FROM post");
    return "something";
    // $row_object = pg_fetch_object($result);
    // while($row_object){
    //   $new_post = new Post(
    //     intval($row_object->id),
    //     $row_object->name,
    //     $row_object->image,
    //     $row_object->body
    //   );
    //   $posts[] = $new_post;
    //   $row_object = pg_fetch_object($result);
    // }
    // return $posts;
  }

  static function create($posts){
    $query = "INSERT INTO post (name, image, body) VALUES ($1, $2, $3)";
    $query_params = array($posts->name, $posts->image, $posts->body);
    pg_query_params($query, $query_params);
    return self::all();
  }

  static function update($updated_post){
      $query = "UPDATE post SET name = $1, image = $2, body = $3 WHERE id = $4";
      $query_params = array($updated_post->name, $updated_post->image, $updated_post->body, $updated_post->id);
      $result = pg_query_params($query, $query_params);

      return self::all();
    }
    static function delete($id){
      $query = "DELETE FROM post WHERE id = $1";
      $query_params = array($id);
      $result = pg_query_params($query, $query_params);

      return self::all();
    }
}
 ?>
