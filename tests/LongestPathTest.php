<?php

use Myxobek\LongestPath\LongestPath;

class LongestPathTest extends PHPUnit_Framework_TestCase
{
    private $cur_dir = '';
    
    private function _getCurrentFileDirectory()
    {
        return realpath(dirname(__FILE__));
    }
    
    public function SetUp()
    {
        $this->cur_dir = $this->_getCurrentFileDirectory();
    }
    
    public function test1()
    {
        $longestPath = new LongestPath();
        
        $longestPath->init( $this->cur_dir . '/test_files/test_1.txt'  );
        
        $this->assertEquals( intval( $longestPath->area_num_cols ), 4 );
        $this->assertEquals( intval( $longestPath->area_num_rows ), 4 );
        $this->assertTrue( is_array( $longestPath->area ) );
    }
    
    public function testInt2Point()
    {
        $longestPath = new LongestPath();
        
        $this->assertEquals( $longestPath->point2int(['x' => 1, 'y' => 1]), 1002 );
        $this->assertEquals( $longestPath->point2int(['x' => 1000, 'y' => 1000]), 1002000 );
    }
    
    public function testPoint2Int()
    {
        $longestPath = new LongestPath();
        
        $this->assertEquals( $longestPath->int2point(1002), ['x' => 1, 'y' => 1] );
        $this->assertEquals( $longestPath->int2point(1002000), ['x' => 1000, 'y' => 1000 ]);
    }
}