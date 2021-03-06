<?php

/**
 * @SuppressWarnings(PHPMD.Design.NpathComplexity)
 */
function openDb($dbHost = 'localhost', $dbUsername = 'skm', $dbPassword = 'skm', $dbName = 'skm')
{	
   global $config;

   $dbHost = isset($config['dbhost']) ? $config['dbhost'] : $dbHost ;
   $dbName = isset($config['dbname']) ? $config['dbname'] : $dbName ;
   $dbUsername = isset($config['dbuser']) ? $config['dbuser'] : $dbUsername ;
   $dbPassword = isset($config['dbpass']) ? $config['dbpass'] : $dbPassword ;

	//@TODO use db credentials as parameter and remove global
	// global $dbHost,$dbUsername,$dbPassword,$dbName;

	//@TODO check if db is already open and return that resource
	//@TODO use global db connection

	// Create connection
	$con=mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

	// Check connection
	if (mysqli_connect_errno($con))
	  {
	  throw new Exception('Failed to connect to MySQL: '. mysqli_connect_error());
	  }

	return $con;
}

// query DB
function queryDb($con, $query)
{
	return mysqli_query($con,$query);
}

// Close DB
function closeDb($con)
{
	mysqli_close($con);

	//TODO We could do the following if we where paranoid, but then we would also have to check in wich more variables these contents are saved
	// unset($user, $pass, $server);  // Flush from memory.
}

function queryValue($query) {
   $result = '';

   $con = openDb();
   $dbresult = queryDb($con, $query);
	$row = mysqli_fetch_row($dbresult);

   $result = $row[0];
   
   return $result;
}

function getKeyFromDb(){
   $result = array();

/*
   $query = 'select key.id,elnumber,code,keycolor.name AS colorname,keytatus.name AS statusname,keymech.bezeichung AS bezeichung,comment from key JOIN keycolor ON (key.color = keycolor.id ) JOIN keytatus ON (key.status = keytatus.id) JOIN keymech ON (key.mechnumber = keymech.id ) ';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      $result .= $row;
   }
*/

  # $query = 'select key.id,elnumber,code,keycolor.name AS colorname,keytatus.name AS statusname,keymech.bezeichung AS bezeichung,comment from key JOIN keycolor ON (key.color = keycolor.id ) JOIN keytatus ON (key.status = keytatus.id) JOIN keymech ON (key.mechnumber = keymech.id ) LIMIT 10';
   $query = 'select * from key limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
/*	while ($row = mysqli_fetch_array($dbresult)){
      $result[] = $row;
   }   
*/
   $result = mysqli_fetch_array($dbresult);
   return $result;

}

function getNextId($table = ''){
    $db=openDb();
    $result = queryDb($db, 'SELECT Auto_increment FROM information_schema.tables WHERE table_name = \'' . $table . '\'');
    $row = mysqli_fetch_array($result);
    return $row['0'];
}

?>
