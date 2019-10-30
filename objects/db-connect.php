<?php
	function dbconn()
	{
		$db = "elaf";
		$servername = "localhost";
		$username = "root";
		$password = "";

		try
		{
    		$conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    		// set the PDO error mode to exception
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
    		//echo "Connected successfully";
    	}
		catch(PDOException $e)
    	{
    		$message = "error";
			die($message);
    		echo "Connection failed: " . $e->getMessage();
    	}
	}
?>
