<?php
/*
 * Copyright (c) 2016 Michel Petit <petit.michel@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */


namespace Malenki\Game\NumberPlace;


/**
 * Grid of number place game, aka Sudoku. 
 * 
 * @author Michel Petit <petit.michel@gmail.com> 
 * @license MIT
 */
class Grid
{
    protected $joker;
    protected $grid;
    protected $symbols;
    protected $use_diagonal = false;






    /**
     * Checks joker's symbol to use.
     *
     * Returns `true` only if all is OK, otherwise, it raises an `InvalidArgumentException`. 
     * 
     * @param string $joker The character to use as joker
     * @return true
     * @throws \InvalidArgumentException If it is not a string, raise this exception
     * @throws \InvalidArgumentException If joker's symbol string length has not a size of one char.
     */
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






    /**
     * Checks symbols to use to fill the grid. 
     *
     * If everything is fine, it must return `true`.
     *
     * One error and it raise an exception.
     * 
     * @param array $symbols Array of strings 
     * @param string $joker Joker's symbol
     * @param integer $count Number of symbols to use
     * @return true
     * @throws \InvalidArgumentException If symbols collection is not an array
     * @throws \InvalidArgumentException If Joker is not a string
     * @throws \InvalidArgumentException If count is not an integer or count is negative or null.
     * @throws \InvalidArgumentException If given count is not equals to number of symbols.
     * @throws \InvalidArgumentException If symbols collection has some duplicates inside
     * @throws \InvalidArgumentException If at least one symbol is equals to joker.
     */
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






    /**
     * Checks a grid content versus symbols list and joker. 
     * 
     * Returns `true` only if everything is fine.
     *
     * @param array $grid The grid content to check
     * @param array $symbols Symbols list
     * @param string $joker Joker symbol
     * @return true
     * @throws \InvalidArgumentException If grid is not an array, raise this exception
     * @throws \InvalidArgumentException If at least one grid's row is not an array
     * @throws \InvalidArgumentException If at least one symbol into grid is unknown
     */
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






    /**
     * Checks if given grid capacity is compatible with number place game. 
     * 
     * Returns `true` if capacity is compatible, `false` otherwise.
     *
     *
     * @param integer $size Capacity
     * @return boolean
     */
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






    /**
     * It converts given string as a grid content. 
     * 
     * @param string $str Symbols to fill a grid with. 
     * @return array
     * @throws \InvalidArgumentException If argument is not a string.
     * @throws \InvalidArgumentException If string length is not compatible with a grid size for number place game.
     */
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






    /**
     * This converts a string into an array. 
     * 
     * @param string $str The string to convert to array
     * @return array
     * @throws \InvalidArgumentException If argument is not a string value.
     */
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






    /**
     * Get area coordinates from given grid coordinate classic row/col. 
     * 
     * @param integer $row Row's index
     * @param integer $col Column's index
     * @param integer $size Size of the game
     * @return string
     * @throws \InvalidArgumentException If size is not an integer or size is negative or null.
     * @throws \InvalidArgumentException If row's index does not belong to game size.
     * @throws \InvalidArgumentException If column's index does not belong to game size.
     */
    public static function areaCoordFromGridCoord($row, $col, $size)
    {
        if (!is_integer($size) || $size <= 0) {
            throw new \InvalidArgumentException('Size must be a positive not null integer!');
        }

        if (!is_integer($row) || $row >= $size || $row < 0) {
            throw new \InvalidArgumentException(
                sprintf("Row must be integer inside [0,%d[", $size)
            );
        }
        if (!is_integer($col) || $col >= $size || $col < 0) {
            throw new \InvalidArgumentException(
                sprintf("Column must be integer inside [0,%d[", $size)
            );
        }
        $area_size = sqrt($size);

        $area_row = 0;
        $area_col = 0;

        for($r = 0; $r <= $row; $r++) {
            if ($r > 0 && ($r % $area_size == 0)) {
                $area_row++;
            }
        }
        
        for($c = 0; $c <= $col; $c++) {
            if ($c > 0 && ($c % $area_size == 0)) {
                $area_col++;
            }
        }

        return "$area_row;$area_col";
    }






