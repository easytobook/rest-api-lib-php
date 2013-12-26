<?php

namespace ETB\Models\Request\Accomodations\Results;

/**
 *
 * @author alex
 *        
 */
class RequestBuilder {
	/**
	 * 
	 * @var \ETB\Transport\Transport
	 */
	private $transport;
	
	private $viewport;
	private $radius;
	private $point;
	private $capacity = array();
	private $params = array();
	
	private $stars = array();
	private $rating = array();
	private $accType = array();
	private $facilities = array();
	
	public function __construct(\ETB\Transport\Transport $transport) {
		$this->transport = $transport;
	}
	
	/**
	 *
	 * @param array $min_rate
	 * @return RequestBuilder
	*/
	public function setMinRate($min_rate) {
		$this->params['minRate'] = (float)$min_rate;
		return $this;
	}
	/**
	 *
	 * @param array $max_rate
	 * @return RequestBuilder
	 */
	public function setMaxRate($max_rate) {
		$this->params['maxRate'] = (float)$max_rate;
		return $this;
	}
	
	/**
	 * @param array $stars
	 * @return RequestBuilder
	 */
	public function setStars(array $stars) {
		$this->stars = $stars;
		return $this;
	}
	
	/**
	 * @param array $rating
	 * @return RequestBuilder
	 */
	public function setTating(array $rating) {
		$this->rating = $rating;
		return $this;
	}
	
	/**
	 * @param array $accType
	 * @return RequestBuilder
	 */
	public function setAccTypes(array $acc_types) {
		$this->accType = $acc_types;
		return $this;
	}
	
	/**
	 * @param array $facilities
	 * @return RequestBuilder
	 */
	public function setFacilities(array $facilities) {
		$this->facilities = $facilities;
		return $this;
	}
	
	/**
	 *
	 * @param int $limit
	 * @return RequestBuilder
	 */
	public function setLimit($limit) {
		$this->params['limit'] = (int)$limit;
		return $this;
	}
	/**
	 *
	 * @param int $offset
	 * @return RequestBuilder
	 */
	public function setOffset($offset) {
		$this->params['offset'] = (int)$offset;
		return $this;
	}
	
	/**
	 *
	 * @param array $capacity
	 * @return RequestBuilder
	 */
	public function setCapacity(array $capacity) {
		$this->capacity = $capacity;
		return $this;
	}
	
	/**
	 *
	 * @param array $viewport [lat,lon,lat,lon]
	 * @return RequestBuilder
	 */
	public function setViewport(array $viewport) {
		$this->viewport = $viewport;
		return $this;
	}
	/**
	 *
	 * @param array $point
	 * @param int $radius km
	 * @return RequestBuilder
	 */
	public function setPoint(array $point, $radius) {
		$this->point = $point;
		$this->radius = $radius;
		return $this;
	}
	/**
	 *
	 * @param string $checkin
	 * @param string $checkout
	 * @return RequestBuilder
	 */
	public function setDates($checkin, $checkout) {
		$this->params['checkIn'] = $checkin;
		$this->params['checkOut'] = $checkout;
		return $this;
	}
	/**
	 *
	 * @param unknown $currency
	 * @return RequestBuilder
	 */
	public function setCurrency($currency) {
		$this->params['currency'] = $currency;
		return $this;
	}
	/**
	 *
	 * @param string $orderBy
	 * @param string $order
	 * @return RequestBuilder
	 */
	public function setOrder($orderBy, $order) {
		$this->params['orderBy'] = $orderBy;
		$this->params['order'] = $order;
		return $this;
	}
	
	/**
	 *
	 * @return \ETB\Transport\Transport
	 */
	public function build() {
		
		$this->transport->path('/accommodations/results');
		
		//$this->transport->set_url('http://etb:etb2014@apitest.easytobook.us/api-demo/web/app_dev.php/v1/accommodations/results');
		//$this->transport->set_header('accept', 'application/json');
		//$this->transport->set_header('Content-type', 'application/json');
	
		$location_type = null;
		if ($this->viewport) {
			$location_type = 'viewport';
			$location = $this->viewport[0].','.$this->viewport[1].';'.$this->viewport[2].','.$this->viewport[3];
		} else if ($this->point) {
			$location_type = 'spr';
			$location = $this->point[0].','.$this->point[1].';'.$this->radius;
		} else {
			throw new RequestBuilderException("Missing location information");
		}
	
		$query = array_merge($this->params,array(
				'locationType' => $location_type,
				'location' => $location
		));
	
		if ($this->stars) {
			$query['stars'] = implode(',', $this->stars);
		}
		if ($this->rating) {
			$query['rating'] = implode(',', $this->rating);
		}
		if ($this->accType) {
			$query['accType'] = implode(',', $this->accType);
		}
		if ($this->facilities) {
			$query['facilities'] = implode(',', $this->facilities);
		}
		if ($this->capacity) {
			$query['capacity'] = implode(',', $this->capacity);
		}
	
		$this->transport->setQuery($query);
	
		return $this->transport->create();
	}
}

class RequestBuilderException extends \Exception {}