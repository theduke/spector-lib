<?php

namespace Spector\Import\Handler;

interface Handler
{
	public function getEntries($data, \Spector\Import\Import $import);
}