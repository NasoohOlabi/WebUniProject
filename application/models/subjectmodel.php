<?php
require_once 'basemodel.php';


class SubjectModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "subject");
    }
}
