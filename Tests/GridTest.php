<?php


use \Malenki\Game\NumberPlace\Grid;

class GridTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringToArrayNotUsingStringShouldRaiseInvalidArgumentException()
    {
        Grid::stringToArray(new \stdClass());
    }

    public function testStringToArrayOfVoidStringSouldReturnVoidArray()
    {
        $arr = Grid::stringToArray('');
        $this->assertEmpty($arr);
        $this->assertInternalType('array', $arr);
    }

    public function testStringToArrayOfNineUtf8CharsShouldGiveNineItems()
    {
        $str = 'C’est top';
        $arr = Grid::stringToArray($str);
        $this->assertCount(9, $arr);
    }
    
    public function testStringToArrayOfNineUtf8CharsShouldGiveSameNineCharsIntoArray()
    {
        $str = 'C’est top';
        $arr = Grid::stringToArray($str);
        $this->assertEquals(['C', '’', 'e', 's', 't', ' ', 't', 'o', 'p'], $arr);
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringToGridShouldRaiseInvalidArgumentExceptionIfItISNotAString()
    {
        Grid::stringToGrid(array('foo'));
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringToGridShouldRaiseInvalidArgumentExceptionIfItHasLessThan81Chars()
    {
        Grid::stringToGrid('azerty');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStringToGridShouldRaiseInvalidArgumentExceptionIfItHasNotAllowedCharsLength()
    {
        $str = '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        Grid::stringToGrid($str);
    }

    public function testStringToGridUsingStringOf16CharsShouldSuccess()
    {
        $str = '1234342143211432';
        $grid = Grid::stringToGrid($str);
        $this->assertCount(4, $grid);

        foreach ($grid as $row) {
            $this->assertCount(4, $row);
        }

        $expected = array(
            ['1','2','3','4'],
            ['3','4','2','1'],
            ['4','3','2','1'],
            ['1','4','3','2']
        );
        $this->assertEquals($expected, $grid);
    }



    public function testStringToGridUsingStringOf81CharsShouldSuccess()
    {
        $str = '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $str .= '123456789123456789123456789';
        $grid = Grid::stringToGrid($str);
        $this->assertCount(9, $grid);

        foreach ($grid as $row) {
            $this->assertCount(9, $row);
        }

        $expected = array(
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9'],
            ['1','2','3','4','5','6','7','8','9']
        );
        $this->assertEquals($expected, $grid);
    }

    public function testStringToGridUsingStringOf256CharsShouldSuccess()
    {
        $str  = '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $str .= '123456789ABCDEFG';
        $grid = Grid::stringToGrid($str);
        $this->assertCount(16, $grid);

        foreach ($grid as $row) {
            $this->assertCount(16, $row);
        }

        $expected = array(
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'],
            ['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G']
        );
        $this->assertEquals($expected, $grid);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Symbols’ list should be an array!
     */
    public function testCheckSymbolsUsingNotArrayShouldRaiseInvalidArgumentException()
    {
        Grid::checkSymbols('azerty', '.', 9);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Joker should be a string!
     */
    public function testCheckSymbolsUsingNotStringHasJokerShouldRaiseInvalidArgumentException()
    {
        Grid::checkSymbols(['a','z','e','r','t','y','u','i','o'], new \stdClass(), 9);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /require \d symbols/
     */
    public function testCheckSymbolsUsingArrayLessThan9ShouldRaiseInvalidArgumentException()
    {
        Grid::checkSymbols(['a','z','e','r','t','y'], '.', 9);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /require \d symbols/
     */
    public function testCheckSymbolsUsingArrayMoreThan9ShouldRaiseInvalidArgumentException()
    {
        Grid::checkSymbols(['a','z','e','r','t','y','u','i','o','p'], '.', 9);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /duplicates/
     */
    public function testCheckSymbolsUsingArrayHavingDuplicatesShouldRaiseInvalidArgumentException()
    {
        Grid::checkSymbols(['a','z','e','r','t','y','a','y','r'], '.', 9);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /symbol is equal to joker/
     */
    public function testCheckSymbolsUsingArrayContainingJokerCharShouldRaiseInvalidArgumentException()
    {
        Grid::checkSymbols(['a','z','e','r','t','y','u','.','o'], '.', 9);
    }
    
    public function testCheckSymbolsUsingValidArraySymbolsAndValidJokerCharShouldReturnTrue()
    {
        $this->assertTrue(Grid::checkSymbols(['a','z','e','r','t','y','u','i','o'], '.', 9));
        $this->assertTrue(Grid::checkSymbols(['a','z','e','r'], '.', 4));
        $this->assertTrue(Grid::checkSymbols(['1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'], '.', 16));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Joker must be a string/
     */
    public function testCheckJokerUsingNotStringShouldRaiseInvalidArgumentException()
    {
        Grid::checkJoker(new \stdClass());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Joker must have only one character!
     */
    public function testCheckJokerUsingVoidStringShouldRaiseInvalidArgumentException()
    {
        Grid::checkJoker('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Joker must have only one character!
     */
    public function testCheckJokerUsingMoreThanOnecharacterShouldRaiseInvalidArgumentException()
    {
        Grid::checkJoker('az');
    }
    
    public function testCheckJokerUsingOneCharStringShouldReturnTrue()
    {
        $this->assertTrue(Grid::checkJoker('Ç'));
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Grid must be an array!
     */
    public function testCheckGridVersusSymbolsUsingNotArrayGridShouldRaiseInvalidArgumentException()
    {
        Grid::checkGridVersusSymbols(
            new \stdClass(), 
            ['a','z','e','r','t','y','u','i','o'],
            '.'
        );
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Grid’s row must be an array!
     */
    public function testCheckGridVersusSymbolsUsingAtLeastInvalidRowsTypeShouldRaiseInvalidArgumentException()
    {
        Grid::checkGridVersusSymbols(
            [
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                'not good',
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
            ], 
            ['a','z','e','r','t','y','u','i','o'],
            '.'
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Grid content has symbols not defined into authorized list!
     */
    public function testCheckGridVersusSymbolsUsingAtLeastOneBoxesHavingNotDefinedSymbolShouldRaiseInvalidArgumentException()
    {
        Grid::checkGridVersusSymbols(
            [
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','p'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o']
            ], 
            ['a','z','e','r','t','y','u','i','o'],
            '.'
        );
    }
    
    public function testCheckGridVersusSymbolsUsingAuthorizedCharactersShouldReturnTrue()
    {
        $this->assertTrue(Grid::checkGridVersusSymbols(
            [
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o']
            ], 
            ['a','z','e','r','t','y','u','i','o'],
            '.'
        ));
    }
    
    public function testCheckGridVersusSymbolsUsingAuthorizedCharactersAndJokerShouldReturnTrue()
    {
        $this->assertTrue(Grid::checkGridVersusSymbols(
            [
                ['a','z','e','r'],
                ['a','z','e','r'],
                ['a','z','.','r'],
                ['a','z','e','r']
            ], 
            ['a','z','e','r'],
            '.'
        ));
        $this->assertTrue(Grid::checkGridVersusSymbols(
            [
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','.','r','t','.','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','.','.','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','.','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o']
            ], 
            ['a','z','e','r','t','y','u','i','o'],
            '.'
        ));
        
        $this->assertTrue(Grid::checkGridVersusSymbols(
            [
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','*','e','r','*','*','*','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['*','z','e','r','t','y','*','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o'],
                ['a','z','e','r','t','y','u','i','o']
            ], 
            ['a','z','e','r','t','y','u','i','o'],
            '*'
        ));
    }


    public function testInstanciateUsingValidArgumentsShouldSuccess()
    {
        $str  = 'AZERTYUIO';
        $str .= '_ZERTYUIO';
        $str .= 'AZ___YUIO';
        $str .= 'AZERTYUIO';
        $str .= 'AZ_RTY_IO';
        $str .= 'AZ_RTY_IO';
        $str .= 'AZ_RTY_IO';
        $str .= 'AZ_R_YUIO';
        $str .= 'AZ_RTYUIO';

        $grid = new Grid($str, 'AZERTYUIO', '_');
        $this->assertInstanceOf('Malenki\Game\NumberPlace\Grid', $grid);
    }

    public function testIfItIsFullShouldSuccess()
    {
        $str = 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->isFull());
        $str = 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'A.CDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertFalse($grid->isFull());
    }

    
    public function testGettingSpecificRowShouldReturnIt()
    {
        $str  = 'C...E..A.';
        $str .= 'EI...D.F.';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $row = $grid->getRowAt(1);

        $this->assertInternalType('array', $row);
        $this->assertCount(9, $row);
        
        $this->assertInternalType('object', $row[0]);
        $this->assertInternalType('object', $row[1]);
        $this->assertInternalType('object', $row[2]);
        $this->assertInternalType('object', $row[3]);
        $this->assertInternalType('object', $row[4]);
        $this->assertInternalType('object', $row[5]);
        $this->assertInternalType('object', $row[6]);
        $this->assertInternalType('object', $row[7]);
        $this->assertInternalType('object', $row[8]);
        
        $this->assertEquals('E', $row[0]);
        $this->assertEquals('I', $row[1]);
        $this->assertEquals('D', $row[5]);
        $this->assertEquals('F', $row[7]);
        
        $this->assertFalse($row[0]->isVoid());
        $this->assertFalse($row[1]->isVoid());
        $this->assertFalse($row[5]->isVoid());
        $this->assertFalse($row[7]->isVoid());
        
        $this->assertTrue($row[0]->isRevealed());
        $this->assertTrue($row[1]->isRevealed());
        $this->assertTrue($row[5]->isRevealed());
        $this->assertTrue($row[7]->isRevealed());
        
        $this->assertTrue($row[2]->isVoid());
        $this->assertTrue($row[3]->isVoid());
        $this->assertTrue($row[4]->isVoid());
        $this->assertTrue($row[6]->isVoid());
        $this->assertTrue($row[8]->isVoid());

        $this->assertFalse($row[2]->isRevealed());
        $this->assertFalse($row[3]->isRevealed());
        $this->assertFalse($row[4]->isRevealed());
        $this->assertFalse($row[6]->isRevealed());
        $this->assertFalse($row[8]->isRevealed());
    }
    
    public function testGettingSpecificColShouldReturnIt()
    {
        $str  = 'C...E..A.';
        $str .= 'EI...D.F.';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $col = $grid->getColAt(7);

        $this->assertInternalType('array', $col);
        $this->assertCount(9, $col);
        
        $this->assertInternalType('object', $col[0]);
        $this->assertInternalType('object', $col[1]);
        $this->assertInternalType('object', $col[2]);
        $this->assertInternalType('object', $col[3]);
        $this->assertInternalType('object', $col[4]);
        $this->assertInternalType('object', $col[5]);
        $this->assertInternalType('object', $col[6]);
        $this->assertInternalType('object', $col[7]);
        $this->assertInternalType('object', $col[8]);
        
        $this->assertEquals('A', $col[0]);
        $this->assertEquals('F', $col[1]);
        
        $this->assertFalse($col[0]->isVoid());
        $this->assertFalse($col[1]->isVoid());
        
        $this->assertTrue($col[0]->isRevealed());
        $this->assertTrue($col[1]->isRevealed());
        
        $this->assertTrue($col[2]->isVoid());
        $this->assertTrue($col[3]->isVoid());
        $this->assertTrue($col[4]->isVoid());
        $this->assertTrue($col[5]->isVoid());
        $this->assertTrue($col[6]->isVoid());
        $this->assertTrue($col[7]->isVoid());
        $this->assertTrue($col[8]->isVoid());

        $this->assertFalse($col[2]->isRevealed());
        $this->assertFalse($col[3]->isRevealed());
        $this->assertFalse($col[4]->isRevealed());
        $this->assertFalse($col[5]->isRevealed());
        $this->assertFalse($col[6]->isRevealed());
        $this->assertFalse($col[7]->isRevealed());
        $this->assertFalse($col[8]->isRevealed());

    }
    
    public function testGettingSpecificAreaShouldReturnIt()
    {
        $str  = 'ABCD';
        $str .= '.C..';
        $str .= 'DA..';
        $str .= 'C.A.';
        
        $grid = new Grid($str, 'ABCD', '.');
        $area = $grid->getAreaAt('1;0');
        
        $this->assertInternalType('array', $area);
        $this->assertCount(4, $area);
        
        $this->assertInternalType('object', $area[0]);
        $this->assertInternalType('object', $area[1]);
        $this->assertInternalType('object', $area[2]);
        $this->assertInternalType('object', $area[3]);

        $this->assertEquals('D', $area[0]);
        $this->assertEquals('A', $area[1]);
        $this->assertEquals('C', $area[2]);

        $this->assertTrue($area[0]->isRevealed());
        $this->assertTrue($area[1]->isRevealed());
        $this->assertTrue($area[2]->isRevealed());
        
        $this->assertFalse($area[0]->isVoid());
        $this->assertFalse($area[1]->isVoid());
        $this->assertFalse($area[2]->isVoid());

        $this->assertFalse($area[3]->isRevealed());
        $this->assertTrue($area[3]->isVoid());

        $str  = 'C...E..A.';
        $str .= 'EI...D.F.';
        $str .= '.........';
        $str .= '...B.....';
        $str .= '....G....';
        $str .= '.....F...';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $area = $grid->getAreaAt('0;2');

        $this->assertInternalType('array', $area);
        $this->assertCount(9, $area);
        
        $this->assertInternalType('object', $area[0]);
        $this->assertInternalType('object', $area[1]);
        $this->assertInternalType('object', $area[2]);
        $this->assertInternalType('object', $area[3]);
        $this->assertInternalType('object', $area[4]);
        $this->assertInternalType('object', $area[5]);
        $this->assertInternalType('object', $area[6]);
        $this->assertInternalType('object', $area[7]);
        $this->assertInternalType('object', $area[8]);

        $this->assertEquals('A', $area[1]);
        $this->assertEquals('F', $area[4]);

        $this->assertTrue($area[1]->isRevealed());
        $this->assertTrue($area[4]->isRevealed());
        
        $this->assertFalse($area[1]->isVoid());
        $this->assertFalse($area[4]->isVoid());

        $this->assertFalse($area[0]->isRevealed());
        $this->assertFalse($area[2]->isRevealed());
        $this->assertFalse($area[3]->isRevealed());
        $this->assertFalse($area[5]->isRevealed());
        $this->assertFalse($area[6]->isRevealed());
        $this->assertFalse($area[7]->isRevealed());
        $this->assertFalse($area[8]->isRevealed());

        $this->assertTrue($area[0]->isVoid());
        $this->assertTrue($area[2]->isVoid());
        $this->assertTrue($area[3]->isVoid());
        $this->assertTrue($area[5]->isVoid());
        $this->assertTrue($area[6]->isVoid());
        $this->assertTrue($area[7]->isVoid());
        $this->assertTrue($area[8]->isVoid());
        
        
        
        $area = $grid->getAreaAt('1;1');

        $this->assertInternalType('array', $area);
        
        $this->assertInternalType('object', $area[0]);
        $this->assertInternalType('object', $area[1]);
        $this->assertInternalType('object', $area[2]);
        $this->assertInternalType('object', $area[3]);
        $this->assertInternalType('object', $area[4]);
        $this->assertInternalType('object', $area[5]);
        $this->assertInternalType('object', $area[6]);
        $this->assertInternalType('object', $area[7]);
        $this->assertInternalType('object', $area[8]);

        $this->assertEquals('B', $area[0]);
        $this->assertEquals('G', $area[4]);
        $this->assertEquals('F', $area[8]);

        $this->assertTrue($area[0]->isRevealed());
        $this->assertTrue($area[4]->isRevealed());
        $this->assertTrue($area[8]->isRevealed());

        $this->assertFalse($area[0]->isVoid());
        $this->assertFalse($area[4]->isVoid());
        $this->assertFalse($area[8]->isVoid());

        $this->assertFalse($area[1]->isRevealed());
        $this->assertFalse($area[2]->isRevealed());
        $this->assertFalse($area[3]->isRevealed());
        $this->assertFalse($area[5]->isRevealed());
        $this->assertFalse($area[6]->isRevealed());
        $this->assertFalse($area[7]->isRevealed());

        $this->assertTrue($area[1]->isVoid());
        $this->assertTrue($area[2]->isVoid());
        $this->assertTrue($area[3]->isVoid());
        $this->assertTrue($area[5]->isVoid());
        $this->assertTrue($area[6]->isVoid());
        $this->assertTrue($area[7]->isVoid());
    }
        
    
    public function testGettingSpecificDiagonalShouldReturnIt()
    {
        $str  = 'C...E..A.';
        $str .= 'EI...D.F.';
        $str .= '.........';
        $str .= '...B.....';
        $str .= '....G....';
        $str .= '.....F...';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $diag = $grid->getDiagonalAt(0);

        $this->assertEquals('C', $diag[0]);
        $this->assertEquals('I', $diag[1]);
        $this->assertEquals('B', $diag[3]);
        $this->assertEquals('G', $diag[4]);
        $this->assertEquals('F', $diag[5]);

        $this->assertTrue($diag[0]->isRevealed());
        $this->assertTrue($diag[1]->isRevealed());
        $this->assertTrue($diag[3]->isRevealed());
        $this->assertTrue($diag[4]->isRevealed());
        $this->assertTrue($diag[5]->isRevealed());

        $this->assertFalse($diag[0]->isVoid());
        $this->assertFalse($diag[1]->isVoid());
        $this->assertFalse($diag[3]->isVoid());
        $this->assertFalse($diag[4]->isVoid());
        $this->assertFalse($diag[5]->isVoid());

        $this->assertFalse($diag[2]->isRevealed());
        $this->assertFalse($diag[6]->isRevealed());
        $this->assertFalse($diag[7]->isRevealed());
        $this->assertFalse($diag[8]->isRevealed());

        $this->assertTrue($diag[2]->isVoid());
        $this->assertTrue($diag[6]->isVoid());
        $this->assertTrue($diag[7]->isVoid());
        $this->assertTrue($diag[8]->isVoid());
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Row's index is not defined for this grid!
     */
    public function testGettingNonExistingRowIndexShouldRaiseOutOfRangeException()
    {
        $str  = 'ABCD';
        $str .= '.C..';
        $str .= 'DA..';
        $str .= 'C.A.';
        
        $grid = new Grid($str, 'ABCD', '.');
        $area = $grid->getRowAt(4);
    }


    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Column's index is not defined for this grid!
     */
    public function testGettingNonExistingColumnIndexShouldRaiseOutOfRangeException()
    {
        $str  = 'ABCD';
        $str .= '.C..';
        $str .= 'DA..';
        $str .= 'C.A.';
        
        $grid = new Grid($str, 'ABCD', '.');
        $area = $grid->getColAt(4);
    }


    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Diagonal's index must be 0 or 1!
     */
    public function testGettingNonExistingDiagonalIndexShouldRaiseOutOfRangeException()
    {
        $str  = 'ABCD';
        $str .= '.C..';
        $str .= 'DA..';
        $str .= 'C.A.';
        
        $grid = new Grid($str, 'ABCD', '.');
        $area = $grid->getDiagonalAt(2);
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Area's row index is not defined for this grid!
     */
    public function testGettingNonExistingAreaRowShouldRaiseOutOfRangeException()
    {
        $str  = 'ABCD';
        $str .= '.C..';
        $str .= 'DA..';
        $str .= 'C.A.';
        
        $grid = new Grid($str, 'ABCD', '.');
        $area = $grid->getAreaAt('2;0');
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Area's col index is not defined for this grid!
     */
    public function testGettingNonExistingAreaColumnShouldRaiseOutOfRangeException()
    {
        $str  = 'ABCD';
        $str .= '.C..';
        $str .= 'DA..';
        $str .= 'C.A.';
        
        $grid = new Grid($str, 'ABCD', '.');
        $area = $grid->getAreaAt('0;2');
    }
    
    
    public function testCheckingRowShouldSuccess()
    {
        $str  = 'ABCDEFGHI';
        $str .= 'EI...D.F.';
        $str .= '.........';
        $str .= '...B.....';
        $str .= '....G....';
        $str .= '.....F...';
        $str .= '.........';
        $str .= '.........';
        $str .= '.........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->checkRowAt(0));
        $this->assertfalse($grid->checkRowAt(1));
        $this->assertfalse($grid->checkRowAt(2));
        $this->assertfalse($grid->checkRowAt(3));
        $this->assertfalse($grid->checkRowAt(4));
        $this->assertfalse($grid->checkRowAt(5));
        $this->assertfalse($grid->checkRowAt(6));
        $this->assertfalse($grid->checkRowAt(7));
        $this->assertfalse($grid->checkRowAt(8));
    }
    
    public function testCheckingColShouldSuccess()
    {
        $str  = 'ABCDEFGHI';
        $str .= 'EI...D.F.';
        $str .= 'B........';
        $str .= 'C..B.....';
        $str .= 'D...G....';
        $str .= 'G....F...';
        $str .= 'F........';
        $str .= 'H........';
        $str .= 'I........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->checkColAt(0));
        $this->assertfalse($grid->checkColAt(1));
        $this->assertfalse($grid->checkColAt(2));
        $this->assertfalse($grid->checkColAt(3));
        $this->assertfalse($grid->checkColAt(4));
        $this->assertfalse($grid->checkColAt(5));
        $this->assertfalse($grid->checkColAt(6));
        $this->assertfalse($grid->checkColAt(7));
        $this->assertfalse($grid->checkColAt(8));
    }
    
    public function testCheckingAreaShouldSuccess()
    {
        $str  = 'AFCDEBGHI';
        $str .= 'EIG..D.F.';
        $str .= 'BHD......';
        $str .= 'C..B.....';
        $str .= 'D...G....';
        $str .= 'G....F...';
        $str .= 'F........';
        $str .= 'H........';
        $str .= 'I........';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->checkAreaAt('0;0'));
        $this->assertfalse($grid->checkAreaAt('1;1'));
        $this->assertfalse($grid->checkAreaAt('2;2'));
        $this->assertfalse($grid->checkAreaAt('0;1'));
        $this->assertfalse($grid->checkAreaAt('1;2'));
        $this->assertfalse($grid->checkAreaAt('2;0'));
        $this->assertfalse($grid->checkAreaAt('0;2'));
        $this->assertfalse($grid->checkAreaAt('1;0'));
        $this->assertfalse($grid->checkAreaAt('2;1'));
    }
    
    public function testCheckingDiagonalShouldSuccess()
    {
        $str  = 'AFCDEBGHI';
        $str .= 'EIG..D.F.';
        $str .= 'BHD......';
        $str .= 'C..B.....';
        $str .= 'D...G....';
        $str .= 'G....F...';
        $str .= 'F.....C..';
        $str .= 'H......E.';
        $str .= 'I.......H';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $grid->useDiagonal();
        $this->assertTrue($grid->checkDiagonalAt(0));
        $this->assertfalse($grid->checkDiagonalAt(1));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCheckingDiagonalIfOptionNotSetShouldRaiseRuntimeException()
    {
        $str  = 'AFCDEBGHI';
        $str .= 'EIG..D.F.';
        $str .= 'BHD......';
        $str .= 'C..B.....';
        $str .= 'D...G....';
        $str .= 'G....F...';
        $str .= 'F.....C..';
        $str .= 'H......E.';
        $str .= 'I.......H';

        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $grid->checkDiagonalAt(0);
    }


    public function testCheckingRowsShouldSuccess()
    {
        $str = 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $str .= 'ABCDEFGHI';
        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->checkRows());
    }


    public function testCheckingColsShouldSuccess()
    {
        $str  = 'AAAAAAAAA';
        $str .= 'BBBBBBBBB';
        $str .= 'CCCCCCCCC';
        $str .= 'DDDDDDDDD';
        $str .= 'EEEEEEEEE';
        $str .= 'FFFFFFFFF';
        $str .= 'GGGGGGGGG';
        $str .= 'HHHHHHHHH';
        $str .= 'IIIIIIIII';
        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->checkCols());
    }

    public function testCheckingAreasShouldSuccess()
    {
        $str  = 'ABCABCABC';
        $str .= 'DEFDEFDEF';
        $str .= 'GHIGHIGHI';
        $str .= 'ABCABCABC';
        $str .= 'DEFDEFDEF';
        $str .= 'GHIGHIGHI';
        $str .= 'ABCABCABC';
        $str .= 'DEFDEFDEF';
        $str .= 'GHIGHIGHI';
        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->checkAreas());
    }

    public function testCheckingDiagonalsShouldSuccess()
    {
        $str  = 'A.......I';
        $str .= '.B.....H.';
        $str .= '..C...G..';
        $str .= '...D.F...';
        $str .= '....E....';
        $str .= '...D.F...';
        $str .= '..C...G..';
        $str .= '.B.....H.';
        $str .= 'A.......I';
        $grid = new Grid($str, 'ABCDEFGHI', '.');
        $this->assertTrue($grid->useDiagonal()->checkDiagonals());
    }

    public function testCheckAllUsingDiagonalsShouldSuccess()
    {
        $str  = '415638972';
        $str .= '362479185';
        $str .= '789215364';
        $str .= '926341758';
        $str .= '138756429';
        $str .= '574982631';
        $str .= '257164893';
        $str .= '843597216';
        $str .= '691823547';
        $grid = new Grid($str, '123456789', '.');
        $this->assertTrue($grid->useDiagonal()->check());
    }
}
