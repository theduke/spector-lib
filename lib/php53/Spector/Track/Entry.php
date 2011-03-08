<?php

namespace Spector\Track;

/**
 * Enter description here ...
 * 
 * 
 * 
 * @author theduke
 *
 *
 * @property id
 * 
 * @property time
 * @property length
 * 
 * @property ip
 * 
 * @property username
 * @property sessionId
 * 
 * @property userAgent
 * 
 * @property extraData
 * 
 * @property method
 * @property host
 * @property path
 * @property query
 * 
 * @property referer
 * 
 * @property cookies
 *
 */
class Entry extends Spector\Common\Configurable
{
	protected $_id;
	
	protected $_time;
	protected $_length;
	
	protected $_ip;
	protected $_username;
	
	protected $_sessionId;
	
	protected $_userAgent;
	
	protected $_method;
	protected $_host;
	protected $_path;
	protected $_query;
	
	protected $_cookies;
	
	protected $_referer;
	
	protected $_extraData = array();
}

