<?php


namespace App\Repositories;


use App\Entities\Eqms\VendorList;
use App\Entities\Eqms\VendorType;
use Illuminate\Http\Request;

class VendorRepo
{
    protected  $vendor;
    protected $vendorType;
    protected $request;

    /**
     *
     * VendorRepo constructor.
     * @param VendorList $vendorList
     */
    public function __construct(VendorList $vendorList, VendorType $vendorType)
    {
        $this->vendor = $vendorList;
        $this->vendorType = $vendorType;
    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->vendor->orderBy('vendor_no', 'desc')->get();
    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAllVendorType() {
        //Todo: Applied filters option as you need
        return $this->vendorType->orderBy('vendor_type_no', 'desc')->get();
    }

    /**
     * Find vendor specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->vendor->where('vendor_no', $id)->first();
    }

    /**
     * Find vendor specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOneVendorType($id) {
        return $this->vendorType->where('vendor_type_no', $id)->first();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getVendor() {
        return $this->vendor;
    }

    /**
     * Get vendor types
     *
     * @return mixed
     */
    public function getVendorTypes() {
        return $this->vendorType->get();
    }
}
