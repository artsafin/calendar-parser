<?php
namespace Parser;

spl_autoload_register(function ($className)
{
	$className = (string)str_replace('\\', DIRECTORY_SEPARATOR, $className);
	include_once($className . '.php');
});

function log()
{
	foreach (func_get_args() as $arg)
		if (is_array($arg))
			var_dump($arg);
		else
			echo $arg;

	echo PHP_EOL, '<br>';
}

$notes = $_GET['s'];

$now = new \DateTime('now');

$result = array();

foreach (explode('-----', $notes) as $input)
{
	$tokenizer = new Tokenizer();
	$tokens = $tokenizer->tokenize($input);

	$f = new TokenMapperFactory($tokens);

	$period = $f->createPeriod();
	$date = $f->createDateTime($now);
	$duration = $f->createDuration($date->getDateTime());
	$message = $f->createMessage();
	$nodeType = $f->createNodeType();

	$result[] = array(
		'dateCreate' => $now->format('Y-m-d H:i:s'),
		'dateChange' => $now->format('Y-m-d H:i:s'),

		'noteType' => $nodeType->getValue(),
		'noteToDate' => $date->getValue(),
		'noteToLength' => $duration->getValue(),
		'noteToRepeatEvent' => $period->getValue(),
		'noteMessage' => $message->getValue(),
	);
}

echo json_encode($result);
