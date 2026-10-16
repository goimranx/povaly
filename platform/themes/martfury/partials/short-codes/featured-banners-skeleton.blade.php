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

    .skeleton-banner {
        width: 100%;
        border-radius: 8px;
    }

    .skeleton-banner-large {
        padding-bottom: 42.86%;
    }

    .skeleton-banner-medium {
        padding-bottom: 85.5%;
    }

    .skeleton-banner-image {
        padding-bottom: 114%;
    }

    .skeleton-banner-grid {
        padding-bottom: 100%;
    }

    .skeleton-banner-small {
        padding-bottom: 130%;
    }
</style>

<div class="ps-featured-banners">
    <div class="ps-container">
        <div class="ps-banner-wrapper">
            {{-- Top Row --}}
            <div class="ps-banner-row ps-banner-top">
                {{-- Left: Large Banner --}}
                <div class="ps-banner-col ps-banner-left">
                    <div class="skeleton-banner skeleton-banner-large skeleton-loading"></div>
                </div>

                {{-- Right: Medium Banner --}}
                <div class="ps-banner-col ps-banner-right">
                    <div class="skeleton-banner skeleton-banner-medium skeleton-loading"></div>
                </div>
            </div>

            {{-- Bottom Row --}}
            <div class="ps-banner-row ps-banner-bottom">
                {{-- Left Split --}}
                <div class="ps-banner-col ps-banner-left-bottom">
                    {{-- Image Banners --}}
                    <div class="ps-banner-images">
                        <div class="skeleton-banner skeleton-banner-image skeleton-loading"></div>
                        <div class="skeleton-banner skeleton-banner-image skeleton-loading"></div>
                    </div>

                    {{-- Grid Banners --}}
                    <div class="ps-banner-grid">
                        <div class="skeleton-banner skeleton-banner-grid skeleton-loading"></div>
                        <div class="skeleton-banner skeleton-banner-grid skeleton-loading"></div>
                        <div class="skeleton-banner skeleton-banner-grid skeleton-loading"></div>
                        <div class="skeleton-banner skeleton-banner-grid skeleton-loading"></div>
                    </div>
                </div>

                {{-- Right: Two Small Banners --}}
                <div class="ps-banner-col ps-banner-right-bottom">
                    <div class="skeleton-banner skeleton-banner-small skeleton-loading"></div>
                    <div class="skeleton-banner skeleton-banner-small skeleton-loading"></div>
                </div>
            </div>
        </div>
    </div>
</div>
