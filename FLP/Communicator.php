<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 1/7/14
 * Time: 2:11 PM
 */

namespace FLP;


class Communicator
{

    public $url;
    public $params;
    public $result;
    public $info;

    public function __construct($url, $params = array())
    {
        $this->url = $url;
        $this->params = $params;
        $this->Post();
    }

    private function Post()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
        $this->result = curl_exec($ch);
        $this->info = curl_getinfo($ch);
    }

	public function json()
	{
		$json = json_decode($this->result);
		if ($json != null) {
			$json->info = $this->info;
		}

		return $json;
	}
} 