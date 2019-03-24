<?php
namespace Modules\Www\Config;

use Phalcon\Mvc\Router\Group;

class WwwRouters extends Group
{
	public function __construct($lang = 'ru')
    {
		$this->setPaths([
			'module' => 'www',
			'namespace' => 'Modules\Www\Controllers'
		]);

		$this->add('/', ['controller' => 'index', 'action' => 'index', 'lang' => $lang]);
		$this->add('/:controller', ['controller' => 1, 'action' => 'index', 'lang' => $lang]);
		$this->add('/:controller/:action', ['controller' => 1, 'action' => 2, 'lang' => $lang]);
		$this->add('/:controller/:action/:params', ['controller' => 1, 'action' => 2, 'params' => 3, 'lang' => $lang]);
	}
}