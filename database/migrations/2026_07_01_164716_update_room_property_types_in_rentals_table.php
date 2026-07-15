<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all rentals that have a property type of 'Room'
        $rentals = DB::table('rentals')->where('property_type', 'Room')->get();

        foreach ($rentals as $rental) {
            $desc = strtolower(($rental->description ?? '') . ' ' . ($rental->property_name ?? ''));
            $isShared = false;
            $sharingKeywords = ['share', 'sharing', 'shared', 'twin', 'roommate', 'co-living', 'coliving', 'buddy', 'room-sharing', '2 pax', 'two pax', 'triple'];
            
            foreach ($sharingKeywords as $keyword) {
                if (str_contains($desc, $keyword)) {
                    $isShared = true;
                    break;
                }
            }

            $newType = $isShared ? 'Shared Room' : 'Single Room';

            DB::table('rentals')->where('id', $rental->id)->update([
                'property_type' => $newType
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'Single Room' and 'Shared Room' back to 'Room'
        DB::table('rentals')
            ->whereIn('property_type', ['Single Room', 'Shared Room'])
            ->update([
                'property_type' => 'Room'
            ]);
    }
};
