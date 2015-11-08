<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Database\Context;


class HomepagePresenter extends BasePresenter
{

	private $database;

	public function __construct(Context $database){
		$this->database = $database;
	}

	public function renderDefault(){
		$this->template->vypis = $this->database->table('user')->where('id', 1)->fetch();
	}

}
