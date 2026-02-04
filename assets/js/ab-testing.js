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
         * Store active variants for tracking
         */
        activeVariants: {},
        
        /**
         * Initialize all A/B tests on the page
         */
        init: function() {
            // Find all test containers
            const testContainers = document.querySelectorAll('[data-ab-test]');
            
            testContainers.forEach(function(container) {
                ABTesting.processTest(container);
            });
            
            // Initialize conversion tracking
            ABTesting.initConversionTracking();
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
            
            // Store active variant for tracking
            ABTesting.activeVariants[testName] = selectedVariant;
            
            // Track view
            ABTesting.trackView(testName, selectedVariant);

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
        },
        
        /**
         * Track a view via AJAX
         * @param {string} testName - Test name
         * @param {string} variant - Variant name
         */
        trackView: function(testName, variant) {
            // Check if we have the necessary data
            if (typeof simpleABTesting === 'undefined') {
                return;
            }
            
            const data = new FormData();
            data.append('action', 'ab_testing_track_view');
            data.append('test_name', testName);
            data.append('variant', variant);
            data.append('nonce', simpleABTesting.nonce);
            
            fetch(simpleABTesting.ajaxUrl, {
                method: 'POST',
                body: data,
                credentials: 'same-origin'
            }).catch(function(error) {
                console.error('Failed to track view:', error);
            });
        },
        
        /**
         * Track a conversion via AJAX
         * @param {string} testName - Test name
         * @param {string} variant - Variant name
         */
        trackConversion: function(testName, variant) {
            // Check if we have the necessary data
            if (typeof simpleABTesting === 'undefined') {
                return;
            }
            
            const data = new FormData();
            data.append('action', 'ab_testing_track_conversion');
            data.append('test_name', testName);
            data.append('variant', variant);
            data.append('nonce', simpleABTesting.nonce);
            
            fetch(simpleABTesting.ajaxUrl, {
                method: 'POST',
                body: data,
                credentials: 'same-origin'
            }).catch(function(error) {
                console.error('Failed to track conversion:', error);
            });
        },
        
        /**
         * Initialize conversion tracking for elements with data-ab-conversion
         */
        initConversionTracking: function() {
            // Find all conversion elements
            const conversionElements = document.querySelectorAll('[data-ab-conversion]');
            
            conversionElements.forEach(function(element) {
                const testName = element.getAttribute('data-ab-conversion');
                
                if (!testName) {
                    return;
                }
                
                // Add click listener
                element.addEventListener('click', function() {
                    // Check if we have an active variant for this test
                    if (ABTesting.activeVariants[testName]) {
                        ABTesting.trackConversion(testName, ABTesting.activeVariants[testName]);
                    }
                });
            });
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
