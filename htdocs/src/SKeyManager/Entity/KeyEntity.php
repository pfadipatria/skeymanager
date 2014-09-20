<?php

namespace SKeyManager\Entity;

class KeyEntity extends AbstractEntity {

    protected $locationPattern = '/key/%s';

    function __construct($id) {
        $this->id = $id;

        $this->select = '
            SELECT
               doorkey.id,
               elnumber,
               code,
               type,
               doorkeycolor.name AS colorname,
               doorkeycolor.id AS colorid,
               doorkeystatus.name AS statusname,
               doorkeystatus.id AS statusid,
               doorkeymech.bezeichung AS bezeichung,
               doorperson.name AS owner,
               doorperson.id AS ownerid,
               doorperson.uid AS owneruid,
               doorkey.comment AS keycomment,
               communication,
               doorkey.lastupdate AS keyupdate
        ';

        $this->from = '
            FROM doorkey
            LEFT JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id )
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
            LEFT JOIN doorkeymech ON (doorkey.mech = doorkeymech.id )
            LEFT JOIN doorperson ON (doorkey.owner = doorperson.id )
        ';

        $this->order = '
            ORDER BY code
        ';
    }

   function getPermissions() {
      $return = '';
      $this->select = 'SELECT
         doorkey_opens_lock.lock AS lockid,
         doorlock.sc AS locksc,
         doorplace.name AS heim,
         doorlock.name AS lockname
         ';
      $this->from = '
         FROM doorkey_opens_lock
         LEFT JOIN doorlock ON (doorkey_opens_lock.lock = doorlock.id )
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         ';
      $rows = parent::getAll();

      return $return;
   }

   function getName() {
      $return = '';
      $this->select = 'SELECT code, doorperson.name AS owner';
      $row = parent::getAll();
      $return .= 'MC '.$row['code'];
      if(!empty($row['owner'])) $return .= ' - '.$row['owner'];

      return $return;
   }

    protected function query($where = ' WHERE true ') {
      $where .= ' AND doorkey.id = '.$this->id;
      error_log($this->select.$this->from.$where.$this->order);
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$where.$this->order);
      $row = array();
      $locations = array();
      $row = mysqli_fetch_assoc($dbresult);
      return $row;
    }
}
