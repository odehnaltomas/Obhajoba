<?php
/**
 * Created by PhpStorm.
 * User: Tom�
 * Date: 6. 11. 2015
 * Time: 22:29
 */
//TODO: komentare
namespace App\Model;


use Nette\Object;
use Nette;

/**
 * Základní model pro všechny ostatní modely.
 * @package App\Model
 */
abstract class BaseManager extends Object
{
    /**
     * Instance pro práci s databází.
     * @var Nette\Database\Context */
    protected $database;

    /**
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database){
        $this->database = $database;
    }
}