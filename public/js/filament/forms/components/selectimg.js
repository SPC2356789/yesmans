$(document).ready(function() {
    function formatOption(option) {
        if (!option.id) {
            return option.text; // 返回文本
        }
        var imgSrc = $(option.element).data('image'); // 獲取圖片URL
        var $option = $(
            '<span><img src="' + imgSrc + '" style="width: 20px; height: 20px; margin-right: 5px;" /> ' + option.text + '</span>'
        );
        return $option; // 返回自定義的選項樣式
    }

    function formatSelection(option) {
        var imgSrc = $(option.element).data('image'); // 獲取圖片URL
        var $selection = $(
            '<span><img src="' + imgSrc + '" style="width: 20px; height: 20px; margin-right: 5px;" /> ' + option.text + '</span>'
        );
        return $selection; // 返回自定義的選中樣式
    }

    $('#imageSelect').select2({
        templateResult: formatOption, // 自定義選項樣式
        templateSelection: formatSelection // 自定義選中樣式
    });
});
