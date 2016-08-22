<?php

namespace Myxobek\LongestPath;

class LongestPath
{
    public $maximum_rows    = 1000;
    public $maximum_cols    = 1000;
    public $maximum_height  = 1500;
    
    public $int2point_multiplier = 1001;
    
    public $area_num_rows;
    public $area_num_cols;
    public $area = [];
    
    public $distances = [];
    
    public $sorted_heights = [];
    
    public $visited_points = [];
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * LongestPath constructor.
     */
    public function __construct()
    {
        
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * init
     *
     * @author      myxobek
     *
     * @param $filename
     */
    public function init( $filename )
    {
        $handle = fopen( $filename, 'r' );
        if ( $handle )
        {
            $param = fgets( $handle );
        
            list( $this->area_num_rows, $this->area_num_cols ) = explode( ' ', $param );
        
            for ( $i = 0; $i < $this->area_num_rows; ++$i )
            {
                $line = fgets( $handle );
                $line_split = explode( ' ', $line );
                for ( $j = 0; $j < $this->area_num_cols; ++$j )
                {
                    $this->area[ $i ][] = $line_split[ $j ];
                    
                    $point = [
                        'x' => $i,
                        'y' => $j
                    ];
                    
                    $int_point = $this->point2int( $point );
                    
                    $this->sorted_heights[ $int_point ] = $this->area[$i][$j];
                    
                    $this->visited_points[ $int_point ] = false;
                    
                    $this->distances[ $int_point ] = [];
                }
            }
        }
        else
        {
            echo('FILE READ ERROR!');
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * point2int
     *
     * @author      myxobek
     *
     * @param   array   $point - [x,y] x - row num, y - col num
     *
     * @return  int
     */
    public function point2int( $point )
    {
        return $point['x'] * $this->int2point_multiplier + $point['y'];
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * int2point
     *
     * @author      myxobek
     *
     * @param   int     $integer
     *
     * @return  array
     */
    public function int2point( $integer )
    {
        return [
            'x' => intval( $integer / $this->int2point_multiplier ),
            'y' => $integer % $this->int2point_multiplier
        ];
    }
    
    ///////////////////////////////////////////////////////////////////////////
}
