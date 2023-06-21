<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
        
        $user = DB::table('users')->where('name', 'admin')->get();

        if($user){

                DB::table('options')->insert(
                [
                    [
                    'user_id' => '18',
                    'type' => '1',
                    'key' => 'user_type',
                    'value' => '[{"0":"admin"},{"1":"user"}]',
                    ],
                    [
                    'user_id' => '18',
                    'type' => '1',
                    'key' => 'device_type',
                    'value' => '[{"0":"نوع 1"},{"1":"نوع 2"},{"2":"نوع 3"}]',
                    ],
                    
                    [
                    'user_id' => '18',
                    'type' => '1',
                    'key' => 'ticket_status',
                    'value' => '[{"0":"باز"},{"1":"درحال انجام"},{"2":"بسته"}]',
                    ],
                    [
                    'user_id' => '18',
                    'type' => '1',
                    'key' => 'position_type',
                    'value' => '[{"0":"جایگاه تیپ ها"},{"1":"جایگاه شماره 2"},{"2":"جایگاه شماره 3 - فالکون"},{"3":"جایگاه ماژول و همزن"},{"4":"جایگاه شماره 5"},{"5":"جایگاه شماره 6"},{"6":"سطل زباله"},{"7":"سمپلرها"}]',
                    ],
                    [
                    'user_id' => '18',
                    'type' => '1',
                    'key' => 'attribute_protocol_type',
                    'value' => '[{"0":"انتقال"},{"1":"پیپت کردن"},{"2":"توقف"},{"3":"ماژول مگنت"},{"4":"ماژول گرمکن-همزن"},{"5":"ماژول وکیوم"},{"6":"ماژول-uvc"}]',
                    ],
                    [
                    'user_id' => '18',
                    'type' => '1',
                    'key' => 'entity_type',
                    'value' => '[{"0":"ظرف"},{"1":"سمپلر"},{"2":"ماژول"},{"3":"فالکون"},{"4":"پروتکل"},{"5":"تیپ"},{"6":"ظرف تیپ"}]',
                    ],
                ]
            );
            
        }
    }
}
