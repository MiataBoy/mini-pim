<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\manager\manLocale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class manLocaleController extends Controller
{
    public function index(Request $request): Collection
    {
        $locales = manLocale::query();

        if ($request->query('enabled')) {
            $locales->where('enabled', true);
        }
        return $locales->get();
    }

    public function setLocale(Request $request): void
    {
//        dd(App::setLocale(locale: $request->input(('locale'))));
        App::setLocale(locale: $request->input(('locale')));
    }

    public function setLanguageStatus($id, Request $request): void
    {
        $locale = manLocale::find($id);

        $locale->enabled = $request->input('enabled');

        $locale->save();
    }
}
