<?php

namespace App\Livewire\Web;

// use Illuminate\Support\Facades\Request;
use App\Models\Customer;
use App\Models\CustomerSaldo;
use App\Models\CustomerReferral;
use App\Models\Task;
use App\Models\TaskCustomer;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

use BaseComponent;

#[Layout('layouts.web')]
class Register extends BaseComponent
{
    #[Title('Register')]
    
    public $name; 
    public $kode = "";

    public function render()
    {
        if(request()->segment(2) != ""){
            $this->kode = request()->segment(2);
        }

        // $code = "mOHAGA";
        // $customer_head_id = 9;
        // $totalReferral = CustomerReferral::where('code', $code)->count();
        // $getTask = Task::leftJoin('task_customers as b', 'b.task_id', '=', 'tasks.id')->where('b.customer_id', $customer_head_id)->orderBy('tasks.level','desc')->select('tasks.id', 'b.total', 'tasks.level')->first();
        // if($totalReferral >= $getTask->total){
        //     $level = $getTask->level + 1;
        //     $getTask = Task::where('level', $level)->first();
        // }
        // dd($getTask->id);
        return view('livewire.web.register');
    }

    public function store(Request $request){
        $code = $request->input('code');
        $email = $request->input('email');
        $checkCustomer = Customer::where('email', $email);
        if($checkCustomer->count() > 0){
            return Redirect::to('/register')->with('error', 'email sudah tersedia, silahkan coba lagi.');
        }

        $customer = Customer::create([
            'name' => $request->input('name'),
            'email' => $email,
            'phone_number' => $request->input('phone_number'),
            'password' => Hash::make($request->input('password')),
            'code' => $this->generateEmailHash($email),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'ip_address' => $request->ip(),
            'status' => 0,
            'created_at' => date("Y-m-d H:i:s")
        ]);


        if ($customer) {
            $getID = Customer::where('email', $email)->first();
            $customer_id = $getID->id;

            if($code != ""){
                $getCustomerByCode = Customer::where('code', $code);
                if($getCustomerByCode->count() > 0){
                    $customer_head = $getCustomerByCode->first();
                    $customer_head_id = $customer_head->id;

                    CustomerReferral::create([
                        'code' => $code,
                        'customer_id' => $customer_id,
                        'total_investmen' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    $totalReferral = CustomerReferral::where('code', $code)->count();
                    $getTask = Task::leftJoin('task_customers as b', 'b.task_id', '=', 'tasks.id')->where('b.customer_id', $customer_head_id)->orderBy('tasks.level','desc')->select('tasks.id', 'b.total', 'tasks.level');
                    if($getTask->first() != null){
                        $getTask2 = $getTask->first();
                        if($totalReferral >= $getTask2->total){
                            $level = $getTask2->level + 1;
                            $getTask = Task::where('level', $level);
                        }
                    }
                    
                    if($getTask->count() > 0){
                        $getTask = $getTask->first();
                        $task_id = $getTask->id;
                        $total_member = $getTask->total_member;
                        $checkTaskCustomer = TaskCustomer::where('customer_id', $customer_head_id)->where('task_id', $task_id);
                        if($checkTaskCustomer->count() > 0){
                            $checkTaskCustomer = $checkTaskCustomer->first();
                            $total = $checkTaskCustomer->total + 1;
                            if($total == $total_member){
                                $status_code = 200;
                            }else{
                                $status_code = 100;
                            }
                            TaskCustomer::where('id', $checkTaskCustomer->id)->update([
                                'task_id' => $task_id,
                                'customer_id' => $customer_head_id,
                                'total' => $total,
                                'status' => $status_code,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                            // dd("check 3");
                        }else{
                            $getTaskCustomerOld = TaskCustomer::where('customer_id', $customer_head_id)->where('status', 200)->orderBy('id', 'desc');
                            if($getTaskCustomerOld->count() > 0){
                                $total = $getTaskCustomerOld->first()->total;
                            }else{
                                $total = 0;
                            }
                            TaskCustomer::create([
                                'task_id' => $task_id,
                                'customer_id' => $customer_head_id,
                                'total' => $total + 1,
                                'status' => 100,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                            // dd("check 4", $task_id, $customer_head_id);
                        }
                        // dd("check 2");
                    }
                    // dd("check 1");
                    

                }
            }
            

            CustomerSaldo::create([
                'customer_id' => $customer_id,
                'nominal' => 0,
                'nominal_investmen' => 0,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return Redirect::to('/login')->with('success', 'Customer registered successfully!');
        } else {
            return Redirect::to('/register')->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function generateEmailHash(string $email)
    {
        $hash = hash('sha256', $email);
        $randomString = Str::random(6);

        return $randomString;
    }
}
