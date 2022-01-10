<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProdactRepository
{
    public function createProdact(array $data)
    {
        return DB::table('prodacts')->insert([

            'link' => $data['link'],
            'image'=> $data['image'],
            'name' => $data['name']

        ]);
    }

    public function exists($link)
    {
        return DB::table('prodacts')->where('link', $link)->exists();
    }


}
