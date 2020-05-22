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

namespace Warlof\Seat\Connector\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Models\Group;

/**
 * Class User.
 *
 * @package Warlof\Seat\Connector\Models
 */
class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'seat_connector_users';

    /**
     * @var array
     */
    protected $fillable = [
        'connector_type', 'connector_id', 'connector_name', 'group_id', 'unique_id',
    ];

    /**
     * @var array
     */
    private $allowed_sets = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * @return bool
     */
    public function isEnabledAccount(): bool
    {
        return ($this->group->users->count() == $this->group->users->where('active', true)->count());
    }

    /**
     * @return bool
     */
    public function areAllTokensValid(): bool
    {
        return $this->group->refresh_tokens->count() == $this->group->users->count();
    }

    /**
     * @param string $set_id
     * @return bool
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function isAllowedSet(string $set_id): bool
    {
        return in_array($set_id, $this->allowedSets());
    }

    /**
     * @return array
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function allowedSets(): array
    {
        $strict_mode = setting('seat-connector.strict', true);

        $active_tokens = $this->group->refresh_tokens;

        if (empty($active_tokens) || ($strict_mode && ! $this->areAllTokensValid()) || ! $this->isEnabledAccount())
            return [];

        if (! empty($this->allowed_sets))
            return $this->allowed_sets;

        $rows = $this->getGroupSets()
            ->union($this->getRoleSets())
            ->union($this->getCorporationSets())
            ->union($this->getTitleSets())
            ->union($this->getAllianceSets())
            ->union($this->getPublicSets())
            ->get();

        $this->allowed_sets = $rows->unique('connector_id')->pluck('connector_id')->toArray();

        return $this->allowed_sets;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getGroupSets()
    {
        $rows = Set::where('connector_type', $this->connector_type)
            ->whereHas('groups', function ($query) {
                $query->where('entity_id', $this->group_id);
            })
            ->select('connector_id');

        return $rows;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getRoleSets()
    {
        $rows = Set::where('connector_type', $this->connector_type)
            ->whereHas('roles', function ($query) {
                $query->whereIn('entity_id', $this->group->roles->pluck('id'));
            })
            ->select('connector_id');

        return $rows;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getCorporationSets()
    {
        $rows = Set::where('connector_type', $this->connector_type)
            ->whereHas('corporations', function ($query) {
                $corporations = $this->group->users->map(function ($item) {
                    return $item->character->corporation_id;
                });

                $query->whereIn('entity_id', $corporations);
            })
            ->select('connector_id');

        return $rows;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getTitleSets()
    {
        $rows = Set::where('connector_type', $this->connector_type)
            ->whereHas('titles', function ($query) {
                $titles = $this->group->users->map(function ($item) {
                    return $item->character->titles->pluck('id');
                });

                $query->whereIn('entity_id', $titles->flatten());
            })
            ->select('connector_id');

        return $rows;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getAllianceSets()
    {
        $rows = Set::where('connector_type', $this->connector_type)
            ->whereHas('alliances', function ($query) {
                $alliances = $this->group->users->map(function ($item) {
                    return $item->character->alliance_id;
                });

                $query->whereIn('entity_id', $alliances);
            })
            ->select('connector_id');

        return $rows;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getPublicSets()
    {
        $rows = Set::where('connector_type', $this->connector_type)
            ->where('is_public', true)
            ->select('connector_id');

        return $rows;
    }

    /**
     * @return string
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function buildConnectorNickname(): string
    {
        $character = $this->group->main_character;
        if (is_null($character))
            $character = $this->group->users->first()->character;

        $nickname = $character->name;

        if (setting('seat-connector.ticker', true)) {
            $corporation = CorporationInfo::find($character->corporation_id);
            $alliance = is_null($character->alliance_id) ? null : Alliance::find($character->alliance_id);
            $format = setting('seat-connector.format', true) ?: '[%2$s] %1$s';

            $corp_ticker = $corporation->ticker ?? '';
            $alliance_ticker = $alliance->ticker ?? '';

            $nickname = sprintf($format, $nickname, $corp_ticker, $alliance_ticker);
        }

        return $nickname;
    }
}
