<?php 
namespace Mod\Customers\Models;

defined('ROOT') or die("Direct script access denied");

class ContactBook
{
    public $id;
    public $company_name; // Firma adı
    public $company_tax_office; // Vergi dairesi
    public $company_tax_number; // vergi numarası
    public $company_insurance_number; // sgk numarası
    public $company_status; // Firma durumu 
    public $add_user; // Firmayı ekleyen kullanıcı 
    public $created_time; // Firmanın eklenme zamanı
    public $modify_time; // firmanın güncellenme zamanı
    public $last_modify_user; // firmayı güncelleyen kullanıcı 
}