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
  public $time_stamp;
  public $last_edited;
  public $likes;
  public function __construct($id, $name, $avatar, $body, $title, $time_stamp, $last_edited, $likes){
    $this->id = $id;
    $this->name = $name;
    $this->avatar = $avatar;
    $this->body = $body;
    $this->title = $title;
    $this->time_stamp = $time_stamp;
    $this->last_edited = $last_edited;
    $this->likes = $likes;
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
        $row_object->title,
        $row_object->time_stamp,
        $row_object->last_edited,
        $row_object->likes
      );
      $posts[] = $new_post;
      $row_object = pg_fetch_object($result);
    }
    return $posts;
    // return "something";
  }

  static function create($posts){

    $query = "INSERT INTO post (name, title, avatar, body) VALUES ($1, $2, $3, $4)";
    $query_params = array($posts->name, $posts->title, $posts->avatar, $posts->body, $posts->time_stamp, $posts->last_edited, $posts->likes);
    pg_query_params($query, $query_params);
    return self::all();
  }

  static function update($updated_post){
      $query = "UPDATE post SET name = $1, title = $2, avatar = $3, body = $4, time_stamp = $5, last_edited = $6, likes = $7 WHERE id = $8";
      $query_params = array($updated_post->name,
      $updated_post->title,
       $updated_post->avatar, $updated_post->body, $updated_post->time_stamp, $updated_post->last_edited, $updated_post->likes, $updated_post->id);
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
