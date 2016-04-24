<?php


use \Malenki\Game\NumberPlace\Grid;
use \Malenki\Game\NumberPlace\Box;

class BoxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Row's index must be a not negative integer!
     */
    public function testInstanciatingWithBadValueTypeForRowCoordShouldRaiseInvalidArgumentException()
    {
        new Box('foo', 3, new Grid('1234432112344321', '1234'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Row's index must be a not negative integer!
     */
    public function testInstanciatingWithNegativeValueForRowCoordShouldRaiseInvalidArgumentException()
    {
        new Box(-3, 3, new Grid('1234432112344321', '1234'));
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Coordinates must be inside grid!
     */
    public function testInstanciatingWithValueOutsideGridDefForRowCoordShouldRaiseOutOfRangeException()
    {
        new Box(4, 0, new Grid('1234432112344321', '1234'));
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Column's index must be a not negative integer!
     */
    public function testInstanciatingWithBadValueTypeForColCoordShouldRaiseInvalidArgumentException()
    {
        new Box(3, 'foo', new Grid('1234432112344321', '1234'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Column's index must be a not negative integer!
     */
    public function testInstanciatingWithNegativeValueForColCoordShouldRaiseInvalidArgumentException()
    {
        new Box(3, -3, new Grid('1234432112344321', '1234'));
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Coordinates must be inside grid!
     */
    public function testInstanciatingWithValueOutsideGridDefForColCoordShouldRaiseOutOfRangeException()
    {
        new Box(0, 4, new Grid('1234432112344321', '1234'));
    }

    public function testInstanciatingUsingValidArgumentsShouldSuccess()
    {
        $this->assertInstanceOf(
            '\Malenki\Game\NumberPlace\Box',
            new Box(0, 2, new Grid('1234432112344321', '1234'))
        );
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cell's value must be a not negative integer!
     */
    public function testSettingNotValidTypeForValueShouldRaiseInvalidArgumentException()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(array('foo'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cell's value must be a not negative integer!
     */
    public function testSettingNegativeValueForValueShouldRaiseInvalidArgumentException()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(-3);
    }

    /**
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Cell's value must be into the range defined by the grid!
     */
    public function testSettingNotInRangeValueShouldRaiseInvalidArgumentException()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(5);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot change value of revealedCell!
     */
    public function testChangingValudOfRevealedCellShouldRaiseRuntimeException()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(3);
        $box->setAsRevealed();
        $box->setValue(1);
    }
    
    public function testSettingPositiveOrZeroValueForValueShouldSuccess()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(3);
        $box = new Box(0, 1, new Grid('1234432112344321', '1234'));
        $box->setValue(0);
    }

    public function testSettingValidValueShouldGettingTheSame()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(3);
        $this->assertEquals(3, $box->getValue());
        $box = new Box(0, 1, new Grid('1234432112344321', '1234'));
        $box->setValue(0);
        $this->assertEquals(0, $box->getValue());
    }
    
    public function testGettingSymbolShouldSuccess()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(2);
        $this->assertEquals('3', $box->getSymbol());
        $this->assertEquals("$box", $box->getSymbol());
        $box = new Box(1, 1, new Grid('ABCDABCDABCDABCD', 'ABCD'));
        $box->setValue(1);
        $this->assertEquals('B', $box->getSymbol());
        $this->assertEquals("$box", $box->getSymbol());
        $box = new Box(0, 1, new Grid('ABCDABCDABCDABCD', 'ABCD'));
        $box->setValue(0);
        $this->assertEquals('A', $box->getSymbol());
        $this->assertEquals("$box", $box->getSymbol());
    }

    public function testGettingSymbolOfVoidBoxShouldReturnJokerSymbol()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(2);
        $box->clear();
        $this->assertEquals('.', $box->getSymbol());
        $box = new Box(0, 2, new Grid('1234432112344321', '1234', '_'));
        $this->assertEquals('_', $box->getSymbol());
    }

    public function testCellMarkedAsRevealedShouldBeDeclaredAsIt()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(2);
        $this->assertFalse($box->isRevealed());
        $box->setAsRevealed();
        $this->assertTrue($box->isRevealed());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot clear value of revealed cell!
     */
    public function testClearingValueOfRevealedCellShouldRaiseRuntimeException()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(2);
        $box->setAsRevealed();
        $box->clear();
    }

    public function testClearingCellContentShouldBeDeclaredAsIt()
    {
        $box = new Box(0, 2, new Grid('1234432112344321', '1234'));
        $box->setValue(2);
        $this->assertFalse($box->isVoid());
        $box->clear();
        $this->assertTrue($box->isVoid());
        
        $box = new Box(0, 1, new Grid('1234432112344321', '1234'));
        $box->setValue(0);
        $this->assertFalse($box->isVoid());
        $box->clear();
        $this->assertTrue($box->isVoid());
    }

    public function testValidCellShouldHaveCoordinates()
    {
        $box = new Box(0, 1, new Grid('1234432112344321', '1234'));
        $coord = $box->getCoordinates();

        $this->assertInternalType('object', $coord);
        $this->assertObjectHasAttribute('row', $coord);
        $this->assertObjectHasAttribute('col', $coord);

        $this->assertEquals(0, $coord->row);
        $this->assertEquals(1, $coord->col);
    }
}
