<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do Sistema de Notificações
    |--------------------------------------------------------------------------
    |
    | Este arquivo contém as configurações para o sistema de notificações
    | da aplicação UniOrthocrin.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Configurações Gerais
    |--------------------------------------------------------------------------
    */
    
    // Tempo de expiração das notificações (em dias)
    'expiration_days' => env('NOTIFICATION_EXPIRATION_DAYS', 30),
    
    // Número máximo de notificações no dropdown
    'max_dropdown_items' => env('MAX_DROPDOWN_NOTIFICATIONS', 10),
    
    // Número máximo de notificações por usuário
    'max_user_notifications' => env('MAX_USER_NOTIFICATIONS', 100),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Sessão
    |--------------------------------------------------------------------------
    */
    
    // Tempo de inatividade para considerar sessão expirada (em horas)
    'session_inactivity_hours' => env('SESSION_INACTIVITY_HOURS', 24),
    
    // Tempo para renovar sessão automaticamente (em horas)
    'session_refresh_hours' => env('SESSION_REFRESH_HOURS', 1),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Visualizações
    |--------------------------------------------------------------------------
    */
    
    // Tempo para considerar visualização como antiga (em dias)
    'view_cleanup_days' => env('VIEW_CLEANUP_DAYS', 365),
    
    // Registrar visualizações automaticamente
    'auto_track_views' => env('AUTO_TRACK_VIEWS', true),
    
    // Registrar downloads automaticamente
    'auto_track_downloads' => env('AUTO_TRACK_DOWNLOADS', true),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Notificações Automáticas
    |--------------------------------------------------------------------------
    */
    
    // Criar notificações automaticamente para conteúdo novo
    'auto_create_notifications' => env('AUTO_CREATE_NOTIFICATIONS', true),
    
    // Tipos de conteúdo que geram notificações automáticas
    'auto_notification_content_types' => [
        'App\Models\Campaign',
        'App\Models\Product',
        'App\Models\Training',
        'App\Models\Library',
        'App\Models\News',
    ],
    
    // Tempo para considerar conteúdo como novo (em dias)
    'new_content_days' => env('NEW_CONTENT_DAYS', 7),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Limpeza Automática
    |--------------------------------------------------------------------------
    */
    
    // Executar limpeza automática
    'auto_cleanup_enabled' => env('AUTO_CLEANUP_ENABLED', true),
    
    // Frequência da limpeza automática (em dias)
    'auto_cleanup_frequency_days' => env('AUTO_CLEANUP_FREQUENCY_DAYS', 7),
    
    // Horário para executar limpeza automática (formato 24h)
    'auto_cleanup_time' => env('AUTO_CLEANUP_TIME', '02:00'),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Interface
    |--------------------------------------------------------------------------
    */
    
    // Mostrar contador de notificações não lidas
    'show_unread_count' => env('SHOW_UNREAD_COUNT', true),
    
    // Animar notificações novas
    'animate_new_notifications' => env('ANIMATE_NEW_NOTIFICATIONS', true),
    
    // Som para notificações novas
    'notification_sound_enabled' => env('NOTIFICATION_SOUND_ENABLED', false),
    
    // URL do som de notificação
    'notification_sound_url' => env('NOTIFICATION_SOUND_URL', '/sounds/notification.mp3'),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Email (Futuro)
    |--------------------------------------------------------------------------
    */
    
    // Enviar notificações por email
    'email_notifications_enabled' => env('EMAIL_NOTIFICATIONS_ENABLED', false),
    
    // Frequência de resumo por email (em dias)
    'email_summary_frequency_days' => env('EMAIL_SUMMARY_FREQUENCY_DAYS', 7),
    
    // Horário para enviar resumo por email (formato 24h)
    'email_summary_time' => env('EMAIL_SUMMARY_TIME', '09:00'),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Push (Futuro)
    |--------------------------------------------------------------------------
    */
    
    // Notificações push habilitadas
    'push_notifications_enabled' => env('PUSH_NOTIFICATIONS_ENABLED', false),
    
    // Chave da API do Firebase
    'firebase_api_key' => env('FIREBASE_API_KEY'),
    
    // ID do projeto Firebase
    'firebase_project_id' => env('FIREBASE_PROJECT_ID'),
    
    /*
    |--------------------------------------------------------------------------
    | Configurações de Relatórios
    |--------------------------------------------------------------------------
    */
    
    // Gerar relatórios de atividade
    'generate_activity_reports' => env('GENERATE_ACTIVITY_REPORTS', true),
    
    // Frequência dos relatórios (em dias)
    'report_frequency_days' => env('REPORT_FREQUENCY_DAYS', 30),
    
    // Manter relatórios por (em dias)
    'report_retention_days' => env('REPORT_RETENTION_DAYS', 365),
];
