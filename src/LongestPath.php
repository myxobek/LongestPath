<?php

namespace Myxobek\LongestPath;

class LongestPath
{
    public $maximum_rows = 1000;
    public $maximum_cols = 1000;
    public $maximum_height = -INF;
    
    public $int2point_multiplier = 10000;
    
    // N, M, matrix
    public $area_num_rows;
    public $area_num_cols;
    public $area = [ ];
    
    // matrix NxM, where a[i][j] - distance to some highest summit
    public $longest_distance_through_point = [ ];
    
    // points sorted by height
    public $sorted_heights = [ ];
    
    // longest path length and path itself
    public $longest_path_length = 1;
    public $longest_path        = [];
    
    public $longest_paths       = [];
    
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
        // read file line by line
        $handle = fopen( $filename, 'r' );
        if ( $handle )
        {
            $param = fgets( $handle );
            
            list( $this->area_num_rows, $this->area_num_cols ) = explode( ' ', $param );
            
            for ( $i = 0; $i < $this->area_num_rows; ++$i )
            {
                $line = fgets( $handle );
                $line = substr( $line, 0, -1 );
                $line_split = explode( ' ', $line );
                for ( $j = 0; $j < $this->area_num_cols; ++$j )
                {
                    $this->area[ $i ][] = $line_split[ $j ];
                    
                    $point = [
                        'x' => $i,
                        'y' => $j
                    ];
                    
                    $int_point = $this->point2int( $point );
                    
                    $this->sorted_heights[ $this->area[ $i ][ $j ] ][] = $int_point;
                    
                    // firstly, all distances are not known
                    $this->longest_distance_through_point[ $i ][ $j ] = -INF;
                }
            }
    
            // sort so that the highest points are ahead
            krsort( $this->sorted_heights );
        }
        else
        {
            echo( 'FILE READ ERROR!' );
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * getAnswer
     *
     * Returns answer for the task
     *
     * @author      myxobek
     *
     * @return      array
     */
    public function getAnswer()
    {
        $this->buildPaths();
    
        $longest_paths_ends = $this->getLongestDistanceEndPoints();
        
        $this->restorePaths( $longest_paths_ends );
        
        $this->longest_path = $this->selectSteepestPath( $this->longest_paths );
        
        return [
            'length'    => $this->longest_path_length,
            'path'      => implode('-', $this->longest_path )
        ];
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * restorePaths
     *
     * Restores paths for all points
     *
     * @author      myxobek
     *
     * @param   array   $points_array
     */
    public function restorePaths( $points_array )
    {
        for( $i = 0, $n = count( $points_array ); $i < $n; ++$i )
        {
            $this->restorePathFromPoint( $points_array[$i], []  );
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * restorePathFromPoint
     *
     * Recursively restore path from point till the highest summit
     *
     * @author      myxobek
     *
     * @param   array   $cur_point
     * @param   array   $path
     */
    public function restorePathFromPoint( $cur_point, $path )
    {
        $x = $cur_point['x'];
        $y = $cur_point['y'];
        
        if ( $this->longest_distance_through_point[ $x ][ $y ] === 1 )
        {
            $this->longest_paths[] = array_reverse( array_merge( $path, [ $this->area[ $x ][ $y ] ] ) );
        }
        
        $cur_distance = $this->longest_distance_through_point[ $x ][ $y ];
        
        $path[] = $this->area[ $x ][ $y ];
    
        // go top
        if (
            ( ( $x - 1 ) > -1 ) &&
            ( $cur_distance-1 === $this->longest_distance_through_point[ $x-1 ][ $y ] ) &&
            $this->area[$x][$y] < $this->area[$x-1][$y]
        )
        {
            $new_point = [
                'x' => $x - 1,
                'y' => $y
            ];
    
            $this->restorePathFromPoint( $new_point, $path );
        }
        // go right
        if (
            ( ( $y + 1 ) < $this->area_num_cols ) &&
            ( $cur_distance-1 === $this->longest_distance_through_point[ $x ][ $y+1 ] ) &&
            $this->area[$x][$y] < $this->area[$x][$y+1]
        )
        {
            $new_point = [
                'x' => $x,
                'y' => $y + 1
            ];
    
            $this->restorePathFromPoint( $new_point, $path );
        }
        // go bottom
        if (
            ( ( $x + 1 ) < $this->area_num_rows ) &&
            ( $cur_distance-1 === $this->longest_distance_through_point[ $x+1 ][ $y ] ) &&
            $this->area[$x][$y] < $this->area[$x+1][$y]
        )
        {
            $new_point = [
                'x' => $x + 1,
                'y' => $y
            ];
    
            $this->restorePathFromPoint( $new_point, $path );
        }
        // go left
        if (
            ( ( $y - 1 ) > -1 ) &&
            ( $cur_distance-1 === $this->longest_distance_through_point[ $x ][ $y-1 ] ) &&
            $this->area[$x][$y] < $this->area[$x][$y-1]
        )
        {
            $new_point = [
                'x' => $x,
                'y' => $y - 1
            ];
    
           $this->restorePathFromPoint( $new_point, $path );
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * selectSteepestPath
     *
     * @author      myxobek
     *
     * @param       array       $paths
     *
     * @return      array
     */
    public function selectSteepestPath( $paths )
    {
        $i          = 0;
        $max_diff   = -INF;
        
        foreach ( $paths as $key => $path )
        {
            if ( $path[0] - $path[ count( $path ) -1 ] > $max_diff )
            {
                $i          = $key;
                $max_diff   = $path[0] - $path[ count( $path ) -1 ];
            }
        }
        
        return $paths[$i];
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * getLongestDistanceEndPoints
     *
     * Searches for ending points
     *
     * @author      myxobek
     *
     * @return      array
     */
    public function getLongestDistanceEndPoints()
    {
        $longest_paths_ends = [];
    
        for ( $i = 0, $n = count( $this->longest_distance_through_point ); $i < $n; ++$i )
        {
            for ( $j = 0, $m = count( $this->longest_distance_through_point ); $j < $m; ++$j )
            {
                if ( $this->longest_distance_through_point[$i][$j] === $this->longest_path_length )
                {
                    $longest_paths_ends[] = [
                        'x' => $i,
                        'y' => $j
                    ];
                }
            }
        }
        
        return $longest_paths_ends;
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * buildPaths
     *
     * For every not visited point should try to 'ski' from it :)
     *
     * @author      myxobek
     *
     */
    public function buildPaths()
    {
        foreach( $this->sorted_heights as $height => $int_points )
        {
            for ( $j = 0, $m = count( $int_points ); $j < $m; ++$j )
            {
                $point = $this->int2point( $int_points[ $j ] );
                
                if ( $this->longest_distance_through_point[ $point['x'] ][ $point['y'] ] === -INF )
                {
                    $this->longest_distance_through_point[ $point['x'] ][ $point['y'] ] = 1;
                    
                    $this->longest_path         = [ $point ];
                    $this->longest_path_length  = max( $this->longest_path_length, 1 );
    
                    $this->skiDown( $point, 1 );
                }
            }
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * skiDown
     *
     * Calculates all distances to all points it can reach
     *
     * @author      myxobek
     *
     * @param   array       $cur_point
     * @param   int         $distance
     */
    public function skiDown( $cur_point, $distance )
    {
        $x = $cur_point[ 'x' ];
        $y = $cur_point[ 'y' ];
    
        // go top
        if ( ( ( $x - 1 ) > -1 ) && ( $this->area[ $x - 1 ][ $y ] < $this->area[ $x ][ $y ] ) )
        {
            $new_point = [
                'x' => $x - 1,
                'y' => $y
            ];
    
            $this->_trySkiThere( $new_point, $distance );
        }
        // go right
        if ( ( ( $y + 1 ) < $this->area_num_cols ) && ( $this->area[ $x ][ $y + 1 ] < $this->area[ $x ][ $y ] ) )
        {
            $new_point = [
                'x' => $x,
                'y' => $y + 1
            ];
    
            $this->_trySkiThere( $new_point, $distance );
        }
        // go bottom
        if ( ( ( $x + 1 ) < $this->area_num_rows ) && ( $this->area[ $x + 1 ][ $y ] < $this->area[ $x ][ $y ] ) )
        {
            $new_point = [
                'x' => $x + 1,
                'y' => $y
            ];
    
            $this->_trySkiThere( $new_point, $distance );
        }
        // go left
        if ( ( ( $y - 1 ) > -1 ) && ( $this->area[ $x ][ $y - 1 ] < $this->area[ $x ][ $y ] ) )
        {
            $new_point = [
                'x' => $x,
                'y' => $y - 1
            ];
    
            $this->_trySkiThere( $new_point, $distance );
        }
            
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * point2int
     *
     * @author      myxobek
     *
     * @param   array $point - [x,y] x - row num, y - col num
     *
     * @return  int
     */
    public function point2int( $point )
    {
        return $point[ 'x' ] * $this->int2point_multiplier + $point[ 'y' ];
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * int2point
     *
     * @author      myxobek
     *
     * @param   int $integer
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
    
    /**
     * _trySkiThere
     *
     * @author      myxobek
     *
     * @param   array   $new_point
     * @param   int     $distance
     */
    private function _trySkiThere( $new_point, $distance )
    {
        if ( $this->longest_distance_through_point[ $new_point['x'] ][ $new_point['y'] ] < ( $distance + 1 ) )
        {
            $this->longest_distance_through_point[ $new_point['x'] ][ $new_point['y'] ] = $distance + 1;
        
            $this->longest_path_length = max( $this->longest_path_length, $distance + 1 );
        
            $this->skiDown( $new_point, $distance + 1 );
        }
    }
    
    ///////////////////////////////////////////////////////////////////////////
    
    /**
     * _show2dimArray
     *
     * Human-readable output for double-dimensional arrays
     *
     * @author      myxobek
     *
     * @param $array
     */
    private function _show2dimArray( $array )
    {
        echo("\n");
        
        for( $i = 0, $n = count( $array ); $i < $n; ++$i )
        {
            for ( $j = 0, $m = count( $array[$j] ); $j < $m; ++$j )
            {
                echo( $array[$i][$j] . " " );
            }
            
            echo ( "\n" );
        }
        
        echo("\n");
    }
    
    ///////////////////////////////////////////////////////////////////////////
}
