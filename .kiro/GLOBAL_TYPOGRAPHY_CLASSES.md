# Global Typography Classes

These classes are defined in `platform/themes/martfury/assets/sass/base/_typography.scss` and can be used throughout the site for consistent heading and subtitle styles.

## Section Title Classes

### `.section-title-lg` - Large Section Titles
- **Desktop/Tablet** (>767px): `22px`
- **Mobile** (≤767px): `20px`
- **Font Weight**: `700`
- **Color**: `var(--global-color-text-main, #1a1a1a)`
- **Line Height**: `1.3`
- **Use for**: Main page sections, major headings

**Currently used in:**
- Product detail accordion titles (`.pd-section-title`)
- Upsale section title (`.ec-upsell-bundle-title h4`)
- Cross-sale section title (`.ec-crosssale-bundle-title h4`)

---

### `.section-title-md` - Medium Section Titles
- **Desktop/Tablet** (>767px): `20px`
- **Mobile** (≤767px): `18px`
- **Font Weight**: `700`
- **Color**: `var(--global-color-text-main, #1a1a1a)`
- **Line Height**: `1.3`
- **Use for**: Secondary sections, subsection headings

---

### `.section-title-sm` - Small Section Titles
- **Desktop/Tablet** (>767px): `18px`
- **Mobile** (≤767px): `16px`
- **Font Weight**: `700`
- **Color**: `var(--global-color-text-main, #1a1a1a)`
- **Line Height**: `1.3`
- **Use for**: Card titles, minor headings

---

### `.section-subtitle` - Subtitles/Descriptions
- **All Devices**: `14px`
- **Font Weight**: `400`
- **Color**: `var(--global-color-text-body, #666)`
- **Line Height**: `1.6`
- **Use for**: Section descriptions, supporting text below titles

---

## Usage Examples

### HTML/Blade Templates
```html
<!-- Large section title -->
<h2 class="section-title-lg">Featured Products</h2>

<!-- Medium section title -->
<h3 class="section-title-md">Related Items</h3>

<!-- Small section title -->
<h4 class="section-title-sm">Product Details</h4>

<!-- Subtitle/description -->
<p class="section-subtitle">Explore our carefully curated selection</p>
```

### SCSS (using @extend)
```scss
.my-custom-section-title {
    @extend .section-title-lg;
    margin-bottom: 20px; // Add custom spacing
}
```

---

## Accordion Button Typography

### `.pd-accordion-btn`
- **Desktop/Tablet** (>767px): `18px`
- **Mobile** (≤767px): `17px`
- **Font Weight**: `700`
- **Color**: `var(--global-color-text-main, #1a1a1a)`
- **Chevron Icon**: `18px` with `10px` right margin

---

## Benefits

1. **Consistency**: All titles across the site maintain the same visual hierarchy
2. **Responsiveness**: Automatic font size adjustments for mobile devices
3. **Maintainability**: Update all titles site-wide by changing one class
4. **Easy to Remember**: Clear naming convention (lg, md, sm)
5. **Accessibility**: Proper color contrast ratios maintained

---

## Future Usage

When creating new sections or components, use these classes instead of defining custom font sizes:

- ✅ **Do**: `<h2 class="section-title-lg">My Title</h2>`
- ❌ **Don't**: `<h2 style="font-size: 22px; font-weight: 700;">My Title</h2>`

---

**Last Updated**: 2026-11-02
**Location**: `platform/themes/martfury/assets/sass/base/_typography.scss`
