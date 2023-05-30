<?php


namespace App\Repositories;


use App\Entities\Eqms\Categories;
use Illuminate\Http\Request;

class CatagoryRepo
{
    protected  $catagory;
    protected $request;

    /**
     *
     * CatagoryRepo constructor.
     * @param Categories $categories
     */
    public function __construct(Categories $categories)
    {
        $this->catagory = $categories;
    }

    /**
     * Find all catagorys for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->catagory->orderBy('catagory_no', 'desc')->get();
    }

    /**
     * Find catagory specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->catagory->where('catagory_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getCatagory() {
        return $this->catagory;
    }
}
