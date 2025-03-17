<?php

namespace Mod\Customers\Models;

use App\Core\Database\Model;

defined('ROOT') or die("Direct script access denied");

class CustomersModel extends Model
{
    private $table_name     = "tbl_customers";

    public function getAllCustomers()
    {
        $data   =  $this->table("tbl_customers")->get();
        $result = [];
        if($data)
        {
            foreach ($data as $customer) {
                $city = $this->table("tbl_adress")
                            ->select([
                                "tbl_adress.*", 
                                "tbl_city.city_name",
                                "tbl_district.district_name",
                            ])
                            ->join("tbl_city","city_id","id")
                            ->join("tbl_district","discrit_id","id")
                            ->where("id", $customer->company_adres_id)->get();
                $customer->company_adres_id = is_array($city) ? $city : null;

                $result[] = (object)[
                    'customer_detail'    => $customer,
                ];
            }
        }
        return  $result;
    }

    public function getFirstCustomer($customer_id)
    {
        // Müşteri bilgilerini almak için sorgu
        $customer = $this->table($this->table_name)
            ->where("customer_id", $customer_id)
            ->first();

        if ($customer) {
            // Müşteri adres bilgilerini almak için sorgu
            $city = $this->table("tbl_adress")
                ->select([
                    "tbl_adress.*",
                    "tbl_city.city_name",
                    "tbl_districts.district_name",
                ])
                ->join("tbl_city", "tbl_adress.city_id","tbl_city.city_id")
                ->join("tbl_districts", "district_id","district_id")
                ->where("tbl_adress.adress_id", $customer->company_adres_id)
                ->first();

            $customer->company_adress = $city;
            // Müşterinin iletişim bilgilerini almak için sorgu
            $contactData = $this->table("tbl_contact_books")
                ->where("customerId", $customer_id)
                ->get();

            // Sonuçları birleştir
            $result = (object)[
                'customer_detail'    => $customer,
                'contacts_detail'    => $contactData
            ];

            return $result;
        }

        return $customer;
    }

    public function addCustomer($data,$lastinsertId)
    {
        $add = $this->table($this->table_name)->addCreate($data,$lastinsertId);
        return $add ?: false;
    }

    public function updateCustomer($data)
    {
        $update = $this->table($this->table_name)->where("customer_id",$data["customer_id"])->update($data);
        return $update ?: false;
    }

    public function deleteCustomer($customer_id)
    {
        $delete = $this->table($this->table_name)->where("customer_id",$customer_id)->delete();
        return $delete ?: false;
    }

    public function customerNameControl($customer_id,$company_name = null)
    {
        $data = $this->table($this->table_name)->whereRaw("company_name=? AND customer_id!=?",[$company_name,$customer_id])->first();
        return $data ?: false;
    }
    
    public function customerControl($customer_id)
    {
        $data = $this->table($this->table_name)->whereRaw("customer_id=?",[$customer_id])->get();
        return $data ?: false;
    }
    
}