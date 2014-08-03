Calendar Parser
===============
Parses russian date and time strings in various formats

Usage
=====
main.php?s=завтра%20в%207%20вечера%20сделать%20работу-----каждый%20год%2030%20января%20в%209%20вечера%20на%202%20часа%20ходим%20с%20мужиками%20в%20баню

Or using code:
```
#!php
<?php
    // Used as starting point for relative timestamps
    $now = new DateTime('now');

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

```