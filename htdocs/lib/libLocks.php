<?php

function showLocksPage(){
   switch(getMenuPath('2')){
      case 'list':
         showLockListPage();
         break;
      case 'show':
         showLockDetailsPage(getMenuPath('3'));
         break;
      default:
         showLockListPage();
   }
}

function showLockListPage(){
   echo getHeader('locks', 'list');
   printLockList();
   echo getFooter();
}

function printLockList(){

   echo '<table cellpadding="5" cellspacing="0">';
   echo '<tr>
      <td>id</td>
      <td>SC</td>
      <td>Heim</td>
      <td>Bezeichnung</td>
      <td>Status</td>
      <td>Kommentar</td>
      </tr>';
   $query = '
      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         doorplace.name AS heim,
         doorlockstatus.name AS statusname
         FROM doorlock
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
         ORDER BY sc
         ';
   // $query = 'select * from doorkey limit 10';
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/locks/show/' . $row['lockid'] . '\';" style="cursor: zoom-in";>
         <td>' . $row['lockid'] . '</td>
         <td>' . $row['sc'] . '</td>
         <td>' . $row['heim'] . '</td>
         <td>' . $row['lockname'] . ' (' . $row['number'] . ')</td>
         <td>' . $row['statusname'] . '</td>
         <td>' . $row['comment'] . '</td>
         </tr>';
   }

   echo '</table>';
}

function showLockDetailsPage($lockId = '0'){
   echo getHeader('locks', '');
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   printLockDetails($lockId);
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   echo getFooter();
}

function printLockDetails($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "

      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         doorplace.name AS heim,
         doorlockstatus.name AS statusname,
         hasbatteries,
         lastupdate,
         type,
         position,
         comment
         FROM doorlock
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
         WHERE doorlock.id = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      if ( $row['hasbatteries'] == '1' ){
         $bat = 'Ja';
      } else {
         $bat = 'Nein';
      }
      echo '
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">id</td><td>' . $row['lockid'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">SC</td><td>' . $row['sc'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Heim</td><td><b>' . $row['heim'] . '</b></td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Bezeichnung</td><td>' . $row['name'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Nummer</td><td>' . $row['number'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Status</td><td>' . $row['statusname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Elektronik</td><td>' . $bat . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Typ</td><td>' . $row['type'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Position</td><td>' . $row['position'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommentar</td><td>' . $row['comment'] . '</td></tr>
         ';
   }

   echo '</table>';
}


?>
