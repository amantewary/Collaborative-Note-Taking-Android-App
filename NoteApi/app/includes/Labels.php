<?php

require 'Logger.php';
require  'HttpLogger.php';
  class Labels {
  
    private $con;
    private $table = 'labels';
    public $id;
    public $name;
    public $user_id;
    public $created_at;
    public $new_name;
    public $old_name;
    public function __construct($db) {
      $this->con = $db;
    }
    public function read() {
      error_log('Invoked read() Method in Labels class');
        try{
            $query = 'CALL spGetLabels(:user_id)';
            $stmt = $this->con->prepare($query);
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $stmt->bindParam(':user_id', $this->user_id);
            if($stmt->execute()) {
                error_log('Retrieved Labels List By User: ' . $this->user_id);
                return $stmt;
            }
        }catch(\PDOException $e) {
            error_log('[Error] While Retrieving Labels: ' . $e->getMessage() . 'By User: ' . $this->user_id);
        return $e;
        }
    }

    public function create()
    {
        error_log('Invoked create() Method Inside Labels Class');
        $query = 'CALL spCreateLabel(:name, :user_id)';

        $stmt = $this->con->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':user_id', $this->user_id);
        try {
            if ($stmt->execute()) {
                error_log('Label Created With Name: ' . $this->name . 'By User: ' . $this->user_id);
                return $stmt;
            }
        }catch (\PDOException $e) {
            error_log("[Error] While Creating Notes: " . $e->getMessage() . 'By User: ' . $this->user_id);
            return $e;
        }
    }

    public function update()
    {
        $query = 'CALL spUpdateLabel(:new_name, :user_id, :old_name)';
        $stmt = $this->con->prepare($query);
        $this->new_name = htmlspecialchars(strip_tags($this->new_name));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->old_name = htmlspecialchars(strip_tags($this->old_name));
        $stmt->bindParam(':new_name', $this->new_name);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':old_name', $this->old_name);
        try {
            if ($stmt->execute()) {
                error_log('Label Updated');
                return $stmt;
            }
        }catch(\PDOException $e) {
            error_log("[Error] " .  $e->getMessage());
            return $e;
        }
    }

    public function delete()
    {

        $query = 'CALL spDeleteLabel(:name, :user_id)';
        $stmt = $this->con->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':user_id', $this->user_id);
        try {
            $stmt->execute();
            if ($stmt->rowCount()) {
                error_log('Label Deleted');
                return $stmt;
            }
        }catch(\PDOException $e) {
            error_log("[Error] Label Deletion Failed: " . $e->getMessage());
        }
    }
  }