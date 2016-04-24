<?php

namespace Malenki\Game\NumberPlace;

class Box
{
    protected $grid;
    protected $value = null;

    protected $revealed = false;
    protected $coord;

    protected $mayBe = array();

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

        $size = $this->grid->getSize();

        if ($row >= $size || $col >= $size) {
            throw new \OutOfRangeException('Coordinates must be inside grid!');
        }
        
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

        if ($value >= $this->grid->getSize()) {
            throw new \OutOfRangeException("Cell's value must be into the range defined by the grid!");
        }

        if ($this->revealed) {
            throw new \RuntimeException('Cannot change value of revealedCell!');
        }

        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSymbol()
    {
        if (is_null($this->value)) {
            return $this->grid->getJoker();
        }

        return $this->grid->getSymbols()[$this->value];
    }

    public function getCoordinates()
    {
        return $this->coord;
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
        } else {
            throw new \RuntimeException('Cannot clear value of revealed cell!');
        }

        return $this;
    }

    public function addPossible($value)
    {
        if (!is_array($value) && !is_integer($value)) {
            throw new \InvalidArgumentException('Possibles values must be an array of integers or an integer!');
        }

        if (is_array($value)) {
            foreach ($value as $v) {
                $this->addPossible($v);
            } 
        }

        if (is_integer($value) && !in_array($value, $this->mayBe)) {
            $this->mayBe[] = $value;
        }

        $this->mayBe = array_unique($this->mayBe);

        return $this;
    }

    public function addImpossible($value)
    {
        if (!is_array($value) && !is_integer($value)) {
            throw new \InvalidArgumentException('Impossibles values must be an array of integers or an integer!');
        }

        if (is_array($value)) {
            foreach ($value as $v) {
                $this->addImpossible($v);
            } 
        }

        if (is_integer($value) && in_array($value, $this->mayBe)) {
            $prov = array_flip($this->mayBe);
            unset($prov[$value]);
            $this->mayBe = array_values(array_flip($prov));
        }
    }

    public function getPossibleValues()
    {
        return $this->mayBe;
    }

    public function randomize()
    {
        if ($this->revealed) {
            throw new \RuntimeException('Cannot Randomized value of revealed cell!');
        }

        $this->value = $this->mayBe[mt_rand(0, count($this->mayBe) - 1)];
    }

    public function __toString()
    {
        return $this->getSymbol();
    }
}
