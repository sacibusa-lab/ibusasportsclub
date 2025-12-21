<div x-data="{ 
    showViewer: false, 
    groupIndex: 0, 
    itemIndex: 0,
    progress: 0,
    timer: null,
    isPlaying: true,
    isMuted: false,
    groups: @js($stories),
    
    openStory(gIndex) {
        this.groupIndex = gIndex;
        this.itemIndex = 0;
        this.showViewer = true;
        this.startItem();
    },
    
    startItem() {
        this.progress = 0;
        this.isPlaying = true;
        if (this.timer) clearInterval(this.timer);
        this.timer = setInterval(() => {
            this.progress += 1;
            if (this.progress >= 100) {
                this.nextItem();
            }
        }, 50);
    },
    
    togglePlayPause() {
        if (this.isPlaying) {
            // Pause
            if (this.timer) clearInterval(this.timer);
            const videos = document.querySelectorAll('video');
            videos.forEach(v => {
                if (v.offsetParent !== null) {
                    v.pause();
                }
            });
            this.isPlaying = false;
        } else {
            // Play
            this.startItem();
            const videos = document.querySelectorAll('video');
            videos.forEach(v => {
                if (v.offsetParent !== null) {
                    v.play();
                }
            });
            this.isPlaying = true;
        }
    },
    
    toggleMute() {
        this.isMuted = !this.isMuted;
        const videos = document.querySelectorAll('video');
        videos.forEach(v => {
            if (v.offsetParent !== null) {
                v.muted = this.isMuted;
            }
        });
    },
    
    nextItem() {
        let currentGroup = this.groups[this.groupIndex];
        if (this.itemIndex < currentGroup.items.length - 1) {
            this.itemIndex++;
            this.startItem();
        } else {
            this.nextGroup();
        }
    },
    
    prevItem() {
        if (this.itemIndex > 0) {
            this.itemIndex--;
            this.startItem();
        } else {
            this.prevGroup();
        }
    },

    nextGroup() {
        if (this.groupIndex < this.groups.length - 1) {
            this.groupIndex++;
            this.itemIndex = 0;
            this.startItem();
        } else {
            this.closeViewer();
        }
    },

    prevGroup() {
        if (this.groupIndex > 0) {
            this.groupIndex--;
            this.itemIndex = this.groups[this.groupIndex].items.length - 1;
            this.startItem();
        }
    },
    
    closeViewer() {
        this.showViewer = false;
        this.isPlaying = false;
        if (this.timer) clearInterval(this.timer);
    }
}" class="mb-12">
    <!-- Stories Carousel -->
    <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
        @foreach($stories as $index => $story)
        @continue($story->items->count() == 0)
        <button @click="openStory({{ $index }})" class="flex-none group focus:outline-none">
            <div class="relative w-32 h-48 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                <img src="{{ $story->thumbnail_url ?? $story->items->first()->media_url }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                
                <!-- Story Title -->
                <div class="absolute bottom-0 left-0 right-0 p-3">
                    <span class="text-xs font-black text-white uppercase tracking-tight line-clamp-2 drop-shadow-lg">{{ $story->title }}</span>
                </div>

                <!-- Ring Border -->
                <div class="absolute inset-0 ring-2 ring-secondary rounded-2xl group-hover:ring-4 transition-all"></div>
            </div>
        </button>
        @endforeach
    </div>

    <!-- Full Screen Viewer Modal -->
    <template x-teleport="body">
        <div x-show="showViewer" 
             class="fixed inset-0 z-[100] bg-black flex items-center justify-center select-none"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @keydown.escape.window="closeViewer()"
             style="display: none;">
            
            <div class="w-full h-full max-w-lg relative flex flex-col bg-zinc-900 md:rounded-3xl overflow-hidden shadow-2xl">
                <!-- Close Button -->
                <button @click="closeViewer()" class="absolute top-8 right-6 z-[120] text-white/50 hover:text-white transition p-2 bg-black/20 rounded-full backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Header Info (Title + Group) -->
                <div class="absolute top-10 left-6 z-[115] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full border border-white/20 overflow-hidden">
                        <img :src="groups[groupIndex]?.thumbnail_url ?? groups[groupIndex]?.items[0]?.media_url" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-white text-[10px] font-black uppercase tracking-widest" x-text="groups[groupIndex]?.title"></span>
                        <span class="text-white/40 text-[8px] font-bold" x-text="(itemIndex + 1) + ' of ' + groups[groupIndex]?.items.length"></span>
                    </div>
                </div>

                <!-- Progress Bars -->
                <div class="absolute top-6 left-6 right-6 z-[115] flex gap-1.5">
                    <template x-for="(item, idx) in groups[groupIndex]?.items" :key="idx">
                        <div class="h-1 flex-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-secondary transition-all"
                                 :style="'width: ' + (idx === itemIndex ? progress : (idx < itemIndex ? 100 : 0)) + '%'">
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Story Content Area -->
                <div class="flex-1 relative flex items-center justify-center overflow-hidden">
                    <template x-for="(item, idx) in groups[groupIndex]?.items" :key="idx">
                        <div x-show="itemIndex === idx" class="w-full h-full flex items-center justify-center">
                            <img :src="item.media_url" class="w-full h-full object-cover" x-show="item.type === 'image'">
                            <video x-show="item.type === 'video'" 
                                   :src="item.media_url" 
                                   x-ref="storyVideo"
                                   autoplay playsinline preload="auto"
                                   class="w-full h-full object-cover"
                                   @ended="nextItem()"
                                   @loadedmetadata="$el.muted = false; $el.volume = 1.0;"
                                   @play="startItem()"
                                   @pause="if (timer) clearInterval(timer)"
                                   style="object-fit: cover;">
                            </video>
                        </div>
                    </template>

                    <!-- Navigation Zones (Lower z-index) -->
                    <div class="absolute inset-0 z-[100] flex">
                        <div @click="prevItem()" class="w-1/3 h-full cursor-pointer"></div>
                        <div class="w-1/3 h-full" @mousedown="if (timer) clearInterval(timer)" @mouseup="startItem()"></div>
                        <div @click="nextItem()" class="w-1/3 h-full cursor-pointer"></div>
                    </div>

                    <!-- Custom Video Controls Overlay (Higher z-index) -->
                    <div class="absolute top-24 right-6 z-[130] flex flex-col gap-3" @click.stop>
                        <!-- Volume Button -->
                        <button @click="toggleMute()" 
                                type="button"
                                class="w-12 h-12 bg-black/60 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-black/80 transition shadow-xl">
                            <svg x-show="!isMuted" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            </svg>
                            <svg x-show="isMuted" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                            </svg>
                        </button>

                        <!-- Play/Pause Button -->
                        <button @click="togglePlayPause()" 
                                type="button"
                                class="w-12 h-12 bg-black/60 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-black/80 transition shadow-xl">
                            <svg x-show="!isPlaying" class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg x-show="isPlaying" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                            </svg>
                        </button>

                        <!-- Share Button -->
                        <button @click="
                            if (navigator.share) {
                                navigator.share({
                                    title: 'Check out this story!',
                                    url: window.location.href
                                });
                            } else {
                                navigator.clipboard.writeText(window.location.href);
                                alert('Link copied to clipboard!');
                            }
                        " 
                                type="button"
                                class="w-12 h-12 bg-black/60 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-black/80 transition shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Story Caption -->
                    <div class="absolute inset-x-0 bottom-32 flex items-center justify-center px-8 z-[115]">
                        <template x-if="groups[groupIndex]?.items[itemIndex]?.caption">
                            <div class="inline-block px-6 py-3 bg-black/80 rounded-xl backdrop-blur-sm">
                                <p class="text-lg font-black leading-tight text-center uppercase tracking-wide"
                                   :style="'color: ' + (groups[groupIndex]?.items[itemIndex]?.caption_color || '#FFFFFF')"
                                   x-text="groups[groupIndex]?.items[itemIndex]?.caption">
                                </p>
                            </div>
                        </template>
                    </div>

                    <!-- Story Link -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 z-[115]">
                        <template x-if="groups[groupIndex]?.link_url || groups[groupIndex]?.items[itemIndex]?.link_url">
                            <div class="flex flex-col items-center gap-4">
                                <a :href="groups[groupIndex]?.link_url || groups[groupIndex]?.items[itemIndex]?.link_url" target="_blank" class="w-full bg-[#0066FF] text-white font-black py-4 rounded-xl uppercase text-[11px] tracking-widest hover:bg-white hover:text-[#0066FF] transition duration-300 shadow-2xl flex items-center justify-center gap-2">
                                    <span>Read More Here!</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
