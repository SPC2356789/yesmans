<div class="relative inline-block text-left" name="select" id="{{$select_id??''}}">
    <button type="button" data-select="All"
            class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
            id="menu-button" aria-expanded="true" aria-haspopup="true">
        @if(isset($Categories,$urlSlug))
            <span>{{$Categories[$urlSlug]}}</span>
        @endif
        @if(isset($placeholder))
            <span>{{$placeholder}}</span>
        @endif
        <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
             aria-hidden="true" data-slot="icon">
            <path fill-rule="evenodd"
                  d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd"/>
        </svg>
    </button>
    <div
        class="h-56 overflow-y-scroll absolute left-0 z-10 mt-2 w-40 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"
        id="{{$menu_id??''}}" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        @if(isset($li))
            <li class=""
                role="menuitem" tabindex="-1" data-value="{{''}}">
            </li>
        @endif
        @if(isset($Categories,$urlSlug))
            @foreach($Categories as $Ck => $Cv)
                <a href="/blog/{{$Ck}}"
                   class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md {{ $urlSlug === $Ck ? 'active' : '' }}"
                   role="menuitem" tabindex="-1" data-value="{{$Ck}}">
                    {{$Cv}}
                </a>
            @endforeach
        @endif


    </div>
</div>
