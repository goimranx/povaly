<div class="ps-deal-of-day">
    <div class="ps-container">
        <div class="ps-section__content">
            <div class="row">
                {{-- Left Side Skeleton --}}
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="ps-block--deal-of-day skeleton-loading">
                        <div class="skeleton-header"></div>
                        <div class="skeleton-image"></div>
                        <div class="skeleton-title"></div>
                        <div class="skeleton-price"></div>
                        <div class="skeleton-button"></div>
                    </div>
                </div>

                {{-- Right Side Skeleton --}}
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                    <div class="ps-block--offers skeleton-loading">
                        <div class="skeleton-header"></div>
                        <div class="row">
                            @for($i = 0; $i < 4; $i++)
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="skeleton-product">
                                        <div class="skeleton-image-small"></div>
                                        <div class="skeleton-title-small"></div>
                                        <div class="skeleton-price-small"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .skeleton-loading {
        background: var(--global-color-card-bg);
        border-radius: 4px;
        padding: 20px;
    }

    .skeleton-header {
        height: 24px;
        width: 60%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .skeleton-image {
        height: 300px;
        width: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .skeleton-title {
        height: 20px;
        width: 80%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .skeleton-price {
        height: 24px;
        width: 50%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .skeleton-button {
        height: 40px;
        width: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
    }

    .skeleton-product {
        margin-bottom: 20px;
    }

    .skeleton-image-small {
        height: 200px;
        width: 100%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .skeleton-title-small {
        height: 16px;
        width: 90%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .skeleton-price-small {
        height: 18px;
        width: 40%;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    @media (max-width: 992px) {
        .ps-block--deal-of-day.skeleton-loading {
            margin-bottom: 30px;
        }
    }
</style>
