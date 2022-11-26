<?php


class Country
{
    private $name;
    private $airports;
    private $skip = false;
    private $maxPrice = null;

    public function __construct(string $name, array $airports)
    {
        $this->name = $name;
        $this->airports = $airports;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<Country>
     */
    public function getAirports(): array
    {
        return $this->airports;
    }

    public function skip(): Country
    {
        $this->skip = true;

        return $this;
    }

    public function shouldSkip(): bool
    {
        return $this->skip;
    }

    public function setMaxPrice($maxPrice): Country
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getMaxPrice()
    {
        return $this->maxPrice;
    }
}