<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\GroupRequest;
use App\Http\Requests\Group\GroupUpdateRequest;
use App\Models\Beneficiary;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(GroupRequest $request): JsonResponse
    {
        $name_group     = $request->name_group;
        $created_group  = $request->created_group;
        $day_payment    = $request->day_payment;
        $id_beneficiary = $request->id_beneficiary;

        $this->authorize('create', [Group::class, $id_beneficiary]);

        $group = new Group();
        $group->name_group      = $name_group;
        $group->created_group   = $created_group;
        $group->day_payment     = $day_payment;
        $group->id_beneficiary  = $id_beneficiary;
        $group->save();

        return new JsonResponse(['group' => $group]);
    }

    public function delete(Group $group): JsonResponse
    {
        $this->authorize('delete', $group);
        $group->delete();
        return new JsonResponse(['group' => $group]);
    }

    public function update(GroupUpdateRequest $request, Group $group): JsonResponse
    {
        $this->authorize('update', $group);
        $group->update([
            'name_group'    => $request->name_group,
            'day_payment'   => $request->day_payment
        ]);
        return new JsonResponse(['group' => $group]);
    }

    public function getAll(Request $request, Beneficiary $beneficiary): JsonResponse
    {
        $this->authorize('viewAny', [Group::class, $beneficiary]);
        $search = $request->input('search', '');
        $archived = $request->input('archived', 0);

        $perPage = 6;
        $groups = $beneficiary->groups()
            ->where(function ($query) use ($search) {
                $query->where('name_group', 'LIKE', "%{$search}%");
            })
            ->where('state_archived_group', '==', $archived)
            ->orderBy('id_group', 'DESC')
            ->paginate($perPage);
        return new JsonResponse(['groups' => $groups]);
    }
}
