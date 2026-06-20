<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class PolicyController extends Controller
{
    public function shipping(): View
    {
        return $this->render('Shipping Policy', 'shipping_policy_content');
    }

    public function returns(): View
    {
        return $this->render('Return Policy', 'return_policy_content');
    }

    public function privacy(): View
    {
        return $this->render('Privacy Policy', 'privacy_policy_content');
    }

    public function terms(): View
    {
        return $this->render('Terms & Conditions', 'terms_content');
    }

    protected function render(string $title, string $key): View
    {
        $content = Setting::getValue($key, '');

        return view('policy.show', compact('title', 'content'));
    }
}
