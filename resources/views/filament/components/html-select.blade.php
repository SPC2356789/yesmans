
{{--<select id="imageSelect" style="width: 200px;">--}}
{{--    <option value="">請選擇圖片</option>--}}
{{--    <option data-image="https://fakeimg.pl/50x50/" value="uuid1">Image 1</option>--}}
{{--    <option data-image="https://fakeimg.pl/50x50/ff0000/ffffff/" value="uuid2">Image 2</option>--}}
{{--    <option data-image="https://fakeimg.pl/50x50/00ff00/ffffff/" value="uuid3">Image 3</option>--}}
{{--    <option data-image="https://fakeimg.pl/50x50/0000ff/ffffff/" value="uuid4">Image 4</option>--}}
{{--</select>--}}

@php
    $level = $getLevel();

@endphp

<{{ $level }}
    x-data
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('select2-stylesheet'))]"
    {{
          $attributes
              ->class([
                  'headings-component',
                  match ($color) {
                      'gray' => 'text-gray-600 dark:text-gray-400',
                      default => 'text-custom-500',
                  },
              ])
              ->style([
                  \Filament\Support\get_color_css_variables($color, [500]) => $color !== 'gray',
              ])
      }}
>
    {{ $getContent() }}
</{{ $level }}>

<select id="customSelect" style="width: 200px;">
    <option value="" disabled selected>請選擇圖片</option>
    <option value="uuid1" data-image="https://fakeimg.pl/50x50/">Image 1</option>
    <option value="uuid2" data-image="https://fakeimg.pl/50x50/ff0000/ffffff/">Image 2</option>
    <option value="uuid3" data-image="https://fakeimg.pl/50x50/00ff00/ffffff/">Image 3</option>
    <option value="uuid4" data-image="https://fakeimg.pl/50x50/0000ff/ffffff/">Image 4</option>
</select>

<input type="hidden" id="selectedValue" name="selectedValue">
<img id="selectedImage" src="https://fakeimg.pl/50x50/" alt="Selected Image">
<span id="selectedLabel">請選擇圖片</span>



