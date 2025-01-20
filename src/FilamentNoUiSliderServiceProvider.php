<?php

namespace JaysonTemporas\FilamentNoUiSlider;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentNoUiSliderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-nouislider')
            ->hasViews();
    }

    public function packageBooted()
    {
        FilamentAsset::register([
            Css::make('nouislider-library', __DIR__.'/../dist/nouislider.css')->loadedOnRequest(),
            Css::make('nouislider', __DIR__.'/../dist/nouislider-default.css')->loadedOnRequest(),
            AlpineComponent::make('nouislider', __DIR__.'/../dist/nouislider.js'),
        ], 'jaysontemporas/filament-nouislider');
    }
}
