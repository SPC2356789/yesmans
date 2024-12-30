import $ from 'jquery';

$(document).ready(function () {

    //標籤行為
    $('button.span_tag').click(function () {
        $(this).toggleClass('active');
    });
    // 熱門
    $('a.addHot').click(function () {
        const id = $(this).attr('id'); // 正確獲取元素的 id 屬性
        $.ajax({
            url: '/blog/active',
            method: 'POST',
            data: {id: id},
            success: function () {
           },
            error: function () {
            }
        });


    });

    //關鍵字搜尋
    const blogSearch = $('#blogSearch')
    const key = blogSearch.data('key')
    let debounceTimer;
    blogSearch.change('input', function () {
        // console.log();  // 确保能在控制台看到更新的值
        clearTimeout(debounceTimer);
        blogItem(key, $(this).val())
    });

    function blogItem(key, searchTerm) {
        $.ajax({
            url: '/blog/' + key + '?term=' + searchTerm,
            method: 'PATCH',
            data: {term: searchTerm, key: key},
            success: function (data) {
                console.log(data)
                // $('#search-results').empty();  // 清空從+
                $('#search-results').html(data);  // 假設返回的是 HTML 內容，將其插入到頁面中
            },
            error: function () {
                $('#search-results').html('<div>搜索時發生錯誤。</div>');
            }
        });
    }
});

