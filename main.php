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

$now = new \DateTime('now', new \DateTimeZone('Etc/GMT-4'));

$result = array();

foreach (explode('-----', $notes) as $input)
{
//	log('Input string: ', $input);

	$tokenizer = new Tokenizer();
	$tokens = $tokenizer->tokenize($input);

//	log('Tokens: ', $tokens);

	$f = new CalcFactory($tokens);

	$period = $f->createPeriod();
	$date = $f->createDateTime($now);
	$duration = $f->createDuration($date->getDateTime());
	$message = $f->createMessage();

	$result[] = array(
		'dateCreate' => $now->format('Y-m-d H:i:s'),
		'dateChange' => $now->format('Y-m-d H:i:s'),
		'noteToDate' => $date->getValue(),
		'noteToLength' => $duration->getValue(),
		'noteToRepeatEvent' => $period->getValue(),
		'noteMessage' => $message->getValue(),
	);
}

echo json_encode($result);
