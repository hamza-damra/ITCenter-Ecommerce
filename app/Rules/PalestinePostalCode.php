<?php

namespace App\Rules;

use App\Models\ShippingBlockedRange;
use App\Models\ShippingCity;
use App\Models\ShippingSetting;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that a Palestinian postal code:
 * 1. Starts with "P" followed by 7 digits (governorate prefix 3 + location 4)
 * 2. The first 3 digits fall within the allowed range for the selected city
 * 3. Does not fall in a blocked (Gaza) range
 *
 * Format: P + 7 digits, e.g., P5840719
 * First 3 digits = governorate/area prefix (validated against city range)
 * Last 4 digits = exact location (free entry)
 */
class PalestinePostalCode implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $postalCode = strtoupper(trim($value));
        $requiredDigits = (int) ShippingSetting::getValue('postal_code_digits', 7);

        // 1. Format check: must be P followed by exactly N digits
        if (!preg_match('/^P(\d{' . $requiredDigits . '})$/', $postalCode, $matches)) {
            $fail(__('messages.invalid_postal_format', ['digits' => $requiredDigits]));
            return;
        }

        $fullNumeric = $matches[1];
        // Extract the first 3 digits as the governorate/area prefix
        $prefix = (int) substr($fullNumeric, 0, 3);

        // 2. Check if the prefix falls in a blocked (Gaza) range
        $blocked = ShippingBlockedRange::active()
            ->where('postal_code_min', '<=', $prefix)
            ->where('postal_code_max', '>=', $prefix)
            ->first();

        if ($blocked) {
            $fail(__('messages.no_shipping_to_gaza'));
            return;
        }

        // 3. Cross-validate with selected city
        $selectedCityKey = $this->data['city'] ?? null;
        if (!$selectedCityKey) {
            $fail(__('messages.select_city_first'));
            return;
        }

        $city = ShippingCity::where('key', $selectedCityKey)->where('is_active', true)->first();
        if (!$city) {
            $fail(__('messages.invalid_city_selected'));
            return;
        }

        // 4. Validate the prefix is within the city's allowed range
        if ($prefix < $city->postal_code_min || $prefix > $city->postal_code_max) {
            $fail(__('messages.postal_code_mismatch', [
                'min' => 'P' . str_pad($city->postal_code_min, 3, '0', STR_PAD_LEFT),
                'max' => 'P' . str_pad($city->postal_code_max, 3, '0', STR_PAD_LEFT),
            ]));
            return;
        }
    }

    /**
     * Get all valid city keys from DB.
     */
    public static function getValidCityKeys(): array
    {
        return ShippingCity::active()->pluck('key')->toArray();
    }
}
