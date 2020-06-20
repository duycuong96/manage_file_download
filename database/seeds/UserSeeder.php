<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $datas = [
            [
                'name'=>'Arron Guyy',
                'email'=>'vuduycuong996@gmail.com',
                'password' => Hash::make('cuongduyvu@160596'),
                'created_at'=>'2020-06-20 00:00:00.000000'
            ],
            [
                'name'=>'ModyPlay',
                'email'=>'vuduynam99@gmail.com',
                'password' => Hash::make('vuduynam@1999'),
                'created_at'=>'2020-06-20 00:00:00.000000'
            ],
        ];
        foreach($datas as $data){
            $client = User::create($data);
        }
    }
}
