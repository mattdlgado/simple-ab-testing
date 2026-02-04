/**
 * Simple A/B Testing - Vanilla JavaScript Implementation
 * Handles A/B/N testing using HTML data attributes
 */

(function() {
    'use strict';

    /**
     * Cookie Management Functions
     */
    const CookieManager = {
        /**
         * Set a cookie
         * @param {string} name - Cookie name
         * @param {string} value - Cookie value
         * @param {number} days - Days until expiration
         */
        set: function(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = 'expires=' + date.toUTCString();
            document.cookie = name + '=' + value + ';' + expires + ';path=/';
        },

        /**
         * Get a cookie value
         * @param {string} name - Cookie name
         * @return {string|null} Cookie value or null if not found
         */
        get: function(name) {
            const nameEQ = name + '=';
            const cookies = document.cookie.split(';');
            
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i];
                while (cookie.charAt(0) === ' ') {
                    cookie = cookie.substring(1, cookie.length);
                }
                if (cookie.indexOf(nameEQ) === 0) {
                    return cookie.substring(nameEQ.length, cookie.length);
                }
            }
            return null;
        }
    };

    /**
     * A/B Testing Core Logic
     */
    const ABTesting = {
        /**
         * Initialize all A/B tests on the page
         */
        init: function() {
            // Find all test containers
            const testContainers = document.querySelectorAll('[data-ab-test]');
            
            testContainers.forEach(function(container) {
                ABTesting.processTest(container);
            });
        },

        /**
         * Process a single A/B test container
         * @param {HTMLElement} container - Test container element
         */
        processTest: function(container) {
            const testName = container.getAttribute('data-ab-test');
            
            if (!testName) {
                console.warn('A/B Test container found without test name');
                return;
            }

            // Find all variant elements
            const variants = container.querySelectorAll('[data-ab-variant]');
            
            if (variants.length === 0) {
                console.warn('No variants found for test: ' + testName);
                return;
            }

            // Get variant names
            const variantNames = [];
            variants.forEach(function(variant) {
                const variantName = variant.getAttribute('data-ab-variant');
                if (variantName) {
                    variantNames.push(variantName);
                }
            });

            if (variantNames.length === 0) {
                console.warn('No valid variant names found for test: ' + testName);
                return;
            }

            // Determine which variant to show
            const selectedVariant = ABTesting.selectVariant(testName, variantNames);

            // Remove all variants except the selected one
            variants.forEach(function(variant) {
                const variantName = variant.getAttribute('data-ab-variant');
                if (variantName !== selectedVariant) {
                    // Remove from DOM (not just hide)
                    variant.parentNode.removeChild(variant);
                }
            });
        },

        /**
         * Select a variant (from cookie or randomly)
         * @param {string} testName - Test name
         * @param {Array} variantNames - Array of available variant names
         * @return {string} Selected variant name
         */
        selectVariant: function(testName, variantNames) {
            const cookieName = 'ab_test_' + testName;
            let selectedVariant = CookieManager.get(cookieName);

            // Check if cookie exists and variant is valid
            if (selectedVariant && variantNames.indexOf(selectedVariant) !== -1) {
                return selectedVariant;
            }

            // No valid cookie found, select randomly
            const randomIndex = Math.floor(Math.random() * variantNames.length);
            selectedVariant = variantNames[randomIndex];

            // Save to cookie (30 days)
            CookieManager.set(cookieName, selectedVariant, 30);

            return selectedVariant;
        }
    };

    /**
     * Execute A/B testing as early as possible
     */
    if (document.readyState === 'loading') {
        // DOM is still loading, wait for DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            ABTesting.init();
        });
    } else {
        // DOM is already loaded, execute immediately
        ABTesting.init();
    }

})();
