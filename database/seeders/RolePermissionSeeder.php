<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions by module
        $permissions = [
            // Dashboard
            'view dashboard',
            // Posts
            'view posts', 'create posts', 'edit posts', 'delete posts', 'publish posts',
            // Pages
            'view pages', 'create pages', 'edit pages', 'delete pages',
            // Announcements
            'view announcements', 'create announcements', 'edit announcements', 'delete announcements',
            // Events
            'view events', 'create events', 'edit events', 'delete events',
            // Lecturers
            'view lecturers', 'create lecturers', 'edit lecturers', 'delete lecturers',
            // Curriculum
            'view curriculum', 'create curriculum', 'edit curriculum', 'delete curriculum',
            // Documents
            'view documents', 'create documents', 'edit documents', 'delete documents',
            // Gallery
            'view gallery', 'create gallery', 'edit gallery', 'delete gallery',
            // Sliders
            'view sliders', 'create sliders', 'edit sliders', 'delete sliders',
            // Testimonials
            'view testimonials', 'create testimonials', 'edit testimonials', 'delete testimonials', 'approve testimonials',
            // Menus
            'view menus', 'create menus', 'edit menus', 'delete menus',
            // Media
            'view media', 'upload media', 'delete media',
            // Settings
            'view settings', 'edit settings',
            // Users
            'view users', 'create users', 'edit users', 'delete users',
            // Categories & Tags
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view tags', 'create tags', 'edit tags', 'delete tags',
            // Contact Messages
            'view contact messages', 'reply contact messages', 'delete contact messages',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles with permissions
        $roles = [
            'Super Admin' => Permission::all()->pluck('name')->toArray(),
            'Admin Prodi' => [
                'view dashboard',
                'view posts', 'create posts', 'edit posts', 'delete posts', 'publish posts',
                'view pages', 'create pages', 'edit pages',
                'view announcements', 'create announcements', 'edit announcements', 'delete announcements',
                'view events', 'create events', 'edit events', 'delete events',
                'view lecturers', 'create lecturers', 'edit lecturers', 'delete lecturers',
                'view curriculum', 'create curriculum', 'edit curriculum', 'delete curriculum',
                'view documents', 'create documents', 'edit documents', 'delete documents',
                'view gallery', 'create gallery', 'edit gallery', 'delete gallery',
                'view sliders', 'create sliders', 'edit sliders', 'delete sliders',
                'view testimonials', 'approve testimonials',
                'view menus', 'create menus', 'edit menus',
                'view media', 'upload media', 'delete media',
                'view settings', 'edit settings',
                'view categories', 'create categories', 'edit categories',
                'view tags', 'create tags', 'edit tags',
                'view contact messages', 'reply contact messages',
            ],
            'Editor Konten' => [
                'view dashboard',
                'view posts', 'create posts', 'edit posts', 'publish posts',
                'view pages', 'create pages', 'edit pages',
                'view announcements', 'create announcements', 'edit announcements',
                'view events', 'create events', 'edit events',
                'view gallery', 'create gallery', 'edit gallery',
                'view media', 'upload media',
                'view categories', 'view tags',
            ],
            'Dosen' => [
                'view dashboard',
                'view posts', 'create posts', 'edit posts',
                'view media', 'upload media',
            ],
            'Operator Akademik' => [
                'view dashboard',
                'view curriculum', 'create curriculum', 'edit curriculum',
                'view documents', 'create documents', 'edit documents',
                'view events', 'create events', 'edit events',
                'view announcements', 'create announcements', 'edit announcements',
                'view media', 'upload media',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
