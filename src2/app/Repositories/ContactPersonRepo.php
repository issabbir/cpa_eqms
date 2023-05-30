<?php


namespace App\Repositories;


use App\Entities\Eqms\ContactPersonList;
use Illuminate\Http\Request;

class ContactPersonRepo
{
    protected  $contactPerson;

    protected $request;

    /**
     *
     * VendorRepo constructor.
     * @param ContactPersonList $contactPersonList
     */
    public function __construct(ContactPersonList $contactPersonList)
    {
        $this->contactPerson = $contactPersonList;


    }

    /**
     * Find all vendors for all
     * @return mixed
     */
    public function findAll() {
        //Todo: Applied filters option as you need
        return $this->contactPerson->get();
    }

    /**
     * Find vendor specific one
     *
     * @param $id
     * @return mixed
     */
    public function findOne($id) {
        return $this->contactPerson->where('CONTACT_PERSON_ID', $id)->first();
    }

    public function findVendorContactPersons($vendor_id) {
        return $this->contactPerson->where('vendor_id', $vendor_id)->get();
    }

    /**
     * Get Single object
     *
     * @return mixed
     */
    public function getContactPerson() {
        return $this->contactPerson;
    }


}
