# Gallery Strip Investigation — Findings Report

**Task:** Investigate why the quick-shop offcanvas gallery shows only selected-variation images instead of ALL images.

---

## Summary Answer (Root Causes)

There are **two independent bugs** that together cause the strip to show only one variation's images:

### Bug 1 (PHP / Blade) — `$productVariations` is NEVER passed to `quick-shop.blade.php`

The blade file builds `$allImages` using `$productVariations` (the collection of every variation with its child product image), but **no such variable exists in the blade's view data**. The controller (`QuickShopController::show`) only passes these keys:

```
product, productImages, productVariation, selectedAttrs, referenceProduct, saleType
```

`$productVariations` is NOT in that list. The blade's guard `isset($productVariations)` therefore evaluates to `false`, and the entire "3. Variation images" block is silently skipped. The strip only contains product-gallery images plus the featured image — never any variation-specific images.

### Bug 2 (JavaScript) — `updateQSGalleryImages` falls through to "jump to 0" when variation image is not in the strip

When a user selects a variation swatch, the JS AJAX handler receives `data.image_with_sizes.origin` (the selected variation's images) and calls `updateQSGalleryImages`. That function searches the *existing* strip for a matching slide. Because variation images are missing from the strip (Bug 1), the match loop never finds them, and the function falls back to `gallery._qsGo(0)` — forcing the viewport back to slide 0 every time any variation is clicked. The user sees the gallery "reset" or appear to show only that variation.

---

## Evidence

### 1. Controller passes no `$productVariations`

**File:** `platform/plugins/ecommerce/src/Http/Controllers/Fronts/QuickShopController.php` lines 62–70

```php
$data = apply_filters('ecommerce_quick_shop_data', [
    'product'          => $product,
    'productImages'    => $productImages,
    'productVariation' => $productVariation,
    'selectedAttrs'    => $selectedAttrs,
    'referenceProduct' => $referenceProduct,
    'saleType'         => $saleType,
]);
```

`$productVariations` (the collection of ALL variation rows with their child `product` relationship) is absent. The `ecommerce_quick_shop_data` filter can add data but no registered listener does so.

### 2. Blade's variation-image loop depends entirely on `$productVariations`

**File:** `platform/themes/martfury/views/ecommerce/includes/quick-shop.blade.php` lines 22–34

```php
// 3. Variation images
$variationImageMap = [];
if ($product->has_variation && isset($productVariations)) {   // <-- NEVER true
    foreach ($productVariations as $pv) {
        if (!$pv->product || !$pv->product->image) continue;
        $img = $pv->product->image;
        if (!$allImages->contains('src', $img)) {
            $allImages->push(['src' => $img, 'type' => 'variation', 'variationId' => $pv->product_id]);
        }
        ...
    }
}
```

Because `isset($productVariations)` is always `false`, **no variation images are ever added to `$allImages`**. The strip renders with only product gallery + featured image.

### 3. What `$productImages` actually contains

**File:** `platform/plugins/ecommerce/src/Supports/EcommerceHelper.php` lines 893–990 (`getProductVariationInfo`)

- `$productImages` starts as `$product->images` (the parent product's gallery, an array of image paths).
- It is only **overwritten** with the selected variation's images if `$params` (URL attribute params) were passed — and the QuickShopController never passes `$params`, so `$productImages` ends up being the parent product gallery.
- Even if it were overwritten: it is the images for **one** variation (the default or the one matching URL params), NOT all variations.

**File:** `platform/plugins/ecommerce/src/Services/Products/ProductImageService.php` — `getProductImagesWithSizes()` returns the images of a single product (variation or parent). It has no concept of "all variation images."

### 4. Where `$productVariations` IS correctly built

**File:** `platform/plugins/ecommerce/src/Supports/RenderProductSwatchesSupport.php` lines 42–47

```php
$productVariations = ProductVariation::query()
    ->where('configurable_product_id', $product->getKey())
    ->with(['product:id,stock_status,...,images,...'])
    ->select('id', 'product_id', 'configurable_product_id', 'is_default')
    ->get();
```

This is exactly the query needed. The `product` relationship is eager-loaded with `images`. The result is passed to the swatches-renderer sub-view (line 91) — but it is **scoped to that sub-view only** and does not bubble back up to the parent `quick-shop.blade.php` scope.

### 5. JavaScript fallback hides the problem

**File:** `platform/themes/martfury/partials/footer.blade.php` — `updateQSGalleryImages()` function (approximately line 1270–1295):

```js
for (var i = 0; i < slides.length; i++) {
    var slideSrc = slides[i].dataset.src || '';
    var slideFile = slideSrc.split('/').pop().split('?')[0];
    var targetFile = firstUrl.split('/').pop().split('?')[0];
    if (slideSrc === firstUrl || slideFile === targetFile) {
        ...
        gallery._qsGo(globalIdx);
        return;
    }
}
// If not found, just jump to first image
gallery._qsGo(0);          // <-- always reached
```

Because the strip never contains variation images (Bug 1), the loop always exhausts without finding a match, and `gallery._qsGo(0)` resets the viewport to the first slide on every swatch click.

### 6. `$productVariation` (singular) vs `$productVariations` (plural)

The controller passes `$productVariation` — a single `Product` model for the default/matched variation, **not** the full collection. This is completely separate from `$productVariations` (the `ProductVariation` collection with `->product` relationship loaded). The blade correctly uses `$productVariation->image` in the "determine default jump index" section (lines 46–56), but that only finds the starting position; the strip content itself still depends on the missing `$productVariations`.

---

## Answers to Investigation Questions

| Question | Answer |
|---|---|
| What is in `$productImages` when passed to the blade? | Array of the parent product's own gallery images (or the default variation's images if URL params were passed, which they are not in quick-shop flow). It is **not** all variation images. |
| What is `$product->images` vs `$productImages`? | `$product->images` is the parent product gallery. `$productImages` starts as `$product->images` and is sometimes overwritten with one variation's images — they can be equal or slightly different. Neither is "all variation images." |
| Does `$productVariations` in the blade have `->product->image` loaded? | It would if passed, because `RenderProductSwatchesSupport` eager-loads `product` with `images`. But it is **never passed** to the blade. |
| Why is `$allImages` not containing all variation images? | `$productVariations` is not in the blade's view data. `isset($productVariations)` returns `false` and the loop never runs. |
| Is the issue in PHP (blade) or JS? | **Primarily PHP/Blade**: the strip is rendered server-side without variation images. JS compounds the problem by falling back to slide 0 instead of a no-op when a variation image is not found. |
| What variables are available in quick-shop.blade.php from the controller? | `$product`, `$productImages`, `$productVariation`, `$selectedAttrs`, `$referenceProduct`, `$saleType`. |

---

## Recommended Fixes

### Fix 1 (required) — Pass `$productVariations` from the controller

In `QuickShopController::show`, before building `$data`, load and pass the full variations collection:

```php
// After: [$productImages, $productVariation, $selectedAttrs] = EcommerceHelper::getProductVariationInfo($product);

$productVariations = null;
if ($product->has_variation) {
    $productVariations = \Botble\Ecommerce\Models\ProductVariation::query()
        ->where('configurable_product_id', $product->getKey())
        ->with(['product:id,image,images,stock_status,quantity,with_storehouse_management,allow_checkout_when_out_of_stock'])
        ->select('id', 'product_id', 'configurable_product_id', 'is_default')
        ->get();
}

$data = apply_filters('ecommerce_quick_shop_data', [
    'product'           => $product,
    'productImages'     => $productImages,
    'productVariation'  => $productVariation,
    'selectedAttrs'     => $selectedAttrs,
    'referenceProduct'  => $referenceProduct,
    'saleType'          => $saleType,
    'productVariations' => $productVariations,   // <-- ADD THIS
]);
```

**Note:** The blade already uses `$pv->product->image` (singular featured image). If variations can also have a gallery (`->product->images`), those can be added in the blade loop too — but at minimum `image` must be in the `select` list of the eager-loaded relationship.

### Fix 2 (secondary) — Guard `updateQSGalleryImages` against a missing slide

With Fix 1 applied the strip will contain all images, so the JS loop will find a match. But as defensive code, instead of jumping to 0 when not found, consider doing nothing (or logging) so the strip stays on whatever was already visible:

```js
// If not found in strip — do NOT jump to 0, just leave current position
// gallery._qsGo(0);  ← remove or comment this line
```

### Fix 3 (optional) — Also include variation gallery images, not just the variation's featured image

The current blade loop only pushes `$pv->product->image` (the variation's single featured image). If a variation has a full gallery (`$pv->product->images` — an array), those images should also be added to `$allImages` for a complete strip. This requires adding `images` to the eager-load in Fix 1 (already included above) and updating the blade loop accordingly.

---

## Summary of Root Cause

The gallery strip is missing all variation images because `$productVariations` — the variable that feeds the "Variation images" section of `$allImages` in `quick-shop.blade.php` — is **never passed from `QuickShopController` to the blade view**. The fix is a one-line addition to the controller's `$data` array, pairing it with a `ProductVariation` query (identical to what `RenderProductSwatchesSupport` already does). The JS `updateQSGalleryImages` fallback to `gallery._qsGo(0)` is a secondary symptom that disappears once the strip contains all images.
