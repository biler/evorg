<?php namespace Buonzz\Evorg\Metrics;

use Jenssegers\Agent\Agent;

class EventDecorator{

	private $data;
	private $agent;

	public function __construct($eventData){
   		$this->agent = new Agent();

		if(!isset($eventData['timestamp']))
			$eventData['timestamp'] = date("c");

		if(!isset($eventData['ip']))
			$eventData['ip'] = request()->ip();

		if(!isset($eventData['user_agent']))
			$eventData['user_agent'] = request()->header('User-Agent');

		if(!isset($eventData['device']))
		{
			if($this->agent->isDesktop())
				$eventData['device'] = 'desktop';
			elseif($this->agent->isTablet())
				$eventData['device'] = 'tablet';
			elseif($this->agent->isMobile()))
				$eventData['device'] = 'mobile';
			else
				$eventData['device'] = 'Unknown Device'
		}

		if(!isset($eventData['languages']))
		{
			$eventData['languages'] = $this->agent->languages();
		}

		if(!isset($eventData['platform']))
		{
			$eventData['platform'] = $this->agent->platform();
		}

		if(!isset($eventData['browser']))
		{
			$eventData['browser'] = $this->agent->browser();
		}

		if(!isset($eventData['session_id']))
		{
			$eventData['session_id'] = \Session::getId();
		}

		$this->data =  $eventData;
	}

	public function decorate(){
		return $this->data;
	}

}