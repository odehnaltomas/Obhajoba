<?php
/**
 * Created by PhpStorm.
 * User: Tomáš
 * Date: 6. 11. 2015
 * Time: 22:29
 */

namespace App\Model;


use Nette\Object;
use Nette;

class BaseManager extends Object
{
    protected $database;

    public function __construct(Nette\Database\Context $database){
        $this->database = $database;
    }
}