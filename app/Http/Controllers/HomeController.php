<?php

namespace App\Http\Controllers;

use App\Http\Services\PaymentService;
use App\Jobs\WelcomeEmailJob;
use App\Models\User;
use Framework\Routing\Router;

class HomeController
{
    public function __construct(protected Router $router, PaymentService $paymentService){}

    public function index()
    {
        return view('home.index');
    }

    public function showRegisterForm()
    {
        return view('home.register',
            [
                'router' => $this->router,
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function register(Router $router)
    {
        $data = validate($_POST, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:5'],
        ]);

        (new WelcomeEmailJob())->dispatch();

        $user = User::find(1);
        print_r($user->orders()->where('amount_due', '>', 200)->first());
        exit();

        $_SESSION['registered'] = true;
        redirect($this->router->route('home'));
    }
}