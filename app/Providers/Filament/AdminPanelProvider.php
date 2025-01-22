<?php

namespace App\Providers\Filament;

use Doctrine\DBAL\Schema\Index;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use SolutionForest\FilamentAccessManagement\FilamentAccessManagementPanel;

use Filament\Navigation\MenuItem;
use Filament\Support\Assets\Css;
//env
use Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin;

//use App\Filament\Clusters\IndexSetting\Resources\IndexBase ;
//單頁非條目

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {

        FilamentAsset::register([
            Css::make('media-select', __DIR__ . '/../../../resources/css/mediaSelect.css')->loadedOnRequest(),
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->profile(isSimple: false)
            ->login()
            ->sidebarWidth('13rem')
            ->userMenuItems([
                'profile' => MenuItem::make()->label('個人資料編輯'),
                // ...
            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')//叢集
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->plugin(
                FilamentEnvEditorPlugin::make(),
            )
            ->plugin(FilamentAccessManagementPanel::make())
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->plugins([
                \FilipFonal\FilamentLogManager\FilamentLogManager::make(),
                \Awcodes\Curator\CuratorPlugin::make()
                    ->resource(\App\Filament\Resources\MediaResource::class)
                ,
            ]);


    }
}
