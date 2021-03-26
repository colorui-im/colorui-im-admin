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

        \App\Models\User::factory(100)->create();
        \App\Models\Group::factory(10)->create();
        \App\Models\DivideGroup::factory(10)->create();

        $divideGroups = \App\Models\DivideGroup::get();
        $groups= \App\Models\Group::get();
        User::chunk(10,function($item)use($divideGroups,$groups){
            $divideGroups->each(function($divideGroup)use($item){
                $divideGroup->users()->sync($item->pluck('id')->toArray());
            });
            $groups->each(function($group)use($item){
                $group->users()->sync($item->pluck('id')->toArray());
            });
        });

    }
}
