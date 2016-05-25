<?php

namespace App\Http\Controllers;

/**
 * Class BooksController
 * @package App\Http\Controllers
 */
class BooksController extends Controller
{
    /**
     * GET /books
     * @return array
     */
    public function index()
    {
        return [
            ['title' => 'War of the Worlds'],
            ['title' => 'A Wrinkle in Time']
        ];
    }
}
