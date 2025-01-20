import noUiSlider from 'nouislider'
import 'nouislider/dist/nouislider.css'
import { $ } from 'vue/macros';

function mergeTooltips(slider, threshold, separator) {
    var textIsRtl = getComputedStyle(slider).direction === 'rtl';
    var isRtl = slider.noUiSlider.options.direction === 'rtl';
    var isVertical = slider.noUiSlider.options.orientation === 'vertical';
    var tooltips = slider.noUiSlider.getTooltips();
    var origins = slider.noUiSlider.getOrigins();
    
    // Get the formatting options from the first tooltip
    var format = slider.noUiSlider.options.tooltips[0];

    if (!tooltips || !tooltips.length) {
        return;
    }

    // Move tooltips into the origin element
    tooltips.forEach(function (tooltip, index) {
        if (tooltip) {
            origins[index].appendChild(tooltip);
        }
    });

    slider.noUiSlider.on('update', function (values, handle, unencoded, tap, positions) {
        var pools = [[]];
        var poolPositions = [[]];
        var poolValues = [[]];
        var atPool = 0;

        // Assign the first tooltip to the first pool, if the tooltip is configured
        if (tooltips[0]) {
            pools[0][0] = 0;
            poolPositions[0][0] = positions[0];
            poolValues[0][0] = values[0];
        }

        for (var i = 1; i < positions.length; i++) {
            if (!tooltips[i] || (positions[i] - positions[i - 1]) > threshold) {
                atPool++;
                pools[atPool] = [];
                poolValues[atPool] = [];
                poolPositions[atPool] = [];
            }

            if (tooltips[i]) {
                pools[atPool].push(i);
                poolValues[atPool].push(values[i]);
                poolPositions[atPool].push(positions[i]);
            }
        }

        pools.forEach(function (pool, poolIndex) {
            var handlesInPool = pool.length;

            for (var j = 0; j < handlesInPool; j++) {
                var handleNumber = pool[j];

                if (j === handlesInPool - 1) {
                    var offset = 0;

                    poolPositions[poolIndex].forEach(function (value) {
                        offset += 1000 - value;
                    });

                    var direction = isVertical ? 'bottom' : 'right';
                    var last = isRtl ? 0 : handlesInPool - 1;
                    var lastOffset = 1000 - poolPositions[poolIndex][last];
                    offset = (textIsRtl && !isVertical ? 100 : 0) + (offset / handlesInPool) - lastOffset;

                    // Format each value before joining
                    const formattedValues = poolValues[poolIndex].map(value => {
                        // If format is an object with 'to' method (custom formatter)
                        if (format && typeof format === 'object' && typeof format.to === 'function') {
                            return format.to(value);
                        }
                        // If format is a function
                        else if (typeof format === 'function') {
                            return format(value);
                        }
                        // Fallback to basic formatting
                        return value;
                    });

                    // Join the formatted values
                    tooltips[handleNumber].innerHTML = formattedValues.join(separator);
                    tooltips[handleNumber].style.display = 'block';
                    tooltips[handleNumber].style[direction] = offset + '%';
                } else {
                    // Hide this tooltip
                    tooltips[handleNumber].style.display = 'none';
                }
            }
        });
    });
}


export default function noUiSliderFormComponent({
    state,
    decimal,
    min,
    max,
    step,
    tooltips,
    connect,
    orientation,
    isRange,
    defaultStart,
    isDisabled,
    shouldMergeOverlappingTooltip,
    pips,
    styling
}) {
    return {
        state: state,
        slider: null,

        init: function() {
            let initialState = this.state;
            
            if (initialState === null || initialState === undefined) {
                initialState = defaultStart;
            }
            
            if (!Array.isArray(initialState)) {
                initialState = [initialState];
            }

            // Apply custom styles
            const style = document.createElement('style');
            const originalElementId = styling.elementId;
            const elementId  = styling.elementId.replace(/\./g, '\\.');
            style.textContent = `
                #${elementId} {
                    height: ${styling.height}px;
                }
                #${elementId} .noUi-connect {
                    background: ${styling.connectColor};
                }
                #${elementId} .noUi-handle {
                    background: ${styling.handleColor};
                    border: ${styling.handleBorderWidth}px solid ${styling.handleBorderColor};
                    width: ${styling.handleSize}px;
                    height: ${styling.handleSize}px;
                    
                    border-radius: ${styling.handleShape === 'circle' ? '50%' : '4px'};
                    ${styling.hasBoxShadow ? 'box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);' : ''}
                }
                #${elementId} .noUi-handle:before,
                #${elementId} .noUi-handle:after {
                    display: none;
                }
                
                #${elementId} .noUi-touch-area{
                    position: relative;
                    width: 100%; /* Adjust width as needed */
                    height: 100%; /* Adjust height as needed */
                }
                
                #${elementId} .noUi-touch-area:before{
                    content: "\u2016"; /* This inserts the 'X' */
                    position: absolute;
                    top: 45%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    color: #efefef; /* Adjust text color as needed */
                    text-align: center;
                }

                #${elementId} .noUi-tooltip {
                    background: ${styling.tooltipColor};
                    color: ${styling.tooltipTextColor};
                    border: none;
                }
                #${elementId} .noUi-target {
                    background: ${styling.trackColor};
                    padding: 0 16px;
                }
            `;

            document.head.appendChild(style);

            // Format configuration
            const format = {
                to: (value) => {
                    if (styling.tooltipFormat) {
                        return styling.tooltipFormat.replace('{{value}}', parseFloat(value).toLocaleString('us', {minimumFractionDigits: decimal, maximumFractionDigits: decimal}));
                    }
                    
                    return parseFloat(value).toLocaleString('us', {minimumFractionDigits: decimal, maximumFractionDigits: decimal})
                    
                },
                from: (value) => {
                    return parseFloat(value.replace(/[^\d.-]/g, ''));
                }
            };

            this.slider = noUiSlider.create(this.$refs.slider, {
                start: initialState,
                connect: connect,
                orientation: orientation,
                step: step,
                tooltips: tooltips ? (Array.isArray(tooltips) ? 
                    tooltips.map(t => t ? format : false) : 
                    Array(initialState.length).fill(format)
                ) : false,
                range: {
                    'min': min,
                    'max': max
                },
            })
            
            if (pips !== '[]') {
                this.slider.pips(JSON.parse(pips));
            }
            

            if (shouldMergeOverlappingTooltip) {
                mergeTooltips(document.getElementById(originalElementId), 15, "-");
            }

            if (isDisabled) {
                this.slider.disable();
            }

            this.slider.on('update', (values) => {
                if (isRange) {
                    this.state = values.map(value => parseFloat(value))
                } else {
                    this.state = parseFloat(values[0])
                }
            })
        },

        destroy: function() {
            this.slider?.destroy()
        }
    }
}