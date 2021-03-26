<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;


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


        foreach ($users as $k=>$user){

            foreach ($groups as $k1=>$group){
                if($k==$k1){
                    $user->groups()->save($group);
                    $group->users()->sync($users->pluck('id')->toArray());
                    break;
                }
            }

            foreach ($divideGroups as $k2=>$divideGroup){
                if($k==$k2){
                    $user->divideGroups()->save($group);
                    $divideGroup->users()->sync($users->pluck('id')->toArray());
                    break;
                }
            }


       

    }
}
