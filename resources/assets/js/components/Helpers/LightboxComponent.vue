<template>
    <div>
        <a :href="images[0]" target="_blank" @click.prevent="show" class="lightbox__thumbnail">
            <img :src="thumbnail" :alt="alternateText">
        </a>
        <div class="lightbox" v-if="visible" @click="hide">
            <div class="lightbox__close" @click="hide">&times;</div>
            <div class="lightbox__element" @click.stop="">
                <div
                    class="lightbox__arrow lightbox__arrow--left"
                    @click.stop.prevent="prev"
                    :class="{'lightbox__arrow--invisible': ! has_prev()}"
                >
                    <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z"/>
                        <path d="M0-.5h24v24H0z" fill="none"/>
                    </svg>
                </div>
                <div class="lightbox__image" @click.stop="">
                    <slot name="loader" v-if="$slots.loader"></slot>
                    <img :src="images[index]" v-if="displayImage">
                </div>
                <div
                    class="lightbox__arrow lightbox__arrow--right"
                    @click.stop.prevent="next"
                    :class="{'lightbox__arrow--invisible': ! has_next()}"
                >
                    <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.59 16.34l4.58-4.59-4.58-4.59L10 5.75l6 6-6 6z"/>
                        <path d="M0-.25h24v24H0z" fill="none"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LightboxComponent",
    extends: Lightbox,
    props: ["thumbnail", "images", "alternateText"],
    data() {
        return {
            visible: false,
            index: 0,
            displayImage: true
        };
    },
    methods: {
        show() {
            this.visible = true;
            this.index = 0;
        },
        hide() {
            this.visible = false;
            this.index = 0;
        },
        has_next() {
            return this.index + 1 < this.images.length;
        },
        has_prev() {
            return this.index - 1 >= 0;
        },
        prev() {
            if (this.has_prev()) {
                this.index -= 1;
                this.tick();
            }
        },
        next() {
            if (this.has_next()) {
                this.index += 1;
                this.tick();
            }
        },
        tick() {
            if (!this.$slots.loader) {
                return;
            }

            this.displayImage = false;

            Vue.nextTick(() => {
                this.displayImage = true;
            });
        },
        eventListener(e) {
            if (this.visible) {
                switch (e.key) {
                    case "ArrowRight":
                        this.next();
                        break;
                    case "ArrowLeft":
                        this.prev();
                        break;
                    case "ArrowDown":
                    case "ArrowUp":
                    case " ":
                        e.preventDefault();
                        break;
                    case "Escape":
                        this.hide();
                        break;
                }
            }
        }
    }
};
</script>

<style>
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 100000;
}

.lightbox__thumbnail {
    width: 100%;
    height: 100%;
}

.lightbox__thumbnail img {
    width: 20px;
}

.lightbox__close {
    position: fixed;
    right: 0;
    top: 0;
    padding: 1rem;
    font-size: 1.5rem;
    cursor: pointer;
    color: #fff;
    width: 4rem;
    height: 4rem;
}

.lightbox__arrow--invisible {
    visibility: hidden;
}

.lightbox__element {
    display: flex;
    width: 100%;
    height: fit-content;
}

.lightbox__arrow {
    padding: 0 2rem;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
}

.lightbox__arrow svg {
    fill: #fff;
    pointer-events: none;
}

.lightbox__image {
    flex: 1;
}

.lightbox__image img {
    width: 50%;
    height: auto !important;
}

@media screen and (max-width: 720px) {
    .lightbox__arrow {
        padding: 0 1rem;
    }
}

@media screen and (max-width: 500px) {
    .lightbox__element {
        position: relative;
    }

    .lightbox__arrow {
        position: absolute;
        padding: 0 2rem;
        height: 100%;
    }

    .lightbox__arrow--right {
        right: 0;
        background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.3));
    }

    .lightbox__arrow--left {
        left: 0;
        background: linear-gradient(to left, transparent, rgba(0, 0, 0, 0.3));
    }
}
</style>
