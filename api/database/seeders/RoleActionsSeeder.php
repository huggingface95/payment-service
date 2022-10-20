<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleAction;
use Exception;
use Illuminate\Database\Seeder;

class RoleActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {

        $actions = [
            ['without_role_id' => 1, 'table' => 'email_templates', 'action' => RoleAction::ACTION_CREATING],
            ['without_role_id' => 1, 'table' => 'email_template_layouts', 'action' => RoleAction::ACTION_CREATING],
        ];

        foreach ($actions as $action) {
                if (array_key_exists('without_role_id', $action)){
                    $roles = Role::query()->where('id', '<>', $action['without_role_id'])->get()->pluck('id');
                    unset($action['without_role_id']);
                }
                elseif (array_key_exists('all_roles', $action)){
                    $roles = Role::query()->get()->pluck('id');
                    unset($action['all_roles']);
                }
                elseif (array_key_exists('role_id', $action)){
                    $roles = Role::query()->where('id', '=', $action['role_id'])->get()->pluck('id');
                    unset($action['role_id']);
                }
                else{
                    continue;
                }

                foreach ($roles as $roleId){
                    $action['role_id'] = $roleId;
                    RoleAction::query()->firstOrCreate($action);
                }
        }
    }
}
