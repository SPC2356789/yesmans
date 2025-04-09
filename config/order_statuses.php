<?php
/**
 * 訂單狀態
 */
return [

    10 => [
        'text' => '報名完成',
        'note' => '初始狀態 - 待小編檢查入帳',
        'icon' => 'heroicon-o-magnifying-glass-minus',  // 放大鏡-，表示等待
        'color' => 'warning',                           // 黃色
    ],

    41 => [
        'text' => '報名完成',
        'note' => '已匯訂 - 小編已確認入帳',
        'icon' => 'heroicon-o-magnifying-glass-plus',   // 放大鏡+，表示完成
        'color' => 'success',                           // 綠色
    ],
    42 => [
        'text' => '報名完成',
        'note' => '已完款 - 小編已確認入帳',
        'icon' => 'heroicon-o-magnifying-glass-plus',   // 放大鏡+，表示完成
        'color' => 'success',                           // 綠色
    ],
    11 => [
        'text' => '報名完成',
        'note' => '待匯款 - 顧客告知晚點匯款',
        'icon' => 'heroicon-o-chat-bubble-bottom-center-text', // 告知圖示，表示等待
        'color' => 'warning',                           // 黃色
    ],
    12 => [
        'text' => '報名完成',
        'note' => '待匯款 - 3天之內沒有匯款也沒有告知，名額釋出',
        'icon' => 'heroicon-o-exclamation-triangle',    // 三角驚嘆號，表示警告
        'color' => 'danger',                            // 紅色
    ],
    14 => [
        'text' => '報名完成',
        'note' => '資料缺少 - 人工聯絡',
        'icon' => 'heroicon-o-document-minus',          // 文件，表示資料相關
        'color' => 'info',                              // 灰色
    ],
    15 => [
        'text' => '報名完成',
        'note' => '失蹤人口 - 人工聯絡',
        'icon' => 'heroicon-o-user-minus',             // 移除用戶，表示失聯
        'color' => 'info',                             // 灰色
    ],
    91 => [
        'text' => '報名取消',
        'note' => '系統取消 - 待退款，已提供帳號',
        'icon' => 'heroicon-o-credit-card',            // 信用卡，表示待退款
        'color' => 'danger',                           // 紅色
    ],
    92 => [
        'text' => '報名取消',
        'note' => '系統取消 - 待退款，請與小編聯繫',
        'icon' => 'heroicon-o-currency-dollar',        // 美元符號，表示待退款
        'color' => 'danger',                           // 紅色
    ],
    93 => [
        'text' => '報名取消',
        'note' => '台端取消 - 待退款，已提供帳號',
        'icon' => 'heroicon-o-credit-card',            // 信用卡，表示待退款
        'color' => 'danger',                           // 紅色
    ],
    94 => [
        'text' => '報名取消',
        'note' => '台端取消 - 待退款，請與小編聯繫',
        'icon' => 'heroicon-o-currency-dollar',        // 美元符號，表示待退款 (修正 typo)
        'color' => 'danger',                           // 紅色
    ],
    98 => [
        'text' => '報名取消',
        'note' => '已退訂金',
        'icon' => 'heroicon-o-currency-dollar',        // 美元符號，表示退款完成
        'color' => 'gray',                             // 灰色 (原為綠色，但你設為 gray)
    ],
    99 => [
        'text' => '報名取消',
        'note' => '已退全款',
        'icon' => 'heroicon-o-currency-dollar',        // 美元符號，表示退款完成
        'color' => 'gray',                             // 灰色 (原為綠色，但你設為 gray)
    ],
    1 => [
        'text' => '行程結束',
        'note' => null,                                // 無附加說明
        'icon' => 'heroicon-o-flag',                   // 旗幟，表示結束
        'color' => 'white',                            // 白色 (可能需要調整，見下方說明)
    ],
];

