
<div class="relative inline-block text-left w-full" name="select" id="{{$select_id??''}}">

    <button type="button" data-select="All"
            class="inline-flex w-full justify-between gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
            id="menu-button" aria-expanded="true" aria-haspopup="true">
        @if(isset($select,$default))
            @if(is_array($select[$default]))
                <span >{{$select[$default]['date']}}</span>
            @else
                <span >{{$select[$default]}}</span>
            @endif
        @endif
        @if(isset($placeholder))
            <span>{{$placeholder}}</span>
        @endif
        <svg class="-mr-1 size-5 text-black" viewBox="0 0 20 20" fill="currentColor"
             aria-hidden="true" data-slot="icon">
            <path fill-rule="evenodd"
                  d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd"/>
        </svg>
    </button>
    <div
        class="max-h-48 overflow-y-scroll absolute left-0 z-10 mt-2 text-start w-auto min-w-max origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"
        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">

        @if(isset($select,$default))

            @foreach($select as $sk => $sv)
                @if(isset($li))
                    <li class="block px-4 py-2 text-sm text-gray-700 border-0 outline-none rounded-md cursor-pointer {{ $default === $sk ? 'active' : '' }}"
                        role="menuitem" tabindex="-1" data-value="{{$sk}}" name="{{$name??''}}">{{$sv['date']}}
                    </li>
                @else
                    <a href="/{{$Slug}}/{{$sk}}"
                       class="block px-4 py-2 text-sm text-gray-700 text-start w-full border-0 outline-none rounded-md {{ $default === $sk ? 'active' : '' }}"
                       role="menuitem" tabindex="-1" data-value="{{$sk}}">
                        {{$sv}}
                    </a>
                @endif
            @endforeach
        @endif


    </div>
</div>
