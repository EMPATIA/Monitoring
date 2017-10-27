<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CreateComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        $modules = array(
            array('id' => '1',	'name' => 'IU7tPOott0noF3SgCyLWLpIr2bp1U3',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '2',	'name' => 'Dkt1hUfWzuBYXAMCysTyi10vJNeHTa',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '3',	'name' => 'R3RDk1iqiAzEAD79yA0xYrWir4h8UJ',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '4',	'name' => 'LpSe2EBeEYZb96J994ZQqbq1RrYabM',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '5',	'name' => 'fQBMQIdN76dJKzk9du8EOzxvuerpUR',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '6',	'name' => 'ydyth1imsTod3C7Y3i41qako4pGQcC',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '7',	'name' => '6FZOB5C2RlYlTvuYXzavsRvl6cr4xp',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '8',	'name' => 'pahrcQW7bqNSmzqUfeAoAXNfKTMDok',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '9',	'name' => 'izpU5d99sjgNIYuXigUPoh54LAwDdr',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '10',	'name' => 'B8NLUW2s0H6wuljgmlHgnjgjN27bTU',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '11',	'name' => 'SSnDv6kBSAjj8Ng0kwr7lhf52o3Gza',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '12',	'name' => 'poSAZprANHQiYq0KxyGuxXTq6N6Hgn',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '13',	'name' => 'yuSAZprANHQiYq0KxyGuxXTq6N6Hgn',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null),
            array('id' => '14',	'name' => 'trSAZprANHQiYq0KxyGuxXTq6N6Hgn',	'created_at' => Carbon::now(),	'updated_at' => Carbon::now(), 'deleted_at' => null)
        );
        DB::table('components')->insert($modules);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('components');
    }
}
