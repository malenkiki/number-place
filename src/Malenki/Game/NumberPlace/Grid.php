<?php

namespace Malenki\Game\NumberPlace;

class Grid
{
    protected $joker;
    protected $grid;
    protected $symbols;
    protected $use_diagonal = false;

    public static function checkJoker($joker)
    {
        if (!is_string($joker)) {
            throw new \InvalidArgumentException('Joker must be a string of one character!');
        }

        if (mb_strlen($joker, 'UTF-8') !== 1) {
            throw new \InvalidArgumentException('Joker must have only one character!');
        }

        return true;
    }

    public static function checkSymbols($symbols, $joker, $count)
    {
        if(!is_array($symbols)) {
            throw new \InvalidArgumentException('Symbols’ list should be an array!');
        }

        if (!is_string($joker)) {
            throw new \InvalidArgumentException('Joker should be a string!');
        }

        if (!is_integer($count) || $count <= 0) {
            throw new \InvalidArgumentException('Count must be positive not null integer!');
        }

        if (count($symbols) !== $count) {
            throw new \InvalidArgumentException("This grid size require $count symbols!");
        }

        $uniq = array_unique($symbols);

        if (count($uniq) < $count) {
            throw new \InvalidArgumentException('Symbols list has some duplicates!');
        }

        if (in_array($joker, $symbols)) {
            throw new \InvalidArgumentException('One symbol is equal to joker character!');
        }

        return true;
    }




    public static function checkGridVersusSymbols($grid, $symbols, $joker)
    {
        if (!is_array($grid)) {
            throw new \InvalidArgumentException('Grid must be an array!');
        }

        foreach ($grid as $row) {
            if (!is_array($row)) {
                throw new \InvalidArgumentException('Grid’s row must be an array!');
            }

            foreach ($row as $box) {
                if ($box === $joker) {
                    continue;
                }

                if (!in_array($box, $symbols)) {
                    throw new \InvalidArgumentException('Grid content has symbols not defined into authorized list!');
                }
            }
        }

        return true;
    }


    public static function isValidSize($size)
    {
        // compute side size of basic area
        $base = sqrt(sqrt($size));

        // must be an integer
        if (($base - (int) $base) != 0) {
            return false;
        }

        // Cannot be smaller than 2
        return $base >= 2;
    }


    public static function stringToGrid($str)
    {
        if (!is_string($str)) {
            throw new \InvalidArgumentException('Argument must be a string!');
        }

        $length = mb_strlen($str, 'UTF-8');

        if (!self::isValidSize($length)) {
            throw new \InvalidArgumentException('Not valid grid size!');
        }

        $arr = self::stringToArray($str);
        return array_chunk($arr, sqrt($length));
    }


    public static function stringToArray($str)
    {
        if (!is_string($str)) {
            throw new \InvalidArgumentException(
                'To convert string to array, you must provide a string value as input!'
            );
        }

        $arr = preg_split('//u', $str);
        $arr = array_filter($arr, function($c){return strlen($c) > 0;});
        return array_values($arr);
    }

    protected function populate()
    {
        $rowsLocked = [];
        $colsLocked = [];

        foreach ($this->grid as $rowIndex => &$row) {
            foreach ($row as $colIndex => &$cell) {
                $box = new Box($rowIndex, $colIndex, $this);
                
                if ($cell !== $this->joker) {
                    // Take numerical index in place of symbol
                    $value = array_flip($this->symbols)[$cell];
                    $box->setValue($value);
                    $box->setAsRevealed();

                    if (!isset($rowsLocked[$rowIndex])) {
                        $rowsLocked[$rowIndex] = []; 
                    }
                    $rowsLocked[$rowIndex][] = $value; 
                    
                    if (!isset($colsLocked[$colIndex])) {
                        $colsLocked[$colIndex] = []; 
                    }
                    $colsLocked[$colIndex][] = $value; 
                } else {
                    $box->addPossible(array_keys($this->symbols));
                }

                $cell = $box;
            }
        }

        
        foreach ($this->grid as $rowIndex => &$row) {
            foreach ($row as $colIndex => &$cell) {
                if ($cell->isRevealed()) {
                    continue;
                }

                if (isset($rowsLocked[$rowIndex])) {
                    $cell->addImpossible($rowsLocked[$rowIndex]);
                }

                if (isset($colsLocked[$colIndex])) {
                    $cell->addImpossible($colsLocked[$colIndex]);
                }
            }
        }
    }


    public function __construct($grid, $symbols = '123456789', $joker = '.')
    {
        $grid = self::stringToGrid($grid);
        $symbols = self::stringToArray($symbols);

        self::checkJoker($joker);
        self::checkSymbols($symbols, $joker, count($grid));
        self::checkGridVersusSymbols($grid, $symbols, $joker);

        $this->joker = $joker;
        $this->grid = $grid;
        $this->symbols = $symbols;

        $this->populate();
    }

