<?php

  class LostItem extends Item
  {
    private $lostitemid;
    private $placelost;
    private $timelost;

    private $lfuser; // stores lfuser instance object

    public function __construct()
    {
       parent::__construct();
    }

    public function get_item_from_db($_itemid)
    {
      try
			{
				$conn = dbconn();
				$stmt = $conn->prepare("SELECT * FROM item, lostitem WHERE lostitem.ITEMID=item.ITEMID AND lostitem.LOSTITEMID=:_itemid;");
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
        $this->set_lostitem_id($row['LOSTITEMID']);
        $this->set_placelost($row['PLACELOST']);
        $this->set_timelost($row['TIMELOST']);
        /* user info */
        $userid = $row['USERID'];
        $user = new LFUser;
        $user->get_userinfo_from_db($userid);
        if($user->isUser()){
          $this->set_lfuser($user);
        }
        else{
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

    public function construst__lostitem($_itemID, $_itemname, $_timestamp, $_desc, $_color, $_brand, $_identifier, $_lostitemid, $_placelost, $_timelost, $_lfuserid)
    {
      $lostitem = new LostItem;
      $lostitem->set_itemID($_itemID);
      $lostitem->set_name($_itemname);
      $lostitem->set_timestamp($_timestamp);
      $lostitem->set_description($_desc);
      $lostitem->set_color($_color);
      $lostitem->set_brand($_brand);
      $lostitem->set_identifier($_identifier);
      $lostitem->set_lostitem_id($_lostitemid);
      $lostitem->set_placelost($_placelost);
      $lostitem->set_timelost($_timelost);

      $user = new LFUser;
      $user->get_userinfo_from_db($_lfuserid);
      if($user->isUser())
        $this->set_lfuser($user);
      else
        parent::set_isItem(false);

      return $lostitem;
    }

    public function store_in_db()
    {
      if(!parent::isItem())
        return false;

      $name = parent::get_name();
      $brand = parent::get_brand();
      $color = parent::get_color();
      $identifier = parent::get_identifier();
      $description = parent::get_description();
      $place = $this->get_placelost();
      $time = $this->get_timelost();

      try {
        $conn = dbconn();
        $conn->beginTransaction();
          $stmt = $conn->prepare("INSERT INTO item (ITEMNAME, ITEMBRAND, ITEMCOLOR, IDENTIFIER, ITEMDESCRIPTION) VALUES (:name, :brand, :color, :identifier, :description)");
          $stmt->bindParam(":name", $name);
          $stmt->bindParam(":brand", $brand);
          $stmt->bindParam(":color", $color);
          $stmt->bindParam(":identifier", $identifier);
          $stmt->bindParam(":description", $description);
          $stmt->execute();

          $last_item_id = $conn->lastInsertId();

          $userid = NULL;

          $_lfuser = $this->get_lfuser(); //  user object associated with found item

          if($_lfuser->isUser())
            $userid = $_lfuser->get_lfuserid();;

          if(!isset($userid)) //  NO user associated to item
            return false;

          $stmt = $conn->prepare("INSERT INTO lostitem (ITEMID, LFUSERID, TIMELOST, PLACELOST) VALUES (:itemid, :userid, :timelost, :placelost)");
          $stmt->bindParam(":itemid", $last_item_id);
          $stmt->bindParam(":userid", $userid);
          $stmt->bindParam(":placelost", $place);
          $stmt->bindParam(":timelost", $time);
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
      $lost->itemID = parent::get_id();   // read only
      $lost->itemname = parent::get_name();
      $lost->timestamp = parent::get_timestamp(); // read only
      $lost->itemdesc = parent::get_description();
      $lost->itemcolor = parent::get_color();
      $lost->itembrand = parent::get_brand(); // nullable
      $lost->identifier = parent::get_identifier(); //nullabe
      $lost->placelost = $this->get_placelost();
      $lost->timelost = $this->get_timelost();

      $the_json = json_encode($lost);
      return $the_json;
    }

    public function get_lostitem_id(){
      return (int)$this->lostitemid;
    }

    public function get_placelost(){
      return $this->placelost;
    }

    public function get_timelost(){
      return $this->timelost;
    }

    public function get_lfuser(){
      return $this->lfuser;
    }

    public function set_lostitem_id($_lostitem_id){
      $this->lostitemid = (int) $_lostitem_id;
    }

    public function set_placelost($_place){
      $this->placelost = $_place;
    }

    public function set_timelost($_time){
      $this->timelost = $_time;
    }

    public function set_lfuser($_lfuser){
      $this->lfuser = $_lfuser;
    }
  }
?>
