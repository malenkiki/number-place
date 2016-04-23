<?php

namespace Malenki\Game\NumberPlace;

class Box
{
    protected $grid;
    protected $value = null;

    protected $revealed = false;
    protected $coord;

    protected $canBe = array();

    public function __construct($row, $col, Grid &$grid)
    {
        if (!is_integer($row) || $row < 0) {
            throw new \InvalidArgumentException(
                "Row's index must be a not negative integer!"
            );
        }
        
        if (!is_integer($col) || $col < 0) {
            throw new \InvalidArgumentException(
                "Column's index must be a not negative integer!"
            );
        }

        $this->grid = $grid;
        
        $this->coord = new \stdClass();
        $this->coord->row = $row;
        $this->coord->col = $col;
    }

    public function setValue($value)
    {
        if (!is_integer($value) || $value < 0) {
            throw new \InvalidArgumentException(
                "Cell's value must be a not negative integer!"
            );
        }

        $this->value = $value;

        return $this;
    }

    public function setAsRevealed()
    {
        $this->revealed = true;
        return $this;
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
        if (!$this->revealed) {
            $this->value = null;
        }

        return $this;
    }

    public function mayBe($value)
    {
        if (!in_array($value, $this->canBe)) {
            $this->canBe[] = $value;
        }

        return $this;
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
        if (is_null($this->value)) {
            return $this->grid->getJoker();
        } else {
            return $this->grid->getSymbols()[$this->value];
        }

        return (string) $this->value;
    }
}
