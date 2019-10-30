<?php
  abstract class Item
  {
    private $itemID;   // read only
    private $itemname;
    private $timestamp; // read only
    private $itemdesc;
    private $itemcolor;
    private $itembrand; // nullable
    private $identifier; //nullabe

    private $isItem;

    public function __construct()
    {
      $this->set_isItem(false);
    }

    public abstract function get_item_from_db($_itemid);

    public abstract function store_in_db();

    public abstract function get_json();

    public function set_isItem($_is_item){
      $this->isItem = $_is_item;
    }

    public function set_timestamp($_timestamp){
      $this->timestamp = $_timestamp;
    }

    public function set_itemID($_item_id){
      $this->itemID = (int) $_item_id;
    }

    public function set_name($_itemname){
      $this->itemname = $_itemname;
    }

    public function set_description($_itemdescription){
      $this->itemdesc = $_itemdescription;
    }

    public function set_color($_itemcolor){
      $this->itemcolor = $_itemcolor;
    }

    public function set_brand($_itembrand){
      $this->itembrand = $_itembrand;
    }

    public function set_identifier($_itemidentifier){
      $this->identifier = $_itemidentifier;
    }

    public function get_name(){
      return $this->itemname;
    }

    public function get_timestamp(){
      return $this->timestamp;
    }

    public function get_description(){
      return $this->itemdesc;
    }

    public function get_color(){
      return $this->itemcolor;
    }

    public function get_brand(){
      return $this->itembrand;
    }

    public function get_identifier(){
      return $this->identifier;
    }

    public function get_id(){
      return $this->itemID;
    }

    public function isItem(){
      return $this->isItem;
    }
  }
?>
