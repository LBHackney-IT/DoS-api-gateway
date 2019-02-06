<?php

namespace App\Http\Controllers;

use App\Component\Model\Provider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProviderController extends AbstractEventController
{

    public $type = 'provider';
}
