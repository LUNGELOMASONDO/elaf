<?php
  class FoundItem extends Item{
    private $founditemid;
    private $itemImage;
    private $placefound;
    private $lfuser;  //  lfuser object
    private $lfadmin; //  lfadmin object

    public function __construct() {
       parent::__construct();
    }

    public function get_item_from_db($_itemid){
      try
      {
        $conn = dbconn();
        $stmt = $conn->prepare("SELECT * FROM item, founditem WHERE founditem.ITEMID=item.ITEMID AND founditem.FOUNDITEMID=:_itemid;");
        $stmt->execute(array(":_itemid" => $itemid));
        $row = $stmt->fetch();

        if($row == null)
        {
          parent::set_isItem(false); //  no results found
          return;
        }

        parent::set_itemID($row['ITEMID']);
        parent::set_name($row['ITEMNAME']);
        parent::set_timestamp($row['ITEMTIME']);
        parent::set_description($row['ITEMDESCRIPTION']);
        parent::set_brand($row['ITEMBRAND']);
        parent::set_color($row['ITEMCOLOR']);
        parent::set_identifier($row['IDENTIFIER']);
        $this->set_founditemid($row['FOUNDITEMID']);
        $this->set_itemImg($row['ITEMIMAGE']);
        $this->set_placefound($row['PLACEFOUND']);
        /* user info */
        $userid = $row['USERID'];
        $user = new LFUser;
        $user->get_userinfo_from_db($userid);
        if($user->isUser())
          $this->set_lfuser($user);
        /* admin info */
        $adminid = $row['ADMINID'];
        $admin = new LFAdmin;
        $admin->get_admininfo_from_db($adminid);
        if($admin->isAdmin())
          $admin->set_lfadmin($admin);
        /* item should have an admin or user or both associated to it. If not, not valid item */
        if(!$user->isUser() && !$admin->isAdmin())
        {
          parent::set_isItem(false);
          return;
        }

      }
      catch (PDOException $e)
      {
        parent::set_isItem(false);  //  sql error
        return;
      }

      parent::set_isItem(true);
    }

    public function construst__founditem($_itemID, $_itemname, $_timestamp, $_desc, $_color, $_brand, $_identifier, $_founditemid, $_placefound, $_lfadminid, $_lfuserid){

      $founditem = new FoundItem;
      $founditem->set_itemID($_itemID);
      $founditem->set_name($_itemname);
      $founditem->set_timestamp($_timestamp);
      $founditem->set_description($_desc);
      $founditem->set_color($_color);
      $founditem->set_brand($_brand);
      $founditem->set_identifier($_identifier);
      $founditem->set_founditemid($_founditemid);
      $founditem->set_placelost($_placelost);
      //  get user associated with the item
      $user = new LFUser;
      $user->get_userinfo_from_db($_lfuserid);
      if($user->isUser())
        $founditem->set_lfuser($user);
      else
        $founditem->set_isItem(false);

      return $founditem;
    }

    public function store_in_db()
    {
      if(!parent::isItem())
        return false;

      try {
        $conn = dbconn();
        $conn->beginTransaction();
          $stmt = $conn->prepare("INSERT INTO item (ITEMNAME, ITEMBRAND, ITEMCOLOR, IDENTIFIER, ITEMDESCRIPTION) VALUES (:name, :brand, :color, :identifier, :description)");
          $stmt->bindParam(":name", parent::get_name());
          $stmt->bindParam(":brand", parent::get_brand());
          $stmt->bindParam(":color", parent::get_color());
          $stmt->bindParam(":identifier", parent::get_identifier());
          $stmt->bindParam(":description", parent::get_description());

          $stmt->execute();

          $last_item_id = $conn->lastInsertId();

          $userid = NULL;
          $adminid = NULL;

          $_lfuser = $this->get_lfuser(); //  user object associated with found item
          $_lfadmin = $this->get_lfadmin(); //admin object associated with found item

          if($_lfuser->isUser())
            $userid = $_lfuser->get_lfuserid();;

          if($_lfadmin->isAdmin())
            $adminid = $_lfadmin->get_lfadmin();

          if(!isset($userid) && !isset($adminid)) //  NO user or admin associated to item
            return false;

          $stmt = $conn->prepare("INSERT INTO founditem (ITEMID, ADMINID, LFUSERID, ITEMIMAGE, PLACEFOUND) VALUES (:itemid, :adminid, :userid, :image, :placefound)");
          $stmt->bindParam(":itemid", $last_item_id);
          $stmt->bindParam(":adminid", $adminid);
          $stmt->bindParam(":userid", $userid);
          $stmt->bindParam(":image", $this->itemImage);
          $stmt->bindParam(":placefound", $this->placefound);
          $stmt->execute();
        $conn->commit();
      }
      catch (PDOException $e) {
        return false;
      }

      return true;
    }

    public function get_json()
    {
      $found->itemID = parent::get_id();   // read only
      $found->itemname = parent::get_name();
      $found->timestamp = parent::get_timestamp(); // read only
      $found->itemdesc = parent::get_description();
      $found->itemcolor = parent::get_color();
      $found->itembrand = parent::get_brand(); // nullable
      $found->identifier = parent::get_identifier(); //nullabe
      $found->placefound = $this->get_placefound();

      $the_json = json_encode($found);
      return $the_json;
    }

    public function set_founditemid($_founditemid){
      $this->founditemid = $_founditemid;
    }
    public function set_itemImg($_image){
      $this->itemImage = $_image;
    }
    public function set_placefound($_placefound){
      $this->placefound = $_placefound;
    }
    public function set_lfuser($_lfuser){
      $this->lfuser = $_lfuser;
    }
    public function set_lfadmin($_lfadmin){
      $this->lfadmin = $_lfadmin;
    }
    public function get_placefound(){
      return $this->placefound;
    }
    public function get_founditemid(){
      return $this->founditemid;
    }
    public function get_itemImg(){
      return $this->itemImage;
    }
    public function get_lfuser(){
      return $this->lfuser;
    }
    public function get_lfadmin(){
      return $this->lfadmin;
    }
  }
?>
