<?php

$finder = PhpCsFixer\Finder::create()
//	->in(__DIR__ . DIRECTORY_SEPARATOR . 'tests')
	->in(__DIR__ . DIRECTORY_SEPARATOR . 'src');

$config = new PhpCsFixer\Config();
return $config->setRules([
	'@PSR12'                 => true,
	'indentation_type'       => true,
	'array_syntax'           => ['syntax' => 'short'],
	'binary_operator_spaces' => [
		'operators' => [
			'=>' => 'align',
			'='  => 'align',
		],
	],
])
	->setIndent("\t")
	->setLineEnding("\n")
	->setFinder($finder);
