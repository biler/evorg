<?php namespace Buonzz\Evorg;

use Elasticsearch\ClientBuilder;
use Monolog\Logger;
use Buonzz\Evorg\Jobs\SaveEvent;

use Illuminate\Support\Collection;

class EventRepository{

	private $idxbuilder;

	public function __construct(){
   		$this->idxbuilder = new IndexNameBuilder();
	}

	public function create($eventName, $eventData){		

		$indexname = $this->idxbuilder->build($eventName);

		if(!isset($eventData['timestamp']))
			$eventData['timestamp'] = date("c");

		if(!isset($eventData['ip']))
			$eventData['ip'] = request()->ip();

		if(!isset($eventData['user_agent']))
			$eventData['user_agent'] = request()->header('User-Agent');

		$params = [
	        'indexname' => $indexname,
	        'eventName' => $eventName,
	        'eventData' => $eventData
	    ];

	    dispatch( new SaveEvent($params));

	} // create

	public function get_all($event_name){
	}

	public function read(){
		
	}

	public function update(){
		
	}

	public function delete($eventName){
		$params = [
				'index' => $this->idxbuilder->build($eventName)
		];

        $response = $this->client->indices()->delete($params);
	}

	private function convert_to_collection($search_result){
		
		$data = $search_result['hits']['hits'];
        $tmp = array();
        if(is_array($data))
        {
            foreach($data as $item)
            {
                    $cur_item = $item['_source'];
                    $cur_item['id'] =  $item['_id'];
                    $tmp[] = $cur_item;
            }
        }
        else
                $tmp[] =  $data['_source'];
        return new Collection($tmp);
	}
}