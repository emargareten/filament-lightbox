<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $limit = $getLimit();
        $state = \Illuminate\Support\Arr::wrap($getState());
        $limitedState = array_slice($state, 0, $limit);
        $isCircular = $isCircular();
        $isSquare = $isSquare();
        $isStacked = $isStacked();
        $overlap = $isStacked ? ($getOverlap() ?? 2) : null;
        $ring = $isStacked ? ($getRing() ?? 2) : null;
        $height = $getHeight() ?? ($isStacked ? '2.5rem' : '8rem');
        $width = $getWidth() ?? (($isCircular || $isSquare) ? $height : null);

        $defaultImageUrl = $getDefaultImageUrl();

        if ((! count($limitedState)) && filled($defaultImageUrl)) {
            $limitedState = [null];
        }

        $ringClasses = \Illuminate\Support\Arr::toCssClasses([
            'ring-white dark:ring-gray-900',
            match ($ring) {
                0 => null,
                1 => 'ring-1',
                2 => 'ring-2',
                3 => 'ring',
                4 => 'ring-4',
                default => $ring,
            },
        ]);

        $hasLimitedRemainingText = $hasLimitedRemainingText();
        $isLimitedRemainingTextSeparate = $isLimitedRemainingTextSeparate();

        $limitedRemainingTextSizeClasses = match ($getLimitedRemainingTextSize()) {
            'xs' => 'text-xs',
            'sm', null => 'text-sm',
            'base', 'md' => 'text-base',
            'lg' => 'text-lg',
            default => $size,
        };
    @endphp

    <div
        {{
            $attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-in-image flex items-center gap-x-2.5',
                ])
        }}
    >
        <a
            {{
                $attributes
                    ->merge([
                        'id' => $getId(),
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
            }}
            class="glightbox"
            href="{{ $getHref() }}"
            data-title="{{ $getTitle() }}"
            data-description="{{ $getDescription() }}"
            data-desc-position="{{ $getDescPosition() }}"
            data-type="{{ $getType() }}"
            data-effect="{{ $getEffect() }}"
            data-width="{{ $getWidthOption() }}"
            data-height="{{ $getHeightOption() }}"
            data-zoomable="{{ $getZoomable() }}"
            data-draggable="{{ $getDraggable() }}"
            data-sizes="{{ $getSizes() }}"
            data-srcset="{{ $getSrtSet() }}"
        >
            @if (count($limitedState))
                <div
                    @class([
                        'flex',
                        match ($overlap) {
                            0 => null,
                            1 => '-space-x-1',
                            2 => '-space-x-2',
                            3 => '-space-x-3',
                            4 => '-space-x-4',
                            5 => '-space-x-5',
                            6 => '-space-x-6',
                            7 => '-space-x-7',
                            8 => '-space-x-8',
                            default => 'gap-x-1.5',
                        },
                    ])
                >
                    @foreach ($limitedState as $stateItem)
                        <img
                            src="{{ filled($getImage()) ? $getImage() : (filled($stateItem) ? $getImageUrl($stateItem) : $defaultImageUrl) }}"
                            {{
                                $getExtraImgAttributeBag()
                                    ->class([
                                        'max-w-none object-cover object-center',
                                        'rounded-full' => $isCircular,
                                        $ringClasses,
                                    ])
                                    ->style([
                                        "height: {$height}" => $height,
                                        "width: {$width}" => $width,
                                    ])
                            }}
                        />
                    @endforeach

                    @if ($hasLimitedRemainingText && ($loop->iteration < count($limitedState)) && (! $isLimitedRemainingTextSeparate) && $isCircular)
                        <div
                            style="
                            @if ($height) height: {{ $height }}; @endif
                            @if ($width) width: {{ $width }}; @endif
                        "
                            @class([
                                'flex items-center justify-center bg-gray-100 font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                                'rounded-full' => $isCircular,
                                $limitedRemainingTextSizeClasses,
                                $ringClasses,
                            ])
                            @style([
                                "height: {$height}" => $height,
                                "width: {$width}" => $width,
                            ])
                        >
                        <span class="-ms-0.5">
                            +{{ count($state) - count($limitedState) }}
                        </span>
                        </div>
                    @endif
                </div>

                @if ($hasLimitedRemainingText && ($loop->iteration < count($limitedState)) && ($isLimitedRemainingTextSeparate || (! $isCircular)))
                    <div
                        @class([
                            'font-medium text-gray-500 dark:text-gray-400',
                            $limitedRemainingTextSizeClasses,
                        ])
                    >
                        +{{ count($state) - count($limitedState) }}
                    </div>
                @endif
            @elseif (($placeholder = $getPlaceholder()) !== null)
                <x-filament-infolists::entries.placeholder>
                    {{ $placeholder }}
                </x-filament-infolists::entries.placeholder>
            @endif
        </a>
    </div>
</x-dynamic-component>


<script type="module">
  const lightbox = GLightbox({
    selector: @js($getSelector()),
    skin: @js($getSkin()),
    openEffect: @js($getOpenEffect()),
    closeEffect: @js($getCloseEffect()),
    slideEffect: @js($getSlideEffect()),
    moreText: @js($getMoreText()),
    moreLength: @js($getMoreLength()),
    closeButton: @js($getCloseButton()),
    touchNavigation: @js($getTouchNavigation()),
    touchFollowAxis: @js($getTouchFollowAxis()),
    keyBoardNavigation: @js($getKeyBoardNavigation()),
    closeOnOutsideClick: @js($getCloseOnOutsideClick()),
    loop: @js($getLoop()),
    dragToleranceX: @js($getDragToleranceX()),
    dragToleranceY: @js($getDragToleranceY()),
    dragAutoSnap: @js($getDragAutoSnap()),
    preload: @js($getPreload()),
    autoplayVideos: @js($getAutoplayVideos()),
    autofocusVideos: @js($getAutofocusVideos()),
    });
</script>