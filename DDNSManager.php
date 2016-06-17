<?php

/**
 * DDNS-INWX-Updater
 *
 * Copyright (c) Patrick Mosch
 *
 * @link http://xuad.net
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */
// Load some classes
require_once 'inwx/domrobot.class.php';
require_once 'log/KLogger.php';


/**
 * Update dynamic ip adress
 * 
 * @author    Patrick Mosch
 * @copyright Patrick Mosch 2012-2014
 */
class DDNSManager
{
	/**
	 * Configuration file
	 * @var string
	 */
	protected $iniFile = "conf/.config.ini";

	/**
	 * Configuration key
	 * @var string
	 */
	protected $configKey = "";

	/**
	 * Access key
	 * @var string
	 */
	protected $key = "";

	/**
	 * INWX api url
	 * @var string 
	 */
	protected $inwxUrl = "";

	/**
	 * INWX user
	 * @var string
	 */
	protected $inwxUser = "";

	/**
	 * INWX password
	 * @var string
	 */
	protected $inwxPasswd = "";

	/**
	 * INWX domain
	 * @var string
	 */
	protected $inwxDomain = "";

	/**
	 * INWX subdomain
	 * @var string 
	 */
	protected $inwxSubdomain = "";

	/**
	 * Log instance
	 * @var KLogger-Object
	 */
	protected $logger;

	/**
	 * Initialize object
	 */
	public function __construct($key)
	{
		// Save access key
		$this->key = $key;

		// Load configuration
		$this->init();
	}

	/**
	 * Load config and initialize KLogger
	 */
	protected function init()
	{
		// Check configuration file exists
		if (!file_exists($this->iniFile))
		{
			return false;
		}

		// Load configuration
		$ini = parse_ini_file($this->iniFile, TRUE);

		// Load access key
		$this->configKey = isset($ini["api"]["api_key"]) ? $this->validateConfigEntry($ini["api"]["api_key"]) : "";

		// Load INWX-API-URL
		$this->inwxUrl = isset($ini["inwx"]["url"]) ? $this->validateConfigEntry($ini["inwx"]["url"]) : "";

		// Load user
		$this->inwxUser = isset($ini["inwx"]["user"]) ? $this->validateConfigEntry($ini["inwx"]["user"]) : "";

		// Load password
		$this->inwxPasswd = isset($ini["inwx"]["passwd"]) ? $this->validateConfigEntry($ini["inwx"]["passwd"]) : "";

		// Load domain
		$this->inwxDomain = isset($ini["inwx"]["domain"]) ? $this->validateConfigEntry($ini["inwx"]["domain"]) : "";

		// Load subsomain
		$this->inwxSubdomain = isset($ini["inwx"]["subdomain"]) ? $this->validateConfigEntry($ini["inwx"]["subdomain"]) : "";

		// Initialisize KLogger
		$filepath = isset($ini["log"]["filepath"]) ? $this->validateConfigEntry($ini["log"]["filepath"]) : "";
		$this->logger = new KLogger($filepath, 2);
	}

	/**
	 * Update domain with new ip
	 * @param type $ip
	 * @return string
	 */
	public function updateIP($ip, $ip6)
	{
		// Check for empty ip string
		if ($ip !== "")
		{
			// err?
		}
		else
		{
			$this->logger->LogError("IP string is empty");
			return false;
		}

		// Check access
		if (!$this->checkAccess())
		{
			$this->logger->LogError("unauthorisized access from IP: " . $_SERVER['REMOTE_ADDR']);
			return false;
		}

		// Get old ip from inwx
		$domrobot = new domrobot($this->inwxUrl);
		$domrobot->setDebug(false);
		$domrobot->setLanguage('en');

		$response = $domrobot->login($this->inwxUser, $this->inwxPasswd);

		// After successfully authentificated start update
		if ($response['code'] == 1000)
		{
			$ip4success = $this->updateRecord($domrobot, "A", $ip);
			$this->logger->LogInfo("IPv4 success: ".$ip4success);
			if($ip6 !== "")
			{
				$ip6success = $this->updateRecord($domrobot, "AAAA", $ip6);
				$this->logger->LogInfo("IPv6 success: ".$ip6success);
			}
			//if(!$ip4success  $ip6success)
		}
		else if ($response['code'] == 2200)
		{
			// Authentification error
			$this->logger->LogError("Wrong username or password!");
		}
	}

	protected function updateRecord($domrobot, $type, $newIP)
	{
		// Get subdomain, recordID and current ip
		$object = "nameserver";
		$method = "info";
		$params = array();
		$params['domain'] = $this->inwxDomain;
		$params['type'] = $type;
		$params['name'] = $this->inwxSubdomain;
		$response = $domrobot->call($object, $method, $params);

		if ($response['code'] == 1000)
		{
			// Get the recordID
			// ID is important for the update
			$recordID = $response["resData"]["record"][0]["id"];

			// Get old IP
			$oldIP = $response["resData"]["record"][0]["content"];

			// Update-Call
			$object = "nameserver";
			$method = "updateRecord";
			$params = array();
			$params['id'] = $recordID;
			$params['content'] = $newIP;
			$response = $domrobot->call($object, $method, $params);

			// After successfully IP update well done
			if ($response['code'] == 1000)
			{
				// Write some information in logfile
				$this->logger->LogInfo("IP update successfully! | Old IP: " . $oldIP . " | New IP: " . $newIP);
				return true;
			}
			else
			{
				// Cant update IP
				$this->logger->LogError("IP update error: " . $newIP);
				return false;
			}
			
		}
		else
		{
			// INWX login error
			$this->logger->LogError("Cant login: " . $response);
			return false;
		}
	}
	
	/**
	 * Check correct access key
	 * @return boolean access allow
	 */
	protected function checkAccess()
	{
		// Compare keys
		if ($this->key == $this->configKey)
		{
			return true;
		}
		else
		{
			// Wrong key
			$this->logger->LogError("Wrong submitted key: " . $this->key);
			return false;
		}
	}

	/**
	 * Validate ini file entry
	 * @param string $entry
	 * @return string
	 */
	protected function validateConfigEntry($entry)
	{
		// Escape string 
		if (isset($entry) && !empty($entry))
		{
			return ($entry);
		}
		else
		{
			return "";
		}
	}
}