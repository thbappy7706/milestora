<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    /**
     * Update the locale stored in the session.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', Rule::in(SetLocale::LOCALES)],
        ]);

        /** @var string $locale */
        $locale = $validated['locale'];

        $request->session()->put('locale', $locale);
        App::setLocale($locale);

        return back();
    }
}