    public function getJoker()
    {
        return $this->joker;
    }

    public function getSymbols()
    {
        return $this->symbols;
    }

    public function useDiagonal()
    {
        $this->use_diagonal = true;

        return $this;
    }

    public function isFull()
    {
        foreach ($this->grid as $row) {
            foreach ($row as $box) {
                if ($box->isVoid()) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getRowAt($idx)
    {
        return $this->grid[$idx];
    }

    public function getColAt($idx)
    {
        $out = [];

        foreach ($this->grid as $row) {
            $out[] = $row[$idx];
        }

        return $out;
    }

    public function getDiagonalAt($idx)
    {
        $out = [];

        for ($i = 0; $i < 9; $i++) {
            $row = $this->getRowAt($i);
            
            if ($idx) {
                $row = array_reverse($row);
            }

            $out[] = $row[$i];
        }

        return $out;
    }

    public function getAreaAt($idx)
    {
        if (!preg_match('/^(0|1|2);(0|1|2)$/', $idx)) {
            throw new \InvalidArgumentException('todo');
        }

        list($col, $row) = explode(';', $idx);

        $grid = array_chunk($this->grid, 3);

        $area = [];
        foreach ($grid[$row] as $sub_row) {
            $chunk = array_chunk($sub_row, 3);
            $area = array_merge($area, $chunk[$col]);
        }

        return $area;
    }

    protected function getCollection($idx, $type)
    {
        if ($type === 'row') {
            $collection = $this->getRowAt($idx);
        }
        
        if ($type === 'col') {
            $collection = $this->getColAt($idx);
        }
        
        if ($type === 'diagonal') {
            $collection = $this->getDiagonalAt($idx);
        }

        if ($type === 'area') {
            $collection = $this->getAreaAt($idx);
        }

        return $collection;
    }

    protected function checkUniqFull($idx, $type)
    {
        if ($type === 'diagonal') {
            if (!$this->use_diagonal) {
                throw new \RuntimeException('Cannot check diagonal: option not set!');
            }
        }
        $collection = $this->getCollection($idx, $type);
        $collection = array_map(function($v){return "$v";}, $collection);
        
        return (count(array_unique($collection)) === count($collection));
    }

    protected function duplicatesCountFor($idx, $type)
    {
        if ($type === 'diagonal') {
            if (!$this->use_diagonal) {
                throw new \RuntimeException('Cannot get duplicates for diagonals: option not set!');
            }
        }
        $collection = $this->getCollection($idx, $type);
        $collection = array_map(function($v){return "$v";}, $collection);
        
        return count($collection) - count(array_unique($collection));
    }

    protected function availablesFor($idx, $type)
    {
        if ($type === 'diagonal') {
            if (!$this->use_diagonal) {
                throw new \RuntimeException('Cannot get duplicates for diagonals: option not set!');
            }
        }
        $collection = $this->getCollection($idx, $type);

        foreach ($collection as $box) {

        }

        $collection = array_map(function($v){return "$v";}, $collection);
        
        return $out;
    }


    public function checkRowAt($idx)
    {
        return $this->checkUniqFull($idx, 'row');
    }

    public function checkColAt($idx)
    {
        return $this->checkUniqFull($idx, 'col');
    }

    public function checkAreaAt($idx)
    {
        return $this->checkUniqFull($idx, 'area');
    }

    public function checkDiagonalAt($idx)
    {
        return $this->checkUniqFull($idx, 'diagonal');
    }


    public function checkRows()
    {
        for ($idx = 0; $idx < 9; $idx++) {
            if (!$this->checkRowAt($idx)) {
                return false;
            }
        }

        return true;
    }

    public function checkCols()
    {
        for ($idx = 0; $idx < 9; $idx++) {
            if (!$this->checkColAt($idx)) {
                return false;
            }
        }

        return true;
    }

    public function checkAreas()
    {
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if (!$this->checkAreaAt("$i;$j")) {
                    return false;
                }
            }
        }

        return true;
    }

    public function checkDiagonals()
    {
        foreach (range(0, 1) as $idx) {
            if (!$this->checkDiagonalAt($idx)) {
                return false;
            }
        }

        return true;
    }

    public function check()
    {
        if (! $this->checkRows()) return false;
        if (! $this->checkCols()) return false;
        if (! $this->checkAreas()) return false;

        if ($this->use_diagonal) {
            if (! $this->checkDiagonals()) return false;
        }

        return true;
    }


    public function getSize()
    {
        return count($this->grid);
    }
}
