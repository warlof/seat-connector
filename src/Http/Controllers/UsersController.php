<?php


namespace Warlof\Seat\Connector\Http\Controllers;

class UsersController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('seat-connector::users.list');
    }
}
