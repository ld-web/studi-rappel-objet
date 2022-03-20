<?php

namespace App\Shop;

final class ProductRect extends AbstractProduct
{
  private int $width;
  private int $height;

  public function __construct(
    string $name,
    float $price,
    int $width,
    int $height,
    ?string $description = null
  ) {
    if ($description === null) {
      parent::__construct($name, $price);
    } else {
      parent::__construct($name, $price, $description);
    }

    $this->width = $width;
    $this->height = $height;
  }

  public function getSurface(): float
  {
    return $this->width * $this->height;
  }

  public function getWidth(): int
  {
    return $this->width;
  }

  public function setWidth(int $width): self
  {
    $this->width = $width;

    return $this;
  }

  public function getHeight(): int
  {
    return $this->height;
  }

  public function setHeight(int $height): self
  {
    $this->height = $height;

    return $this;
  }
}
