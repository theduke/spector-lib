<?php 

require_once 'Spector/Writer.php';
require_once 'Spector/BaseWriter.php';
require_once 'Spector/LogEntry.php';
require_once 'Spector/Log.php';
require_once 'Spector/LogHandler.php';
require_once('Spector/StreamWriter.php');
require_once 'Spector/MongoWriter.php';

// set up logger
$log = new \Spector\Log();

// specify default project and environment
$log->setProject('test');
$log->setEnvironment('dev');

// add a streamwriter to log to standard php output
//$log->addWriter(new \Spector\StreamWriter('php://output', \Spector\StreamWriter::FORMAT_READABLE));

$log->addWriter(new \Spector\StreamWriter('/tmp/serialized.log', \Spector\StreamWriter::FORMAT_SERIALIZED));

// create new mongo connection and add mongo writer to log
//$connection = new Mongo('server.theduke.at');
//$log->addWriter(new \Spector\MongoWriter($connection, 'spector_test'));

// set up the Singleton helper, 
// which allows accesing your logger from everywhere in your code
// this is optional
\Spector\LogHandler::setLog($log);

// log using the log instance
$log->log('Serialization test', \Spector\LogEntry::CRITICAL);

// use priorities as shortcuts for logging
$log->debug('Debug test');

// log using the singleton
\Spector\LogHandler::log('Testnachricht', \Spector\LogEntry::CRITICAL);
\Spector\LogHandler::log('Lala 2', \Spector\LogEntry::DEBUG);


