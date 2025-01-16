import $ from 'jquery';
import axios from 'axios';
$(document).ready(function () {

    //標籤行為
    $('button.span_tag').click(function () {
        $(this).toggleClass('active');
    });
    // 熱門
    $('a.addHot').click(function () {
        const id = $(this).attr('id'); // 獲取元素的 id 屬性
        axios.post('/blog/active', { id: id })
            .then(response => {


            })
            .catch(error => {


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
        axios.patch('/blog/' + key, {
            term: searchTerm,
            key: key
        })
            .then(response => {
                // console.log(response.data);
                $('#search-results').html(response.data);
            })
            .catch(error => {
                // console.error('搜尋時發生錯誤：', error);
                $('#search-results').html('<div>搜尋時發生錯誤。</div>');
            });
    }
});

