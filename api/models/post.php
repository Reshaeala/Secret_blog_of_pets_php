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
  public $avatar;
  public $body;
  public $title;
  public function __construct($id, $name, $avatar, $body, $title){
    $this->id = $id;
    $this->name = $name;
    $this->avatar = $avatar;
    $this->body = $body;
    $this->title = $title;
  }
}

class Posts {
  static function all(){
    $posts = array();

    $result = pg_query("SELECT * FROM post");

    $row_object = pg_fetch_object($result);
    while($row_object){
      $new_post = new Post(
        intval($row_object->id),
        $row_object->name,
        $row_object->avatar,
        $row_object->body,
        $row_object->title
      );
      $posts[] = $new_post;
      $row_object = pg_fetch_object($result);
    }
    return $posts;
    // return "something";
  }

  static function create($posts){

    $query = "INSERT INTO post (name, title, avatar, body) VALUES ($1, $2, $3, $4)";
    $query_params = array($posts->name, $posts->title, $posts->avatar, $posts->body);
    pg_query_params($query, $query_params);
    return self::all();
  }

  static function update($updated_post){
      $query = "UPDATE post SET name = $1, title = $2, avatar = $3, body = $4 WHERE id = $5";
      $query_params = array($updated_post->name,
      $updated_post->title,
       $updated_post->avatar, $updated_post->body, $updated_post->id);
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
