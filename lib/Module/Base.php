<?php
namespace Module;
use Application\Web;

class Base
{
		public $application;
		function __construct(Web $application)
		{
			$this->application = $application;
		}

	function responseJson($data = null)
	{
		header('Content-type:   application/json');
		echo json_encode($data);
		exit(0);
	}
}