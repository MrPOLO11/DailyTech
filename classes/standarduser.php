<?php

/**
 * Class StandardUser
 *
 * The following class determines the attributes of a standard user
 * whom is not an admin. The privileges standard user has is editing
 * their own posts and removing their own posts
 */
class StandardUser extends User
{
        //fields
        private $_edit;
        private $_remove;

    /**
     * The following function determines if user can edit
     * @return mixed
     */
    public function getEdit()
    {
        return $this->_edit;
    }

    /**
     * The following function determines if user can edit
     * @param mixed $edit
     */
    public function setEdit($edit)
    {
        $this->_edit = $edit;
    }

    /**
     * The following function determines if user can remove
     * @return mixed
     */
    public function getRemove()
    {
        return $this->_remove;
    }

    /**
     * The following function determines if user can remove
     * @param mixed $remove
     */
    public function setRemove($remove)
    {
        $this->_remove = $remove;
    }
}