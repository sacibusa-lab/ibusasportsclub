<div x-data="{ 
    showViewer: false, 
    groupIndex: 0, 
    itemIndex: 0,
    progress: 0,
    timer: null,
    groups: @js($stories),
    
    openStory(gIndex) {
        this.groupIndex = gIndex;
        this.itemIndex = 0;
        this.showViewer = true;
        this.startItem();
    },
    
    startItem() {
        this.progress = 0;
        if (this.timer) clearInterval(this.timer);
        this.timer = setInterval(() => {
            this.progress += 1;
            if (this.progress >= 100) {
                this.nextItem();
            }
        }, 50);
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
        if (this.timer) clearInterval(this.timer);
    }
}" class="mb-12">
    <!-- Stories Carousel -->
    <div class="flex gap-4 md:gap-6 overflow-x-auto no-scrollbar pb-2">
        @foreach($stories as $index => $story)
        @continue($story->items->count() == 0)
        <button @click="openStory({{ $index }})" class="flex flex-col items-center gap-2 shrink-0 group focus:outline-none">
            <div class="p-[3px] rounded-full bg-gradient-to-tr from-secondary via-primary to-accent group-hover:scale-110 transition duration-300">
                <div class="p-[2px] bg-white rounded-full">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-2 border-white shadow-sm">
                        <img src="{{ $story->thumbnail_url ?? $story->items->first()->media_url }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
            <span class="text-[10px] md:text-xs font-black text-primary uppercase tracking-tight truncate max-w-[80px]">{{ $story->title }}</span>
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
                                   autoplay muted playsinline
                                   class="w-full h-full object-cover">
                            </video>
                        </div>
                    </template>

                    <!-- Navigation Zones -->
                    <div class="absolute inset-0 z-[110] flex">
                        <div @click="prevItem()" class="w-1/3 h-full cursor-pointer"></div>
                        <div class="w-1/3 h-full" @mousedown="if (timer) clearInterval(timer)" @mouseup="startItem()"></div>
                        <div @click="nextItem()" class="w-1/3 h-full cursor-pointer"></div>
                    </div>

                    <!-- Story Caption/Link -->
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
