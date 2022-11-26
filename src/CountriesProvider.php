<?php


class CountriesProvider {
	private $countries;

	/**
	 * CountriesProvider constructor.
	 */
	public function __construct($countries) {
		$this->countries = $countries;
	}

	/**
	 * @return mixed
	 */
	public function getCountries() {
		return $this->countries;
	}
}