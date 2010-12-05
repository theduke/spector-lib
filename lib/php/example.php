<?php 

require_once 'Spector/LogHandler.php';
require_once('Spector/StreamWriter.php');
require_once 'Spector/MongoWriter.php';

// set up logger
$log = new Spector_Log();

// specify default project and environment
$log->setProject('test');
$log->setEnvironment('dev');

// add a streamwriter to log to standard php output
$log->addWriter(new Spector_StreamWriter('php://output', Spector_StreamWriter::FORMAT_READABLE));

// create new mongo connection and add mongo writer to log
$connection = new Mongo('server.theduke.at');
$log->addWriter(new Spector_MongoWriter($connection, 'spector_test'));

// set up the Singleton helper, 
// which allows accesing your logger from everywhere in your code
// this is optional
Spector_LogHandler::setLog($log);

// log using the log instance
$log->log('Testmessage', Spector_LogEntry::CRITICAL);

// use priorities as shortcuts for logging
$log->debug('Debug message');

// log using the singleton
Spector_LogHandler::log('Testnachricht', Spector_LogEntry::CRITICAL);
Spector_LogHandler::log('Lala 2', Spector_LogEntry::DEBUG);


