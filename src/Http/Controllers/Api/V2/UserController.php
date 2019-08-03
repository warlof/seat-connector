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
     *                  description="First page"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="last",
     *                  description="Last page"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="prev",
     *                  description="Previous page"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="next",
     *                  description="Next page"
     *              )
     *          ),
     *          @SWG\Property(
     *              type="object",
     *              property="meta",
     *              description="Information related to the paginated response",
     *              @SWG\Property(
     *                  type="integer",
     *                  property="current_page",
     *                  description="The current page"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  property="from",
     *                  description="The first entity number on the page"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  property="last_page",
     *                  description="The last page available"
     *              ),
     *              @SWG\Property(
     *                  type="string",
     *                  format="uri",
     *                  property="path",
     *                  description="The base endpoint"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  property="per_page",
     *                  description="The pagination step"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  property="to",
     *                  description="The last entity number on the page"
     *              ),
     *              @SWG\Property(
     *                  type="integer",
     *                  property="total",
     *                  description="The total of available entities"
     *              )
     *          )
     *      ),
     *      examples={
     *          "application/json": {
     *              {
     *                  "group_id": 1,
     *                  "connector_type": "discord",
     *                  "connector_id": "133312047051046912",
     *                  "connector_name": "Demo User"
     *              }
     *          }
     *     }),
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
