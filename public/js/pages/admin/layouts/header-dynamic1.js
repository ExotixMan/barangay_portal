const __cfg_admin_layouts_header_1 = JSON.parse(document.getElementById('js-config-admin-layouts-header-1')?.dataset.jsConfig || '{}');

        window.adminNotificationConfig = {
            adminId: __cfg_admin_layouts_header_1.v1,

            feedUrl: __cfg_admin_layouts_header_1.v2,

            readAllUrl: __cfg_admin_layouts_header_1.v3,

            readUrlTemplate: __cfg_admin_layouts_header_1.v4,

            openUrlTemplate: __cfg_admin_layouts_header_1.v5,

            csrfToken: __cfg_admin_layouts_header_1.v6
        };
