<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dashboard = Menu::create([
            'name' => 'dashboard',
            'icon' => 'tachometer-alt',
            'route_or_url' => 'dashboard',
            'position' => 1,
            'deleteable' => false,
        ]);

        $builtin = Menu::create([
            'name' => 'builtin',
            'icon' => 'circle',
            'position' => 2,
            'deleteable' => false,
        ]);

        $builtin->permissions()->attach(
            Permission::whereIn('name', [
                'read permission',
                'read role',
                'read user',
                'read menu',
                'read translation'
            ])->get()->pluck(['id'])
        );

        $permission = $builtin->childs()->create([
            'name' => 'permission',
            'route_or_url' => 'super_admin.permission.index',
            'icon' => 'key',
            'position' => 1,
            'deleteable' => false,
            'actives' => [
                'super_admin.permission.*',
            ],
        ]);

        $permission->permissions()->attach(
            Permission::whereIn('name', [
                'create permission',
                'read permission',
                'update permission',
                'delete permission',
            ])->get(['id'])
        );

        $role = $builtin->childs()->create([
            'name' => 'role',
            'route_or_url' => 'super_admin.role.index',
            'icon' => 'user-cog',
            'position' => 2,
            'deleteable' => false,
            'actives' => [
                'super_admin.role.*',
            ],
        ]);

        $role->permissions()->attach(
            Permission::whereIn('name', [
                'create role',
                'read role',
                'update role',
                'delete role',
            ])->get(['id'])
        );

        $user = $builtin->childs()->create([
            'name' => 'user',
            'route_or_url' => 'super_admin.user.index',
            'icon' => 'user',
            'position' => 3,
            'deleteable' => false,
            'actives' => [
                'super_admin.user.*',
            ],
        ]);

        $user->permissions()->attach(
            Permission::whereIn('name', [
                'create user',
                'read user',
                'update user',
                'delete user',
            ])->get(['id'])
        );

        $menu = $builtin->childs()->create([
            'name' => 'menu',
            'route_or_url' => 'super_admin.menu.index',
            'icon' => 'bars',
            'position' => 4,
            'deleteable' => false,
            'actives' => [
                'super_admin.menu.*',
            ],
        ]);

        $menu->permissions()->attach(
            Permission::whereIn('name', [
                'create menu',
                'read menu',
                'update menu',
                'delete menu',
            ])->get(['id'])
        );

        $translation = $builtin->childs()->create([
            'name' => 'translation',
            'route_or_url' => 'super_admin.translation.index',
            'icon' => 'language',
            'position' => 5,
            'deleteable' => false,
            'actives' => [
                'super_admin.translation.*',
            ],
        ]);

        $translation->permissions()->attach(
            Permission::whereIn('name', [
                'create translation',
                'read translation',
                'update translation',
                'delete translation',
            ])->get(['id'])
        );

        $activities = Menu::create([
            'name' => 'activities',
            'icon' => 'address-card',
            'position' => 3,
            'deleteable' => false,
        ]);

        $activities->permissions()->attach(
            Permission::whereIn('name', [
                'read activities',
            ])->get()->pluck(['id'])
        );

        $login = $activities->childs()->create([
            'name' => 'login',
            'route_or_url' => 'super_admin.activity.login',
            'icon' => 'user-clock',
            'position' => 1,
            'deleteable' => false,
            'actives' => [
                'super_admin.activity.login',
            ],
        ]);

        $login->permissions()->attach(
            Permission::whereIn('name', [
                'read login activities',
            ])->get(['id'])
        );
    }
}
