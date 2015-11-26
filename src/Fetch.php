<?php

namespace rcbytes\Fetch;

use App;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Builder;

class Fetch extends Facade
{

    protected static function getFacadeAccessor() { return 'fetch'; }

    public function __construct()
    {

    }


    /**
     * Get all of the models from the database.
     *
     * @param  Builder  $qb
     * @param  array    $where
     * @param  integer  $paginate (per page)
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all(Builder $qb, $where = [], $paginate = 0)
    {   

        $instance = new static;        
        $request = App::make('Illuminate\Http\Request');

        $qb = $instance->where($qb, $where, $request);
        $qb = $instance->with($qb, $request);
        $qb = $instance->take($qb, $request);

        $paginate = Input::query('paginate', $paginate);

        return $paginate ? $qb->paginate($paginate): $qb->get();

    }


    /**
     * Queries models
     *
     * @param  Builder  $qb
     * @param  array    $where
     * @param  integer  $paginate (per page)
     *
     * @return Builder Returns the Builder
     */
    private function where(Builder $qb, array $where, Request $request)
    {

        // Where

        $query = json_decode(\Input::get('where', '[]'));

        if (is_object($query)){
            $query = get_object_vars($query);
        } 

        // makes sure your "wheres" are not overwritten
        $where = array_merge($query, $where);

        foreach ($where as $key => $value) {
                    
            $operand = '=';

            if (is_object($value)) {
                
                $arr = (array) $value;
                $operand = array_keys($arr)[0];             
                $value = $arr[$operand];
                
            } elseif (is_array($value)) {

                $operand = $value[0];
                $value = $value[1];

            }

            // timestamp filters
            // e.g.: where : { createdFrom : '2016-10-10', createdUntill : '2019-10-10' }
            if ($key == 'createdFrom') {

                $qb = $qb->whereDate('created_at', '>=', $value);

            } elseif ($key == 'createdUntill') {
                
                $qb = $qb->whereDate('created_at', '<=', $value);

            } else {

                // only 1 level deep - for now
                $pos = strpos($key, '.');

                if ($pos !== false) {
                    
                    $xpl = explode('.', $key);
                    $relationship = $xpl[0];
                    $attr = $xpl[1];

                    $qb = $qb->whereHas($relationship, function ($query) use ($attr, $operand, $value) {
                        
                        $query->where($attr, $operand, $value);

                    });


                } else {

                    $qb = $qb->where($key, $operand, $value);
                
                }

            }

        }

        return $qb;

    }

    public function with(Builder $qb, Request $request)
    {

        $with = $request->with ? json_decode($request->with) : [];

        if ($with) {

            foreach ($with as $key => $relationship) {

                $qb = $qb->with($relationship);
            
            }
        
        }

        return $qb;
        
    }

    public function take(Builder $qb, Request $request)
    {
        
        if ($request->take) {
            
            $qb = $qb->take($request->take);

        }

        return $qb;

    }

    public static function __callStatic($method, $parameters)
    {
        $instance = new static;

        return call_user_func_array([$instance, $method], $parameters);
    }

}
