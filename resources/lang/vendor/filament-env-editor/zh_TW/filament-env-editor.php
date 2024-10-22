<?php
return [
    'navigation' => [
        'group' => '系統',
        'label' => '.Env 編輯器',
    ],

    'page' => [
        'title' => '.Env 編輯器',
    ],
    'tabs' => [
        'current-env' => [
            'title' => '目前的 .env',
        ],
        'backups' => [
            'title' => '備份',
        ],
    ],
    'actions' => [
        'add' => [
            'title' => '新增條目',
            'modalHeading' => '新增條目',
            'success' => [
                'title' => '鍵 ":Name" 已成功寫入',
            ],
            'form' => [
                'fields' => [
                    'key' => '鍵名',
                    'value' => '值',
                    'index' => '插入到現有鍵後（可選）',
                ],
                'helpText' => [
                    'index' => '如果您需要將此新條目放在現有條目之後，您可以選擇一個現有的鍵',
                ],
            ],
        ],
        'edit' => [
            'tooltip' => '編輯條目 ":name"',
            'modal' => [
                'text' => '編輯條目',
            ],
        ],
        'delete' => [
            'tooltip' => '移除條目 ":name"',
            'confirm' => [
                'title' => '您將永久移除 ":name"。確定要執行此操作嗎？',
            ],
        ],
        'clear-cache' => [
            'title' => '清除快取',
            'tooltip' => '有時 Laravel 會快取 ENV 變數，您需要清除所有快取（"artisan optimize:clear"），以重新讀取 .env 變更',
        ],

        'backup' => [
            'title' => '創建新備份',
            'success' => [
                'title' => '備份已成功創建',
            ],
        ],
        'download' => [
            'title' => '下載當前 .env',
            'tooltip' => '下載 ":name" 備份檔案',
        ],
        'upload-backup' => [
            'title' => '上傳備份檔案',
        ],
        'show-content' => [
            'modalHeading' => '顯示 ":name" 備份的原始內容',
            'tooltip' => '顯示原始內容',
        ],
        'restore-backup' => [
            'confirm' => [
                'title' => '您即將還原 ":name"，取代當前的 ".env" 文件。請確認您的選擇',
            ],
            'modalSubmit' => '還原',
            'tooltip' => '將 ":name" 還原為當前的 ENV',
        ],
        'delete-backup' => [
            'tooltip' => '移除 ":name" 備份檔案',
            'confirm' => [
                'title' => '您將永久移除 ":name" 備份檔案。確定要執行此操作嗎？',
            ],
        ],
    ],
];
