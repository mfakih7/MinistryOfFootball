<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index', [
            'title' => 'Contact Us',
            'metaDescription' => 'Get in touch with Ministry Of Football. We are happy to help with orders, products, and support.',
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        ContactMessage::query()->create($request->validated());

        return redirect()->route('contact')->with('success', 'Thank you! Your message has been sent. We will get back to you soon.');
    }
}
