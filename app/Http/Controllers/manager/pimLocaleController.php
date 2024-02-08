<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\manager\createPimLocaleRequest;
use App\Http\Requests\manager\updatePimLocaleRequest;
use App\Models\manager\pimLocale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class pimLocaleController extends Controller
{
    public function index(Request $request): Collection
    {
        $locales = pimLocale::query();

        if ($request->query('enabled')) {
            $locales->where('enabled', true);
        }
        return $locales->get();
    }

    public function disableLang($id, Request $request): void
    {
        $locale = pimLocale::find($id);

        $locale->enabled = $request->input('enabled');

        $locale->save();
    }

    public function deleteLang($id): void
    {
        pimLocale::find($id)->delete();
    }

    public function saveLang(createPimLocaleRequest $request): void
    {
        $locale = new pimLocale();

        $locale->code = $request->input('language.code');
        $locale->name = $request->input('language.name');
        $locale->displayClass = $request->input('language.displayClass');

        $locale->save();
    }

    public function updateLang($id, updatePimLocaleRequest $request): void
    {
        $locale = pimLocale::find($id);

        $locale->code = $request->input('language.code');
        $locale->name = $request->input('language.name');
        $locale->displayClass = $request->input('language.displayClass');

        $locale->save();
    }
}
