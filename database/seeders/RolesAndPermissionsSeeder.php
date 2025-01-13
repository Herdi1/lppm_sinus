<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            //dosen
            'create_research',
            'read_research',
            'update_research',
            'delete_research',
            'update_status_research',
            'create_logbook',
            'read_logbook',
            'update_logbook',
            'delete_logbook',
            'create_research_progress_report',
            'read_research_progress_report',
            'update_research_progress_report',
            'delete_research_progress_report',
            'create_research_final_report',
            'read_research_final_report',
            'update_research_final_report',
            'delete_research_final_report',

            //reviewer
            'create_research_review',
            'read_research_review',
            'update_research_review',
            'delete_research_review',
            'create_research_monev_review',
            'read_research_monev_review',
            'update_research_monev_review',
            'delete_research_monev_review',

            'create_community_service',
            'read_community_service',
            'update_community_service',
            'delete_community_service',
            'update_status_community_service',
            'create_community_service_logbook',
            'read_community_service_logbook',
            'update_community_service_logbook',
            'delete_community_service_logbook',
            'create_community_service_progress_report',
            'read_community_service_progress_report',
            'update_community_service_progress_report',
            'delete_community_service_progress_report',
            'create_community_service_final_report',
            'read_community_service_final_report',
            'update_community_service_final_report',
            'delete_community_service_final_report',
            'create_community_service_review',
            'read_community_service_review',
            'update_community_service_review',
            'delete_community_service_review',
            'create_community_service_monev_review',
            'read_community_service_monev_review',
            'update_community_service_monev_review',
            'delete_community_service_monev_review',

            'assign_reviewer',
            'set_report_deadline',
            'assign_permission',
            'assign_role',
            'create_user',
            'reset_password',
            'assign_role_to_user',
            'create_activity_period',
            'read_activity_period',
            'update_activity_period',
            'delete_activity_period',
            'add_activity',
            'update_activity',
            'get_activity',
            'get_all_activity',

            'create_service',
            'read_service',
            'update_service',
            'delete_service',

            'get_users',

            'set_approval_funds',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create(['name' => 'Dosen', 'guard_name' => 'api'])
            ->givePermissionTo([
                'create_research',
                'read_research',
                'update_research',
                'delete_research',
                'update_status_research',
                'create_logbook',
                'read_logbook',
                'update_logbook',
                'delete_logbook',
                'create_research_progress_report',
                'read_research_progress_report',
                'update_research_progress_report',
                'delete_research_progress_report',
                'create_research_final_report',
                'read_research_final_report',
                'update_research_final_report',
                'delete_research_final_report',
                'get_users',

                'create_community_service',
                'read_community_service',
                'update_community_service',
                'delete_community_service',
                'update_status_community_service',
                'create_community_service_logbook',
                'read_community_service_logbook',
                'update_community_service_logbook',
                'delete_community_service_logbook',
                'create_community_service_progress_report',
                'read_community_service_progress_report',
                'update_community_service_progress_report',
                'delete_community_service_progress_report',
                'create_community_service_final_report',
                'read_community_service_final_report',
                'update_community_service_final_report',
                'delete_community_service_final_report',
            ]);

        $role = Role::create(['name' => 'Reviewer', 'guard_name' => 'api'])
            ->givePermissionTo([
                'read_research',
                'update_status_research',
                'create_research_review',
                'read_research_review',
                'update_research_review',
                'delete_research_review',
                'read_research_progress_report',
                'create_research_monev_review',
                'read_research_monev_review',
                'update_research_monev_review',
                'delete_research_monev_review',
                'get_activity',

                'read_community_service',
                'update_status_community_service',
                'create_community_service_review',
                'read_community_service_review',
                'update_community_service_review',
                'delete_community_service_review',
                'create_community_service_monev_review',
                'read_community_service_monev_review',
                'update_community_service_monev_review',
                'delete_community_service_monev_review',
            ]);

        $role = Role::create(['name' => 'Operator', 'guard_name' => 'api'])
            ->givePermissionTo([
                'read_research',
                'assign_reviewer',
                'update_status_research',
                'read_research_progress_report',
                'read_research_final_report',

                'read_community_service',
                'update_status_community_service',
                'read_community_service_progress_report',
                'read_community_service_final_report',

                'get_users',
                'create_user',
                'reset_password',
                'assign_role_to_user',
                'set_report_deadline',
                'create_activity_period',
                'read_activity_period',
                'update_activity_period',
                'delete_activity_period',
                'add_activity',
                'update_activity',
                'get_activity',
                'get_all_activity',
            ]);

        $role = Role::create(['name' => 'Kaprodi', 'guard_name' => 'api'])
            ->givePermissionTo(
                [
                    'read_research',
                    'update_status_research',
                    'get_activity',

                    'read_community_service',
                    'update_status_community_service',
                ]
            );
        $role = Role::create(['name' => 'Kepala LPPM', 'guard_name' => 'api'])
            ->givePermissionTo(
                [
                    'read_research',
                    'update_status_research',
                    'read_research_progress_report',
                    'read_research_final_report',
                    'get_activity',
                    'set_approval_funds',

                    'read_community_service',
                    'update_status_community_service',
                    'read_community_service_progress_report',
                    'read_community_service_final_report',
                ]
            );
    }
}
