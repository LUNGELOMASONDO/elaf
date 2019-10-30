<?php
/**
 * @author Masondo
 *
 * This script contains the LFUser class
 *
 */
	class LFUser
	{
		private $lfuserid; //	auto incremented id
		private $userid;
		private $seconduserid;
		private $name;
		private $email;
		private $phone;

		private $isUser;	//	readonly

		public function __construct() /* get user info from the database */
		{
			$this->isUser = false;
		}

		public function get_user_from_db($_userid, $_pass)
		{
			$userid = $_userid;
			$pass = $_pass;

			try
			{
				$conn = dbconn();
				$stmt = $conn->prepare("SELECT * FROM lfuser WHERE userid=:_userid AND userpassword=:_pass");
				$stmt->execute(array("_userid" => $userid, "_pass" => $pass));
				$row = $stmt->fetch();

				if($row == null) // invalid admin info
				{
					$this->isUser = false;
					return;
				}

				$this->set_lfuserid($row['LFUSERID']);
				$this->set_userid($row['USERID']);
				$this->set_secondID($row['SECONDARYID']);
				$this->set_name($row['USERNAME']);
				$this->set_email($row['email']);
				$this->set_phone($row['phone']);
			}
			catch (PDOException $e)
			{
				$this->isUser = false;
				return;
			}

			$this->isUser = true;
		}

		public function get_associated_lostitems()
		{
			$items_arr = NULL;
			try
			{
				$conn = dbconn();
				$stmt = $conn->prepare("SELECT item.*, lostitem.LOSTITEMID, lostitem.PLACELOST, lostitem.TIMELOST FROM item, lostitem, lfuser WHERE lostitem.ITEMID=item.ITEMID AND lfuser.LFUSERID=lostitem.LFUSERID AND lostitem.LFUSERID=:_userid");
				$stmt->execute(array(":_userid" => $this->get_lfuserid()));
				$row = $stmt->fetchAll();

				if($row == null)
				{
					return NULL;
				}
				else
				{
					$itemcount = 0;
					foreach($row as $data)
					{
						$lostitem = new LostItem;

						$lostitem->set_itemID($data['ITEMID']);
						$lostitem->set_name($data['ITEMNAME']);
						$lostitem->set_timestamp($data['ITEMTIME']);
						$lostitem->set_description($data['ITEMDESCRIPTION']);
						$lostitem->set_brand($data['ITEMBRAND']);
						$lostitem->set_color($data['ITEMCOLOR']);
						$lostitem->set_identifier($data['IDENTIFIER']);
						$lostitem->set_lostitem_id($data['LOSTITEMID']);
						$lostitem->set_placelost($data['PLACELOST']);
						$lostitem->set_timelost($data['TIMELOST']);

						$items_arr[$itemcount] = $lostitem;

						$itemcount ++;
					}

					if($itemcount == 0)
						return NULL;
				}
			}
			catch (PDOException $e)
			{
				return NULL;
			}

			return $items_arr;
		}

		public function get_userinfo_from_db($_userid)
		{
			/*
			 ** 	get user info without password
			*/
			$userid = $_userid;

			try
			{
				$conn = dbconn();
				$stmt = $conn->prepare("SELECT * FROM lfuser WHERE LFUSERID=:_userid");
				$stmt->execute(array(":_userid" => $userid));
				$row = $stmt->fetch();

				if($row == null)
				{
					$this->isUser = false;
					return;
				}

				$this->set_lfuserid($row['LFUSERID']);
				$this->set_userid($row['USERID']);
				$this->set_secondID($row['SECONDARYID']);
				$this->set_name($row['USERNAME']);
				$this->set_email($row['email']);
				$this->set_phone($row['phone']);
			}
			catch (PDOException $e)
			{
				$this->isUser = false;
				return;
			}

			$this->isUser = true;
		}

		public function construct__lfuser($_lfuserid, $_userid, $_seconduserid, $_idnumber, $_name, $_email, $_phone)
		{
			$user = new LFUser;
			$user->lfuserid = $_lfuserid;
			$user->userid = $_userid;
			$user->seconduserid = $_seconduserid;
			$user->name = $_name;
			$user->email = $_email;
			$user->phone = $_phone;

			$user->isUser = true;
			return $user;
		}

		public function get_lfuserid()
		{
			return $this->lfuserid;
		}

		public function get_userid()
		{
			return $this->userid;
		}

		public function get_secondID()
		{
			return $this->seconduserid;
		}

		public function get_phone()
		{
			return $this->phone;
		}

		public function get_name()
		{
			return $this->name;
		}

		public function get_email()
		{
			return $this->email;
		}

		public function set_lfuserid($_lfuserid)
		{
			$this->lfuserid = $_lfuserid;
		}

		public function set_userid($_userid)
		{
			$this->userid = $_userid;
		}

		public function set_secondID($_seconduserid)
		{
			$this->seconduserid = $_seconduserid;
		}

		public function set_phone($_phone)
		{
			$this->phone = $_phone;
		}

		public function set_name($_name)
		{
			$this->name = $_name;
		}

		public function set_email($_email)
		{
			$this->email = $_email;
		}

		public function isUser()
		{
			return $this->isUser;
		}
		/*
		public function isVerified()
		{
			try {
				$conn = dbconn();
				$conn = dbconn();
				$stmt = $conn->prepare("SELECT * FROM verifieduser WHERE LFUSERID=:_userid");
				$stmt->execute(array(":_userid" => $userid));
				$row = $stmt->fetch();
			} catch (PDOException $e) {

			}

		}
		*/
	}
?>
