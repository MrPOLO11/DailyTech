<?php


class StandardUser extends User
{
        private $_edit;
        private $_remove;

    /**
     * @return mixed
     */
    public function getEdit()
    {
        return $this->_edit;
    }

    /**
     * @param mixed $edit
     */
    public function setEdit($edit)
    {
        $this->_edit = $edit;
    }

    /**
     * @return mixed
     */
    public function getRemove()
    {
        return $this->_remove;
    }

    /**
     * @param mixed $remove
     */
    public function setRemove($remove)
    {
        $this->_remove = $remove;
    }
}