<?php

abstract class AbstractFetcher
{
	protected $_config;
	
	public function initialize(Import\Import $config)
	{
		$this->validateConfig($config);
		$this->_config = $config;
	}
}