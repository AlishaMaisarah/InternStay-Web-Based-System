<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use App\Services\PropertyScraperService;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        // optional: allow shortcuts
        $qLower = strtolower($q);
        if ($qLower === 'kl') $q = 'kuala lumpur';
        if ($qLower === 'pj') $q = 'petaling jaya';

        // If user types number like "1500", try match rent too
        $qDigits = preg_replace('/[^\d]/', '', $q);
        $rentNumber = $qDigits !== '' ? (int)$qDigits : null;

        $rentals = Rental::query()
            ->when($q !== '', function ($query) use ($q, $rentNumber) {
                $query->where(function ($sub) use ($q, $rentNumber) {
                    $sub->where('property_name', 'like', "%{$q}%")
                        ->orWhere('property_type', 'like', "%{$q}%")
                        ->orWhere('address', 'like', "%{$q}%")
                        ->orWhere('source', 'like', "%{$q}%");

                    if ($rentNumber !== null) {
                        $sub->orWhere('rent_amount', $rentNumber);
                    }
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('rentals.index', compact('rentals', 'q'));
    }

    public function create()
    {
        return view('rentals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_name'  => 'required|string|max:255',
            'property_type'  => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'rent_amount'    => 'required|numeric|min:0',
            'currency'       => 'nullable|string|max:10',
            'bedrooms'       => 'nullable|integer|min:0',
            'bathrooms'      => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
            //'contact_name'   => 'nullable|string|max:255',
            //'contact_phone'  => 'nullable|string|max:50',
            /*'contact_email'  => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== '-' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('Contact email must be a valid email or "-"');
                    }
                }
            ],*/
            'is_available'   => 'nullable|boolean',
        ]);

        Rental::create([
            'property_name' => $request->property_name,
            'property_type' => $request->property_type,
            'address'       => $request->address,
            'rent_amount'   => $request->rent_amount,
            'currency'      => $request->currency ?? 'MYR',
            'bedrooms'      => $request->bedrooms ?? 0,
            'bathrooms'     => $request->bathrooms ?? 0,
            'description'   => $request->description ?? '',
            //'contact_name'  => $request->contact_name ?? '-',
            //'contact_phone' => $request->contact_phone ?? '-',
            //'contact_email' => $request->contact_email ?? '-',
            'is_available'  => $request->boolean('is_available', true),
        ]);

        return redirect()->route('rentals.index')
            ->with('success', 'Rental created successfully');
    }

    public function show(Rental $rental)
    {
        return view('rentals.show', compact('rental'));
    }

    public function edit(Rental $rental)
    {
        return view('rentals.edit', compact('rental'));
    }

    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'property_name'  => 'required|string|max:255',
            'property_type'  => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'rent_amount'    => 'required|numeric|min:0',
            'currency'       => 'nullable|string|max:10',
            'bedrooms'       => 'nullable|integer|min:0',
            'bathrooms'      => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
            //'contact_name'   => 'nullable|string|max:255',
            //'contact_phone'  => 'nullable|string|max:50',
            /*'contact_email'  => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== '-' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('Contact email must be a valid email or "-"');
                    }
                }
            ],*/
            'is_available'   => 'nullable|boolean',
        ]);

        $rental->update([
            'property_name' => $request->property_name,
            'property_type' => $request->property_type,
            'address'       => $request->address,
            'rent_amount'   => $request->rent_amount,
            'currency'      => $request->currency ?? 'MYR',
            'bedrooms'      => $request->bedrooms ?? 0,
            'bathrooms'     => $request->bathrooms ?? 0,
            'description'   => $request->description ?? '',
            //'contact_name'  => $request->contact_name ?? '-',
            //'contact_phone' => $request->contact_phone ?? '-',
            //'contact_email' => $request->contact_email ?? '-',
            'is_available'  => $request->boolean('is_available', true),
        ]);

        return redirect()->route('rentals.index')
            ->with('success', 'Rental updated successfully');
    }

    public function destroy(Rental $rental)
    {
        $rental->delete();

        return redirect()->route('rentals.index')
            ->with('success', 'Rental deleted successfully');
    }

    public function scrapeReal(Request $request, PropertyScraperService $scraper)
    {
        $request->validate([
            'state' => 'required|string',
            'city' => 'required|string',
        ]);

        $state = $request->input('state');
        $city = $request->input('city');

        $sources = ['propertyguru', 'iproperty', 'ibilik'];
        $totalNew = 0;
        $totalUpdated = 0;
        $details = [];
        $warningDetails = [];
        $failedCount = 0;

        foreach ($sources as $source) {
            $siteDisplay = match($source) {
                'iproperty' => 'iProperty',
                'ibilik' => 'iBilik',
                default => 'PropertyGuru'
            };

            try {
                // Dynamically reset the execution time limit for each scraping run
                if (function_exists('set_time_limit')) {
                    @set_time_limit(180);
                }

                $result = $scraper->scrape($state, $city, 15, $source);
                if ($result === -1) {
                    throw new \Exception("Scraper returned failure code (-1)");
                }

                if (is_array($result)) {
                    $totalNew += $result['new'];
                    $totalUpdated += $result['updated'];
                    $details[] = "{$siteDisplay} ({$result['new']} new, {$result['updated']} updated)";

                    if ($result['total'] === 0) {
                        $targetFile = match($source) {
                            'iproperty' => 'iproperty_target.html',
                            'ibilik' => 'ibilik_debug.html',
                            default => 'guru_target.html'
                        };
                        $warningDetails[] = "<li><strong>{$siteDisplay}</strong> was blocked or returned no listings. Save search page as <code>storage/app/{$targetFile}</code> to scrape manually.</li>";
                    }
                } else {
                    $totalNew += $result;
                    $details[] = "{$siteDisplay} ({$result})";

                    if ($result === 0) {
                        $targetFile = match($source) {
                            'iproperty' => 'iproperty_target.html',
                            'ibilik' => 'ibilik_debug.html',
                            default => 'guru_target.html'
                        };
                        $warningDetails[] = "<li><strong>{$siteDisplay}</strong> was blocked. Save search page as <code>storage/app/{$targetFile}</code> to scrape manually.</li>";
                    }
                }
            } catch (\Throwable $e) {
                Log::error("[Scraper] Multi-source rental scrape failed for: {$siteDisplay}", [
                    'state' => $state,
                    'city' => $city,
                    'error' => $e->getMessage(),
                ]);
                $failedCount++;
                $details[] = "{$siteDisplay} (failed)";
                $warningDetails[] = "<li><strong>{$siteDisplay}</strong> failed to run: " . e($e->getMessage()) . "</li>";
            }
        }

        $detailsStr = implode(', ', $details);
        $redirect = redirect()->route('rentals.index');

        if ($failedCount === count($sources)) {
            return $redirect->with('error', "All rental scrapers failed to execute. Details: {$detailsStr}");
        }

        if (count($warningDetails) > 0) {
            $warningMsg = "Some rental sources had issues:<br/><ul class='mb-0'>" . implode('', $warningDetails) . "</ul>";
            $redirect = $redirect->with('warning', $warningMsg);
        }

        $totalSaved = $totalNew + $totalUpdated;
        return $redirect->with('success', "Successfully scraped and imported {$totalSaved} live rental listings ({$totalNew} new, {$totalUpdated} updated)! Details: {$detailsStr}");
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('rentals.index')
                ->with('error', 'No rental properties selected for deletion');
        }

        $count = Rental::whereIn('id', $ids)->delete();

        return redirect()->route('rentals.index')
            ->with('success', "{$count} rental property/properties deleted successfully");
    }
}
