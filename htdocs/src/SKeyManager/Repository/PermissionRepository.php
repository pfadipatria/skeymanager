<?php

namespace SKeyManager\Repository;

class PermissionRepository extends AbstractRepository {

    function __construct() {
        $this->select = '
            SELECT
               id
        ';

        $this->from = '
            FROM permission
        ';

        $this->where = '
        ';

        $this->order = '
        ';
    }

    function getByLockId($id) {
        return $this->query('WHERE lock = '.$id, 'SKeyManager\Entity\Permission');
    }

    function getByKeyId($id) {
        return $this->query('WHERE key = '.$id, 'SKeyManager\Entity\Permission');
    }

    function getAllowedByKeyId($id) {
        return $this->query('WHERE `key` = '.$id.' AND `allows` = 1', 'SKeyManager\Entity\Permission');
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Permission');
    }
}
