<?php
/**
 * This file is part of seat-connector and provides user synchronization between both SeAT and third party platform
 *
 * Copyright (C) 2019  Loïc Leuilliot <loic.leuilliot@gmail.com>
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSeatConnectorPermissionGroupEntityTable.
 */
class CreateSeatConnectorPermissionGroupEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_connector_permission_group_entity', function (Blueprint $table) {

            $table->unsignedInteger('permission_group_id');
            $table->string('entity_type');
            $table->bigInteger('entity_id');

            $table->primary(['permission_group_id', 'entity_type', 'entity_id'], 'pk_permission_group_entity');

            Schema::disableForeignKeyConstraints();

            $table->foreign('permission_group_id', 'fk_permission_groups')
                ->references('id')
                ->on('seat_connector_permission_groups')
                ->onDelete('cascade');

            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_connector_permission_group_entity');
    }
}
