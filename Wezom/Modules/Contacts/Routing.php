<?php   
    
    return [
        // Callback
        'wezom/callback/index' => 'contacts/callback/index',
        'wezom/callback/index/page/<page:[0-9]*>' => 'contacts/callback/index',
        'wezom/callback/edit/<id:[0-9]*>' => 'contacts/callback/edit',
        'wezom/callback/delete/<id:[0-9]*>' => 'contacts/callback/delete',
        // Contacts
        'wezom/contacts/index' => 'contacts/contacts/index',
        'wezom/contacts/index/page/<page:[0-9]*>' => 'contacts/contacts/index',
        'wezom/contacts/edit/<id:[0-9]*>' => 'contacts/contacts/edit',
        'wezom/contacts/delete/<id:[0-9]*>' => 'contacts/contacts/delete',
    ];