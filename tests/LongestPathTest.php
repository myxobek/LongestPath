<?php

use Myxobek\LongestPath\LongestPath;

class LongestPathTest extends PHPUnit_Framework_TestCase
{
    private $cur_dir = '';
    
    ///////////////////////////////////////////////////////////////////////////
    
    private function _getCurrentFileDirectory()
    {
        return realpath(dirname(__FILE__));
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function SetUp()
    {
        $this->cur_dir = $this->_getCurrentFileDirectory();
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testInit()
    {
        $longestPath = new LongestPath();
        
        $longestPath->init( $this->cur_dir . '/test_files/test_1.txt'  );
        
        $this->assertEquals( intval( $longestPath->area_num_cols ), 4 );
        $this->assertEquals( intval( $longestPath->area_num_rows ), 4 );
        $this->assertTrue( is_array( $longestPath->area ) );
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testInt2Point()
    {
        $longestPath = new LongestPath();
        
        $this->assertEquals( $longestPath->point2int(['x' => 1, 'y' => 1]), 10001 );
        $this->assertEquals( $longestPath->point2int(['x' => 1000, 'y' => 1000]), 10001000 );
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testPoint2Int()
    {
        $longestPath = new LongestPath();
        
        $this->assertEquals( $longestPath->int2point(10001), ['x' => 1, 'y' => 1] );
        $this->assertEquals( $longestPath->int2point(10001000), ['x' => 1000, 'y' => 1000 ]);
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testGetAnswer1()
    {
        $longestPath = new LongestPath();
    
        $longestPath->init( $this->cur_dir . '/test_files/test_1.txt'  );
    
        $this->assertEquals( $longestPath->getAnswer()['length'], 5 );
        $this->assertEquals( $longestPath->getAnswer()['path'], '9-5-3-2-1' );
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testGetAnswer2()
    {
        $longestPath = new LongestPath();
        
        $longestPath->init( $this->cur_dir . '/test_files/test_2.txt'  );
        
        $this->assertEquals( $longestPath->getAnswer()['length'], 1 );
        $this->assertEquals( $longestPath->getAnswer()['path'], '1' );
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testGetAnswer3()
    {
        $longestPath = new LongestPath();
        
        $longestPath->init( $this->cur_dir . '/test_files/test_3.txt'  );
        
        $this->assertEquals( $longestPath->getAnswer()['length'], 3 );
        $this->assertEquals( $longestPath->getAnswer()['path'], '3-2-1' );
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testGetAnswer4()
    {
        $longestPath = new LongestPath();
        
        $longestPath->init( $this->cur_dir . '/test_files/test_4.txt'  );
        
        $this->assertEquals( $longestPath->getAnswer()['length'], 11 );
        $this->assertEquals( $longestPath->getAnswer()['path'], '1496-1479-1407-1371-1133-951-693-542-343-32-27' );
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    public function testGetAnswer5()
    {
        $longestPath = new LongestPath();
        
        $longestPath->init( $this->cur_dir . '/test_files/test_5.txt'  );
        
        $this->assertEquals( $longestPath->getAnswer()['length'], 14 );
        $this->assertEquals( $longestPath->getAnswer()['path'], '1481-1224-1109-1093-1090-1039-857-630-505-496-352-342-254-67' );
    }
    
    ///////////////////////////////////////////////////////////////////////////
}