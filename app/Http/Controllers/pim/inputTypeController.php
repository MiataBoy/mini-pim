<?php

namespace App\Http\Controllers\pim;

use App\Http\Controllers\Controller;
use App\Models\pim\Specification_inputType;
use Illuminate\Database\Eloquent\Collection;

class inputTypeController extends Controller
{
    public function index(): Collection
    {
        return Specification_inputType::all();
    }
}
