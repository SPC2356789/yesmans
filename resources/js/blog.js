import $ from 'jquery';
import axios from 'axios';
import {Tool} from './tools';

$(document).ready(function () {


    // 熱門
    $('a.addHot').click(function () {
        const id = $(this).attr('id'); // 獲取元素的 id 屬性
        axios.post('/blog/active', {id: id})
            .then(response => {

            })
            .catch(error => {
            });
    });

    //關鍵字搜尋
    const Search = $('#blogSearch')
    const key = Search.data('key')
    let debounceTimer;
    Search.change('input', function () {
        // console.log();  // 确保能在控制台看到更新的值
        clearTimeout(debounceTimer);
        $('#loading-icon').html(
            Tool.loading(),//等待圖示
        );
        blogItem(key, $(this).val());
    });

    function blogItem(key, searchTerm) {
        axios.patch('/blog/' + key, {
            term: Tool.sanitizeInput(searchTerm),
            key: key
        })
            .then(response => {
                setTimeout(() => {
                    $('#search-results').html(response.data);
                }, 500); // 延遲 1000 毫秒（1秒）
            })
            .catch(error => {
                // console.error('搜尋時發生錯誤：', error);
                $('#search-results').html('<div>發生不可預期的錯誤，請聯絡管理員。</div>');
            });
    }


});

