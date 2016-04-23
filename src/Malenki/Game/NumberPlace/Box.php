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
        $this->value = $this->mayBe[mt_rand(0, count($this->mayBe) - 1)];
    }

    public function __toString()
    {
        return $this->getSymbol();
    }
}
