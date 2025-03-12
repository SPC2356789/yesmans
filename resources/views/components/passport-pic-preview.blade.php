
@php
    $filePath = 'passport/' . $getRecord()->PassPort ;
    $fileContent = Storage::disk('private')->exists($filePath)
        ? base64_encode(Storage::disk('private')->get($filePath))
        : null;
@endphp

@if ($fileContent)
    <img src="data:image/jpeg;base64,{{ $fileContent }}" alt="護照照片" class="w-auto">
@else
    <p class="text-red-500">找不到護照圖片，請確認護照號碼或重新上傳</p>
@endif
