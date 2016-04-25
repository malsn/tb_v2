<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 25.04.2016
 * Time: 13:08
 */

namespace Tests\TooBig\AppBundle\Model;

use TooBig\AppBundle\Model\BrandModel;

class BrandModelTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $calc = new BrandModel();
        $result = $calc->add(30, 12);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }
}