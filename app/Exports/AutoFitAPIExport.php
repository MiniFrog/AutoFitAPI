<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AutoFitAPIExport implements FromView
{
    private $apiUUID;

    private $data;

    public function __construct($apiUUID, $data)
    {
        $this->apiUUID = $apiUUID;
        $this->data    = $data;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function view(): View
    {
        return view("export.$this->apiUUID.index", ['data' => $this->data]);
    }
}
