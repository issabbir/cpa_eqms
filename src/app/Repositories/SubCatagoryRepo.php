<?php


namespace App\Repositories;


use App\Entities\Eqms\SubCategories;
use Illuminate\Http\Request;

class SubCatagoryRepo
{
    protected  $sub_catagory;
    protected $request;

    /**
     *
     * SubCatagoryRepo constructor.
     * @param SubCategories $sub_categories
     */
    public function __construct(SubCategories $sub_categories)
    {
        $this->sub_catagory = $sub_categories;
    }

    /**
     * Find all sub_catagorys for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->sub_catagory->get();
    }

    /**
     * Find sub_catagory specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->sub_catagory->where('sub_catagory_no', $id)->first();
    }

    public function findSubCatagories($catagory_no) {
        return $this->sub_catagory->where('catagory_no', $catagory_no)->get();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getsub_catagory() {
        return $this->sub_catagory;
    }
}
