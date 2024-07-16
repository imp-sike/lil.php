<?php

namespace App\Middleware;
use Stevel\Middleware;

class SampleMiddleware implements Middleware {
    public function Run() {
        echo "RUNNING SAMPLE MIDDLEWARE";
    }
}