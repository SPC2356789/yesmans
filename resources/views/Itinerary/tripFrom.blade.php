<form>
    <div class=" space-y-4">
        <h2 class="text-base/7 font-semibold text-gray-900">報名資訊</h2>
        <div class="border-b border-gray-900/10 pb-4 flex flex-col gap-5" data-number=1 name="apply_trip">
            <div class="flex flex-row justify-between">
                <p class="mt-1 text-sm/6 text-gray-600" name="apply_title">序號1</p>
                <div
                    class="inline-flex items-center cursor-pointer text-gray-600 hover:text-[#EE9900] active:scale-95 transition-transform duration-200 ease-in-out hidden"
                    name="remove_number">
                    <i class="fa-solid fa-user-minus"></i>
                </div>
            </div>
            <fieldset>
                <legend class="text-sm/6 font-semibold text-gray-900">個人資訊</legend>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm/6 font-medium text-gray-900">全名</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" autocomplete="name"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>

                    <div class="sm:col-span-1">
                        <span class="block text-sm/6 font-medium text-gray-900 ">性別</span>
                        <div class="flex flex-col gap-x-3">
                            <div>
                                <input id="gender-male" name="gender" type="radio" checked class="tailwind_radio">
                                <label for="gender-male">男</label>
                            </div>
                            <div>
                                <input id="gender-female" name="gender" type="radio" class="tailwind_radio">
                                <label for="gender-female">女</label>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="birthday" class="block text-sm/6 font-medium text-gray-900">生日</label>
                        <div class="mt-2">
                            <input id="birthday" name="birthday" type="date" autocomplete="bday"
                                   min="{{ \Carbon\Carbon::today()->subYears(80)->toDateString() }}"
                                   max="{{ \Carbon\Carbon::today()->toDateString() }}"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <select class="js-country" id="country" name="country">
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="id-card"
                               class="block text-sm/6 font-medium text-gray-900">身分證/居留證</label>
                        <div class="mt-2">
                            <input id="id-card" name="id-card" type="text" autocomplete="off"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="id-card"
                               class="block text-sm/6 font-medium text-gray-900">護照</label>
                        <div class="mt-2">
                            <input id="id-card" name="PassPort" type="text" autocomplete="off"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="col-span-full">
                        <label for="street-address" class="block text-sm/6 font-medium text-gray-900">居住地址</label>
                        <div class="mt-2">
                            <input type="text" name="street-address" id="street-address"
                                   autocomplete="street-address"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm/6 font-medium text-gray-900">電子郵件</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-sm/6 font-medium text-gray-900">聯絡電話</label>
                        <div class="mt-2">
                            <input id="phone" name="phone" type="tel" autocomplete="tel"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="emergency_contact"
                               class="block text-sm/6 font-medium text-gray-900">緊急連絡人</label>
                        <div class="mt-2">
                            <input id="emergency_contact" name="emergency_contact" type="text" autocomplete="off"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="emergency_contact_phone"
                               class="block text-sm/6 font-medium text-gray-900">緊急連絡人電話</label>
                        <div class="mt-2">
                            <input id="emergency_contact_phone" name="emergency_contact_phone" type="tel"
                                   autocomplete="tel"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>

                </div>
            </fieldset>
            <fieldset>
                <legend class="text-sm/6 font-semibold text-gray-900">聯絡資訊(擇一填寫)</legend>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <div class="sm:col-span-3">
                        <label for="media_LINE" class="block text-sm/6 font-medium text-gray-900">LINE-ID</label>
                        <div class="mt-2">
                            <input id="media_LINE" name="media_LINE" type="text"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="media_IG" class="block text-sm/6 font-medium text-gray-900">IG-ID</label>
                        <div class="mt-2">
                            <input id="media_IG" name="media_IG" type="text"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    {{--                        <div class="sm:col-span-3">--}}
                    {{--                            <label for="media_fb" class="block text-sm/6 font-medium text-gray-900">Facebook</label>--}}
                    {{--                            <div class="mt-2">--}}
                    {{--                                <input id="media_fb" name="media_fb" type="text"--}}
                    {{--                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                </div>
            </fieldset>
            <fieldset>
                <legend class="text-sm/6 font-semibold text-gray-900">過往經驗(請準確填寫)</legend>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="medical_history" class="block text-sm/6 font-medium text-gray-900">病史</label>
                        <div class="mt-2">
                            <input id="medical_history" name="medical_history" type="text"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="mountain_experience"
                               class="block text-sm/6 font-medium text-gray-900">爬山/運動經驗(過去一年)</label>
                        <div class="mt-2">
                            <input id="mountain_experience" name="mountain_experience" type="text"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-yes-major sm:text-sm/6">
                        </div>
                    </div>
                    @if ($trip_times['food'])
                        <div class="sm:col-span-1">
                            <span class="block text-sm/6 font-medium text-gray-900">飲食</span>
                            <div class="flex flex-row gap-x-3">
                                <div>
                                    <input id="diet-vegetarian" name="diet" type="radio" class="tailwind_radio">
                                    <label for="diet-vegetarian">素食</label>
                                </div>
                                <div>
                                    <input id="diet-non-vegetarian" name="diet" type="radio" class="tailwind_radio"
                                           checked>
                                    <label for="diet-non-vegetarian">葷食</label>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </fieldset>
        </div>
        <div
            class="inline-flex items-center cursor-pointer text-gray-600 hover:text-yes-major active:scale-95 transition-transform duration-200 ease-in-out"
            name="add_number">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <div class="flex justify-between">
            <div class="w-44">
                {{--            <img src="{{ captcha_src() }}" onclick="this.src='/captcha?'+Math.random()" alt="驗證碼">--}}
                {{--            <input type="text" name="captcha" placeholder="輸入驗證碼">--}}
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">

                <div id="fillTestData" type="button"
                     class="rounded-md bg-yes-major px-3 py-2 text-sm font-semibold  hover:text-yes-major text-neutral-50 shadow-sm hover:bg-neutral-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yes-major">
                    測試
                </div>
                <button type="button" id="submitTestForm"
                        class="rounded-md bg-yes-major px-3 py-2 text-sm font-semibold  hover:text-yes-major text-neutral-50 shadow-sm hover:bg-neutral-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yes-major">
                    報名
                </button>
            </div>
        </div>

    </div>
</form>

