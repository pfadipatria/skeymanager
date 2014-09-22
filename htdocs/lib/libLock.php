<?php

function showLockListPage(){

   $locks = new \SKeyManager\Repository\LockRepository;

   $view = array(
        'header' => getHeader('locks', 'list'),
        'body' => getLockList($locks),
        'footer' => getFooter()
    );

    echo render($view, 'layout');
}

function getLockList($locks = null){

    $view = array(
        'locks' => $locks->getAll()
    );

    return render($view, 'lock_list');
}

function showLockDetailsPage($lockId = '0'){

   $lock = new \SKeyManager\Entity\Lock($lockId);
   $lock->load();

   $view = array(
      'header' => getHeader('lock', $lockId),
      'body' => getLockDetails($lock),
      'footer' => getFooter()
   );

   echo render($view, 'layout');
}

function getLockDetails($lock = null){

   $denies = new \SKeyManager\Repository\PermissionRepository;
   $allowed = new \SKeyManager\Repository\PermissionRepository;

   $lockView = array(
     'lock' => $lock
   );

   $denialView = array(
      'title' => _('Denials for Keys'),
      'keys' => $denies->getDeniesByLock($lock->getId())
   );

   $permissionView = array(
      'title' => _('Allowed on Keys'),
      'keys' => $allowed->getAllowsByLock($lock->getId())
   );

   $view = array(
      'lock' => render($lockView, 'lock_entry'),
      'denials' => render($denialView, 'lock_keylist'),
      'allowed' => render($permissionView, 'lock_keylist')
   );

   return render($view, 'lock_layout');
}

function showLockEditPage($lockId = '0'){
   $view = array(
      'header' => getHeader('lock', ''),
      'footer' => getFooter()
   );

   if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
      $message = '';
      $id = null;
      if (array_key_exists('id',$_POST)) {
         $id = $_POST['id'];
      }
      $lock = new SKeyManager\Entity\Lock($id);
      try {
         if (array_key_exists('id',$_POST)) {
            $key->load();
         }
         $lock->setElNumber($_POST['elnumber']);
         $lock->setCode($_POST['code']);
         $lock->setStatusId($_POST['statusid']);
         $lock->setType($_POST['type']);
         $lock->setColorId($_POST['colorid']);
         $lock->setComment($_POST['comment']);
         $lock->setHolderId($_POST['holderid']);
         $result = $lock->save();
      } catch (Exception $exception) {
         $result = false;
         $message = ' ('.$exception->getMessage().')';
      }

      if($result){
         $view['success'] = _('OK! Der Eintrag wurde aktualisiert.');
         $newLock = new \SKeyManager\Entity\Key($lock->getId());
         $newLock->load();
         $view['body'] = getKeyDetails($newLock);
      } else {
         $view['danger'] = _('Fehler! Der Eintrag konnte nicht aktualisiert werden.'.$message);
         $view['body'] = getLockEdit($lock);
      }
   } else {
      if ($lockId === '0') {
         $view['body'] = getLockEdit();
      } else {
         $key = new \SKeyManager\Entity\Lock($lockId);
         $key->load();
         $view['body'] = getLockEdit($key);
      }
   }

   echo render($view, 'layout');
}

function getLockEdit($lock = null){
   $hasData = false;
   if ($lock !== null) {
      $hasData = true;
      $view['title'] = $lock->getFullName();
   } else {
      $lock = new \SKeyManager\Entity\Lock();
      $view['title'] = _('Add a new Lock');
   }

   $view['hasData'] = $hasData;
   $view['key'] = $lock;

   return render($view, 'lock_edit');
}

//////////////////////////////////////////////////////////

function oldshowLockDetailsPage($lockId = '0'){
   echo getHeader('locks', '');
   echo '<p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p>';
   printLockDetails($lockId);
   echo '<br><a href="/locks/edit/' . $lockId . '">Bearbeiten</a><br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><hr><h3>Sperren:</h3><br>';
   printLockDenials($lockId);
   echo '<br><h3>Berechtigungen auf Schl&uumlssel:</h3><br>';
   printLockPermissions($lockId);
   echo '<br><p onclick="goBack()" style="cursor: pointer">Zur&uuml;ck</p><br>';
   echo getFooter();
}

function oldshowLockEditPage($lockId = '0'){
   echo getHeader('locks', '');
   echo '<br>';
   printLockEdit($lockId);
   echo '<br><br><hr><br><h3>Sperren:</h3><br>';
   printLockDenials($lockId);
   echo '<br>';
   echo getFooter();
}

