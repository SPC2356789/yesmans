<!-- Cookie 同意橫幅 -->
<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-4 z-50 animate-fade-in-up">
    <div class="max-w-[600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-4 rounded-xl bg-neutral-800 opacity-85 shadow-lg border !border-neutral-950">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex-1">
                    <p class="text-white text-sm md:text-base cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="flex-col w-full sm:w-auto flex gap-2">
                    <button class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-5 py-2 rounded-full text-sm font-medium text-white bg-yes-major bg-opacity-75 hover:bg-opacity-100 transition-colors duration-200">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                    <button class="js-cookie-consent-reject cursor-pointer flex items-center justify-center px-5 py-2 rounded-full text-sm font-medium text-white bg-neutral-600 bg-opacity-75 hover:bg-opacity-100 transition-colors duration-200">
                        拒絕
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 遮罩層 -->
<div id="cookie-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 pointer-events-auto"></div>

<!-- 自定義樣式 -->
<style>
    /* 自定義動畫 */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
    /* 鎖定頁面時禁用滾動 */
    body.locked {
        overflow: hidden;
    }
</style>

<!-- JavaScript -->
<script>
    document.querySelector('.js-cookie-consent-agree').addEventListener('click', function () {
        document.cookie = 'cookieConsent=1; path=/; max-age=31536000';
        document.body.classList.remove('locked');
        document.querySelector('.js-cookie-consent').style.display = 'none';
        document.querySelector('#cookie-overlay').style.display = 'none';
        {{--fetch('/set-cookie-consent', {--}}
        {{--    method: 'POST',--}}
        {{--    headers: {--}}
        {{--        'Content-Type': 'application/json',--}}
        {{--        'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
        {{--    },--}}
        {{--    body: JSON.stringify({ consent: '1' })--}}
        {{--});--}}
    });

    document.querySelector('.js-cookie-consent-reject').addEventListener('click', function () {
        document.cookie = 'cookieConsent=0; path=/; max-age=31536000';
        window.history.back();
        {{--fetch('/set-cookie-consent', {--}}
        {{--    method: 'POST',--}}
        {{--    headers: {--}}
        {{--        'Content-Type': 'application/json',--}}
        {{--        'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
        {{--    },--}}
        {{--    body: JSON.stringify({ consent: '0' })--}}
        {{--});--}}
    });
</script>
