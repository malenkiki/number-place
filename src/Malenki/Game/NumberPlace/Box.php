<?php

namespace Malenki\Game\NumberPlace;

class Box
{
    protected $value = null;

    protected $revealed = false;

    protected $canBe = array();

    public function __construct($value = null, $revealed = false)
    {
        if (is_string($value) && (mb_strlen($value, 'UTF-8') === 1)) {
            $this->value = $value;
        }

        $this->revealed = (boolean) $revealed;
    }


    public function isRevealed()
    {
        return $this->revealed;
    }

    public function isVoid()
    {
        return is_null($this->value);
    }

    public function clear()
    {
        $this->value = null;
        return $this;
    }

    public function clearPossibilities()
    {
        $this->canBe = [];
    }

    public function mayBe($value)
    {
        if (!in_array($value, $this->canBe)) {
            $this->canBe[] = $value;
        }
    }

    public function mayNotBe($value)
    {
        if (in_array($value, $this->canBe)) {
            $prov = array_flip($this->canBe);
            unset($prov[$value]);
            $this->canBe = array_values(array_flip($prov));
        }
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