function oldprintLockDetails($lockId = '0'){

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
      } else if ( $row['hasbatteries'] == '0' ) {
         $bat = 'Nein';
      } else {
         $bat = 'n/a';
      }
      echo '<h2>' . $row['heim'] . ' ' . $row['lockname'] . ' - SC ' . $row['sc'] . '</h2>
         <table cellpadding="5" cellspacing="0">
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">id</td><td>' . $row['lockid'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">SC</td><td>' . $row['sc'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Heim</td><td>' . $row['heim'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Bezeichnung</td><td>' . $row['lockname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Nummer</td><td>' . $row['number'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Status</td><td>' . $row['statusname'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Elektronik</td><td>' . $bat . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Typ</td><td>' . $row['type'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Position</td><td>' . $row['position'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Kommentar</td><td>' . $row['comment'] . '</td></tr>
         <tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'"><td align="right">Letztes Update</td><td>' . $row['lastupdate'] . '</td></tr>
         </table>';
   }
}

function oldprintLockEdit($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "

      SELECT
         doorlock.id AS lockid,
         number,
         doorlock.name AS lockname,
         sc,
         place,
         status,
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
         $bat = ' checked ';
      } else {
         $bat = '';
      }

         $placeChoose = '<select name="place" size="1">';
         $pquery = 'SELECT id, name FROM doorplace';
         $pcon = openDb();
         $pdbresult = queryDb($pcon, $pquery);
	      while ($prow = mysqli_fetch_array($pdbresult)){
            $placeChoose .= '<option value="' . $prow['id'] . '"';
            if ($prow['id'] == $row['place']){
               $placeChoose .= ' selected ';
            }
            $placeChoose .= '>' . $prow['name'] . '</option>';
         }
         $placeChoose .= '</select>';

         $statusChoose = '<select name="status" size="1">';
         $squery = 'SELECT id, name FROM doorlockstatus';
         $scon = openDb();
         $sdbresult = queryDb($scon, $squery);
	      while ($srow = mysqli_fetch_array($sdbresult)){
            $statusChoose .= '<option value="' . $srow['id'] . '"';
            if ($srow['id'] == $row['status']){
               $statusChoose .= ' selected ';
            }
            $statusChoose .= '>' . $srow['name'] . '</option>';
         }
         $statusChoose .= '</select>';

      echo '<form action="/keys/show/' . $lockId . '" method="post">
         <tr><td align="right">id</td><td>' . $row['lockid'] . '</td></tr>
         <tr><td align="right">SC</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['sc'] . '"></td></tr>
         <tr><td align="right">Heim</td><td>' . $placeChoose . '</td></tr>
         <tr><td align="right">Bezeichnung</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['lockname'] . '"></td></tr>
         <tr><td align="right">Nummer</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['number'] . '"></td></tr>
         <tr><td align="right">Status</td><td>' . $statusChoose . '</td></tr>
         <tr><td align="right">Elektronik</td><td><input type="checkbox" name="zutat" value="communication" ' . $bat . '></td></tr>
         <tr><td align="right">Typ</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['type'] . '"></td></tr>
         <tr><td align="right">Position</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['position'] . '"></td></tr>
         <tr><td align="right">Kommentar</td><td><input name="comment" type="text" size="30" maxlength="30" value="' . $row['comment'] . '"></td></tr>
         <tr><td align="right">Letztes Update</td><td>' . $row['lastupdate'] . '</td></tr>
         <tr></tr>
         <tr><td><input type="button" name="back" value=" Abbrechen " onclick="goBack()"></td><td><input type="submit" value=" Speichern "></td></form>';
   }

   echo '</table>';
}

function printLockPermissions($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorkey_opens_lock.key AS keyid,
         doorkey.code AS keycode,
         doorperson.name AS personname
         FROM doorkey_opens_lock
         LEFT JOIN doorkey ON (doorkey_opens_lock.key = doorkey.id )
         LEFT JOIN doorperson ON (doorkey.owner = doorperson.id)
         WHERE doorkey_opens_lock.lock = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['keyid'] . '\';" style="cursor: zoom-in">
               <td>MC ' . $row['keycode'] . '</td>
               <td>' . $row['personname'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

function printLockDenials($lockId = '0'){
   echo '<table cellpadding="5" cellspacing="0">';

   $query = "
      SELECT
         doorlock_locks_key.key AS keyid,
         doorkey.code AS keycode,
         doorperson.name AS personname
         FROM doorlock_locks_key
         LEFT JOIN doorkey ON (doorlock_locks_key.key = doorkey.id )
         LEFT JOIN doorperson ON (doorkey.owner = doorperson.id)
         WHERE doorlock_locks_key.lock = '" . $lockId . "'
      ";
   // error_log($query);
   $con = openDb();
   $dbresult = queryDb($con, $query);
	while ($row = mysqli_fetch_array($dbresult)){
      echo '<tr onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\'normal\'" onclick="document.location = \'/keys/show/' . $row['keyid'] . '\';" style="cursor: zoom-in">
               <td>MC ' . $row['keycode'] . '</td>
               <td>' . $row['personname'] . '</td>
            </tr>
         ';
   }

   echo '</table>';
}

?>
