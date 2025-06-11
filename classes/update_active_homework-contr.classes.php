<?php

class UpdateActiveHomeworkContr extends UpdateActiveHomework
{

    private $id;
    private $statusVal;

    public function __construct($id, $statusVal)
    {
        $this->id = $id;
        $this->statusVal = $statusVal;
    }

    public function updateActiveHomeworkDb()
    {

        $this->setHomeworkActive($this->id, $this->statusVal);
    }
}