    protected function populate()
    {
        $rowsLocked = [];
        $colsLocked = [];
        $areasLocked = [];

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

                    $areaIndex = self::areaCoordFromGridCoord($rowIndex, $colIndex, count($this->grid));
                    
                    if (!isset($areasLocked[$areaIndex])) {
                        $areasLocked[$areaIndex] = []; 
                    }
                    $areasLocked[$areaIndex][] = $value; 

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
                
                $areaIndex = self::areaCoordFromGridCoord($rowIndex, $colIndex, count($this->grid));
                if (isset($areasLocked[$areaIndex])) {
                    $cell->addImpossible($areasLocked[$areaIndex]);
                }
                
            }
        }
    }






    /**
     * Instanciate new grid using starting content, given all symbols list and joker symbol to use. 
     *
     * Note: starting content defines revealed boxes.
     * 
     * @param string $grid First content.
     * @param string $symbols Symbol list
     * @param string $joker Joker's symbol
     * @return void
     */
    public function __construct($grid, $symbols = '123456789', $joker = '.')
    {
        // convert to array
        $grid = self::stringToGrid($grid);
        $symbols = self::stringToArray($symbols);

        // check all given things to each other and themself
        self::checkJoker($joker);
        self::checkSymbols($symbols, $joker, count($grid));
        self::checkGridVersusSymbols($grid, $symbols, $joker);

        // Everything is OK? So, lets ending instanciation :)
        $this->joker = $joker;
        $this->grid = $grid;
        $this->symbols = $symbols;

        $this->populate();
    }






    /**
     * Gets joker's symbol used into this grid definition. 
     * 
     * @return string
     */
    public function getJoker()
    {
        return $this->joker;
    }







    /**
     * Get symbols' list defined into current grid.
     * 
     * @return array
     */
    public function getSymbols()
    {
        return $this->symbols;
    }






    /**
     * Define current grid to use diagonal rule in addition of row, column and area checkings. 
     * 
     * @return Grid
     */
    public function useDiagonal()
    {
        $this->use_diagonal = true;

        return $this;
    }






    /**
     * Checks whether grid is full. 
     * 
     * @return boolean
     */
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






    /**
     * Returns row defined at given index. 
     * 
     * @param integer $idx Index of the row to get
     * @return array
     * @throws \OutOfRangeException If index does not exists.
     */
    public function getRowAt($idx)
    {
        if (!isset($this->grid[$idx])) {
            throw new \OutOfRangeException(
                "Row's index is not defined for this grid!"
            );
        }

        return $this->grid[$idx];
    }







    /**
     * Returns column defined at given index. 
     * 
     * @param integer $idx Index of the column to get
     * @return array
     * @throws \OutOfRangeException If index does not exists.
     */
    public function getColAt($idx)
    {
        $out = [];

        foreach ($this->grid as $row) {
            if (!isset($row[$idx])) {
                throw new \OutOfRangeException(
                    "Column's index is not defined for this grid!"
                );
            }
            $out[] = $row[$idx];
        }

        return $out;
    }






    /**
     * Returns diagonal at given index.
     *
     *  - Index of 0 stands for diagonal starting to the top left corner.
     *  - Index of 1 stands for diagonal starting to the top right corner.
     * 
     * @param integer $idx Must be 0 or 1, other values are impossible.
     * @return array
     * @throws \OutOfRangeException If diagonal index is not valid.
     */
    public function getDiagonalAt($idx)
    {
        if ($idx !== 0 && $idx !== 1) {
            throw new \OutOfRangeException("Diagonal's index must be 0 or 1!");
        }

        $out = [];

        foreach ($this->grid as $i => $row) {
            if ($idx) {
                $row = array_reverse($row);
            }

            $out[] = $row[$i];
        }

        return $out;
    }






    /**
     * Gets area content at given area index. 
     * 
     * @param string $idx Area index coordinate
     * @return array
     * @throws \InvalidArgumentException If given argument is not a valid area coordinate.
     * @throws \OutOfRangeException If column index or row index obtained from area coordinates are not defined.
     */
    public function getAreaAt($idx)
    {
        if (!preg_match('/^[0-9]+;[0-9]+$/', $idx)) {
            throw new \InvalidArgumentException("Area's coordinates not valid!");
        }

        list($row, $col) = explode(';', $idx);

        $area_size = sqrt(count($this->grid));

        $grid = array_chunk($this->grid, $area_size);

        $area = [];

        if (!isset($grid[$row])) {
            throw new \OutOfRangeException(
                "Area's row index is not defined for this grid!"
            );
        }

        foreach ($grid[$row] as $sub_row) {
            $chunk = array_chunk($sub_row, $area_size);
            if (!isset($chunk[$col])) {
                throw new \OutOfRangeException(
                    "Area's col index is not defined for this grid!"
                );
            }
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
        // TODO : Bad, now, Sudoku can have any size
        for ($idx = 0; $idx < 9; $idx++) {
            if (!$this->checkRowAt($idx)) {
                return false;
            }
        }

        return true;
    }






    public function checkCols()
    {
        // TODO : Bad, now, Sudoku can have any size
        for ($idx = 0; $idx < 9; $idx++) {
            if (!$this->checkColAt($idx)) {
                return false;
            }
        }

        return true;
    }






    public function checkAreas()
    {
        // TODO : Bad, now, Sudoku can have any size
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
