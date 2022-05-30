<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\ArrayUtil;

use function PHPUnit\Framework\assertTrue;

class ArrayUtilTest extends TestCase {

    public function test_removeElementFromArray() {
        // given
        $arr = [0,1,2,3];
        $removeableIndex = 2;
        $removeableElement = $arr[$removeableIndex];
        $expectedSize = count($arr) - 1;

        // when
        $arr = ArrayUtil::removeElementByIndex($arr, $removeableIndex);

        // then
        $this->assertEquals($expectedSize, count($arr));
        $this->assertNotEquals($removeableElement, $arr[$removeableIndex]);
    }

    public function test_removeElementFromArray_exception_throwing() {
        // given
        $arr = [0,1];
        $incorrectIndex = count($arr) + 2;
        $expectedMessage = 'Index out of array';

        // when
        $this->expectExceptionMessage($expectedMessage);
        ArrayUtil::removeElementByIndex($arr, $incorrectIndex);

        // then
    }

    public function test_getRandomIndex() {
        // given
        $arr = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        $allIndexesWereCorrect = true;

        // when
        for ($i = 0; $i < 20; ++$i) {
            $randomIndex = ArrayUtil::getRandomIndex($arr);
            $allIndexesWereCorrect = $allIndexesWereCorrect && $randomIndex >= 0 && $randomIndex < count($arr);
        }

        // then
        assertTrue($allIndexesWereCorrect);
    }

    public function test_getRandomIndex_empty_array() {
        // given
        $arr = [];
        $expectedMessage = 'Array is empty';

        // when
        $this->expectExceptionMessage($expectedMessage);
        ArrayUtil::getRandomIndex($arr);

        // then
    }

    public function test_getRandomElement() {
        // given
        $arr = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        $allElementsWereCorrect = true;

        // when
        for ($i = 0; $i < 20; ++$i) {
            $randomElement = ArrayUtil::getRandomElement($arr);
            $allElementsWereCorrect = $allElementsWereCorrect && in_array($randomElement, $arr);
        }

        // then
        assertTrue($allElementsWereCorrect);
    }

    public function test_getRandomElement_empty_array() {
        // given
        $arr = [];
        $expectedMessage = 'Array is empty';

        // when
        $this->expectExceptionMessage($expectedMessage);
        ArrayUtil::getRandomElement($arr);

        // then
    }

}