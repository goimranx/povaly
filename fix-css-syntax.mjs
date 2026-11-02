#!/usr/bin/env node
/**
 * Post-build CSS syntax fixer
 * 
 * Tabler's compiled CSS output has a known bug where closing braces
 * are directly followed by selectors without newlines (e.g., "}:root{").
 * Also fixes empty CSS variable declarations that cause parsing errors.
 * 
 * This script runs after `npm run prod` to fix these syntax errors.
 */

import { readFile, writeFile } from 'node:fs/promises'
import { glob } from 'node:fs/promises'

const CSS_FILES = [
    'platform/core/base/public/css/core.css',
    'platform/core/base/public/css/core.rtl.css',
]

async function fixCssSyntax(filePath) {
    console.log(`[fix-css] Processing ${filePath}`)
    
    let content = await readFile(filePath, 'utf8')
    let fixCount = 0
    
    // Fix 1: @charset immediately followed by :root
    const beforeCharset = content
    content = content.replace(/@charset\s+"UTF-8";:root\{/g, '@charset "UTF-8";\n:root{')
    if (content !== beforeCharset) {
        fixCount++
        console.log(`  ✓ Fixed @charset;:root`)
    }
    
    // Fix 2: Closing brace immediately followed by :root, [data-, .theme-, etc.
    const beforeRoot = content
    content = content.replace(/\}(:[a-z-]+(?:\[|,|\{))/g, '}\n$1')
    const rootMatches = (beforeRoot.match(/\}(:[a-z-]+(?:\[|,|\{))/g) || []).length
    if (rootMatches > 0) {
        fixCount += rootMatches
        console.log(`  ✓ Fixed ${rootMatches} }:selector patterns`)
    }
    
    // Fix 3: Empty CSS variable declarations (e.g., --var-name:;)
    // Replace with a comment to preserve the variable definition intent
    const beforeEmpty = content
    content = content.replace(/(--[a-z][a-z0-9-]*:\s*);/g, '$1 /* empty */;')
    const emptyMatches = (beforeEmpty.match(/(--[a-z][a-z0-9-]*:\s*);/g) || []).length
    if (emptyMatches > 0) {
        fixCount += emptyMatches
        console.log(`  ✓ Fixed ${emptyMatches} empty CSS variables`)
    }
    
    // Fix 4: Empty CSS variable followed by closing brace (e.g., --var-name:})
    // This is invalid syntax where the value is missing before a rule closes
    const beforeEmptyBrace = content
    content = content.replace(/(--[a-z][a-z0-9-]*:\s*)\}/g, '$1 /* empty */}')
    const emptyBraceMatches = (beforeEmptyBrace.match(/(--[a-z][a-z0-9-]*:\s*)\}/g) || []).length
    if (emptyBraceMatches > 0) {
        fixCount += emptyBraceMatches
        console.log(`  ✓ Fixed ${emptyBraceMatches} empty CSS variables before }`)
    }
    
    await writeFile(filePath, content, 'utf8')
    console.log(`[fix-css] ${filePath}: ${fixCount} fixes applied\n`)
    
    return fixCount
}

async function main() {
    console.log('[fix-css] Starting CSS syntax fixes...\n')
    
    let totalFixes = 0
    for (const file of CSS_FILES) {
        try {
            const fixes = await fixCssSyntax(file)
            totalFixes += fixes
        } catch (error) {
            console.error(`[fix-css] Error processing ${file}:`, error.message)
        }
    }
    
    console.log(`[fix-css] Complete. ${totalFixes} total syntax errors fixed.`)
}

main().catch((err) => {
    console.error('[fix-css] Fatal error:', err)
    process.exit(1)
})
