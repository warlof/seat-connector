<?php

/*
 * This file is part of seat-connector and provides user synchronization between both SeAT and third party platform
 *
 * Copyright (C) 2019 to 2022 Loïc Leuilliot <loic.leuilliot@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSeatConnectorUsersTable.
 */
class CreateSeatConnectorUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_connector_users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('connector_type');
            $table->string('connector_id');
            $table->string('connector_name');
            $table->string('unique_id');

            $table->unsignedInteger('group_id');

            $table->timestamps();

            $table->unique(['connector_type', 'connector_id'], 'uk_users_connector');
            $table->unique(['connector_type', 'group_id'], 'uk_users_seat_group');

            if (Schema::hasTable('groups')) {
                Schema::disableForeignKeyConstraints();

                $table->foreign('group_id', 'fk_groups')
                    ->references('id')
                    ->on('groups')
                    ->onDelete('cascade');

                Schema::enableForeignKeyConstraints();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_connector_users');
    }
}
