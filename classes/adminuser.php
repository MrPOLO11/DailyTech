<?php

/**
 * Class AdminUser
 *
 * The following class defines the attributes of an admin user
 */
class AdminUser extends User
{
    private $_removeAll;

    /**
     * The following function allows for removing all posts
     * @return mixed
     */
    public function getRemoveAll()
    {
        return $this->_removeAll;
    }

    /**
     * The following function determines if user can remove all posts
     * @param mixed $removeAll
     */
    public function setRemoveAll($removeAll)
    {
        $this->_removeAll = $removeAll;
    }
}