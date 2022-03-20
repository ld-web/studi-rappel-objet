<?php

namespace App\Security;

use App\IDisplayable;

class User implements IDisplayable
{
  private string $name;
  private string $email;

  public function __construct(
    string $name = "BOB",
    string $email = "test@test.com"
  ) {
    $this->name = $name;
    $this->email = $email;
  }

  public function display(): void
  {
    echo $this->name . " - " . $this->email . "<br />";
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }
}
