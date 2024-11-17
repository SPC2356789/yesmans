<?php

return [
    'version' => [
        'tiny' => '7.3.0',
        'language' => [
            // https://cdn.jsdelivr.net/npm/tinymce-i18n@latest/
            'version' => '24.7.29',
            'package' => 'langs7',
        ],
        'licence_key' => env('TINY_LICENSE_KEY', 'no-api-key'),
    ],
    'provider' => 'cloud', // cloud|vendor
    // 'direction' => 'rtl',
    /**
     * change darkMode: 'auto'|'force'|'class'|'media'|false|'custom'
     */
    'darkMode' => 'auto',

    /** cutsom */
    'skins' => [
        // oxide, oxide-dark, tinymce-5, tinymce-5-dark
        'ui' => 'oxide',

        // dark, default, document, tinymce-5, tinymce-5-dark, writer
        'content' => 'writer'
    ],

    'profiles' => [
        'default' => [
            'plugins' => 'accordion autoresize  directionality advlist link image lists preview pagebreak searchreplace wordcount code fullscreen insertdatetime media table emoticons code',
            'toolbar' => 'undo redo removeformat | fontfamily fontsize fontsizeinput font_size_formats styles | bold italic underline | rtl ltr | alignjustify alignleft aligncenter alignright | numlist bullist outdent indent | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen | code',
            'upload_directory' => null,
//            'paste_webkit_styles' => 'color font-size',
        ],
        'aal' => [
            'plugins' => 'accordion autoresize code directionality advlist autolink link image lists charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons help',
            'toolbar' => 'undo redo removeformat code | fontfamily  fontsizeinput font_size_formats styles | bold italic underline | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist outdent indent accordion | forecolor backcolor | blockquote table toc hr code | image link  media codesample emoticons | visualblocks preview wordcount fullscreen help',
            'upload_directory' => null,
        ],
        'simple' => [
            'plugins' => 'autoresize directionality emoticons link wordcount',
            'toolbar' => 'removeformat | blocks fontfamily fontsize | bold italic | rtl ltr | numlist bullist | link emoticons',
            'upload_directory' => null,
        ],

        'minimal' => [
            'plugins' => 'link wordcount',
            'toolbar' => 'bold italic link numlist bullist',
            'upload_directory' => null,
        ],

        'full' => [
            'plugins' => 'accordion autoresize code directionality advlist autolink link image lists charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons help',
            'toolbar' => 'undo redo removeformat | fontfamily  fontsizeinput font_size_formats styles | bold italic underline | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist outdent indent accordion | forecolor backcolor | blockquote table toc hr | image link anchor media codesample emoticons | visualblocks print preview wordcount fullscreen help|code',
            'upload_directory' => null,
        ],
    ],

    /**
     * this option will load optional language file based on you app locale
     * example:
     * languages => [
     *      'fa' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@24.7.29/langs7/fa.min.js',
     *      'es' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@24.7.29/langs7/es.min.js',
     *      'ja' => asset('assets/ja.min.js')
     * ]
     */
    'language' => [
        'zh-TW' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@24.7.29/langs7/zh_TW.min.js'
    ],

    'extra' => [
        'toolbar' => [
            'fontsize' => '10px 12px 13px 14px 16px 18px 20px',
            'fontfamily' =>'寒蟬圓黑=Chill Round Gothic,Inter,Noto Sans TC ,Microsoft JhengHei , Arial, sans-serif;英文預設=Inter,Chill Round Gothic,Noto Sans TC ,Microsoft JhengHei , Arial, sans-serif;微軟正黑=Microsoft JhengHei,Chill Round Gothic,Inter,Noto Sans TC , Arial, sans-serif;',

        ]
    ]
];
