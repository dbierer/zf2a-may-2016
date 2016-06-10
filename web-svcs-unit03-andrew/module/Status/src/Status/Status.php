<?php

namespace Status;

class Status
{
    protected $id;
    
    protected $text;
    
    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return the $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param field_type $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
    
}
