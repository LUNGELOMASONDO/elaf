<?php
/**
 * @author Masondo
 *
 * This script contains the Admin class
 *
 */
  class LFAdmin
  {
    private $adminid;
    private $name;
    private $phone;
    private $type;

    private $isAdmin;

    public function __construct()
    {
      $this->isAdmin;
    }

    public function construct__admin($_adminid, $_name, $_phone, $_type)
    {
      $admin = new LFAdmin;
      $admin->adminid = $_adminid;
      $admin->name = $_name;
      $admin->phone = $_phone;
      $admin->type = $_type;

      $admin->isAdmin = true;
      return $admin;
    }

    public function get_admin_from_db($_adminid, $_pass)
    {
      $adminid = $_adminid;
      $pass = $_pass;

      try
      {
        $conn = dbconn();
        $stmt = $conn->prepare("SELECT * FROM lfadmin WHERE adminid=:_adminid AND adminpassword=:_pass");
        $stmt->execute(array("_adminid" => $adminid, "_pass" => $pass));
        $row = $stmt->fetch();

        if($row == null) // invalid admin info
        {
          $this->isAdmin = false;
          return;
        }

        $this->set_adminid($row['ADMINID']);
        $this->set_name($row['ADMINNAME']);
        $this->set_phone($row['PHONE']);
        $this->set_type($row['ADMINTYPE']);
      }
      catch (PDOException $e)
      {
        $this->isAdmin = false;
        return;
      }

      $this->isAdmin = true;
    }

    public function get_admininfo_from_db($_adminid)
    {
      /*
       ** 	get admin info without password
      */
      $adminid = $_adminid;

      try
      {
        $conn = dbconn();
        $stmt = $conn->prepare("SELECT * FROM lfadmin WHERE ADMINID=:_adminid");
        $stmt->execute(array(":_adminid" => $adminid));
        $row = $stmt->fetch();

        if($row == null)
        {
          $this->isAdmin = false;
          return;
        }

        $this->set_adminid($row['ADMINID']);
        $this->set_name($row['ADMINNAME']);
        $this->set_phone($row['PHONE']);
        $this->set_type($row['ADMINTYPE']);
      }
      catch (PDOException $e)
      {
        $this->isUser = false;
        return;
      }

      $this->isUser = true;

    }

    public function get_adminid(){
      return $this->adminid;
    }

    public function get_name(){
      return $this->name;
    }

    public function get_phone(){
      return $this->phone;
    }

    public function get_type(){
      return $this->type;
    }

    public function isAdmin(){
      return $this->isAdmin;
    }

    public function set_adminid($_adminid){
      $this->adminid = $_adminid;
    }

    public function set_name($_name){
      $this->name = $_name;
    }

    public function set_phone($_phone){
      $this->phone = $_phone;
    }

    public function set_type($_type){
      $this->type = $_type;
    }
  }
 ?>
