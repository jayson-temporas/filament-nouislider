# NoUiSlider Filament Component

A custom Filament form component that integrates [noUiSlider](https://refreshless.com/nouislider/), a lightweight JavaScript library for creating highly customizable sliders. This component allows you to add a dynamic and interactive slider to your Filament forms with ease.

## Features

- Supports single and range sliders.
- Customizable slider configuration.
- Easy integration with Filament forms.

## Installation

Require the package via Composer:
```bash
composer require jaysontemporas/filament-nouislider
```

## Usage

You can use the `NoUiSlider` component in your Filament forms as follows:

### Basic Example

```php
use JaysonTemporas\FilamentNoUiSlider\Forms\Components\NoUiSlider;

NoUiSlider::make('price')
    ->min(0)
    ->max(1000)
    ->defaultStart(500)
    ->step(10)
    ->tooltip()
```

### Range Slider
```php
NoUiSlider::make('price_range')
    ->range()
    ->min(0)
    ->max(1000)
    ->step(50)
    ->defaultStart([200, 800]) 
    ->label('Price Range')
    ->helperText('Select a price range.');
```

Range slider will return an array of selected points. On your model, you can cast your column to array

```php
protected $casts = [
    'price_range' => 'array',
];

// Ex. "[200, 800]" will be saved to your price_range column
```

You can also use mutateFormDataBeforeCreate() on your Create page / Form to modify the behaviour before saving

```php
protected function mutateFormDataBeforeCreate(array $data): array
{
    // $data['price_range'][0]
    // $data['price_range'][1]

    return $data;
}
```
### Additional Configuration
You can pass additional configs to the slider by chaining
```php
NoUiSlider::make('my_slider')
    ->range()
    ->min(0)
    ->max(10000)
    ->step(100)
    ->defaultStart([1000, 3000, 6000]) // range with 3 points
    ->connect([false, true, true, false])
    /**
         min and first handle is not connected
         first handle and second handle is connected
         second handle and third handle is connected
         third handle and max is not connected
    */
    ->tooltip([true, true, true])
    ->orientation('horizontal')
    ->color('primary') // primary, gray, success, danger, info
    ->height(20)
    ->handleSize(34)
    ->tooltipPrefix('$')
    ->tooltipSuffix('USD')
    ->decimal(2)
    ->mergeOverlappingTooltip()
    ->pips([
        'mode' => 'positions',
        'values' => [0, 25, 50, 75, 100],
        'density' => 4,
        'stepped' => true,
    ])
```
## Vertical Slider
You can use vertial orientation but make sure you adjust your slider's height 

```php
NoUiSlider::make('slider2')
    ->range()
    ->min(0)
    ->max(10000)
    ->step(100)
    ->defaultStart([1000, 3000, 6000])
    ->connect([false, true, true, false])
    ->tooltip([true, true, true])
    ->orientation('vertical') 
    ->color('primary')
    ->height(400) // Adjust slider height
    ->handleSize(34)
    ->tooltipPrefix('$')
    ->decimal(2)
    ->mergeOverlappingTooltip()
    ->pips([
        'mode' => 'positions',
        'values' => [0, 25, 50, 75, 100],
        'density' => 4,
        'stepped' => true,
    ]),
```
## Styling
You can use your app default colors (primary, gray, info, success, warning, danger)
```php
NoUiSlider::make('price')
    ->min(0)
    ->max(1000)
    ->color('info')
```

You can also style each section

```php
$this
    ->height(20)
    ->handleSize(34)
    ->handleColor('#3b82f6')
    ->handleBorderColor('#ffffff')
    ->handleBorderWidth(1)
    ->connectColor('#3b82f6')
    ->tooltipColor('#3b82f6')
    ->tooltipTextColor('#ffffff')
    ->trackColor('#e5e7eb');
```
    
## Additional Options
- mergeOverlappingTooltip()
- circleHandle(), squareHandle()
- sliderDisabled()
- tooltipPrefix('$'), tooltipSuffix(' celsius')
- pips(pips config here)


## Requirements
- PHP 8.0+
- Laravel 10+
- Filament 3+

## Contributing
Feel free to submit issues or pull requests. Contributions are welcome!

## License
This project is open-source and licensed under the [MIT license](LICENSE).
