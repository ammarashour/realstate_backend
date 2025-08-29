<?php

namespace App\Http\Contracts\Utilities;

interface DynamicLinkServiceInterface
{
    public function get($shortCode = null);

    public function getAll($shortCode = null, $type = null);

    public function getDeepLinkServiceProvider();

    /**
     * Summary of generateDynamicLinks
     * @param mixed $models Array of models to map
     * @param mixed $queryColumnMap Key(query) and Column(taken from model) to map
     * @param mixed $type Type of Dynamic Link
     * @param mixed $column Column from model to update dynamic link
     * @return \Illuminate\Support\Collection
     */
    public function generateDynamicLinks($models, $queryColumnMap, $type);

    /**
     * Get the redirect data for a dynamic link.
     *
     * @param string $shortCode The shortcode identifying the dynamic link.
     *
     * @return array{
     *     appRedirect: string,          // Deep link URL for opening the app
     *     iosPackageId: string,           // iOS App Store package ID
     *     appPackageId: string,           // Android package name
     *     webRedirect: string              // Fallback URL for browsers
     * }
     */
    public function getDynamicLinkRedirectData($shortCode);
}
