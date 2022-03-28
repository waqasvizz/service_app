<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\AssignService;
use App\Models\Category;
use App\Models\Mosque;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Role;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\PostAssets;
use App\Models\Filepond;

use DB;
use Validator;
use Auth;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $AssignServiceObj;
    public $CategoryObj;
    public $MosqueObj;
    public $PaymentObj;
    public $PostObj;
    public $RoleObj;
    public $ServiceObj;
    public $SettingObj;
    public $UserObj;
    public $PostAssetsObj;
    public $FilepondObj;

    public function __construct() {
        
        $this->AssignServiceObj = new AssignService();
        $this->CategoryObj = new Category();
        $this->MosqueObj = new Mosque();
        $this->PaymentObj = new Payment();
        $this->PostObj = new Post();
        $this->RoleObj = new Role();
        $this->ServiceObj = new Service();
        $this->SettingObj = new Setting();
        $this->UserObj = new User();
        $this->PostAssetsObj = new PostAssets();
        $this->FilepondObj = new Filepond();
    }


}
