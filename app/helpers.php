<?php
if (!function_exists('vite_ngrok')) {
    function vite_ngrok($paths) {
        $ngrokUrl=config('app.vite');
//            dd($ngrokUrl);
        $output = '';

        // 添加 Vite 客戶端腳本

        // 將 paths 字符串解析為陣列
        $pathsArray = explode(',', str_replace(['[', ']', '"', "'"], '', $paths));

        foreach ($pathsArray as $path) {
            $path = trim($path); // 去掉空白字符
            $output .= '<script type="module" src="' . $ngrokUrl . '/@vite/client" data-navigate-track="reload"></script>';
            if (str_ends_with($path, '.css')) {
                $output .= '<link rel="stylesheet" href="' . $ngrokUrl . '/' . $path . '" data-navigate-track="reload" />';
            } elseif (str_ends_with($path, '.js')) {
                $output .= '<script type="module" src="' . $ngrokUrl . '/' . $path . '" data-navigate-track="reload"></script>';
            }
        }
        return $output;    }

}

