<?php

namespace JaysonTemporas\FilamentNoUiSlider\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Support\Facades\FilamentColor;
use Spatie\Color\Rgb;

class NoUiSlider extends Field
{
    protected string $view = 'filament-nouislider::forms.components.nouislider';

    protected int $decimal = 1;

    protected int|float $min = 0;

    protected int|float $max = 100;

    protected int|float $step = 1;

    protected bool|array $tooltips = false;

    protected bool $isConnected = false;

    protected bool $isRange = false;

    protected ?array $connect = null;

    protected string $orientation = 'horizontal';

    protected array|float|int|null $defaultStart = null;

    protected ?int $handleCount = null;

    // Styling properties
    protected string $handleColor = '#3b82f6'; // Default blue

    protected string $handleBorderColor = '#ffffff';

    protected int $handleBorderWidth = 1;

    protected string $connectColor = '#3b82f6';

    protected string $trackColor = '#e5e7eb';

    protected string $tooltipColor = '#3b82f6';

    protected string $tooltipTextColor = '#ffffff';

    protected int $height = 20;

    protected int $handleSize = 34;

    protected bool $hasBoxShadow = true;

    protected bool $sliderDisabled = false;

    protected string $handleShape = 'square'; // 'circle' or 'square'

    protected ?string $tooltipFormat = null;

    protected string $tooltipLocation = 'top';

    protected bool $mergeOverlappingTooltip = false;

    protected int $marginTop = 35;

    protected int $marginBottom = 35;

