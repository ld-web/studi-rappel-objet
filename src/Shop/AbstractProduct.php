<?php

namespace App\Shop;

use App\IDisplayable;

abstract class AbstractProduct implements IDisplayable
{
  protected int $id;
  protected string $name;
  protected float $price;
  protected string $description;

  public function __construct(
    string $name,
    float $price,
    string $description = 'cabin old hunter quick team bag division short flame pretty mouse grandfather grandmother model carefully beside suppose doctor gather other laugh ahead color base'
  ) {
    $this->name = $name;
    $this->price = $price;
    $this->description = $description;
  }

  abstract public function getSurface(): float;

  public function display(): void
  {
    echo $this->getName() . " - " . $this->getSurface() . "<br />";
  }

  public function getName(): string
  {
    return strtoupper($this->name);
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getPrice(): float
  {
    return $this->price;
  }

  public function setPrice(float $price): self
  {
    $this->price = $price;

    return $this;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getId(): int
  {
    return $this->id;
  }
}
