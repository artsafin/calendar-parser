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

$input = $_GET['s'];

log('Input string: ', $input);

$tokenizer = new Tokenizer();
$tokens = $tokenizer->tokenize($input);

log('Tokens: ', $tokens);

$f = new CalcFactory($tokens);

$period = $f->createPeriod();
$date = $f->createDateTime(new \DateTime());
$duration = $f->createDuration($date->getDateTime());
$message = $f->createMessage();

$return = array(
	'noteToDate' => $date->getValue(),
	'noteToLength' => $duration->getValue(),
	'noteToRepeatEvent' => $period->getValue(),
	'noteMessage' => $message->getValue(),
);

var_dump($return);



