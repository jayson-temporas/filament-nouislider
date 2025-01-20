@php 
    use Filament\Support\Facades\FilamentAsset;
    $elementUniqid = uniqid();
    $libraryCss = FilamentAsset::getStyleHref('nouislider-library', 'jaysontemporas/filament-nouislider');
    $overrideCss = FilamentAsset::getStyleHref('nouislider', 'jaysontemporas/filament-nouislider');
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        ax-load
        ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('nouislider', 'jaysontemporas/filament-nouislider') }}"
        x-ignore
        x-data="noUiSliderFormComponent({
            state: $wire.entangle('{{ $getStatePath() }}'),
            decimal: {{ $getDecimal() }},
            min: {{ $getMin() }},
            max: {{ $getMax() }},
            step: {{ $getStep() }},
            tooltips: @js($getTooltips()),
            connect: @js($getConnect()),
            orientation: '{{ $getOrientation() }}',
            isRange: @js($isRange()),
            defaultStart: @js($getDefaultStart()),
            isDisabled: @js($isSliderDisabled()),
            shouldMergeOverlappingTooltip: @js($shouldMergeOverlappingTooltip()),
            pips: @js(json_encode($getPips())),
            styling: {
                handleColor: '{{ $getHandleColor() }}',
                handleBorderColor: '{{ $getHandleBorderColor() }}',
                handleBorderWidth: {{ $getHandleBorderWidth() }},
                connectColor: '{{ $getConnectColor() }}',
                trackColor: '{{ $getTrackColor() }}',
                tooltipColor: '{{ $getTooltipColor() }}',
                tooltipTextColor: '{{ $getTooltipTextColor() }}',
                height: {{ $getHeight() }},
                handleSize: {{ $getHandleSize() }},
                hasBoxShadow: @js($hasBoxShadow()),
                handleShape: '{{ $getHandleShape() }}',
                tooltipFormat: @js($getTooltipFormat()),
                elementId: 'my-slider-{{ $getId() }}-{{ $elementUniqid }}'
            }
        })"
        x-on:keydown.stop=""
        wire:ignore
        {{
            $attributes
                ->merge($getExtraAttributes())
                ->class([
                    'filament-forms-nouislider-component',
                ])
        }}

        x-load-css="['{{ $libraryCss }}', '{{ $overrideCss }}']"
        style="margin-top: {{ $getMarginTop() }}px;margin-bottom: {{ $getMarginBottom() }}px;"
    >
        <div
            id="my-slider-{{ $getId() }}-{{ $elementUniqid }}"
            x-ref="slider"
            class="filament-forms-nouislider-component-slider  bg-white dark:bg-white/5 rounded-lg shadow-sm ring-1 {{ $getTooltipLocation() }}-tooltip"
        ></div>
    </div>
</x-dynamic-component>