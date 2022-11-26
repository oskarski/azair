<?php


class Country {
	private $name;
	private $airports;

	public function __construct( string $name, array $airports ) {
		$this->name     = $name;
		$this->airports = $airports;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return array<Country>
	 */
	public function getAirports(): array {
		return $this->airports;
	}
}