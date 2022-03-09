<?php

namespace App\Form\Model;

use App\Entity\Campus;
use Doctrine\DBAL\Types\BooleanType;

class SearchOuting
{
    public $campus;
    public $search;
    public $dateStarted;
    public $dateEnded;
    public $isOrganized;
    public $isRegistered;
    public $isNotRegistered;
    public $isOver;


}
