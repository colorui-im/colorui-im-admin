<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Group;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users =  \App\Models\User::factory(10)->create();
        $groups = \App\Models\Group::factory(10)->create();
        $divideGroups = \App\Models\DivideGroup::factory(10)->create();


        foreach ($users as $k=>$user) {
            foreach ($groups as $k1=>$group) {
                if ($k==$k1) {
                    $user->groups()->save($group);//这个用户创建的群
                    $group->users()->sync($users->pluck('id')->toArray());
                    break;
                }
            }

            foreach ($divideGroups as $k2=>$divideGroup) {
                if ($k==$k2) {
                    $user->divideGroups()->save($divideGroup);//我的分组
                    $divideGroup->users()->sync($users->pluck('id')->toArray());//我的分组下面的用户
                   
                    foreach ($users as $friiendUser){//给我的好友建个小群,就可以像在“群里”好友聊天啦
                        $a =[];
                        $a[]=$user->id;
                        $a[]=$friiendUser->id;
                        sort($a);
                        $user_to_user = implode('-',$a);
                        $group = Group::where('user_to_user', $user_to_user)->where('type', 0)->first();
                        if(!$group){
                            $group = \App\Models\Group::factory()->create();
                            $group->user_to_user = $user_to_user;
                            $group->type = 0;
                            $group->save();
                        }
                        $group->users()->sync($a);
                        
                    }
                    break;
                }
            }
        }
    }

}