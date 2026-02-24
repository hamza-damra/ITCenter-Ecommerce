<?php

/**
 * Permission definitions for the RBAC system.
 *
 * Each group represents a module/section of the admin dashboard.
 * Each permission has a unique key (group.action) used for checking access.
 * The 'sidebar' key maps to the route pattern used for sidebar visibility.
 */
return [
    'groups' => [
        'dashboard' => [
            'label' => 'messages.dashboard',
            'icon' => 'fas fa-chart-line',
            'sidebar_route' => 'admin.dashboard',
            'permissions' => [
                'dashboard.view' => 'messages.permission_view',
            ],
        ],
        'orders' => [
            'label' => 'messages.orders',
            'icon' => 'fas fa-shopping-bag',
            'sidebar_route' => 'admin.orders.*',
            'permissions' => [
                'orders.view' => 'messages.permission_view',
                'orders.edit' => 'messages.permission_edit',
                'orders.delete' => 'messages.permission_delete',
                'orders.export' => 'messages.permission_export',
            ],
        ],
        'contacts' => [
            'label' => 'messages.contact_messages',
            'icon' => 'fas fa-envelope',
            'sidebar_route' => 'admin.contacts.*',
            'permissions' => [
                'contacts.view' => 'messages.permission_view',
                'contacts.edit' => 'messages.permission_edit',
                'contacts.delete' => 'messages.permission_delete',
            ],
        ],
        'promotional_offers' => [
            'label' => 'messages.promotional_management',
            'icon' => 'fas fa-bullhorn',
            'sidebar_route' => 'admin.promotional-offers.*',
            'permissions' => [
                'promotional_offers.view' => 'messages.permission_view',
                'promotional_offers.create' => 'messages.permission_create',
                'promotional_offers.edit' => 'messages.permission_edit',
                'promotional_offers.delete' => 'messages.permission_delete',
            ],
        ],
        'banners' => [
            'label' => 'messages.banner_management',
            'icon' => 'fas fa-images',
            'sidebar_route' => 'admin.banners.*',
            'permissions' => [
                'banners.view' => 'messages.permission_view',
                'banners.create' => 'messages.permission_create',
                'banners.edit' => 'messages.permission_edit',
                'banners.delete' => 'messages.permission_delete',
            ],
        ],
        'promotional_ads' => [
            'label' => 'messages.promotional_ads',
            'icon' => 'fas fa-ad',
            'sidebar_route' => 'admin.promotional-ads.*',
            'permissions' => [
                'promotional_ads.view' => 'messages.permission_view',
                'promotional_ads.create' => 'messages.permission_create',
                'promotional_ads.edit' => 'messages.permission_edit',
                'promotional_ads.delete' => 'messages.permission_delete',
            ],
        ],
        'products' => [
            'label' => 'messages.products',
            'icon' => 'fas fa-box',
            'sidebar_route' => 'admin.products.*',
            'permissions' => [
                'products.view' => 'messages.permission_view',
                'products.create' => 'messages.permission_create',
                'products.edit' => 'messages.permission_edit',
                'products.delete' => 'messages.permission_delete',
            ],
        ],
        'categories' => [
            'label' => 'messages.categories',
            'icon' => 'fas fa-folder',
            'sidebar_route' => 'admin.categories.*',
            'permissions' => [
                'categories.view' => 'messages.permission_view',
                'categories.create' => 'messages.permission_create',
                'categories.edit' => 'messages.permission_edit',
                'categories.delete' => 'messages.permission_delete',
            ],
        ],
        'brands' => [
            'label' => 'messages.brands',
            'icon' => 'fas fa-tag',
            'sidebar_route' => 'admin.brands.*',
            'permissions' => [
                'brands.view' => 'messages.permission_view',
                'brands.create' => 'messages.permission_create',
                'brands.edit' => 'messages.permission_edit',
                'brands.delete' => 'messages.permission_delete',
            ],
        ],
        'tags' => [
            'label' => 'messages.tags_management',
            'icon' => 'fas fa-tags',
            'sidebar_route' => 'admin.tags.*',
            'permissions' => [
                'tags.view' => 'messages.permission_view',
                'tags.create' => 'messages.permission_create',
                'tags.edit' => 'messages.permission_edit',
                'tags.delete' => 'messages.permission_delete',
            ],
        ],
        'spec_templates' => [
            'label' => 'messages.specification_templates',
            'icon' => 'fas fa-clipboard-list',
            'sidebar_route' => 'admin.spec-templates.*',
            'permissions' => [
                'spec_templates.view' => 'messages.permission_view',
                'spec_templates.create' => 'messages.permission_create',
                'spec_templates.edit' => 'messages.permission_edit',
                'spec_templates.delete' => 'messages.permission_delete',
            ],
        ],
        'filters' => [
            'label' => 'messages.filters_management',
            'icon' => 'fas fa-filter',
            'sidebar_route' => 'admin.filters.*',
            'permissions' => [
                'filters.view' => 'messages.permission_view',
                'filters.create' => 'messages.permission_create',
                'filters.edit' => 'messages.permission_edit',
                'filters.delete' => 'messages.permission_delete',
            ],
        ],
        'reviews' => [
            'label' => 'messages.reviews',
            'icon' => 'fas fa-star',
            'sidebar_route' => 'admin.reviews.*',
            'permissions' => [
                'reviews.view' => 'messages.permission_view',
                'reviews.delete' => 'messages.permission_delete',
            ],
        ],
        'backup' => [
            'label' => 'messages.backup_management',
            'icon' => 'fas fa-database',
            'sidebar_route' => 'admin.backup.*',
            'permissions' => [
                'backup.view' => 'messages.permission_view',
                'backup.create' => 'messages.permission_create',
                'backup.restore' => 'messages.permission_restore',
                'backup.delete' => 'messages.permission_delete',
            ],
        ],
        'home_sections' => [
            'label' => 'messages.home_sections_management',
            'icon' => 'fas fa-th-large',
            'sidebar_route' => 'admin.home-sections.*',
            'permissions' => [
                'home_sections.view' => 'messages.permission_view',
                'home_sections.create' => 'messages.permission_create',
                'home_sections.edit' => 'messages.permission_edit',
                'home_sections.delete' => 'messages.permission_delete',
            ],
        ],
        'shipping' => [
            'label' => 'messages.shipping_management',
            'icon' => 'fas fa-truck',
            'sidebar_route' => 'admin.shipping.*',
            'permissions' => [
                'shipping.view' => 'messages.permission_view',
                'shipping.create' => 'messages.permission_create',
                'shipping.edit' => 'messages.permission_edit',
                'shipping.delete' => 'messages.permission_delete',
            ],
        ],
        'employees' => [
            'label' => 'messages.employee_management',
            'icon' => 'fas fa-users-cog',
            'sidebar_route' => 'admin.employees.*',
            'admin_only' => true,
            'permissions' => [
                'employees.view' => 'messages.permission_view',
                'employees.create' => 'messages.permission_create',
                'employees.edit' => 'messages.permission_edit',
                'employees.delete' => 'messages.permission_delete',
            ],
        ],
        'roles' => [
            'label' => 'messages.role_management',
            'icon' => 'fas fa-shield-alt',
            'sidebar_route' => 'admin.roles.*',
            'admin_only' => true,
            'permissions' => [
                'roles.view' => 'messages.permission_view',
                'roles.create' => 'messages.permission_create',
                'roles.edit' => 'messages.permission_edit',
                'roles.delete' => 'messages.permission_delete',
            ],
        ],
    ],
];
