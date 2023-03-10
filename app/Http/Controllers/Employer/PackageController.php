<?php

namespace App\Http\Controllers\Employer;

use App\Enums\StatusCode;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AccountPayment;
use App\Models\Employer;
use App\Models\Packageoffer;
use App\Models\packageofferbought;
use App\Models\Vnpay;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public Vnpay $vnpay;
    public $vnp_HashSecret = 'KNAREAARTPBAELKXTPLZKBUMSTCJHIYE';
    public Packageoffer $package;
    public function __construct(Vnpay $vnpay, Packageoffer $package)
    {
        $this->vnpay = $vnpay;
        $this->package = $package;
    }
    public function index()
    {
        $pachageForEmployer = Packageofferbought::leftjoin('admin_package_offer', 'admin_package_offer.id', '=', 'package_offer_bought.package_offer_id')
            ->leftjoin('users', 'users.id', '=', 'package_offer_bought.company_id')
            ->leftjoin('employer', 'employer.user_id', '=', 'users.id')
            ->select('admin_package_offer.name as name_package', 'admin_package_offer.price as price', 'package_offer_bought.id as id', 'package_offer_bought.package_offer_id as package_id', 'package_offer_bought.start_time as start_time', 'package_offer_bought.end_time as end_time', 'package_offer_bought.lever', 'package_offer_bought.status')
            ->orderby('package_offer_bought.status', 'ASC')
            ->where('package_offer_bought.company_id', Auth::guard('user')->user()->id)
            ->get();
        $accPayment = AccountPayment::where('user_id', Auth::guard('user')->user()->id)->first();
        $package = Packageoffer::select('*')->whereNotIn('id', $pachageForEmployer->pluck('package_id'))->with('timeofer')->get();
        return view('employer.package.index', [
            'data' => $package,
            'accPayment' => $accPayment,
            'pachageForEmployer' => $pachageForEmployer,
            'total' => AccountPayment::where('user_id', Auth::guard('user')->user()->id)->with('user')->first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetail($id)
    {
        return response()->json([
            'data' => $this->package->where('id', $id)->with('timeofer')->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function Payment(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('employer.package.payment.return');
        $vnp_TmnCode = "S50PEHFY"; //M?? website t???i VNPAY 
        $vnp_HashSecret = $this->vnp_HashSecret; //Chu???i b?? m???t
        $vnp_TxnRef = $request->id;
        $vnp_OrderInfo = $request->name;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount =  $request->price * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = '192.168.1.6';

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }


        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function vnpayReturn(Request $request)
    {
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        // $url_ipn = $request->getRequestUri();
        // dd($request->getRequestUri());
        $url_ipn = route('employer.package.payment.output', $_GET);
        // dd($url_ipn);
        $secureHash = hash_hmac('sha512', $hashData,  $this->vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $this->setFlash(__('Giao d???ch th??nh c??ng'));
                header('Location:' . $url_ipn);
                die;
            } else {
                $this->setFlash(__('Giao d???ch kh??ng th??nh c??ng'), 'error');
            }
        } else {
            $this->setFlash(__('chu ky khong hop le'), 'error');
        }
        return redirect()->route('employer.package.index');
    }
    public function vnpayOutput(Request $request)
    {
        $inputData = array();
        $returnData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        // dd($request->all());
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo']; //M?? giao d???ch t???i VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ng??n h??ng thanh to??n
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // S??? ti???n thanh to??n VNPAY ph???n h???i
        $Status = 0; // L?? tr???ng th??i thanh to??n c???a giao d???ch ch??a c?? IPN l??u t???i h??? th???ng c???a merchant chi???u kh???i t???o URL thanh to??n.
        $orderId = $inputData['vnp_TxnRef'];
        // try {
        //Check Orderid    
        //Ki???m tra checksum c???a d??? li???u
        if ($secureHash == $vnp_SecureHash) {

            $invoice = Packageoffer::where('id', $orderId)->first(); //$orderId
            if ($invoice != NULL) {
                if ($invoice["price"] == $vnp_Amount) //Ki???m tra s??? ti???n thanh to??n c???a giao d???ch: gi??? s??? s??? ti???n ki???m tra l?? ????ng. //$order["Amount"] == $vnp_Amount
                {
                    if ($invoice["status"] == NULL && $invoice["status"] == 0) {
                        if ($inputData['vnp_ResponseCode'] == '00' && $inputData['vnp_TransactionStatus'] == '00') {
                            $Status = 1; // Tr???ng th??i thanh to??n th??nh c??ng
                        } else {
                            $Status = 2; // Tr???ng th??i thanh to??n th???t b???i / l???i
                        }
                        $package = new packageofferbought();
                        $employer = Employer::where('user_id', Auth::guard('user')->user()->id)->first();
                        $package->company_id = Auth::guard('user')->user()->id;
                        $package->package_offer_id = 1;
                        $package->status = $Status;
                        $package->start_time = Carbon::parse(Carbon::now());
                        if ($orderId == 1) {
                            $employer->prioritize += 1;
                            $package->end_time = Carbon::parse(Carbon::now())->addDay(1)->format('Y-m-d');
                            $package->lever = 1;
                        } else if ($orderId == 2) {
                            $employer->prioritize += 2;
                            $package->end_time = Carbon::parse(Carbon::now())->addDay(7)->format('Y-m-d');
                            $package->lever = 2;
                        } else if ($orderId == 3) {
                            $employer->prioritize += 3;
                            $package->end_time = Carbon::parse(Carbon::now())->addDay(30)->format('Y-m-d');
                            $package->lever = 3;
                        } else if ($orderId == 4) {
                            $employer->prioritize += 4;
                            $package->end_time = Carbon::parse(Carbon::now())->addDay(365)->format('Y-m-d');
                            $package->lever = 4;
                        }
                        $package->save();
                        $employer->save();
                        AccountPayment::create($request->all());
                        $returnData['RspCode'] = '00';
                        $returnData['Message'] = 'X??c nh???n th??nh c??ng';
                    } else {
                        $returnData['RspCode'] = '02';
                        $returnData['Message'] = '????n ?????t h??ng ???? ???????c x??c nh???n';
                    }
                } else {
                    $returnData['RspCode'] = '04';
                    $returnData['Message'] = 'S??? ti???n kh??ng h???p l???';
                }
            } else {
                $returnData['RspCode'] = '01';
                $returnData['Message'] = 'Kh??ng t???n t???i h??a ????n';
            }
        } else {
            $returnData['RspCode'] = '97';
            $returnData['Message'] = 'Ch??? k?? kh??ng h???p l???';
        }
        // } catch (Exception $e) {
        //     $returnData['RspCode'] = '99';
        //     $returnData['Message'] = 'L???i kh??ng x??c ?????nh';
        // }
        if ($returnData['RspCode'] != 00) {
            $this->setFlash($returnData['Message'], 'error');
        } else {
            $this->setFlash($returnData['Message']);
        }
        return redirect()->route('employer.package.index');
    }
    public function byAccount(Request $request)
    {

        try {
            $employer = Employer::where('user_id', Auth::guard('user')->user()->id)->first();
            $package = new packageofferbought();
            $package->company_id = Auth::guard('user')->user()->id;
            $package->package_offer_id = $request['data']['id'];
            $package->status = 1;
            $package->start_time =
                Carbon::parse(Carbon::now());
            if ($request['data']['timeofer']['id'] == 1) {
                $employer->prioritize += 1;
                $package->end_time = Carbon::parse(Carbon::now())->addDay(1)->format('Y-m-d');
                $package->lever = 1;
            } else if ($request['data']['timeofer']['id'] == 2) {
                $employer->prioritize += 2;
                $package->end_time = Carbon::parse(Carbon::now())->addDay(7)->format('Y-m-d');
                $package->lever = 2;
            } else if ($request['data']['timeofer']['id'] == 3) {
                $employer->prioritize += 3;
                $package->end_time = Carbon::parse(Carbon::now())->addDay(30)->format('Y-m-d');
                $package->lever = 3;
            } else if ($request['data']['timeofer']['id'] == 4) {
                $employer->prioritize += 4;
                $package->end_time = Carbon::parse(Carbon::now())->addDay(365)->format('Y-m-d');
                $package->lever = 4;
            }
            $package->save();
            $employer->save();
            $account = AccountPayment::where('user_id', Auth::guard('user')->user()->id)->first();
            $account->surplus = $account->surplus - $request['data']['price'];
            $account->save();
            return response()->json([
                'message' => 'Thanh to??n ????n h??ng th??nh c??ng',
                'status' => StatusCode::OK,
            ], StatusCode::OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'C?? m???t l???i kh??ng x??c ?????nh ???? x???y ra',
                'message' =>  StatusCode::FORBIDDEN,
            ], StatusCode::OK,);
        }
    }
}