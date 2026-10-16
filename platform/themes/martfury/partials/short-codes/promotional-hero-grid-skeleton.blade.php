<style>
    @keyframes skeleton-loading {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    .skeleton-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
    }

    .skeleton-hero-banner {
        width: 100%;
        border-radius: 8px;
    }

    .skeleton-hero-large {
        padding-bottom: 81.82%;
    }

    .skeleton-hero-vertical {
        padding-bottom: 60%;
    }

    .skeleton-hero-small {
        padding-bottom: 80%;
    }

    .skeleton-hero-tall {
        padding-bottom: 150%;
    }
</style>

<div class="ps-promotional-hero-grid">
    <div class="ps-container">
        <div class="ps-hero-grid-wrapper">
            {{-- Left Column - Large Banner --}}
            <div class="ps-hero-left">
                <div class="skeleton-hero-banner skeleton-hero-large skeleton-loading"></div>
            </div>

            {{-- Middle Column - Top Vertical + Bottom Split --}}
            <div class="ps-hero-middle">
                {{-- Top Vertical Banner --}}
                <div class="skeleton-hero-banner skeleton-hero-vertical skeleton-loading"></div>

                {{-- Bottom Split --}}
                <div class="ps-hero-split">
                    <div class="skeleton-hero-banner skeleton-hero-small skeleton-loading"></div>
                    <div class="skeleton-hero-banner skeleton-hero-small skeleton-loading"></div>
                </div>
            </div>

            {{-- Right Column - Tall Banner --}}
            <div class="ps-hero-right">
                <div class="skeleton-hero-banner skeleton-hero-tall skeleton-loading"></div>
            </div>
        </div>
    </div>
</div>