    protected array $pips = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function ($state) {
            info($state);
            if (is_array($state)) {
                return array_map(fn ($value) => is_numeric($value) ? (float) $value : null, $state);
            }

            return is_numeric($state) ? (float) $state : null;
        });
    }

    public static function make(string $name): static
    {
        $static = parent::make($name);

        $rgb = FilamentColor::getColors()['primary'][500];

        $static->color(Rgb::fromString("rgb($rgb)")->toHex());

        return $static;
    }

    public function range(bool $isRange = true): static
    {
        $this->isRange = $isRange;

        return $this;
    }

    public function defaultStart(array|float|int $value): static
    {
        $this->defaultStart = $value;

        return $this;
    }

    public function decimal(int $decimal): static
    {
        $this->decimal = $decimal;

        return $this;
    }

    public function min(int|float $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int|float $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function step(int|float $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function tooltip(bool|array $config = true): static
    {
        $this->tooltips = $config;

        return $this;
    }

    public function connect(array $connections): static
    {
        $this->connect = $connections;

        return $this;
    }

    public function orientation(string $orientation): static
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getMin(): int|float
    {
        return $this->min;
    }

    public function getDecimal(): int
    {
        return $this->decimal;
    }

    public function getMax(): int|float
    {
        return $this->max;
    }

    public function getStep(): int|float
    {
        return $this->step;
    }

    public function hasTooltip(): bool|array
    {
        return $this->tooltips;
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function isRange(): bool
    {
        return $this->isRange;
    }

    public function getHandleCount(): int
    {
        if ($this->handleCount !== null) {
            return $this->handleCount;
        }

        if (is_array($this->defaultStart)) {
            return count($this->defaultStart);
        }

        return 1;
    }

    public function getDefaultStart(): array
    {
        if (is_array($this->defaultStart)) {
            return $this->defaultStart;
        }

        return array_fill(0, $this->getHandleCount(), $this->defaultStart ?? $this->min);
    }

    public function getConnect(): array
    {
        if ($this->connect !== null) {
            return $this->connect;
        }

        // Default connect behavior: true between handles, false on ends
        $count = $this->getHandleCount();
        $connections = array_fill(0, $count + 1, false);

        // For single handle, connect to left by default
        if ($count === 1) {
            $connections[0] = true;

            return $connections;
        }

        // For multiple handles, connect between handles by default
        for ($i = 1; $i < $count; $i++) {
            $connections[$i] = true;
        }

        return $connections;
    }

    public function getTooltips(): array|bool
    {
        if (is_array($this->tooltips)) {
            return $this->tooltips;
        }

        return $this->tooltips
            ? array_fill(0, $this->getHandleCount(), true)
            : false;
    }

    public function color(string $colorHex, string $borderColor = '#ffffff', string $textColor = '#ffffff'): static
    {
        if (in_array($colorHex, array_keys(FilamentColor::getColors()))) {
            $rgb = FilamentColor::getColors()[$colorHex][500] ?? '#000000';
            $colorHex = Rgb::fromString("rgb($rgb)")->toHex();
        }

        $this->handleColor($colorHex)
            ->handleBorderColor($borderColor)
            ->handleBorderWidth(0)
            ->connectColor($colorHex)
            ->tooltipColor($colorHex)
            ->tooltipTextColor($textColor);

        return $this;
    }

    public function handleColor(string $color): static
    {
        $this->handleColor = $color;

        return $this;
    }

    public function handleBorderColor(string $color): static
    {
        $this->handleBorderColor = $color;

        return $this;
    }

    public function handleBorderWidth(int $width): static
    {
        $this->handleBorderWidth = $width;

        return $this;
    }

    public function connectColor(string $color): static
    {
        $this->connectColor = $color;

        return $this;
    }

    public function trackColor(string $color): static
    {
        $this->trackColor = $color;

        return $this;
    }

    public function tooltipColor(string $color): static
    {
        $this->tooltipColor = $color;

        return $this;
    }

    public function tooltipTextColor(string $color): static
    {
        $this->tooltipTextColor = $color;

        return $this;
    }

    public function marginTop(int $marginTop): static
    {
        $this->marginTop = $marginTop;

        return $this;
    }

    public function marginBottom(int $marginBottom): static
    {
        $this->marginBottom = $marginBottom;

        return $this;
    }

    public function height(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function handleSize(int $size): static
    {
        $this->handleSize = $size;

        return $this;
    }

    public function withoutBoxShadow(): static
    {
        $this->hasBoxShadow = false;

        return $this;
    }

    public function sliderDisabled(): static
    {
        $this->sliderDisabled = true;

        return $this;
    }

    public function squareHandle(): static
    {
        $this->handleShape = 'square';

        return $this;
    }

    public function circleHandle(): static
    {
        $this->handleShape = 'circle';

        return $this;
    }

    public function tooltipLocation(string $tooltipLocation = 'top'): static
    {
        $this->tooltipLocation = $tooltipLocation;

        return $this;
    }
    
    public function mergeOverlappingTooltip(): static
    {
        $this->mergeOverlappingTooltip = true;

        return $this;
    }

    public function tooltipFormat(string $format): static
    {
        $this->tooltipFormat = $format;

        return $this;
    }
    
    public function tooltipPrefix(string $prefix): static
    {
        if ($this->tooltipFormat) {
            $this->tooltipFormat = $prefix . $this->tooltipFormat;
        } else {
            $this->tooltipFormat =  $prefix . "{{value}}";
        }
        
        return $this;
    }
    
    public function tooltipSuffix(string $suffix): static
    {
        if ($this->tooltipFormat) {
            $this->tooltipFormat = $this->tooltipFormat . $suffix;
        } else {
            $this->tooltipFormat = "{{value}}" . $suffix;
        }
        
        return $this;
    }

    public function pips(array $pips = []): static
    {
        $this->pips = $pips;

        return $this;
    }

    // Getter methods for styling
    public function getHandleColor(): string
    {
        return $this->handleColor;
    }

    public function getHandleBorderColor(): string
    {
        return $this->handleBorderColor;
    }

    public function getHandleBorderWidth(): int
    {
        return $this->handleBorderWidth;
    }

    public function getConnectColor(): string
    {
        return $this->connectColor;
    }

    public function getTrackColor(): string
    {
        return $this->trackColor;
    }

    public function getTooltipColor(): string
    {
        return $this->tooltipColor;
    }
    
    public function getTooltipLocation(): string
    {
        return $this->tooltipLocation;
    }
    
    public function shouldMergeOverlappingTooltip(): string
    {
        return $this->mergeOverlappingTooltip;
    }

    public function getTooltipTextColor(): string
    {
        return $this->tooltipTextColor;
    }

    public function getMarginTop(): int
    {
        return $this->marginTop;
    }

    public function getMarginBottom(): int
    {
        return $this->marginBottom;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getHandleSize(): int
    {
        return $this->handleSize;
    }

    public function hasBoxShadow(): bool
    {
        return $this->hasBoxShadow;
    }

    public function isSliderDisabled(): bool
    {
        return $this->sliderDisabled;
    }

    public function getHandleShape(): string
    {
        return $this->handleShape;
    }

    public function getTooltipFormat(): ?string
    {
        return $this->tooltipFormat;
    }
    
    public function getPips(): array
    {
        return $this->pips;
    }
}
