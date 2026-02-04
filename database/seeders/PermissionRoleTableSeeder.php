<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    private $permissionSets = [
        // CSWD Office
        2 => [
            'application_management_access',
            'patient_record_create',
            'patient_record_edit',
            'patient_record_show',
            'patient_record_delete',
            'patient_record_access',
            'process_tracking_access',
            'submit_patient_application',
            'analytics_access',
            'CSWD-ANALYTICS',
            'documents_management',
            'settings',
            'online_application_access'
        ],
        // Mayors Office
        3 => [
            'application_management_access',
            'patient_record_access',
            'process_tracking_access',
            'approve_patient',
            'analytics_access',
            'CSWD-ANALYTICS',
            'documents_management',
            'BUDGET-ANALYTICS',
            'TREASURY-ANALYTICS',
            'ACCOUNTING-ANALYTICS'
        ],
        // Budget Office
        4 => [
            'application_management_access',
            'patient_record_show',
            'patient_record_access',
            'process_tracking_access',
            'analytics_access',
            'budget_allocate',
            'documents_management',
            'BUDGET-ANALYTICS',
            'budget_records'
        ],
        // Accounting Office
        5 => [
            'application_management_access',
            'patient_record_show',
            'patient_record_access',
            'process_tracking_access',
            'analytics_access',
            'accounting_dv_input',
            'documents_management',
            'ACCOUNTING-ANALYTICS'
        ],
        // Treasury Office
        6 => [
            'application_management_access',
            'patient_record_show',
            'patient_record_access',
            'process_tracking_access',
            'analytics_access',
            'documents_management',
            'TREASURY-ANALYTICS',
            'treasury_disburse'
        ],
    ];

    public function run()
    {
        // Admin gets all permissions
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        // Assign permissions to other roles
        foreach ($this->permissionSets as $roleId => $permissions) {
            $permissionIds = Permission::whereIn('title', $permissions)->pluck('id');
            Role::findOrFail($roleId)->permissions()->sync($permissionIds);
        }
    }
}