<?php

namespace Core\Request;
class Http extends Base
{
	private $request = array();

	private $requestUri;
	/**
	 * Обрабатываем запрос
	 */
	public function processRequest()
	{
		$this->processGet()
			->processPost()
			->processFiles()
			->processCookies();
		unset($_GET);
		unset($_POST);
		unset($_FILES);
		unset($_COOKIE);

		$this->requestUri = $_SERVER['REQUEST_URI'];
	}

	public function getAbsoluteUrlWithoutParameters()
	{
		return array_shift(explode('?',$this->requestUri));
	}

	public function getAbsoluteUrl()
	{
		return $this->requestUri;
	}

	public function getQueryParam($paramName, $default = null)
	{
		return isset($this->request[$paramName]) ? $this->request[$paramName] : $default;
	}

	private function processGet()
	{
		foreach($_GET as $field => $value)
		{
			$this->request[$field] = $value;
		}
		return $this;
	}

	private function processPost()
	{
		foreach($_POST as $field => $value)
		{
			$this->request[$field] = $value;
		}
		return $this;
	}

	private function processFiles()
	{
		return $this;
	}

	private function processCookies()
	{
		return $this;
	}

	public function redirect($url)
	{
		header('Location: ' . $url);
	}
}