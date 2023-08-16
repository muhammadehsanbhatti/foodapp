<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;
use App\Models\User;
use App\Models\EmailLogs;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\EmailTemplate;
use App\Models\NotificationMessage;
use App\Models\ShortCode;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\Business;
// use Spatie\Permission\Models\Permission;
use App\Models\Permission;
use App\Models\AssignPermission;
use App\Models\RestaurantMenue;
use App\Models\RestaurantFile;
use App\Models\MenueVariant;
use App\Models\BusinessCuisine;
use App\Models\About;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use App\Models\UserAddress;
use App\Models\AddToCart;
use App\Models\Payment;
use App\Models\PaymentHistroy;
use App\Models\UserCartMenueVariants;
use App\Models\FavMenu;



use DB;
use Validator;
use Auth;
use Session;
use Carbon;
use Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $RoleObj;
    public $UserObj;
    public $EmailObj;
    public $NotificationObj;
    public $FcnTokenObj;
    public $EmailTemplateObj;
    public $NotificationMessageObj;
    public $EmailShortCodeObj;
    public $MenuObj;
    public $SubMenuObj;
    public $PermissionObj;
    public $AssignPermissionObj;
    public $ContactObj;
    public $BusinessObj;
    public $RestaurantMenueObj;
    public $RestaurantFileObj;
    public $MenueVariantsObj;
    public $BusinessCuisineObj;
    public $AboutObj;
    public $PrivacyPolicyObj;
    public $TermsConditionObj;
    public $UserAddressObj;
    public $AddToCartObj;
    public $PaymentObj;
    public $PaymentHistroyObj;
    public $UserCartMenueVariantsObj;
    public $FavMenuObj;

    public function __construct() {
        
        $this->RoleObj = new Role();
        $this->UserObj = new User();
        $this->EmailObj = new EmailLogs();
        $this->FcnTokenObj = new FcmToken();
        $this->NotificationObj = new Notification();
        $this->NotificationMessageObj = new NotificationMessage();
        $this->EmailTemplateObj = new EmailTemplate();
        $this->EmailShortCodeObj = new ShortCode();
        $this->MenuObj = new Menu();
        $this->SubMenuObj = new SubMenu();
        $this->PermissionObj = new Permission();
        $this->AssignPermissionObj = new AssignPermission();
        $this->BusinessObj = new Business();
        $this->RestaurantMenueObj = new RestaurantMenue();
        $this->RestaurantFileObj = new RestaurantFile();
        $this->MenueVariantsObj = new MenueVariant();
        $this->BusinessCuisineObj = new BusinessCuisine();
        $this->AboutObj = new About();
        $this->PrivacyPolicyObj = new PrivacyPolicy();
        $this->TermsConditionObj = new TermsCondition();
        $this->UserAddressObj = new UserAddress();
        $this->AddToCartObj = new AddToCart();
        $this->PaymentObj = new Payment();
        $this->PaymentHistroyObj = new PaymentHistroy();
        $this->UserCartMenueVariantsObj = new UserCartMenueVariants();
        $this->FavMenuObj = new FavMenu();
        
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result = array(), $message, $count = 0)
    {
    	$response = [
            'success' => true,
            'records'    => $result,
            'message' => $message,
            'count'    => $count,
        ];
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = array(), $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['records'] = $errorMessages;
        }
        
        return response()->json($response, $code);
    }
}