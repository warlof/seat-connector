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

namespace Warlof\Seat\Connector\Http\Controllers\Api\V2;

use Seat\Api\Http\Controllers\Api\v2\ApiController;
use Warlof\Seat\Connector\Http\Resources\UserResource;
use Warlof\Seat\Connector\Models\User;

/**
 * Class ApiController.
 *
 * @package Warlof\Seat\Connector\Http\Controllers\Api\V1
 */
class UserController extends ApiController
{
    /**
     * @SWG\Get(
     *     path="/seat-connector/users",
     *     tags={"SeAT Connector"},
     *     summary="Get a list of users",
     *     description="Return list of users along with their mapping",
     *     security={
     *      {"ApiKeyAuth": {}}
     *     },
     *     @SWG\Response(response=200, description="Successful operation",
     *      @SWG\Schema(
     *          @SWG\Property(
     *              type="array",
     *              property="data",
     *              @SWG\Items(ref="#/definitions/UserResource")
     *          ),
     *          @SWG\Property(
     *              type="object",
     *              property="links",
     *              description="Provide pagination urls for navigation",
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="first",
     *                  description="First page",
     *                  example="https://example.com/api/v2/seat-connector/users?page=1"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="last",
     *                  description="Last page",
     *                  example="https://example.com/api/v2/seat-connector/users?page=3"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="prev",
     *                  description="Previous page",
     *                  example="https://example.com/api/v2/seat-connector/users?page=1"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="next",
     *                  description="Next page",
     *                  example="https://example.com/api/v2/seat-connector/users?page=3"
     *              )
     *          ),
     *          @SWG\Property(
     *              type="object",
     *              property="meta",
     *              description="Information related to the paginated response",
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="current_page",
     *                  description="The current page",
     *                  example=2
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="from",
     *                  description="The first entity number on the page",
     *                  example=16
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="last_page",
     *                  description="The last page available",
     *                  example=3
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="path",
     *                  description="The base endpoint",
     *                  example="https://example.com/api/v2/seat-connector/users"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="per_page",
     *                  description="The pagination step",
     *                  example=15
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=1,
     *                  property="to",
     *                  description="The last entity number on the page",
     *                  example=30
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  minimum=0,
     *                  property="total",
     *                  description="The total of available entities",
     *                  example=35
     *              )
     *          )
     *      )
     *     ),
     *     @SWG\Response(response=400, description="Bad request"),
     *     @SWG\Response(response=401, description="Unauthorized")
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }
}
