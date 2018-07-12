<?php

require 'Logger.php';
require 'HttpLogger.php';
class Notes
{
    private $con;
    private $table = 'notes';

    public function __construct($db)
    {
        $this->con = $db;
    }

    public $id;
    public $label_name;
    public $title;
    public $body;
    public $url;
    public $user_id;
    public $created_at;

    public function create()
    {
        error_log('Invoked create() Method');
        $query = 'INSERT INTO ' .
            $this->table . '
        SET
          title = :title,
          body = :body,
          url = :url,
          user_id = :user_id,
          label_name = :label_name';

        $stmt = $this->con->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->label_name = htmlspecialchars(strip_tags($this->label_name));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':label_name', $this->label_name);
        try {
            if ($stmt->execute()) {
                error_log('Note Created');
                return true;
            }else {
                throw new PDOException();
            }
        }catch (\PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return $e;
        }
    }

    public function read()
    {
        error_log('Invoked read() Method');
        try {
            $query = 'SELECT n.id, n.label_name, n.title, n.body, n.url, n.user_id, n.created_at FROM ' . $this->table . ' n ORDER BY n.created_at DESC';
            $stmt = $this->con->prepare($query);
            if($stmt->execute()) {
                error_log('Retrieved Notes List');
                return $stmt;
            }else{
                throw new PDOException();
            }
        } catch (\PDOException $e) {
            error_log('Error while retrieving notes: ' . $e->getMessage());
            return $e;
        }
    }

    public function read_single()
    {
        error_log('Invoked read_single() Method');
        try {
            $query = 'SELECT n.id, n.label_name, n.title, n.body, n.url, n.user_id, n.created_at FROM ' . $this->table . ' n  WHERE n.id = ? LIMIT 0,1';
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(1, $this->id);
            if($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->title = $row['title'];
                $this->body = $row['body'];
                $this->url = $row['url'];
                $this->user_id = $row['user_id'];
                $this->label_name = $row['label_name'];
                error_log('Retrieved Note');
                return true;
            }else{
                throw new PDOException();
            }
        } catch (\PDOException $e) {
            error_log('Note Not Available: ' . $e->getMessage());
            return $e;
        }
    }

    public function update()
    {
        $query = 'UPDATE ' .
            $this->table . '
        SET
          title = :title,
          body = :body,
          url = :url,
          user_id = :user_id,
          label_name = :label_name
        WHERE
          id = :id';
        $stmt = $this->con->prepare($query);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->label_name = htmlspecialchars(strip_tags($this->label_name));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':label_name', $this->label_name);
        $stmt->bindParam(':id', $this->id);
        try {
            if ($stmt->execute()) {
                error_log('Note Updated');
                return true;
            }else {
                throw new PDOException();
            }
        }catch(\PDOException $e) {
            error_log("Error: " .  $e->getMessage());
            return $e;
        }
    }

    public function delete()
    {

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->con->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        try {
            if ($stmt->execute()) {
                error_log('Note Deleted');
                return true;
            }
        }catch(\PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return $e;
        }
    }

}