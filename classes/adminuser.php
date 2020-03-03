<?php


class AdminUser extends User
{
    private $_removeAll;

    /**
     * @return mixed
     */
    public function getRemoveAll()
    {
        return $this->_removeAll;
    }

    /**
     * @param mixed $removeAll
     */
    public function setRemoveAll($removeAll)
    {
        $this->_removeAll = $removeAll;
    }
}