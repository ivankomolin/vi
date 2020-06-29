<?php

use App\Container;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$entityManager = Container::create()
	->get('doctrine_entity_manager');

return ConsoleRunner::createHelperSet($entityManager);
