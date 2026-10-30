@extends('layouts.app')

@php
    $latestJam = config('gamejam.latest_jam', '2024');
    $votingActive = (bool) config('gamejam.voting.active', false);
    $votingUrl = config('gamejam.voting.url', '');
    $nextJam = config('gamejam.registration.next_jam');
    $registrationActive = (bool) config('gamejam.registration.active', false);
    $registrationUrl = config('gamejam.registration.url', '');

    $homepage = \HagenbergJams\JamEngine\GameJamData::getHomepage();
    $hero = $homepage['hero'] ?? [];
    $heroImages = $hero['images'] ?? ['gamejam_index_1.jpg', 'gamejam_index_2.jpg', 'gamejam_index_3.jpg'];
    $about = $homepage['about'] ?? [];
    $video = $homepage['video'] ?? [];
    $sponsors = $homepage['sponsors'] ?? [];

    $firstHeroImage = ($heroImages[0] ?? '') ? (str_starts_with($heroImages[0], '/') ? $heroImages[0] : '/media/' . ltrim($heroImages[0], '/')) : '';
@endphp

@section('head')
@if($firstHeroImage)
    <link rel="preload" as="image" href="{{ $firstHeroImage }}" fetchpriority="high">
@endif
@endsection

<!-- Hero Section with Slider -->
@section('header')
<section class="relative h-full flex items-center justify-center overflow-hidden">
    <div class="hero-slider-container absolute inset-0 w-full h-full z-0">
        <div class="hero-slider-track relative w-full h-full">
            @foreach($heroImages as $index => $image)
                @php
                    $imageSrc = str_starts_with($image, '/') ? $image : '/media/' . ltrim($image, '/');
                @endphp
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}" @if($index !== 0) style="background-image: url('{{ $imageSrc }}');" @endif>
                    @if($index === 0)
                        {{-- LCP: use <img> for first slide so it's discoverable in HTML, with fetchpriority=high --}}
                        <img src="{{ $imageSrc }}" alt="" class="hero-slide-img" fetchpriority="high" loading="eager" width="1920" height="1080">
                    @endif
                </div>
            @endforeach
        </div>
        <div class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none">
            <div class="container mx-auto px-4 text-center text-white drop-shadow-lg">
                <hgroup>
                    @if(isset($hero['subtitle']) && $hero['subtitle'])
                    <p class="text-lg md:text-xl mb-4">{{ $hero['subtitle'] }}</p>
                    @endif
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-4">{{ $hero['title'] ?? 'Hagenberg Game Jam' }}</h1>
                    @if(isset($hero['description']) && $hero['description'])
                    <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto mt-8">{{ $hero['description'] }}</p>
                    @endif
                </hgroup>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/{{ $latestJam }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors pointer-events-auto">
                        View the games of {{ $latestJam }}
                    </a>
                    @if($votingActive)
                    <a href="{{ $votingUrl }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors pointer-events-auto">
                        Vote for the games of {{ $latestJam }}
                    </a>
                    @endif
                    @if($registrationActive && $nextJam)
                    <a href="{{ $registrationUrl }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors pointer-events-auto">
                        Register for {{ $nextJam }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Categories Section -->
<section class="py-16 bg-gray-50 dark:bg-gray-800" aria-labelledby="categories-heading">
    <div class="container mx-auto px-4">
        <h2 id="categories-heading" class="sr-only">What makes the Hagenberg Game Jam special</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Category 1 -->
            <div class="bg-gray-200 dark:bg-gray-700 p-8 rounded-lg shadow-lg text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-white dark:bg-indigo-900 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="w-3 h-3 mx-auto mb-4 bg-indigo-600 rounded-full"></div>
                <h3 class="text-2xl font-bold mb-4 dark:text-white">1 Topic</h3>
                <p class="text-gray-600 dark:text-gray-300">Each Hagenberg Game Jam follows a single topic, often related to current events.</p>
            </div>

            <!-- Category 2 -->
            <div class="bg-gray-200 dark:bg-gray-700 p-8 rounded-lg shadow-lg text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-white dark:bg-indigo-900 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="w-3 h-3 mx-auto mb-4 bg-indigo-600 rounded-full"></div>
                <h3 class="text-2xl font-bold mb-4 dark:text-white">36 Hours</h3>
                <p class="text-gray-600 dark:text-gray-300">From Saturday, 9 am to Sunday, 9 pm: there are only 36 hours available to create a game.</p>
            </div>

            <!-- Category 3 -->
            <div class="bg-gray-200 dark:bg-gray-700 p-8 rounded-lg shadow-lg text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-white dark:bg-indigo-900 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="w-3 h-3 mx-auto mb-4 bg-indigo-600 rounded-full"></div>
                <h3 class="text-2xl font-bold mb-4 dark:text-white">&infin; Ideas</h3>
                <p class="text-gray-600 dark:text-gray-300">Perfection isn't usually the essential quality in a game jam. Great ideas are.</p>
            </div>
        </div>
    </div>
                </section>

<!-- About Section -->
<section class="py-16 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1 flex items-center">
                @if(isset($about['gallery']) && is_array($about['gallery']) && count($about['gallery']) > 0)
                    {{-- Masonry Gallery --}}
                    <div class="masonry-gallery w-full">
                        @foreach($about['gallery'] as $index => $galleryItem)
                            @php
                                // Support both old format (string) and new format (object)
                                $imageFile = is_array($galleryItem) ? ($galleryItem['image'] ?? '') : $galleryItem;
                                $description = is_array($galleryItem) ? ($galleryItem['description'] ?? 'Scene from the Hagenberg Game Jam') : 'Scene from the Hagenberg Game Jam';
                                
                                $imageSrc = ($imageFile !== '' && str_starts_with($imageFile, '/')) ? $imageFile : '/media/' . ltrim($imageFile, '/');
                                // Responsive variants (400w, 800w) generated at build time
                                $variants = \HagenbergJams\JamEngine\Services\OptimizeImagesService::getResponsiveVariants($imageFile);
                                $imgSrc = !empty($variants[400]) ? '/media/' . $variants[400] : $imageSrc;
                                $srcset = !empty($variants)
                                    ? implode(', ', array_map(fn ($w, $v) => '/media/' . $v . ' ' . $w . 'w', array_keys($variants), $variants))
                                    : null;
                                // Create masonry pattern: first and every 4th image spans 2 rows
                                $rowSpan = ($index === 0 || ($index + 1) % 4 === 0) ? 'row-span-2' : 'row-span-1';
                                
                                // Use description for both alt and Lightbox
                                $glightboxData = 'title: ' . $description;
                            @endphp
                            <a href="{{ $imageSrc }}" 
                               class="masonry-item {{ $rowSpan }} glightbox" 
                               data-glightbox="{{ $glightboxData }}">
                                <img src="{{ $imgSrc }}" 
                                     @if($srcset)srcset="{{ $srcset }}"
                                     sizes="(max-width: 768px) 100vw, 356px"@endif
                                     alt="{{ $description }}" 
                                     class="masonry-image">
                            </a>
                        @endforeach
                    </div>
                @else
                    {{-- Fallback: Show message if no gallery is configured --}}
                    <div class="rounded-lg shadow-lg w-full bg-gray-100 dark:bg-gray-800 p-8 text-center">
                        <p class="text-gray-600 dark:text-gray-400">No gallery images configured.</p>
                    </div>
                @endif
            </div>
            <div class="order-1 lg:order-2">
                <span class="inline-block w-3 h-3 bg-indigo-600 rounded-full"></span>
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold uppercase tracking-wide ml-2">{{ $about['eyebrow'] ?? 'About' }}</span>
                <h2 class="text-4xl font-bold mt-4 mb-6 dark:text-white">{{ $about['title'] ?? 'The Hagenberg Game Jam' }}</h2>
                <h3 class="text-2xl font-semibold mb-6 dark:text-gray-200">{{ $about['subtitle'] ?? 'Creating a game in just one weekend?' }}</h3>
                <div class="prose dark:prose-invert max-w-none">
                    {!! \Illuminate\Support\Str::markdown((string) ($about['markdown'] ?? '')) !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Section (always visible; video or placeholder depending on cookie consent) -->
<section id="youtube-video-section" class="relative py-16 bg-gray-900 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-end mb-12">
            <div class="text-right">
                <span class="text-indigo-400 uppercase tracking-wide text-sm">{{ $video['eyebrow'] ?? 'intro video' }}</span>
                <h2 class="text-4xl md:text-5xl font-bold text-white mt-2">{!! nl2br(e($video['title'] ?? "Hagenberg Game Jam\nin a Nutshell"), false) !!}</h2>
            </div>
            <div>
                <p class="text-gray-200 text-lg">{{ $video['description'] ?? 'Experience the feeling of a Hagenberg Game Jam in this short clip from 2019.' }}</p>
            </div>
        </div>
        <div class="max-w-4xl mx-auto">
            {{-- Shown when cookies rejected or no choice: placeholder with Accept button --}}
            <div id="youtube-cookie-placeholder" class="relative rounded-lg overflow-hidden shadow-2xl" style="padding-bottom: 56.25%; height: 0; position: relative;">
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-800 gap-4 p-8">
                    <p class="text-gray-300 text-center text-lg">To watch the video, please accept YouTube cookies.</p>
                    <button type="button" id="youtube-accept-cookies" class="px-6 py-3 rounded-lg font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                        Accept cookies
                    </button>
                </div>
            </div>
            {{-- Shown when cookies accepted: video player --}}
            <div id="youtube-video-container" class="relative rounded-lg overflow-hidden shadow-2xl youtube-click-to-play hidden" style="padding-bottom: 56.25%; height: 0; position: relative;" data-youtube-id="{{ $video['youtube_id'] ?? 'S4UBw5cPjfY' }}">
                @php
                    $youtubeId = $video['youtube_id'] ?? 'S4UBw5cPjfY';
                    $thumbnailUrl = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
                @endphp
                <div class="youtube-placeholder absolute inset-0 flex items-center justify-center bg-gray-800 cursor-pointer group" role="button" tabindex="0" aria-label="Play Hagenberg Game Jam Intro Video">
                    <img src="{{ $thumbnailUrl }}" alt="" class="absolute inset-0 w-full h-full object-cover" loading="lazy" width="1280" height="720"
                         onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg'">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                    <div class="relative z-10 w-20 h-20 flex items-center justify-center rounded-full bg-red-600 group-hover:bg-red-700 group-hover:scale-110 transition-all shadow-lg">
                        <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="youtube-iframe-container absolute inset-0 hidden">
                    <iframe class="absolute top-0 left-0 w-full h-full border-0"
                            title="Hagenberg Game Jam Intro Video"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sponsors Section -->
<section class="py-16 bg-gray-50 dark:bg-gray-200">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-4 text-gray-800">{{ $sponsors['title'] ?? 'Our Sponsors' }}</h2>
        @if(isset($sponsors['description']) && $sponsors['description'])
        <p class="text-center text-gray-600 dark:text-gray-700 mb-8">{{ $sponsors['description'] }}</p>
        @endif
        <div class="sponsors-slider swiper max-w-6xl mx-auto">
            <div class="swiper-wrapper">
                @foreach(($sponsors['items'] ?? []) as $sponsor)
                    @php
                        $logo = (string) ($sponsor['logo'] ?? '');
                        $logoSrc = ($logo !== '' && str_starts_with($logo, '/')) ? $logo : '/media/' . ltrim($logo, '/');
                        $logoWidth = (int) ($sponsor['width'] ?? 200);
                        $logoHeight = (int) ($sponsor['height'] ?? 64);
                    @endphp
                    <div class="swiper-slide">
                        <div class="flex justify-center items-center h-32">
                            <a href="{{ $sponsor['url'] ?? '#' }}" target="_blank"
                               class="opacity-100 hover:opacity-70 transition-opacity">
                                <img src="{{ $logoSrc }}" alt="{{ $sponsor['name'] ?? 'Sponsor' }}"
                                     width="{{ $logoWidth }}" height="{{ $logoHeight }}" class="h-16 object-contain">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.youtube-click-to-play').forEach(function (container) {
        var placeholder = container.querySelector('.youtube-placeholder');
        var iframeContainer = container.querySelector('.youtube-iframe-container');
        var iframe = iframeContainer.querySelector('iframe');
        var youtubeId = container.getAttribute('data-youtube-id');

        if (placeholder && iframeContainer && iframe && youtubeId) {
            function loadVideo() {
                if (localStorage.getItem('cookie-consent-youtube') !== 'accepted') {
                    return;
                }
                placeholder.classList.add('hidden');
                iframeContainer.classList.remove('hidden');
                iframe.src = 'https://www.youtube-nocookie.com/embed/' + youtubeId + '?autoplay=1';
            }
            placeholder.addEventListener('click', loadVideo);
            placeholder.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    loadVideo();
                }
            });
        }
    });
});
</script>
@endpush

