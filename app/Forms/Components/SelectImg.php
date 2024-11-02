<?php

namespace App\Forms\Components;

//use Doctrine\DBAL\Schema\View;
use Filament\Forms\Components\Field;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Contracts\View\View;

class SelectImg extends Field
{
//    protected string $view = 'forms.components.select-img';
//    protected array $options = []; // 定义一个属性来存储选项
//
//    public function setOptions(array $options): self
//    {
//        $this->options = $options; // 设置选项
//        return $this;
//    }
//
//    public function render(): View
//    {
//        return view('forms.components.select-img', ['options' => $this->options]);
//    }
    public function render(): View
    {
        $options = collect([
            'option1' => 'Option 1',
            'option2' => 'Option 2',
            'option3' => 'Option 3',
        ]);

        $field = 'aaa';
        return view('forms.components.select-img',['options' => $options]);
    }

}
